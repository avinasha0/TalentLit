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
    public function index(Request $request, string $tenant)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
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

        return view('tenant.dashboard', [
            'tenant' => $tenantModel,
            'openJobsCount' => $openJobsCount,
            'activeCandidatesCount' => $activeCandidatesCount, 
            'interviewsThisWeek' => $applicationsInInterviewStage,
            'hiresThisMonth' => $hiresThisMonth,
            'recentApplications' => $recentApplications
        ]);
    }

    public function json(Request $request, string $tenant)
    {
        $tenantModel = tenant();
        
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
