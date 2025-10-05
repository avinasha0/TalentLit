<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomPermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permission)
    {
        // Convert permission from 'view dashboard' to 'view_dashboard'
        $permission = str_replace(' ', '_', $permission);
        
        // Get current user
        $user = auth()->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // Get tenant from route parameter or session
        $tenant = null;
        if ($request->route('tenant')) {
            $tenant = \App\Models\Tenant::where('slug', $request->route('tenant'))->first();
        } elseif (session('current_tenant_id')) {
            $tenant = \App\Models\Tenant::find(session('current_tenant_id'));
        }
        
        if (!$tenant) {
            abort(403, 'Tenant not found');
        }

        // Check if user has the required permission for this tenant
        $hasPermission = $this->userHasPermission($user->id, $tenant->id, $permission);
        
        if (!$hasPermission) {
            abort(403, 'Insufficient permissions');
        }

        return $next($request);
    }

    private function userHasPermission(int $userId, string $tenantId, string $permission): bool
    {
        // Get user's roles for this tenant
        $userRoles = DB::table('custom_user_roles')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->pluck('role_name');

        if ($userRoles->isEmpty()) {
            return false;
        }

        // Get permissions for user's roles
        $rolePermissions = DB::table('custom_tenant_roles')
            ->where('tenant_id', $tenantId)
            ->whereIn('name', $userRoles)
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->map(function ($permissions) {
                return json_decode($permissions, true);
            })
            ->flatten()
            ->unique()
            ->toArray();

        return in_array($permission, $rolePermissions);
    }
}
