<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecruitingController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        // For now, redirect to dashboard or show a placeholder
        // This can be expanded later with recruiting-specific functionality
        return view('tenant.recruiting.index', [
            'tenant' => $tenantModel,
        ]);
    }
}

