<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AssignsRolesToUsers;

class DashboardTest extends TestCase
{
    use RefreshDatabase, AssignsRolesToUsers;
    public function test_dashboard_returns_200_and_shows_kpi_headings()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        
        // Assign Owner role to user
        $this->assignRoleToUser($user, $tenant, 'Owner');

        $response = $this->actingAs($user)->get("/{$tenant->slug}/dashboard");

        $response->assertStatus(200);
        $response->assertSee('Open Jobs');
        $response->assertSee('Active Candidates');
        $response->assertSee('Interviews This Week');
        $response->assertSee('Hires This Month');
    }
}
