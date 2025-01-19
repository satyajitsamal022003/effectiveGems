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
     * Check the status of a payment using order ID
     */
    public function checkPaymentStatus($orderId)
    {
        $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
        
        try {
            // Fetch order details
            $order = $api->order->fetch($orderId);
            
            // Check if payment is done
            if ($order['status'] === 'paid') {
                // Get the payment details
                $payments = $api->order->fetch($orderId)->payments()->items;
                
                if (!empty($payments)) {
                    // $payment = $payments[0]; // Get the latest payment
                    
                    // Generate signature
                    // $attributes = [
                    //     'razorpay_order_id' => $orderId,
                    //     'razorpay_payment_id' => $payment['id'],
                    // ];
                    // $signature = $api->utility->verifyPaymentSignature($attributes);
                    
                    return response()->json([
                        'status' => 'paid',
                        // 'payment_id' => $payment['id'],
                        // 'order_id' => $orderId,
                        'payments' => $payments
                    ]);
                }
            }
            
            // Check if payment failed
            if ($order['status'] === 'attempted') {
                $payments = $api->order->fetch($orderId)->payments()->items;
                if (!empty($payments) && $payments[0]['status'] === 'failed') {
                    return response()->json(['status' => 'failed']);
                }
            }
            
            // Payment still processing - include order details
            return response()->json([
                'status' => 'processing',
                'order_status' => $order['status'],
                'order_details' => [
                    'status' => $order['status'],
                    'amount_paid' => $order['amount_paid'],
                    'amount' => $order['amount']
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment status check failed: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'order_id' => $orderId,
                'full_error' => json_encode($e)
            ], 500);
        }
    }

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
            $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);
            $paymentMode = $paymentDetails['method'];
            $order->paymentMode = $paymentMode;
        } catch (\Throwable $th) {
        }
        $order->paymentCompleted = 1;
        $order->orderStatus = 'Placed';
        $order->transactionId = $request->razorpay_payment_id;
        $order->save();

        // Send confirmation email
        try{
            Mail::to($order->email)->send(new OrderConfirmation($order));
        }
        catch (\Exception $e){

        }
        $userId='';
        if (Auth::guard('euser')->check()) {
            $euser = Auth::guard('euser')->user();
            $userId = $euser->id;
        }

        if ($userId) {
            $cart = Cart::where("userId", $userId)->first();
            $cartId = $cart->id;
        } else {
            $cart = Cart::where("ip", $ip)->first();
            $cartId = $cart->id;
        }
        // $cart = Cart::where("ip", $ip)->first();
        // $cartItems = CartItem::where("cart_id", $cart->id)->delete();
        $cart->delete();
        try {
            $attributes = array(
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature
            );

            $api->utility->verifyPaymentSignature($attributes);

            // Payment verified successfully
            // Session::flash('success', 'Payment successful');
            return redirect()->route('payment.success');
        } catch (Exception $e) {
            // Payment verification failed
            // Session::flash('error', 'Payment failed');
            return redirect()->route('payment.failure');
        }
    }
}
