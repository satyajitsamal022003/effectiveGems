<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;
use App\Models\Cart;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Razorpay webhook received', ['payload' => $request->all()]);

        try {
            $api = new Api(env('RAZORPAY_KEY', 'rzp_live_aseSEVdODAvC9T'), env('RAZORPAY_SECRET', 'CuE9QlvenogbMuLlt3aVCGIJ'));
            
            // Verify webhook signature
            $webhookSignature = $request->header('x-razorpay-signature');
            $webhookBody = $request->getContent();
            
            try {
                $api->utility->verifyWebhookSignature(
                    $webhookBody,
                    $webhookSignature,
                    env('RAZORPAY_WEBHOOK_SECRET')
                );
            } catch (SignatureVerificationError $e) {
                Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }

            $payload = json_decode($webhookBody, true);
            
            // Handle payment events
            if ($payload['event'] === 'payment.captured' || $payload['event'] === 'payment.authorized') {
                $paymentId = $payload['payload']['payment']['entity']['id'];
                $payment = $api->payment->fetch($paymentId);
                
                // Extract order ID from receipt (format: orderId-{localOrderId})
                $receipt = $payment->order_id;
                $localOrderId = substr($receipt, strpos($receipt, '-') + 1);
                
                $order = Order::find($localOrderId);
                if (!$order) {
                    Log::error('Order not found', ['orderId' => $localOrderId]);
                    return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
                }

                // Update order status
                $order->paymentMode = $payment->method;
                $order->paymentCompleted = 1;
                $order->orderStatus = 'Placed';
                $order->transactionId = $paymentId;
                $order->save();

                // Send confirmation email
                try {
                    Mail::to($order->email)->send(new OrderConfirmation($order));
                } catch (\Exception $e) {
                    Log::error('Failed to send confirmation email', ['error' => $e->getMessage()]);
                }

                // Clear cart
                if ($order->userId) {
                    Cart::where("userId", $order->userId)->delete();
                } else {
                    Cart::where("ip", $order->ip)->delete();
                }

                Log::info('Payment processed successfully', [
                    'orderId' => $order->id,
                    'paymentId' => $paymentId,
                    'method' => $payment->method
                ]);
            }

            return response()->json(['status' => 'success']);
            
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
