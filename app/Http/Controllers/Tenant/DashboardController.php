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

        $interviewsThisWeek = Interview::whereHas('application', function ($query) use ($tenantModel) {
                $query->where('tenant_id', $tenantModel->id);
            })
            ->thisWeek()
            ->count();

        $hiresThisMonth = Application::where('tenant_id', $tenantModel->id)
            ->thisMonthHired()
            ->count();

        // Recent Applications
        $recentApplications = Application::where('tenant_id', $tenantModel->id)
            ->recent(10)
            ->get();

        return view('tenant.dashboard', [
            'tenant' => $tenantModel,
            'openJobsCount' => $openJobsCount,
            'activeCandidatesCount' => $activeCandidatesCount, 
            'interviewsThisWeek' => $interviewsThisWeek,
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
            'interviews_this_week' => Interview::whereHas('application', function ($query) use ($tenantModel) {
                    $query->where('tenant_id', $tenantModel->id);
                })
                ->thisWeek()
                ->count(),
            'hires_this_month' => Application::where('tenant_id', $tenantModel->id)
                ->thisMonthHired()
                ->count(),
        ]);
    }
}
