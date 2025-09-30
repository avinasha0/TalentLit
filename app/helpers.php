<?php

use App\Support\Tenancy;

if (! function_exists('tenant')) {
    function tenant()
    {
        return Tenancy::get();
    }
}
if (! function_exists('tenant_id')) {
    function tenant_id()
    {
        return Tenancy::id();
    }
}

if (! function_exists('currentTenantId')) {
    function currentTenantId()
    {
        return app('currentTenantId');
    }
}

if (! function_exists('userRole')) {
    function userRole($user = null)
    {
        $user = $user ?? auth()->user();
        return $user ? $user->roles->first() : null;
    }
}

if (! function_exists('userRoleName')) {
    function userRoleName($user = null)
    {
        $role = userRole($user);
        return $role ? $role->name : 'No Role';
    }
}

if (! function_exists('hasPermission')) {
    function hasPermission($permission, $user = null)
    {
        $user = $user ?? auth()->user();
        return $user ? $user->can($permission) : false;
    }
}
