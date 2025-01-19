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
     * Handle Razorpay webhook notifications
     */
    public function webhook(Request $request)
    {
        $webhookSecret = env('RAZORPAY_WEBHOOK_SECRET');
        
        // Verify webhook signature
        $webhookSignature = $request->header('x-razorpay-signature');
        $payload = $request->getContent();
        
        try {
            // Verify webhook signature
            $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);
            if ($webhookSignature !== $expectedSignature) {
                Log::error('Razorpay webhook signature verification failed');
                return response()->json(['status' => 'error'], 400);
            }

            $data = json_decode($payload, true);
            
            // Handle payment.authorized event
            if ($data['event'] === 'payment.authorized') {
                $payment = $data['payload']['payment']['entity'];
                
                // Extract order ID from payment description or notes
                $orderId = null;
                if (isset($payment['notes']['order_id'])) {
                    $orderId = $payment['notes']['order_id'];
                } else {
                    // Try to find order by payment ID
                    $order = Order::where('transactionId', $payment['id'])->first();
                    if ($order) {
                        $orderId = $order->id;
                    }
                }

                if ($orderId) {
                    $order = Order::find($orderId);
                    if ($order) {
                        // Update order status
                        $order->paymentCompleted = 1;
                        $order->orderStatus = 'Placed';
                        $order->transactionId = $payment['id'];
                        $order->paymentMode = $payment['method'];
                        $order->save();

                        // Send confirmation email
                        try {
                            Mail::to($order->email)->send(new OrderConfirmation($order));
                        } catch (\Exception $e) {
                            Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                        }

                        // Clear cart
                        $userId = '';
                        if (Auth::guard('euser')->check()) {
                            $euser = Auth::guard('euser')->user();
                            $userId = $euser->id;
                            $cart = Cart::where("userId", $userId)->first();
                        } else {
                            // Since webhook is async, IP-based cart clearing might not be reliable
                            // Consider other ways to identify the cart
                            $cart = Cart::where("ip", $request->ip())->first();
                        }

                        if ($cart) {
                            $cart->delete();
                        }
                    }
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Razorpay webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
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
        $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));

        // Handle GET request for payment status check
        if ($request->isMethod('get')) {
            try {
                $paymentId = $request->razorpay_payment_id;
                if (!$paymentId) {
                    return response()->json(['error' => 'Payment ID required'], 400);
                }

                $paymentDetails = $api->payment->fetch($paymentId);
                return response()->json([
                    'status' => $paymentDetails['status'],
                    'order_id' => $paymentDetails['order_id']
                ]);
            } catch (\Exception $e) {
                Log::error('Error checking payment status: ' . $e->getMessage());
                return response()->json(['error' => 'Failed to check payment status'], 500);
            }
        }

        // Handle POST request for payment completion
        $ip = $request->getClientIp();
        try {
            $order = Order::find($request->orderId);
            if (!$order) {
                Log::error('Order not found: ' . $request->orderId);
                return redirect()->route('payment.failed');
            }

            // Double-check payment status with Razorpay
            $paymentDetails = $api->payment->fetch($request->razorpay_payment_id);
            
            if ($paymentDetails['status'] !== 'captured' && $paymentDetails['status'] !== 'authorized') {
                Log::error('Payment not successful. Status: ' . $paymentDetails['status']);
                return redirect()->route('payment.failed');
            }

            // Update order details
            $order->paymentMode = $paymentDetails['method'];
            $order->paymentCompleted = 1;
            $order->orderStatus = 'Placed';
            $order->transactionId = $request->razorpay_payment_id;
            $order->save();

            // Send confirmation email
            try {
                Mail::to($order->email)->send(new OrderConfirmation($order));
            } catch (\Exception $e) {
                Log::error('Failed to send confirmation email: ' . $e->getMessage());
            }

            // Clear cart
            $userId = '';
            if (Auth::guard('euser')->check()) {
                $euser = Auth::guard('euser')->user();
                $userId = $euser->id;
                $cart = Cart::where("userId", $userId)->first();
            } else {
                $cart = Cart::where("ip", $ip)->first();
            }

            if ($cart) {
                $cart->delete();
            }
            // Verify payment signature if provided
            if ($request->razorpay_signature) {
                $attributes = array(
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                );

                try {
                    $api->utility->verifyPaymentSignature($attributes);
                } catch (\Exception $e) {
                    Log::error('Payment signature verification failed: ' . $e->getMessage());
                    return redirect()->route('payment.failed');
                }
            }

            return redirect()->route('payment.success');
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage());
            return redirect()->route('payment.failed');
        }
    }
}
