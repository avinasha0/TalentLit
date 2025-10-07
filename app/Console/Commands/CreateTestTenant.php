<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateTestTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-test {slug=testsix}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test tenant for testing purposes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $slug = $this->argument('slug');
        
        // Check if tenant already exists
        $tenant = Tenant::where('slug', $slug)->first();
        if ($tenant) {
            $this->info("Tenant '{$slug}' already exists: {$tenant->name}");
            return;
        }

        // Create tenant
        $tenant = Tenant::create([
            'name' => ucfirst($slug),
            'slug' => $slug,
        ]);

        $this->info("Created tenant: {$tenant->slug} - {$tenant->name}");

        // Create admin user
        $admin = User::create([
            'name' => ucfirst($slug) . ' Admin',
            'email' => "admin@{$slug}.com",
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Add user to tenant
        $admin->tenants()->attach($tenant->id);

        // Create custom roles for tenant
        $this->createCustomRolesForTenant($tenant);

        // Assign Owner role to admin
        $this->assignCustomOwnerRole($admin, $tenant);

        $this->info("Created admin user: admin@{$slug}.com (password: password)");
        $this->info("Tenant setup complete!");
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

        // Create Owner role
        DB::table('custom_tenant_roles')->insertOrIgnore([
            'tenant_id' => $tenant->id,
            'name' => 'Owner',
            'permissions' => json_encode([
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'delete_jobs', 'publish_jobs', 'close_jobs',
                'manage_stages', 'view_stages', 'create_stages', 'edit_stages', 'delete_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'delete_candidates', 'move_candidates', 'import_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews',
                'view_analytics', 'manage_users', 'manage_settings', 'manage_email_templates'
            ]),
            'created_at' => now(),
            'updated_at' => now()
        ]);
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

        // Assign Owner role to user
        DB::table('custom_user_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_name' => 'Owner',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
