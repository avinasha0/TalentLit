<?php

namespace Tests\Feature;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\TenantSubscription;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AssignsRolesToUsers;

class SubscriptionLimitsTest extends TestCase
{
    use RefreshDatabase, AssignsRolesToUsers;

    private Tenant $tenant;
    private User $owner;
    private SubscriptionPlan $freePlan;

    protected function setUp(): void
    {
        parent::setUp();

        // Create free subscription plan
        $this->freePlan = SubscriptionPlan::create([
            'name' => 'Free',
            'slug' => 'free',
            'description' => 'Free plan',
            'price' => 0.00,
            'currency' => 'INR',
            'billing_cycle' => 'monthly',
            'is_active' => true,
            'max_users' => 2,
            'max_job_openings' => 3,
            'max_candidates' => 50,
            'max_applications_per_month' => 25,
            'max_interviews_per_month' => 10,
            'max_storage_gb' => 1,
        ]);

        // Create tenant and owner user
        $this->tenant = Tenant::factory()->create(['slug' => 'test-tenant']);
        $this->owner = User::factory()->create();

        // Add owner to tenant
        $this->owner->tenants()->attach($this->tenant->id);

        // Create Owner role for tenant
        $ownerRole = TenantRole::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Owner',
            'permissions' => json_encode(['*']),
        ]);

        // Assign Owner role to user
        $this->owner->assignRole($ownerRole);

        // Create free subscription for tenant
        TenantSubscription::create([
            'tenant_id' => $this->tenant->id,
            'subscription_plan_id' => $this->freePlan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => null,
            'payment_method' => 'free',
        ]);

        // Set tenant context
        Tenancy::set($this->tenant);
    }

    public function test_user_limit_enforcement_prevents_exceeding_free_plan_limit()
    {
        // Free plan allows 2 users, owner is already 1 user
        // So we should be able to create 1 more user
        $this->actingAs($this->owner);

        // Create first additional user (should succeed)
        $response = $this->post("/{$this->tenant->slug}/users", [
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
            'role' => $this->getRecruiterRoleId(),
            'send_invitation' => false,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test1@example.com',
        ]);

        // Try to create second additional user (should fail - would exceed limit of 2)
        $response = $this->post("/{$this->tenant->slug}/users", [
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'role' => $this->getRecruiterRoleId(),
            'send_invitation' => false,
        ]);

        // Should redirect to pricing page with error
        $response->assertRedirect(route('subscription.pricing'));
        $response->assertSessionHas('error');

        // Verify second user was NOT created
        $this->assertDatabaseMissing('users', [
            'email' => 'test2@example.com',
        ]);
    }

    public function test_user_limit_allows_creation_when_under_limit()
    {
        $this->actingAs($this->owner);

        // Should be able to create one user (owner + 1 new user = 2 total, which is the limit)
        $response = $this->post("/{$this->tenant->slug}/users", [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => $this->getRecruiterRoleId(),
            'send_invitation' => false,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify user was created
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_team_page_shows_correct_user_limit_info()
    {
        $this->actingAs($this->owner);

        // Test when under limit
        $response = $this->get("/{$this->tenant->slug}/settings/team");
        $response->assertStatus(200);
        $response->assertSee('1 / 2 users'); // Current count / max users
        $response->assertSee('Add Team Member'); // Button should be enabled

        // Create one more user to reach the limit
        User::factory()->create()->tenants()->attach($this->tenant->id);

        // Test when at limit
        $response = $this->get("/{$this->tenant->slug}/settings/team");
        $response->assertStatus(200);
        $response->assertSee('2 / 2 users'); // Current count / max users
        $response->assertSee('Add Team Member'); // Button should still be visible but disabled
    }

    public function test_user_limit_message_redirects_to_tenant_subscription_page()
    {
        $this->actingAs($this->owner);

        // Create one more user to reach the limit
        User::factory()->create()->tenants()->attach($this->tenant->id);

        // Test that the page contains the correct subscription route
        $response = $this->get("/{$this->tenant->slug}/settings/team");
        $response->assertStatus(200);
        
        // Check that the JavaScript contains the correct tenant-specific subscription route
        $expectedRoute = route('subscription.show', $this->tenant->slug);
        $response->assertSee($expectedRoute);
    }

    private function getRecruiterRoleId()
    {
        $recruiterRole = TenantRole::firstOrCreate([
            'tenant_id' => $this->tenant->id,
            'name' => 'Recruiter',
        ], [
            'permissions' => json_encode(['view candidates', 'create jobs']),
        ]);

        return $recruiterRole->id;
    }
}
