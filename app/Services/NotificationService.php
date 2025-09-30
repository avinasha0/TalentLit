<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\TenantRole;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Get users with specific roles for a tenant
     */
    public static function getUsersWithRoles(Tenant $tenant, array $roleNames): Collection
    {
        $roleIds = TenantRole::forTenant($tenant->id)
            ->whereIn('name', $roleNames)
            ->pluck('id');

        return User::whereHas('tenants', function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            })
            ->whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('id', $roleIds);
            })
            ->get();
    }

    /**
     * Get recruiter users for a tenant (Owner, Admin, Recruiter roles)
     */
    public static function getRecruiterUsers(Tenant $tenant): Collection
    {
        return self::getUsersWithRoles($tenant, ['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Get tenant's from address for emails
     */
    public static function getTenantFromAddress(Tenant $tenant): string
    {
        $domain = $tenant->domain ?? config('mail.from.address');
        return "noreply@{$domain}";
    }

    /**
     * Get tenant's from name for emails
     */
    public static function getTenantFromName(Tenant $tenant): string
    {
        return "{$tenant->name} Careers";
    }
}
