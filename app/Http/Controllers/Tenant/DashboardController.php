<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\Interview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request, string $tenant = null)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        // If tenant is null, it means we're using subdomain routing
        // The tenant is already set by the middleware
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        // KPI Metrics
        $openJobsCount = JobOpening::where('tenant_id', $tenantModel->id)
            ->where('status', 'published')
            ->count();

        $activeCandidatesCount = Candidate::where('tenant_id', $tenantModel->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // Count applications in "Interview" stage
        $applicationsInInterviewStage = Application::where('tenant_id', $tenantModel->id)
            ->whereHas('currentStage', function ($query) {
                $query->where('name', 'like', '%Interview%');
            })
            ->count();

        // Count applications in "Hired" stage
        $hiresThisMonth = Application::where('tenant_id', $tenantModel->id)
            ->whereHas('currentStage', function ($query) {
                $query->where('name', 'like', '%Hired%');
            })
            ->count();

        // Recent Applications
        $recentApplications = Application::where('tenant_id', $tenantModel->id)
            ->with(['candidate', 'jobOpening'])
            ->orderBy('applied_at', 'desc')
            ->limit(10)
            ->get();

        // Get referral link for the authenticated user (if referral system exists)
        $user = auth()->user();
        
        // Initialize referral variables with defaults
        $referralLink = null;
        $referralCode = null;
        $totalReferrals = 0;
        $successfulReferrals = 0;
        
        // Check if referral methods exist (for backward compatibility)
        if (method_exists($user, 'getReferralLink')) {
            $referralLink = $user->getReferralLink($tenantModel);
        }
        if (property_exists($user, 'referral_code') || method_exists($user, 'generateReferralCode')) {
            $referralCode = $user->referral_code ?? (method_exists($user, 'generateReferralCode') ? $user->generateReferralCode() : null);
        }
        if (method_exists($user, 'referrals')) {
            $totalReferrals = $user->referrals()->count();
            $successfulReferrals = $user->referrals()->where('reward_claimed', true)->count();
        }

        return view('tenant.dashboard', [
            'tenant' => $tenantModel,
            'openJobsCount' => $openJobsCount,
            'activeCandidatesCount' => $activeCandidatesCount, 
            'interviewsThisWeek' => $applicationsInInterviewStage,
            'hiresThisMonth' => $hiresThisMonth,
            'recentApplications' => $recentApplications,
            'referralLink' => $referralLink,
            'referralCode' => $referralCode,
            'totalReferrals' => $totalReferrals,
            'successfulReferrals' => $successfulReferrals,
        ]);
    }

    public function json(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        // If tenant is null, it means we're using subdomain routing
        // The tenant is already set by the middleware
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return response()->json([
            'open_jobs_count' => JobOpening::where('tenant_id', $tenantModel->id)
                ->where('status', 'published')
                ->count(),
            'active_candidates_count' => Candidate::where('tenant_id', $tenantModel->id)
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
            'interviews_this_week' => Application::where('tenant_id', $tenantModel->id)
                ->whereHas('currentStage', function ($query) {
                    $query->where('name', 'like', '%Interview%');
                })
                ->count(),
            'hires_this_month' => Application::where('tenant_id', $tenantModel->id)
                ->whereHas('currentStage', function ($query) {
                    $query->where('name', 'like', '%Hired%');
                })
                ->count(),
        ]);
    }
}
