<?php

namespace App\Services;

use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\Log;
use App\Models\SubscriptionPlan;
use App\Models\TenantSubscription;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;

class RazorPayService
{
    protected $api;
    protected $keyId;
    protected $keySecret;

    public function __construct()
    {
        $this->keyId = config('razorpay.key_id');
        $this->keySecret = config('razorpay.key_secret');
        
        if ($this->keyId && $this->keySecret) {
            $this->api = new Api($this->keyId, $this->keySecret);
        }
    }

    /**
     * Check if RazorPay is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->keyId) && !empty($this->keySecret);
    }

    /**
     * Check if Pro plan is in payment mode
     */
    public function isProPlanActive(): bool
    {
        return config('razorpay.pro_plan_mode') === 'active';
    }

    /**
     * Create a RazorPay order
     */
    public function createOrder(array $data): array
    {
        try {
            $orderData = [
                'amount' => $data['amount'] * 100, // Convert to paise
                'currency' => $data['currency'] ?? config('razorpay.currency'),
                'receipt' => substr($data['receipt'], 0, 40), // RazorPay receipt max 40 chars
                'notes' => $data['notes'] ?? [],
            ];

            $order = $this->api->order->create($orderData);

            return [
                'success' => true,
                'order' => $order,
                'order_id' => $order['id'],
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Order Creation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Verify payment signature
     */
    public function verifyPayment(array $attributes): array
    {
        try {
            $this->api->utility->verifyPaymentSignature($attributes);
            
            return [
                'success' => true,
                'verified' => true,
            ];
        } catch (SignatureVerificationError $e) {
            Log::error('RazorPay Payment Verification Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'verified' => false,
                'error' => 'Payment verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Payment Verification Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'verified' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Capture payment
     */
    public function capturePayment(string $paymentId, int $amount): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            $captured = $payment->capture([
                'amount' => $amount * 100, // Convert to paise
                'currency' => config('razorpay.currency'),
            ]);

            return [
                'success' => true,
                'payment' => $captured,
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Payment Capture Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create subscription for Pro plan
     */
    public function createSubscription(array $data): array
    {
        try {
            $subscriptionData = [
                'plan_id' => $data['plan_id'],
                'customer_notify' => 1,
                'quantity' => 1,
                'total_count' => 12, // 12 months
                'start_at' => time(),
                'expire_by' => time() + (365 * 24 * 60 * 60), // 1 year
                'notes' => $data['notes'] ?? [],
            ];

            $subscription = $this->api->subscription->create($subscriptionData);

            return [
                'success' => true,
                'subscription' => $subscription,
                'subscription_id' => $subscription['id'],
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Subscription Creation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process successful payment
     */
    public function processSuccessfulPayment(array $paymentData, User $user, SubscriptionPlan $plan): array
    {
        try {
            // Get user's tenant
            $tenant = $user->tenants->first();
            
            if (!$tenant) {
                return [
                    'success' => false,
                    'error' => 'User does not have a tenant',
                ];
            }

            // Create or update tenant subscription
            $subscription = TenantSubscription::updateOrCreate(
                ['tenant_id' => $tenant->id],
                [
                    'subscription_plan_id' => $plan->id,
                    'status' => 'active',
                    'starts_at' => now(),
                    'expires_at' => now()->addMonth(),
                    'payment_method' => 'razorpay',
                    'payment_id' => $paymentData['razorpay_payment_id'],
                    'amount_paid' => $paymentData['amount'] / 100, // Convert from paise
                    'currency' => $paymentData['currency'],
                ]
            );

            return [
                'success' => true,
                'subscription' => $subscription,
                'message' => 'Subscription activated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Payment Processing Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => 'Failed to process payment',
            ];
        }
    }

    /**
     * Get payment details
     */
    public function getPayment(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            
            return [
                'success' => true,
                'payment' => $payment,
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Payment Fetch Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Refund payment
     */
    public function refundPayment(string $paymentId, int $amount = null): array
    {
        try {
            $refundData = [];
            if ($amount) {
                $refundData['amount'] = $amount * 100; // Convert to paise
            }

            $refund = $this->api->payment->fetch($paymentId)->refund($refundData);

            return [
                'success' => true,
                'refund' => $refund,
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Refund Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle webhook events
     */
    public function handleWebhook(array $payload, string $signature): array
    {
        try {
            // Verify webhook signature
            $this->api->utility->verifyWebhookSignature($payload, $signature, config('razorpay.webhook_secret'));

            $event = $payload['event'];
            $payment = $payload['payload']['payment']['entity'] ?? null;

            switch ($event) {
                case 'payment.captured':
                    return $this->handlePaymentCaptured($payment);
                case 'payment.failed':
                    return $this->handlePaymentFailed($payment);
                case 'subscription.charged':
                    return $this->handleSubscriptionCharged($payload['payload']['subscription']['entity']);
                case 'subscription.cancelled':
                    return $this->handleSubscriptionCancelled($payload['payload']['subscription']['entity']);
                default:
                    return [
                        'success' => true,
                        'message' => 'Event not handled',
                    ];
            }
        } catch (\Exception $e) {
            Log::error('RazorPay Webhook Handling Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle payment captured webhook
     */
    protected function handlePaymentCaptured($payment): array
    {
        // Update subscription status
        if ($payment && isset($payment['id'])) {
            $subscription = TenantSubscription::where('payment_id', $payment['id'])->first();
            if ($subscription) {
                $subscription->update(['status' => 'active']);
            }
        }

        return [
            'success' => true,
            'message' => 'Payment captured handled',
        ];
    }

    /**
     * Handle payment failed webhook
     */
    protected function handlePaymentFailed($payment): array
    {
        // Update subscription status
        if ($payment && isset($payment['id'])) {
            $subscription = TenantSubscription::where('payment_id', $payment['id'])->first();
            if ($subscription) {
                $subscription->update(['status' => 'failed']);
            }
        }

        return [
            'success' => true,
            'message' => 'Payment failed handled',
        ];
    }

    /**
     * Handle subscription charged webhook
     */
    protected function handleSubscriptionCharged($subscription): array
    {
        // Handle recurring payment
        return [
            'success' => true,
            'message' => 'Subscription charged handled',
        ];
    }

    /**
     * Handle subscription cancelled webhook
     */
    protected function handleSubscriptionCancelled($subscription): array
    {
        // Handle subscription cancellation
        return [
            'success' => true,
            'message' => 'Subscription cancelled handled',
        ];
    }
}
