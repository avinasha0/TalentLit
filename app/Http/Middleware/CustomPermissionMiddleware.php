<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\PermissionService;

class CustomPermissionMiddleware
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        // Convert permission from 'view dashboard' to 'view_dashboard'
        $permission = str_replace(' ', '_', $permission);
        
        // Get current user
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get tenant from route parameter, tenant() helper (for subdomain routes), or session
        $tenant = null;
        if ($request->route('tenant')) {
            // Path-based routing
            $tenant = \App\Models\Tenant::where('slug', $request->route('tenant'))->first();
        } elseif (function_exists('tenant') && tenant()) {
            // Subdomain routing - tenant is set by ResolveTenantFromSubdomain middleware
            $tenant = tenant();
        } elseif (session('current_tenant_id')) {
            // Fallback to session
            $tenant = \App\Models\Tenant::find(session('current_tenant_id'));
        }
        
        if (!$tenant) {
            abort(403, 'Tenant not found');
        }

        // Check if user has the required permission for this tenant
        // PermissionService will log the check automatically
        $hasPermission = $this->permissionService->userHasPermission($user->id, $tenant->id, $permission, true);
        
        if (!$hasPermission) {
            abort(403, 'You don\'t have permission to perform this action. Contact an Owner.');
        }

        return $next($request);
    }
}
