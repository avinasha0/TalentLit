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
                'payment_capture' => 1, // Auto-capture payment (1 = auto, 0 = manual)
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
     * Verify subscription signature
     */
    public function verifySubscription(array $attributes): array
    {
        try {
            Log::info('RazorPay Subscription Verification Attempt', [
                'subscription_id' => $attributes['razorpay_subscription_id'] ?? 'missing',
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
                'signature_present' => isset($attributes['razorpay_signature']),
            ]);
            
            $this->api->utility->verifyPaymentSignature($attributes);
            
            Log::info('RazorPay Subscription Verification Success', [
                'subscription_id' => $attributes['razorpay_subscription_id'] ?? 'missing',
            ]);
            
            return [
                'success' => true,
                'verified' => true,
            ];
        } catch (SignatureVerificationError $e) {
            Log::error('RazorPay Subscription Verification Failed: ' . $e->getMessage(), [
                'subscription_id' => $attributes['razorpay_subscription_id'] ?? 'missing',
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
            ]);
            
            return [
                'success' => false,
                'verified' => false,
                'error' => 'Subscription verification failed: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Subscription Verification Error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'verified' => false,
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
            // Log verification attempt for debugging
            Log::info('RazorPay Payment Verification Attempt', [
                'order_id' => $attributes['razorpay_order_id'] ?? 'missing',
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
                'signature_present' => isset($attributes['razorpay_signature']),
            ]);
            
            $this->api->utility->verifyPaymentSignature($attributes);
            
            Log::info('RazorPay Payment Verification Success', [
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
            ]);
            
            return [
                'success' => true,
                'verified' => true,
            ];
        } catch (SignatureVerificationError $e) {
            Log::error('RazorPay Payment Verification Failed: ' . $e->getMessage(), [
                'order_id' => $attributes['razorpay_order_id'] ?? 'missing',
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
                'error_details' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode() ?? 'N/A',
                ],
            ]);
            
            return [
                'success' => false,
                'verified' => false,
                'error' => 'Payment verification failed: ' . $e->getMessage(),
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Payment Verification Error: ' . $e->getMessage(), [
                'order_id' => $attributes['razorpay_order_id'] ?? 'missing',
                'payment_id' => $attributes['razorpay_payment_id'] ?? 'missing',
                'error_type' => get_class($e),
            ]);
            
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
            
            // Check if payment is already captured
            if (isset($payment['status']) && $payment['status'] === 'captured') {
                Log::info('Payment already captured', [
                    'payment_id' => $paymentId,
                    'status' => $payment['status'],
                ]);
                
                return [
                    'success' => true,
                    'payment' => $payment,
                ];
            }
            
            // Capture the payment
            $captured = $payment->capture([
                'amount' => $amount * 100, // Convert to paise
                'currency' => config('razorpay.currency'),
            ]);

            Log::info('Payment captured successfully', [
                'payment_id' => $paymentId,
                'amount' => $amount,
            ]);

            return [
                'success' => true,
                'payment' => $captured,
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Payment Capture Failed: ' . $e->getMessage(), [
                'payment_id' => $paymentId,
                'amount' => $amount,
                'error_type' => get_class($e),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create or get Razorpay Plan for recurring subscription
     */
    public function createOrGetPlan(SubscriptionPlan $plan): array
    {
        try {
            // Check if plan already exists in Razorpay by searching
            $plans = $this->api->plan->all(['count' => 100]);
            
            // Look for existing plan with matching amount
            $planAmount = $plan->price * 100; // Convert to paise
            foreach ($plans['items'] as $razorpayPlan) {
                if ($razorpayPlan['amount'] == $planAmount && 
                    $razorpayPlan['currency'] == strtoupper($plan->currency) &&
                    $razorpayPlan['interval'] == 1) { // Monthly
                    Log::info('Found existing Razorpay plan', [
                        'plan_id' => $razorpayPlan['id'],
                        'amount' => $razorpayPlan['amount'],
                    ]);
                    return [
                        'success' => true,
                        'plan_id' => $razorpayPlan['id'],
                        'plan' => $razorpayPlan,
                    ];
                }
            }
            
            // Create new plan if not found
            $planData = [
                'period' => 'monthly',
                'interval' => 1,
                'item' => [
                    'name' => $plan->name . ' Subscription',
                    'description' => $plan->description ?? $plan->name . ' Monthly Subscription',
                    'amount' => $planAmount,
                    'currency' => strtoupper($plan->currency),
                ],
                'notes' => [
                    'internal_plan_id' => $plan->id,
                    'plan_slug' => $plan->slug,
                ],
            ];
            
            $razorpayPlan = $this->api->plan->create($planData);
            
            Log::info('Created new Razorpay plan', [
                'plan_id' => $razorpayPlan['id'],
                'amount' => $razorpayPlan['amount'],
            ]);
            
            return [
                'success' => true,
                'plan_id' => $razorpayPlan['id'],
                'plan' => $razorpayPlan,
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Plan Creation Failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Create Razorpay Subscription for recurring payments
     */
    public function createSubscription(array $data): array
    {
        try {
            $subscriptionData = [
                'plan_id' => $data['plan_id'], // Razorpay plan ID
                'customer_notify' => 1,
                'quantity' => 1,
                'total_count' => 0, // 0 = infinite recurring (until cancelled)
                'start_at' => time() + 60, // Start 1 minute from now
                'notes' => $data['notes'] ?? [],
            ];

            // Add customer details if provided
            if (isset($data['customer'])) {
                $subscriptionData['customer_notify'] = 1;
            }

            $subscription = $this->api->subscription->create($subscriptionData);

            Log::info('Razorpay subscription created', [
                'subscription_id' => $subscription['id'],
                'plan_id' => $data['plan_id'],
            ]);

            return [
                'success' => true,
                'subscription' => $subscription,
                'subscription_id' => $subscription['id'],
            ];
        } catch (\Exception $e) {
            Log::error('RazorPay Subscription Creation Failed: ' . $e->getMessage(), [
                'plan_id' => $data['plan_id'] ?? 'N/A',
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Process successful subscription payment
     */
    public function processSuccessfulPayment(array $paymentData, User $user, SubscriptionPlan $plan, string $razorpaySubscriptionId = null): array
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
                    'payment_id' => $paymentData['razorpay_payment_id'] ?? null,
                    'amount_paid' => isset($paymentData['amount']) ? $paymentData['amount'] / 100 : $plan->price, // Convert from paise
                    'currency' => $paymentData['currency'] ?? $plan->currency,
                    'external_subscription_id' => $razorpaySubscriptionId, // Store Razorpay subscription ID for recurring payments
                ]
            );

            Log::info('Subscription activated', [
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'razorpay_subscription_id' => $razorpaySubscriptionId,
            ]);

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
                case 'invoice.paid':
                    // Recurring subscription payment - invoice.paid is sent when subscription invoice is paid
                    return $this->handleInvoicePaid($payload['payload']['invoice']['entity'] ?? $payload['payload']);
                case 'invoice.partially_paid':
                    // Subscription payment partially paid
                    return $this->handleInvoicePartiallyPaid($payload['payload']['invoice']['entity'] ?? $payload['payload']);
                case 'invoice.expired':
                    // Subscription invoice expired (payment failed or not completed)
                    return $this->handleInvoiceExpired($payload['payload']['invoice']['entity'] ?? $payload['payload']);
                case 'order.paid':
                    // One-time payment order completed
                    return $this->handleOrderPaid($payload['payload']['order']['entity'] ?? $payload['payload']);
                default:
                    Log::info('Unhandled webhook event', ['event' => $event]);
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
     * Handle invoice paid webhook (recurring subscription payment)
     * This is the actual event Razorpay sends for subscription charges
     */
    protected function handleInvoicePaid($invoice): array
    {
        try {
            Log::info('Invoice paid webhook received', ['invoice' => $invoice]);
            
            if (!$invoice || !isset($invoice['subscription_id'])) {
                Log::error('Invalid invoice data in webhook', ['invoice' => $invoice]);
                return [
                    'success' => false,
                    'error' => 'Invalid invoice data',
                ];
            }

            $razorpaySubscriptionId = $invoice['subscription_id'];
            $paymentId = $invoice['payment_id'] ?? null;
            $amount = isset($invoice['amount']) ? $invoice['amount'] / 100 : null;
            
            // Find subscription by Razorpay subscription ID
            $tenantSubscription = TenantSubscription::where('external_subscription_id', $razorpaySubscriptionId)
                ->first();
            
            if ($tenantSubscription) {
                // Update subscription with new payment - extend by 1 month
                $tenantSubscription->update([
                    'status' => 'active',
                    'payment_id' => $paymentId,
                    'starts_at' => now(),
                    'expires_at' => now()->addMonth(), // Extend by 1 month
                    'amount_paid' => $amount ?? $tenantSubscription->amount_paid,
                ]);
                
                Log::info('Recurring payment processed via invoice.paid', [
                    'subscription_id' => $razorpaySubscriptionId,
                    'tenant_id' => $tenantSubscription->tenant_id,
                    'payment_id' => $paymentId,
                    'amount' => $amount,
                ]);
            } else {
                Log::warning('Subscription not found for recurring payment', [
                    'razorpay_subscription_id' => $razorpaySubscriptionId,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Invoice paid handled',
            ];
        } catch (\Exception $e) {
            Log::error('Handle invoice paid failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle invoice partially paid webhook (subscription payment partially completed)
     */
    protected function handleInvoicePartiallyPaid($invoice): array
    {
        try {
            Log::info('Invoice partially paid webhook received', ['invoice' => $invoice]);
            
            if (!$invoice || !isset($invoice['subscription_id'])) {
                Log::error('Invalid invoice data in webhook', ['invoice' => $invoice]);
                return [
                    'success' => false,
                    'error' => 'Invalid invoice data',
                ];
            }

            $razorpaySubscriptionId = $invoice['subscription_id'];
            
            // Find subscription by Razorpay subscription ID
            $tenantSubscription = TenantSubscription::where('external_subscription_id', $razorpaySubscriptionId)
                ->first();
            
            if ($tenantSubscription) {
                // You might want to handle partial payments differently
                // For now, we'll log it but keep subscription active
                Log::warning('Subscription payment partially paid', [
                    'subscription_id' => $razorpaySubscriptionId,
                    'tenant_id' => $tenantSubscription->tenant_id,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Invoice partially paid handled',
            ];
        } catch (\Exception $e) {
            Log::error('Handle invoice partially paid error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle invoice expired webhook (subscription payment failed or not completed)
     * This is the actual event Razorpay sends when subscription invoice expires
     */
    protected function handleInvoiceExpired($invoice): array
    {
        try {
            Log::info('Invoice expired webhook received', ['invoice' => $invoice]);
            
            if (!$invoice || !isset($invoice['subscription_id'])) {
                Log::error('Invalid invoice data in webhook', ['invoice' => $invoice]);
                return [
                    'success' => false,
                    'error' => 'Invalid invoice data',
                ];
            }

            $razorpaySubscriptionId = $invoice['subscription_id'];
            
            // Find subscription by Razorpay subscription ID
            $tenantSubscription = TenantSubscription::where('external_subscription_id', $razorpaySubscriptionId)
                ->first();
            
            if ($tenantSubscription) {
                // Update subscription status - you might want to keep it active for a grace period
                // or mark it as failed based on your business logic
                $tenantSubscription->update([
                    'status' => 'failed', // or 'past_due' if you want to give grace period
                ]);
                
                Log::warning('Subscription invoice expired (payment failed)', [
                    'subscription_id' => $razorpaySubscriptionId,
                    'tenant_id' => $tenantSubscription->tenant_id,
                ]);
            }

            return [
                'success' => true,
                'message' => 'Invoice expired handled',
            ];
        } catch (\Exception $e) {
            Log::error('Handle invoice expired error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle order paid webhook (one-time payment completed)
     */
    protected function handleOrderPaid($order): array
    {
        try {
            Log::info('Order paid webhook received', ['order' => $order]);
            
            // This is handled by payment.captured for most cases
            // But we can use this for additional order-specific logic if needed
            
            return [
                'success' => true,
                'message' => 'Order paid handled',
            ];
        } catch (\Exception $e) {
            Log::error('Handle order paid error: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
