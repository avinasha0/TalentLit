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
        
        // Simple test data
        $openJobs = 5;
        $activeCandidates = 12;
        $interviewsThisWeek = 3;
        $hires = 2;
        $recentJobs = collect([]);

        return view('tenant.dashboard', [
            'tenant' => $tenantModel,
            'openJobs' => $openJobs,
            'activeCandidates' => $activeCandidates, 
            'interviewsThisWeek' => $interviewsThisWeek,
            'hires' => $hires,
            'recentJobs' => $recentJobs
        ]);
    }
}
