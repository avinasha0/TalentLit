<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_dashboard_returns_200_and_shows_tenant_name(): void
    {
        $tenant = Tenant::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/acme/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome to Acme Dashboard');
    }

    public function test_unauthenticated_user_redirected_to_login(): void
    {
        $tenant = Tenant::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $response = $this->get('/acme/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_unknown_tenant_returns_404(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/nope/dashboard');

        $response->assertStatus(404);
    }
}
