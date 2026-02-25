<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\MembershipOrder;
use App\Models\Payment;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $webhookSecret      = env('RAZORPAY_SECRET');
        $payload            = $request->getContent();
        $signature          = $request->header('X-Razorpay-Signature');

        // Verify Razorpay signature
        $expectedSignature  = hash_hmac('sha256', $payload, $webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            Log::warning('Invalid Razorpay webhook signature detected');
            return response('Invalid signature', 400);
        }

        // Decode event
        Log::info("Webhook Response =" . json_encode($payload));
        $event              = json_decode($payload, true);
        $order              = $event['payload']['order']['entity'] ?? '';
        $receiptId          = $order['receipt'] ?? '0';
        $eventType          = $event['event'] ?? 'unknown';

        // Handle events
        switch ($eventType) {
            case 'payment.captured':
                $payment    = $event['payload']['payment']['entity'];
                $paymentId  = $payment['id'];
                $amount     = $payment['amount'] / 100;
                $email      = $payment['email'];

                // Example: Update order/payment status in DB
                // Payment::where('razorpay_payment_id', $paymentId)->update(['status' => 'paid']);

                if ($receiptId) {
                    $orderDetail = MembershipOrder::where('msorder_unique_id', $receiptId)->where('msorder_status', '<>', '1')->first();
                    if ($orderDetail) {
                        $paymentId = Payment::create([
                            'msorder_id' => $orderDetail->msorder_id,
                            'ORDERID' => $orderDetail->msorder_unique_id,
                            'TXNAMOUNT' => $amount,
                            'CURRENCY' => "INR",
                            'TXNID' => $paymentId,
                            'BANKTXNID' => NULL,
                            'STATUS' => "captured",
                            'RESPCODE' => 0,
                            'RESPMSG' =>  NULL,
                            'TXNDATE' => date('Y-m-d H:i:s'),
                            'GATEWAYNAME' => NULL,
                            'PAYMENTMODE' => NULL,
                            'CHECKSUMHASH' => NULL,
                            'BANKNAME' => NULL,
                            'REFERENCE' => NULL,
                            'created_at' => now(),
                        ])->payment_id;

                        MembershipOrder::where('msorder_id', $orderDetail->msorder_id)->update([
                            'msorder_start_date' => date('Y-m-d'),
                            'msorder_end_date' => ($orderDetail->membership_id <> 3) ? date('Y-m-d', strtotime('+6 months')) : date('Y-m-d', strtotime('+1 year')),
                            'msorder_status' => '1',
                            'msorder_token' => NULL,
                            'payment_id' => $paymentId,
                            'payment_date' => date('Y-m-d'),
                            'payment_time' => date('H:i:s')
                        ]);
                    }
                } else if ($receiptId) {
                    $orderDetail = Order::where('order_unique_id', $receiptId)->where('order_status', '<>', '1')->first();
                    if ($orderDetail) {
                        $paymentId = Payment::create([
                            'order_id' => $orderDetail->order_id,
                            'ORDERID' => $orderDetail->msorder_unique_id,
                            'TXNAMOUNT' => $amount,
                            'CURRENCY' => "INR",
                            'TXNID' => $paymentId,
                            'BANKTXNID' => NULL,
                            'STATUS' => "captured",
                            'RESPCODE' => 0,
                            'RESPMSG' =>  NULL,
                            'TXNDATE' => date('Y-m-d H:i:s'),
                            'GATEWAYNAME' => NULL,
                            'PAYMENTMODE' => NULL,
                            'CHECKSUMHASH' => NULL,
                            'BANKNAME' => NULL,
                            'REFERENCE' => NULL,
                            'created_at' => now(),
                        ])->payment_id;

                        $cart = Cart::where([
                            'lounge_id' => $orderDetail->lounge_id,
                            'customer_id' => $orderDetail->customer_id,
                            'cart_status' => '1'
                        ])->first();

                        if ($cart) {
                            $cart->update([
                                'cart_status' => '3',
                                'updated_ip' => $request->ip(),
                                'updated_at' => now()
                            ]);

                            $cartId = $cart->cart_id;
                        } else {
                            // No matching cart found
                            $cartId = 0;
                        }

                        Order::where('order_id', $orderDetail->order_id)->update([
                            'cart_id' => $cartId,
                            'order_status' => '1',
                            'order_token' => NULL,
                            'payment_id' => $paymentId,
                            'payment_date' => date('Y-m-d'),
                            'payment_time' => date('H:i:s')
                        ]);
                    }
                }

                Log::info("Payment captured: ID={$paymentId}, Amount=₹{$amount}, Email={$email}");
                break;

            case 'order.paid':
                Log::info('Order paid event received.');
                break;

            default:
                Log::info("Unhandled Razorpay event type: {$eventType}");
                break;
        }

        return response('Webhook handled successfully', 200);
    }
}
