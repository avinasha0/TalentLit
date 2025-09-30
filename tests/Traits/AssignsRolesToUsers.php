<?php

namespace Tests\Traits;

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Spatie\Permission\Models\Permission;

trait AssignsRolesToUsers
{
    protected function assignRoleToUser(User $user, Tenant $tenant, string $roleName = 'Owner'): void
    {
        // Ensure user belongs to tenant
        if (!$user->belongsToTenant($tenant->id)) {
            $user->tenants()->attach($tenant->id);
        }

        // Create roles and permissions for tenant if they don't exist
        $this->ensureRolesExistForTenant($tenant);

        // Assign role to user
        $role = TenantRole::forTenant($tenant->id)->where('name', $roleName)->first();
        if ($role) {
            $user->assignRole($role);
        }
    }

    protected function ensureRolesExistForTenant(Tenant $tenant): void
    {
        // Create permissions if they don't exist
        $permissions = [
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard', 'manage users', 'manage settings'
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Create Owner role if it doesn't exist
        $ownerRole = TenantRole::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        $ownerRole->syncPermissions(Permission::all());
    }

    protected function actingAsUserWithRole(User $user, Tenant $tenant, string $roleName = 'Owner')
    {
        $this->assignRoleToUser($user, $tenant, $roleName);
        return $this->actingAs($user);
    }
}
