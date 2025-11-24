<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use App\Models\TenantSubscription;
use App\Support\Tenancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        $plans = SubscriptionPlan::active()->orderBy('price')->get();
        
        return view('subscription.pricing', compact('plans'));
    }

    /**
     * Display the current subscription for a tenant.
     */
    public function show(Request $request)
    {
        $tenant = Tenancy::get();
        // Get the latest subscription (including cancelled ones)
        $subscription = $tenant->subscription ?? $tenant->activeSubscription;
        $plan = $subscription?->plan;
        
        // Get usage statistics
        $usage = $this->getUsageStatistics($tenant);
        
        // Calculate days until renewal (even if cancelled, show days left for already paid period)
        $daysUntilRenewal = null;
        if ($subscription && $subscription->expires_at) {
            $daysUntilRenewal = max(0, now()->diffInDays($subscription->expires_at, false));
        }
        
        return view('subscription.show', compact('subscription', 'plan', 'usage', 'daysUntilRenewal'));
    }

    /**
     * Subscribe to a plan (for free plans only).
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $tenant = Tenancy::get();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Check if tenant already has an active subscription
        $activeSubscription = $tenant->activeSubscription;
        if ($activeSubscription) {
            // If trying to subscribe to Pro plan, check if user has Free plan
            if ($plan->slug === 'pro' && $activeSubscription->plan->slug !== 'free') {
                return redirect()->back()->with('error', 'You already have an active subscription. Only Free plan users can upgrade to Pro.');
            } elseif ($plan->slug === 'pro' && $activeSubscription->plan->slug === 'free') {
                return redirect()->back()->with('error', 'You already have a Free plan. Please use the upgrade option to switch to Pro.');
            } elseif ($plan->slug !== 'pro') {
                return redirect()->back()->with('error', 'You already have an active subscription.');
            }
        }

        // Check if plan requires payment
        if (!$plan->isFree()) {
            // Check if Pro plan is in payment mode
            if ($plan->slug === 'pro' && config('razorpay.pro_plan_mode') === 'active' && config('razorpay.key_id')) {
                // Redirect to payment flow
                return redirect()->route('payment.create-order')->with('plan_id', $plan->id);
            } else {
                // Plan not available for direct subscription
                return redirect()->back()->with('error', 'This plan is not available for direct subscription. Please contact support.');
            }
        }

        DB::transaction(function () use ($tenant, $plan) {
            // Cancel any existing subscription
            $tenant->subscription?->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ]);

            // Create new subscription
            TenantSubscription::create([
                'tenant_id' => $tenant->id,
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => null, // Free plans don't expire
                'payment_method' => 'free',
            ]);
        });

        return redirect()->route('subscription.show')
            ->with('success', 'Successfully subscribed to ' . $plan->name . ' plan!');
    }

    /**
     * Cancel current subscription.
     */
    public function cancel(Request $request)
    {
        $tenant = Tenancy::get();
        // Get subscription (including recently cancelled ones that might still be active)
        $subscription = $tenant->subscription ?? $tenant->activeSubscription;

        if (!$subscription) {
            return redirect()->back()->with('error', 'No subscription found.');
        }

        if ($subscription->status === 'cancelled') {
            return redirect()->back()->with('error', 'Subscription is already cancelled.');
        }

        // Cancel subscription but keep expires_at unchanged (user already paid for the period)
        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            // expires_at remains unchanged - user keeps access until the end of paid period
        ]);

        return redirect()->back()->with('success', 'Subscription cancelled successfully. You will continue to have access until ' . ($subscription->expires_at ? $subscription->expires_at->format('F d, Y') : 'the end of your billing period') . '.');
    }

    /**
     * Get usage statistics for the tenant.
     */
    private function getUsageStatistics($tenant)
    {
        $currentPlan = $tenant->currentPlan();
        
        if (!$currentPlan) {
            return null;
        }

        return [
            'users' => [
                'current' => $tenant->users()->count(),
                'limit' => $currentPlan->max_users,
                'remaining' => $tenant->getRemainingLimit('max_users', $tenant->users()->count()),
            ],
            'job_openings' => [
                'current' => $tenant->jobOpenings()->count(),
                'limit' => $currentPlan->max_job_openings,
                'remaining' => $tenant->getRemainingLimit('max_job_openings', $tenant->jobOpenings()->count()),
            ],
            'candidates' => [
                'current' => $tenant->candidates()->count(),
                'limit' => $currentPlan->max_candidates,
                'remaining' => $tenant->getRemainingLimit('max_candidates', $tenant->candidates()->count()),
            ],
            'applications_this_month' => [
                'current' => $tenant->applications()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'limit' => $currentPlan->max_applications_per_month,
                'remaining' => $tenant->getRemainingLimit('max_applications_per_month', 
                    $tenant->applications()
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count()
                ),
            ],
            'interviews_this_month' => [
                'current' => $tenant->interviews()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'limit' => $currentPlan->max_interviews_per_month,
                'remaining' => $tenant->getRemainingLimit('max_interviews_per_month',
                    $tenant->interviews()
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count()
                ),
            ],
        ];
    }
}
