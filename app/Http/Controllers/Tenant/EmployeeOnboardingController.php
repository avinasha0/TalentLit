<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmployeeOnboardingController extends Controller
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
        
        // Check if using subdomain routing
        $currentRoute = request()->route()->getName();
        $isSubdomain = str_starts_with($currentRoute, 'subdomain.');
        
        // Redirect to All Onboardings by default
        if ($isSubdomain) {
            return redirect()->route('subdomain.employee-onboarding.all');
        } else {
            return redirect()->route('tenant.employee-onboarding.all', $tenantModel->slug);
        }
    }

    public function all(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.all', [
            'tenant' => $tenantModel,
        ]);
    }

    public function new(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.new', [
            'tenant' => $tenantModel,
        ]);
    }

    public function tasks(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.tasks', [
            'tenant' => $tenantModel,
        ]);
    }

    public function documents(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.documents', [
            'tenant' => $tenantModel,
        ]);
    }

    public function itAssets(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.it-assets', [
            'tenant' => $tenantModel,
        ]);
    }

    public function approvals(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.approvals', [
            'tenant' => $tenantModel,
        ]);
    }
}

