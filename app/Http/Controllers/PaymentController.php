<?php

namespace App\Http\Controllers;

use App\Services\RazorPayService;
use App\Models\SubscriptionPlan;
use App\Models\TenantSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    protected $razorPayService;

    public function __construct(RazorPayService $razorPayService)
    {
        $this->razorPayService = $razorPayService;
    }

    /**
     * Create payment order
     */
    public function createOrder(Request $request)
    {
        // DEBUG: Log incoming request
        Log::info('DEBUG: Payment createOrder request received', [
            'request_data' => $request->all(),
            'plan_id' => $request->plan_id,
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
            'timestamp' => now()->toDateTimeString(),
        ]);

        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        // DEBUG: Log after validation
        Log::info('DEBUG: Request validation passed', [
            'plan_id' => $request->plan_id,
        ]);

        // Check if RazorPay is configured
        $isConfigured = $this->razorPayService->isConfigured();
        Log::info('DEBUG: Razorpay configuration check', [
            'is_configured' => $isConfigured,
            'has_key_id' => !empty(config('razorpay.key_id')),
            'has_key_secret' => !empty(config('razorpay.key_secret')),
        ]);

        if (!$isConfigured) {
            Log::error('DEBUG: Razorpay not configured');
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured',
            ], 400);
        }

        // Check if Pro plan is active or Razorpay is configured
        $proPlanActive = $this->razorPayService->isProPlanActive() || $this->razorPayService->isConfigured();
        Log::info('DEBUG: Pro plan availability check', [
            'pro_plan_active' => $proPlanActive,
            'is_pro_plan_active' => $this->razorPayService->isProPlanActive(),
            'pro_plan_mode' => config('razorpay.pro_plan_mode'),
        ]);

        if (!$proPlanActive) {
            Log::error('DEBUG: Pro plan not active');
            return response()->json([
                'success' => false,
                'message' => 'Pro plan is not available for payment',
            ], 400);
        }

        $user = Auth::user();
        Log::info('DEBUG: User retrieved', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        Log::info('DEBUG: Plan retrieved', [
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'plan_slug' => $plan->slug,
            'plan_price' => $plan->price,
            'plan_currency' => $plan->currency,
            'is_free' => $plan->isFree(),
            'requires_contact' => $plan->requiresContactForPricing(),
        ]);

        // Check if plan is payable
        if ($plan->isFree() || $plan->requiresContactForPricing()) {
            return response()->json([
                'success' => false,
                'message' => 'This plan is not available for payment',
            ], 400);
        }

        // Get user's tenant
        $tenant = $user->tenants->first();
        Log::info('DEBUG: Tenant check', [
            'has_tenant' => !is_null($tenant),
            'tenant_id' => $tenant->id ?? 'N/A',
            'tenant_slug' => $tenant->slug ?? 'N/A',
        ]);

        if (!$tenant) {
            Log::error('DEBUG: User has no tenant');
            return response()->json([
                'success' => false,
                'message' => 'User does not have a tenant',
            ], 400);
        }

        // Check if user has Free plan (required for Pro upgrade)
        $hasFreePlan = $tenant->hasFreePlan();
        Log::info('DEBUG: Free plan check', [
            'has_free_plan' => $hasFreePlan,
            'current_subscription' => $tenant->activeSubscription ? [
                'id' => $tenant->activeSubscription->id,
                'plan_slug' => $tenant->activeSubscription->plan->slug ?? 'N/A',
                'status' => $tenant->activeSubscription->status ?? 'N/A',
            ] : 'No active subscription',
        ]);

        if (!$hasFreePlan) {
            Log::error('DEBUG: User does not have Free plan');
            return response()->json([
                'success' => false,
                'message' => 'You must first subscribe to the Free plan before upgrading to Pro.',
            ], 400);
        }

        // Try to create subscription first, fallback to one-time order if subscriptions not enabled
        $useSubscriptions = true;
        $subscriptionResult = null;
        $orderResult = null;
        
        // First, try to create Razorpay Plan for recurring subscription
        Log::info('DEBUG: Attempting to create Razorpay plan for subscription', [
            'plan_id' => $plan->id,
            'plan_price' => $plan->price,
            'plan_currency' => $plan->currency,
        ]);

        $planResult = $this->razorPayService->createOrGetPlan($plan);
        
        Log::info('DEBUG: Razorpay plan creation/get result', [
            'success' => $planResult['success'] ?? 'missing',
            'has_plan_id' => isset($planResult['plan_id']),
            'plan_id' => $planResult['plan_id'] ?? 'N/A',
            'error' => $planResult['error'] ?? null,
        ]);
        
        if ($planResult['success']) {
            // Plan created successfully, try to create subscription
            $razorpayPlanId = $planResult['plan_id'];
            
            $subscriptionData = [
                'plan_id' => $razorpayPlanId,
                'notes' => [
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                ],
            ];
            
            Log::info('DEBUG: Starting Razorpay subscription creation', [
                'razorpay_plan_id' => $razorpayPlanId,
                'subscription_data' => $subscriptionData,
            ]);
            
            $subscriptionResult = $this->razorPayService->createSubscription($subscriptionData);
            
            if (!$subscriptionResult['success']) {
                Log::warning('DEBUG: Subscription creation failed, falling back to one-time order', [
                    'error' => $subscriptionResult['error'] ?? 'Unknown error',
                ]);
                $useSubscriptions = false;
            }
        } else {
            // Plan creation failed (likely subscriptions not enabled), use one-time order
            Log::warning('DEBUG: Plan creation failed, using one-time order instead', [
                'error' => $planResult['error'] ?? 'Unknown error',
            ]);
            $useSubscriptions = false;
        }
        
        // If subscriptions failed, create one-time order instead
        if (!$useSubscriptions || !$subscriptionResult || !$subscriptionResult['success']) {
            Log::info('DEBUG: Creating one-time payment order', [
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'currency' => $plan->currency,
            ]);
            
            $orderData = [
                'amount' => $plan->price,
                'currency' => $plan->currency,
                'receipt' => 'plan_' . $plan->id . '_' . $tenant->id . '_' . time(),
                'notes' => [
                    'tenant_id' => $tenant->id,
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'payment_type' => 'one_time',
                ],
            ];
            
            $orderResult = $this->razorPayService->createOrder($orderData);
            
            Log::info('DEBUG: One-time order creation result', [
                'success' => $orderResult['success'] ?? 'missing',
                'has_order_id' => isset($orderResult['order_id']),
                'order_id' => $orderResult['order_id'] ?? 'N/A',
                'error' => $orderResult['error'] ?? null,
            ]);
            
            if (!$orderResult['success']) {
                Log::error('DEBUG: Both subscription and order creation failed', [
                    'subscription_error' => $subscriptionResult['error'] ?? 'N/A',
                    'order_error' => $orderResult['error'] ?? 'N/A',
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment. Please try again or contact support.',
                    'error' => $orderResult['error'] ?? 'Payment creation failed',
                ], 500);
            }
        }

        // Prepare response data based on payment type
        if ($useSubscriptions && $subscriptionResult && $subscriptionResult['success']) {
            // Subscription payment
            $subscription = $subscriptionResult['subscription'];
            $subscriptionId = $subscriptionResult['subscription_id'];
            
            // Convert subscription to array if needed
            if (is_object($subscription)) {
                $subscription = json_decode(json_encode($subscription), true);
            }
            
            // Ensure subscription has id
            if (is_array($subscription) && !isset($subscription['id']) && $subscriptionId) {
                $subscription['id'] = $subscriptionId;
            }
            
            Log::info('DEBUG: Using subscription payment', [
                'subscription_id' => $subscriptionId,
            ]);
            
            $responseData = [
                'success' => true,
                'subscription' => $subscription,
                'subscription_id' => $subscriptionId,
                'key_id' => config('razorpay.key_id'),
                'currency' => $plan->currency,
                'amount' => $plan->price,
                'name' => config('app.name'),
                'description' => $plan->name . ' Subscription',
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ];
        } else {
            // One-time order payment
            $order = $orderResult['order'];
            $orderId = $orderResult['order_id'];
            
            // Convert order to array if needed
            if (is_object($order)) {
                $order = json_decode(json_encode($order), true);
            }
            
            Log::info('DEBUG: Using one-time order payment', [
                'order_id' => $orderId,
            ]);
            
            $responseData = [
                'success' => true,
                'order' => $order,
                'order_id' => $orderId,
                'key_id' => config('razorpay.key_id'),
                'currency' => $plan->currency,
                'amount' => $plan->price,
                'name' => config('app.name'),
                'description' => $plan->name . ' Payment',
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ];
        }
        
        // DEBUG: Log what we're sending to frontend
        Log::info('DEBUG: JSON response being sent to frontend', [
            'response_keys' => array_keys($responseData),
            'has_subscription' => isset($responseData['subscription']),
            'subscription_id_in_subscription' => is_array($responseData['subscription']) ? ($responseData['subscription']['id'] ?? 'missing') : 'not_array',
            'subscription_id_at_root' => $responseData['subscription_id'] ?? 'missing',
            'response_sample' => json_encode($responseData, JSON_PRETTY_PRINT),
        ]);

        return response()->json($responseData);
    }

    /**
     * Handle payment success
     */
    public function success(Request $request)
    {
        // For subscriptions, we get subscription_id instead of order_id
        $isSubscription = $request->has('razorpay_subscription_id');
        
        if ($isSubscription) {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_subscription_id' => 'required|string',
                'razorpay_signature' => 'required|string',
            ]);
        } else {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
                'razorpay_order_id' => 'required|string|not_in:undefined',
                'razorpay_signature' => 'required|string',
            ], [
                'razorpay_order_id.not_in' => 'Invalid order ID. Please try the payment again.',
                'razorpay_order_id.required' => 'Order ID is required for payment verification.',
            ]);
            
            // Additional check for undefined string
            if ($request->razorpay_order_id === 'undefined' || empty(trim($request->razorpay_order_id))) {
                Log::error('Order ID is undefined or empty', [
                    'payment_id' => $request->razorpay_payment_id,
                    'order_id' => $request->razorpay_order_id,
                    'signature_present' => !empty($request->razorpay_signature),
                ]);
                
                return redirect()->route('payment.failure')
                    ->with('error', 'Invalid order ID. Please contact support with your payment ID: ' . $request->razorpay_payment_id);
            }
        }

        $user = Auth::user();
        $tenant = $user->tenants->first();

        if (!$tenant) {
            return redirect()->route('subscription.pricing')
                ->with('error', 'User does not have a tenant');
        }

        // Handle subscription vs one-time payment
        if ($isSubscription) {
            // For subscriptions, verify using subscription_id
            $verificationResult = $this->razorPayService->verifySubscription([
                'razorpay_subscription_id' => $request->razorpay_subscription_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        } else {
            // For one-time payments, verify using order_id
            $verificationResult = $this->razorPayService->verifyPayment([
                'razorpay_order_id' => $request->razorpay_order_id,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_signature' => $request->razorpay_signature,
            ]);
        }

        $signatureVerified = $verificationResult['success'] && $verificationResult['verified'];
        
        // Get payment details first to verify payment status regardless of signature
        $paymentResult = $this->razorPayService->getPayment($request->razorpay_payment_id);
        
        if (!$paymentResult['success']) {
            // If signature verification also failed, log both errors
            if (!$signatureVerified) {
                Log::error('Payment verification and fetch both failed', [
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'payment_id' => $request->razorpay_payment_id,
                    'is_subscription' => $isSubscription,
                    'order_id' => $isSubscription ? 'N/A' : $request->razorpay_order_id,
                    'subscription_id' => $isSubscription ? $request->razorpay_subscription_id : 'N/A',
                    'signature_error' => $verificationResult['error'] ?? 'Unknown error',
                    'fetch_error' => $paymentResult['error'] ?? 'Unknown error',
                ]);
            }
            
            return redirect()->route('payment.failure')
                ->with('error', 'Failed to fetch payment details');
        }

        $payment = $paymentResult['payment'];
        
        // For one-time payments, verify order_id matches
        if (!$isSubscription) {
            $paymentOrderId = $payment['order_id'] ?? null;
            if ($paymentOrderId !== $request->razorpay_order_id) {
                Log::error('Order ID mismatch', [
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'payment_id' => $request->razorpay_payment_id,
                    'expected_order_id' => $request->razorpay_order_id,
                    'actual_order_id' => $paymentOrderId,
                    'signature_verified' => $signatureVerified,
                ]);
                
                return redirect()->route('payment.failure')
                    ->with('error', 'Payment order ID mismatch. Please contact support.');
            }
        }
        
        // If signature verification failed but payment is valid, log warning but proceed
        if (!$signatureVerified) {
            Log::warning('Payment signature verification failed but proceeding with API verification', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'is_subscription' => $isSubscription,
                'order_id' => $isSubscription ? 'N/A' : $request->razorpay_order_id,
                'subscription_id' => $isSubscription ? $request->razorpay_subscription_id : 'N/A',
                'payment_status' => $payment['status'] ?? 'unknown',
                'signature_error' => $verificationResult['error'] ?? 'Unknown error',
            ]);
        }
        
        // Check payment status
        $paymentStatus = $payment['status'] ?? null;
        
        // If payment is authorized but not captured, capture it
        if ($paymentStatus === 'authorized') {
            $amount = $payment['amount'] / 100; // Convert from paise
            $captureResult = $this->razorPayService->capturePayment($request->razorpay_payment_id, $amount);
            
            if (!$captureResult['success']) {
                Log::error('Payment capture failed', [
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'payment_id' => $request->razorpay_payment_id,
                    'error' => $captureResult['error'] ?? 'Unknown error',
                ]);
                
                return redirect()->route('payment.failure')
                    ->with('error', 'Payment capture failed. Please contact support.');
            }
            
            // Update payment object with captured payment details
            $payment = $captureResult['payment'];
            $paymentStatus = $payment['status'] ?? null;
        }
        
        // Verify payment is captured
        if ($paymentStatus !== 'captured') {
            Log::error('Payment not captured', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'payment_status' => $paymentStatus,
            ]);
            
            return redirect()->route('payment.failure')
                ->with('error', 'Payment is not in captured state. Please contact support.');
        }
        
        // Check if this payment has already been processed
        $existingSubscription = TenantSubscription::where('payment_id', $request->razorpay_payment_id)
            ->where('tenant_id', $tenant->id)
            ->first();
            
        if ($existingSubscription && $existingSubscription->status === 'active') {
            Log::info('Payment already processed', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'subscription_id' => $existingSubscription->id,
            ]);
            
            return redirect()->route('subscription.show', $tenant->slug)
                ->with('success', 'Payment already processed. Your subscription is active.');
        }
        
        // Find the plan based on amount
        $amount = $payment['amount'] / 100; // Convert from paise
        $plan = SubscriptionPlan::where('price', $amount)
            ->where('slug', 'pro')
            ->first();

        if (!$plan) {
            Log::error('Plan not found for payment', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'amount' => $amount,
            ]);
            
            return redirect()->route('payment.failure')
                ->with('error', 'Invalid plan');
        }

        // Process successful payment
        $razorpaySubscriptionId = $isSubscription ? $request->razorpay_subscription_id : null;
        
        $processResult = $this->razorPayService->processSuccessfulPayment([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $isSubscription ? null : $request->razorpay_order_id,
            'amount' => $payment['amount'],
            'currency' => $payment['currency'],
        ], $user, $plan, $razorpaySubscriptionId);

        if (!$processResult['success']) {
            return redirect()->route('payment.failure')
                ->with('error', 'Failed to process payment');
        }

        // Prepare success message based on payment type
        if ($isSubscription) {
            $successMessage = '🎉 Recurring Subscription Activated! Your Pro plan subscription is now active and will automatically renew monthly. You will be charged automatically each month until you cancel.';
        } else {
            $successMessage = 'Payment successful! Your Pro subscription has been activated.';
        }

        // Redirect to tenant subscription page
        return redirect()->route('subscription.show', $tenant->slug)
            ->with('success', $successMessage);
    }

    /**
     * Handle payment failure
     */
    public function failure(Request $request)
    {
        $error = $request->get('error', 'Payment failed');
        
        return view('payment.failure', compact('error'));
    }

    /**
     * Show payment success page
     */
    public function successPage()
    {
        $user = Auth::user();
        $tenant = $user->tenants->first();
        
        if (!$tenant) {
            return redirect()->route('subscription.pricing')
                ->with('error', 'User does not have a tenant');
        }

        $subscription = TenantSubscription::where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        return view('payment.success', compact('subscription'));
    }

    /**
     * Handle RazorPay webhook
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Razorpay-Signature');

        if (!$signature) {
            return response()->json(['error' => 'Missing signature'], 400);
        }

        $result = $this->razorPayService->handleWebhook($payload, $signature);

        if ($result['success']) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['error' => $result['error']], 400);
        }
    }

    /**
     * Get payment status
     */
    public function status(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|string',
        ]);

        $result = $this->razorPayService->getPayment($request->payment_id);

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch payment status',
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'payment' => $result['payment'],
        ]);
    }

    /**
     * Check if Pro plan is available for payment
     */
    public function checkProPlanAvailability()
    {
        $isConfigured = $this->razorPayService->isConfigured();
        $isActive = $this->razorPayService->isProPlanActive();

        return response()->json([
            'success' => true,
            'available' => $isConfigured && $isActive,
            'mode' => config('razorpay.pro_plan_mode'),
        ]);
    }
}