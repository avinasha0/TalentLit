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
        $subscription = $tenant->activeSubscription;
        $plan = $subscription?->plan;
        
        // Get usage statistics
        $usage = $this->getUsageStatistics($tenant);
        
        return view('subscription.show', compact('subscription', 'plan', 'usage'));
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
        if ($tenant->activeSubscription) {
            return redirect()->back()->with('error', 'You already have an active subscription.');
        }

        // Only allow free plans for now (no payment gateway)
        if (!$plan->isFree()) {
            return redirect()->back()->with('error', 'Payment gateway integration not implemented yet.');
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
        $subscription = $tenant->activeSubscription;

        if (!$subscription) {
            return redirect()->back()->with('error', 'No active subscription found.');
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Subscription cancelled successfully.');
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
