<?php

namespace App\Http\Middleware;

use App\Support\Tenancy;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckSubscriptionLimits
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $limit = null): Response
    {
        $tenant = Tenancy::get();
        
        if (!$tenant) {
            return $next($request);
        }

        // Check if tenant has an active subscription
        $subscription = $tenant->activeSubscription;
        if (!$subscription) {
            return $this->redirectToPricing($request, 'You need an active subscription to access this feature.');
        }
        
        // Load the plan relationship
        $subscription->load('plan');

        // Check specific limit if provided
        if ($limit) {
            $plan = $subscription->plan;
            if (!$plan) {
                return $this->redirectToPricing($request, 'Invalid subscription plan.');
            }

            $currentCount = $this->getCurrentCount($tenant, $limit);
            $maxLimit = $plan->$limit ?? 0;

            // -1 means unlimited
            if ($maxLimit !== -1 && $currentCount >= $maxLimit) {
                return $this->redirectToPricing($request, "You've reached the limit for {$limit}. Please upgrade your plan.");
            }
        }

        return $next($request);
    }

    /**
     * Get current count for a specific limit.
     */
    private function getCurrentCount($tenant, string $limit): int
    {
        switch ($limit) {
            case 'max_users':
                return $tenant->users()->count();
            case 'max_job_openings':
                return $tenant->jobOpenings()->count();
            case 'max_candidates':
                return $tenant->candidates()->count();
            case 'max_applications_per_month':
                return $tenant->applications()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
            case 'max_interviews_per_month':
                return $tenant->interviews()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count();
            default:
                return 0;
        }
    }

    /**
     * Redirect to subscription page with error message.
     */
    private function redirectToPricing(Request $request, string $message)
    {
        $tenant = Tenancy::get();
        
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $message,
                'redirect' => $tenant ? route('subscription.show', $tenant->slug) : route('subscription.pricing')
            ], 403);
        }

        // Redirect to tenant-specific subscription page if tenant is available
        if ($tenant) {
            return redirect()->route('subscription.show', $tenant->slug)
                ->with('error', $message);
        }

        // Fallback to public pricing page if no tenant context
        return redirect()->route('subscription.pricing')
            ->with('error', $message);
    }
}
