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
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        // Check if RazorPay is configured
        if (!$this->razorPayService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment gateway not configured',
            ], 400);
        }

        // Check if Pro plan is active or Razorpay is configured
        $proPlanActive = $this->razorPayService->isProPlanActive() || $this->razorPayService->isConfigured();
        if (!$proPlanActive) {
            return response()->json([
                'success' => false,
                'message' => 'Pro plan is not available for payment',
            ], 400);
        }

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Check if plan is payable
        if ($plan->isFree() || $plan->requiresContactForPricing()) {
            return response()->json([
                'success' => false,
                'message' => 'This plan is not available for payment',
            ], 400);
        }

        // Get user's tenant
        $tenant = $user->tenants->first();
        if (!$tenant) {
            return response()->json([
                'success' => false,
                'message' => 'User does not have a tenant',
            ], 400);
        }

        // Check if user has Free plan (required for Pro upgrade)
        if (!$tenant->hasFreePlan()) {
            return response()->json([
                'success' => false,
                'message' => 'You must first subscribe to the Free plan before upgrading to Pro.',
            ], 400);
        }

        // Create order
        $orderData = [
            'amount' => $plan->price,
            'currency' => $plan->currency,
            'receipt' => 'TL_' . substr($tenant->id, 0, 8) . '_' . time(),
            'notes' => [
                'tenant_id' => $tenant->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
            ],
        ];

        $orderResult = $this->razorPayService->createOrder($orderData);

        if (!$orderResult['success']) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment order',
                'error' => $orderResult['error'],
            ], 500);
        }

        return response()->json([
            'success' => true,
            'order' => $orderResult['order'],
            'key_id' => config('razorpay.key_id'),
            'currency' => $plan->currency,
            'amount' => $plan->price,
            'name' => config('app.name'),
            'description' => $plan->name . ' Subscription',
            'prefill' => [
                'name' => $user->name,
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Handle payment success
     */
    public function success(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        $user = Auth::user();
        $tenant = $user->tenants->first();

        if (!$tenant) {
            return redirect()->route('subscription.pricing')
                ->with('error', 'User does not have a tenant');
        }

        // Verify payment signature
        $verificationResult = $this->razorPayService->verifyPayment([
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
        ]);

        if (!$verificationResult['success'] || !$verificationResult['verified']) {
            Log::error('Payment verification failed', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'error' => $verificationResult['error'] ?? 'Unknown error',
            ]);

            return redirect()->route('payment.failure')
                ->with('error', 'Payment verification failed');
        }

        // Get payment details
        $paymentResult = $this->razorPayService->getPayment($request->razorpay_payment_id);
        
        if (!$paymentResult['success']) {
            Log::error('Failed to fetch payment details', [
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'payment_id' => $request->razorpay_payment_id,
                'error' => $paymentResult['error'] ?? 'Unknown error',
            ]);
            
            return redirect()->route('payment.failure')
                ->with('error', 'Failed to fetch payment details');
        }

        $payment = $paymentResult['payment'];
        
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
        $processResult = $this->razorPayService->processSuccessfulPayment([
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'amount' => $payment['amount'],
            'currency' => $payment['currency'],
        ], $user, $plan);

        if (!$processResult['success']) {
            return redirect()->route('payment.failure')
                ->with('error', 'Failed to process payment');
        }

        // Redirect to tenant subscription page
        return redirect()->route('subscription.show', $tenant->slug)
            ->with('success', 'Payment successful! Your Pro subscription has been activated.');
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