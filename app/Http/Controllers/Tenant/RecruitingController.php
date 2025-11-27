<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class RecruitingController extends Controller
{
    public function index(Request $request, string $tenant = null)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        // Get applications with status 'hired' (displayed as Shortlisted)
        $applications = Application::where('tenant_id', $tenantModel->id)
            ->where('status', 'hired')
            ->with(['candidate', 'jobOpening', 'jobOpening.department', 'currentStage'])
            ->orderBy('applied_at', 'desc')
            ->paginate(20);
        
        return view('tenant.recruiting.index', [
            'tenant' => $tenantModel,
            'applications' => $applications,
        ]);
    }
}

