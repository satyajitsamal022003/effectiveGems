<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Session;
use Exception;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class RazorpayController extends Controller
{
    /**
     * Show the payment page.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOrder()
    {
        return view('user.checkout.razorpay');
    }
    public function testRazorpayCredentials()
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        try {
            $razorpayOrder = $api->order->create([
                'receipt'         => 'test_receipt',
                'amount'          => 100, // Amount in paisa (1 INR)
                'currency'        => 'INR',
                'payment_capture' => 1
            ]);
            dd($razorpayOrder);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Handle the payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function storePayment(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
        $orderId = $request->orderId;
        // Order creation in Razorpay
        $order = $api->order->create(array(
            'receipt' => 'orderId-' . $orderId,
            'amount' => $request->amount,
            'currency' => 'INR'
        ));

        $transactionId = $order['id'];

        // Pass the order id to the view
        return view('user.checkout.razorpay', compact('transactionId'));
    }

    /**
     * Handle the Razorpay payment callback.
     */
    public function paymentCallback(Request $request)
    {
        $ip = $request->getClientIp();
        $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));

        $order = Order::find($request->orderId);
        
        try {
            // For UPI/QR payments, we'll handle the status in webhook
            if ($request->has('razorpay_payment_id')) {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];

                // Verify signature for immediate payments (card, UPI intent)
                $api->utility->verifyPaymentSignature($attributes);
                
                // Fetch payment details
                $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);
                
                // Update order status for immediate payments
                $this->updateOrderStatus($order, $paymentDetails, $ip);
                
                return redirect()->route('payment.success');
            }
            
            // For QR/Scan & Pay, redirect to a waiting page
            // The webhook will handle the status update
            return view('user.checkout.payment-waiting', [
                'orderId' => $order->id,
                'razorpayOrderId' => $request->razorpay_order_id
            ]);
            
        } catch (Exception $e) {
            \Log::error('Payment verification failed: ' . $e->getMessage());
            return redirect()->route('payment.failure');
        }
    }

    /**
     * Check payment status for async payments (UPI QR)
     */
    public function checkPaymentStatus(Request $request)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
            
            // Get the order from Razorpay
            $razorpayOrder = $api->order->fetch($request->razorpayOrderId);
            
            // If payment is successful
            if ($razorpayOrder->status === 'paid') {
                $order = Order::find($request->orderId);
                
                // Get the payment details
                $payments = $api->order->fetch($request->razorpayOrderId)->payments();
                $payment = $payments->items[0];
                
                // Update order status
                $this->updateOrderStatus($order, $payment, $request->getClientIp());
                
                return response()->json(['status' => 'success']);
            }
            
            // If order is still pending
            return response()->json(['status' => 'pending']);
            
        } catch (\Exception $e) {
            Log::error('Failed to check payment status: ' . $e->getMessage());
            return response()->json(['status' => 'error']);
        }
    }

    private function updateOrderStatus($order, $paymentDetails, $ip)
    {
        try {
            $order->paymentMode = $paymentDetails['method'];
            $order->paymentCompleted = 1;
            $order->orderStatus = 'Placed';
            $order->transactionId = $paymentDetails['id'];
            $order->save();

            // Send confirmation email
            try {
                Mail::to($order->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                \Log::error('Failed to send confirmation email: ' . $e->getMessage());
            }

            // Clear cart
            $userId = '';
            if (Auth::guard('euser')->check()) {
                $euser = Auth::guard('euser')->user();
                $userId = $euser->id;
            }

            if ($userId) {
                $cart = Cart::where("userId", $userId)->first();
            } else {
                $cart = Cart::where("ip", $ip)->first();
            }

            if ($cart) {
                $cart->delete();
            }

        } catch (\Exception $e) {
            \Log::error('Failed to update order status: ' . $e->getMessage());
            throw $e;
        }
    }
}
