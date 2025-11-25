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
        
        // For now, return a placeholder view
        // This can be expanded later with employee onboarding functionality
        return view('tenant.employee-onboarding.index', [
            'tenant' => $tenantModel,
        ]);
    }
}

