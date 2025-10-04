<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            // Job permissions
            'view jobs',
            'create jobs',
            'edit jobs',
            'delete jobs',
            'publish jobs',
            'close jobs',
            
            // Job stage permissions
            'manage stages',
            'view stages',
            'create stages',
            'edit stages',
            'delete stages',
            'reorder stages',
            
            // Candidate permissions
            'view candidates',
            'create candidates',
            'edit candidates',
            'delete candidates',
            'move candidates',
            'import candidates',
            
            // Dashboard permissions
            'view dashboard',
            
            // Analytics permissions
            'view analytics',
            
            // Interview permissions
            'view interviews',
            'create interviews',
            'edit interviews',
            'delete interviews',
            
            // Admin permissions
            'manage users',
            'manage settings',
            'manage email templates',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }

        // Get all tenants and create roles for each
        $tenants = Tenant::all();
        
        foreach ($tenants as $tenant) {
            $this->createRolesForTenant($tenant);
        }

        // Assign first user as Owner of first tenant
        $firstUser = User::first();
        $firstTenant = Tenant::first();
        
        if ($firstUser && $firstTenant) {
            // Add user to tenant
            if (!$firstUser->belongsToTenant($firstTenant->id)) {
                $firstUser->tenants()->attach($firstTenant->id);
            }
            
            // Assign Owner role
            $ownerRole = TenantRole::forTenant($firstTenant->id)->where('name', 'Owner')->first();
            if ($ownerRole) {
                $firstUser->assignRole($ownerRole);
            }
        }
    }

    private function createRolesForTenant(Tenant $tenant): void
    {
        // Owner - All permissions
        $ownerRole = TenantRole::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $ownerRole->syncPermissions(Permission::all());

        // Admin - Most permissions except user management
        $adminRole = TenantRole::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $adminRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard', 'view analytics',
            'view interviews', 'create interviews', 'edit interviews', 'delete interviews',
            'manage email templates',
        ]);

        // Recruiter - Job and candidate management
        $recruiterRole = TenantRole::firstOrCreate([
            'name' => 'Recruiter',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $recruiterRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'publish jobs', 'close jobs',
            'view stages', 'create stages', 'edit stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'move candidates',
            'view dashboard', 'view analytics',
            'view interviews', 'create interviews', 'edit interviews', 'delete interviews',
            'manage email templates',
        ]);

        // Hiring Manager - View only
        $hiringManagerRole = TenantRole::firstOrCreate([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $hiringManagerRole->syncPermissions([
            'view jobs',
            'view stages',
            'view candidates',
            'view dashboard',
            'view analytics',
            'view interviews',
        ]);
    }
}