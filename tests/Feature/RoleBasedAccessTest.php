<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a tenant
        $this->tenant = Tenant::factory()->create();
        
        // Create a user
        $this->user = User::factory()->create();
        
        // Add user to tenant
        $this->user->tenants()->attach($this->tenant->id);
        
        // Seed roles and permissions for this tenant
        $this->seedRolesForTenant($this->tenant);
    }
    
    private function seedRolesForTenant($tenant)
    {
        // Create permissions
        $permissions = [
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard', 'manage users', 'manage settings'
        ];
        
        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
        
        // Create roles for this tenant
        $ownerRole = \App\Models\TenantRole::firstOrCreate([
            'name' => 'Owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        $ownerRole->syncPermissions(\Spatie\Permission\Models\Permission::all());
        
        $adminRole = \App\Models\TenantRole::firstOrCreate([
            'name' => 'Admin',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        $adminRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs',
            'manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates',
            'view dashboard',
        ]);
        
        $recruiterRole = \App\Models\TenantRole::firstOrCreate([
            'name' => 'Recruiter',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        $recruiterRole->syncPermissions([
            'view jobs', 'create jobs', 'edit jobs', 'publish jobs', 'close jobs',
            'view stages', 'create stages', 'edit stages', 'reorder stages',
            'view candidates', 'create candidates', 'edit candidates', 'move candidates',
            'view dashboard',
        ]);
        
        $hiringManagerRole = \App\Models\TenantRole::firstOrCreate([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);
        $hiringManagerRole->syncPermissions([
            'view jobs',
            'view stages',
            'view candidates',
            'view dashboard',
        ]);
    }

    /** @test */
    public function recruiter_can_access_job_creation()
    {
        // Assign Recruiter role to user
        $recruiterRole = TenantRole::forTenant($this->tenant->id)
            ->where('name', 'Recruiter')
            ->first();
        
        $this->user->assignRole($recruiterRole);

        // Act as the user and set tenant context
        $this->actingAs($this->user);
        app()->instance('currentTenantId', $this->tenant->id);

        // Test the permission directly
        $this->assertTrue($this->user->can('create jobs'));
        $this->assertTrue($this->user->hasRole('Recruiter'));
    }

    /** @test */
    public function hiring_manager_cannot_access_job_creation()
    {
        // Assign Hiring Manager role to user
        $hiringManagerRole = TenantRole::forTenant($this->tenant->id)
            ->where('name', 'Hiring Manager')
            ->first();
        
        $this->user->assignRole($hiringManagerRole);

        // Act as the user and set tenant context
        $this->actingAs($this->user);
        app()->instance('currentTenantId', $this->tenant->id);

        // Test the permission directly
        $this->assertFalse($this->user->can('create jobs'));
        $this->assertTrue($this->user->hasRole('Hiring Manager'));
    }

    /** @test */
    public function hiring_manager_can_view_jobs()
    {
        // Assign Hiring Manager role to user
        $hiringManagerRole = TenantRole::forTenant($this->tenant->id)
            ->where('name', 'Hiring Manager')
            ->first();
        
        $this->user->assignRole($hiringManagerRole);

        // Act as the user and set tenant context
        $this->actingAs($this->user);
        app()->instance('currentTenantId', $this->tenant->id);

        // Should be able to view jobs
        $response = $this->get("/{$this->tenant->slug}/jobs");
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_access_other_tenant_jobs()
    {
        // Create another tenant
        $otherTenant = Tenant::factory()->create();
        
        // Assign role in first tenant
        $recruiterRole = TenantRole::forTenant($this->tenant->id)
            ->where('name', 'Recruiter')
            ->first();
        
        $this->user->assignRole($recruiterRole);

        // Act as the user and set tenant context
        $this->actingAs($this->user);
        app()->instance('currentTenantId', $this->tenant->id);

        // Test that user has permissions in their tenant
        $this->assertTrue($this->user->can('view jobs'));
        
        // Test that user belongs to their tenant but not the other tenant
        $this->assertTrue($this->user->belongsToTenant($this->tenant->id));
        $this->assertFalse($this->user->belongsToTenant($otherTenant->id));
    }
}