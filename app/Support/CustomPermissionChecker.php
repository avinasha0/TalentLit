<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomPermissionChecker
{
    /**
     * Check if user has custom permission
     */
    public function check($permission, $tenant = null)
    {
        // Parse the expression from Blade directive
        // Format: 'permission_name', $tenant
        $permission = trim($permission, "'\"");
        
        $tenant = $tenant ?? tenant();
        if (!$tenant || !Auth::check()) {
            return false;
        }

        // Handle both tenant object and tenant ID string
        $tenantId = is_object($tenant) ? $tenant->id : $tenant;

        $userRole = DB::table('custom_user_roles')
            ->where('user_id', Auth::id())
            ->where('tenant_id', $tenantId)
            ->value('role_name');

        if (!$userRole) {
            return false;
        }

        $rolePermissions = DB::table('custom_tenant_roles')
            ->where('tenant_id', $tenantId)
            ->where('name', $userRole)
            ->value('permissions');

        if (!$rolePermissions) {
            return false;
        }

        $permissions = json_decode($rolePermissions, true);
        return in_array($permission, $permissions);
    }
}
