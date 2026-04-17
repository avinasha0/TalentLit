<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;
use App\Services\PermissionService;

class CustomPermissionChecker
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Check if user has custom permission
     * Used by Blade directives - logs permission checks
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

        // Use PermissionService for centralized permission checking
        // Log permission checks from UI (set logCheck to true for audit trail)
        return $this->permissionService->userHasPermission(Auth::id(), $tenantId, $permission, true);
    }
}
