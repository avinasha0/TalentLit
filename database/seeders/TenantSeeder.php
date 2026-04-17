<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Acme tenant
        $tenant = Tenant::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        // Create admin user for Acme
        $admin = User::create([
            'name' => 'Acme Admin',
            'email' => 'admin@acme.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Add user to tenant
        $admin->tenants()->attach($tenant->id);

        // Create custom roles for tenant
        $this->createCustomRolesForTenant($tenant);

        // Assign Owner role to admin using custom system
        $this->assignCustomOwnerRole($admin, $tenant);
    }

    private function createCustomRolesForTenant(Tenant $tenant): void
    {
        // Create custom roles table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('custom_tenant_roles')) {
            DB::statement('
                CREATE TABLE custom_tenant_roles (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    tenant_id VARCHAR(36) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    permissions JSON,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY unique_tenant_role (tenant_id, name),
                    INDEX idx_tenant_id (tenant_id)
                )
            ');
        }

        // Use PermissionService to ensure all roles are created with correct permissions
        app(\App\Services\PermissionService::class)->ensureTenantRoles($tenant->id);
    }

    private function assignCustomOwnerRole(User $user, Tenant $tenant): void
    {
        // Create custom user roles table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('custom_user_roles')) {
            DB::statement('
                CREATE TABLE custom_user_roles (
                    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    user_id BIGINT UNSIGNED NOT NULL,
                    tenant_id VARCHAR(36) NOT NULL,
                    role_name VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP NULL,
                    updated_at TIMESTAMP NULL,
                    UNIQUE KEY unique_user_tenant_role (user_id, tenant_id, role_name),
                    INDEX idx_user_id (user_id),
                    INDEX idx_tenant_id (tenant_id)
                )
            ');
        }

        // Assign Owner role to user using User model method (enforces single role)
        $user->assignRoleForTenant('Owner', $tenant->id);
    }
}
