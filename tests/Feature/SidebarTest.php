<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\TenantRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\View;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_component_renders()
    {
        $tenant = Tenant::factory()->create(['name' => 'Test Company']);
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        
        $this->actingAs($user);
        
        $view = View::make('components.sidebar', ['tenant' => $tenant]);
        $html = $view->render();
        
        $this->assertStringContainsString('Dashboard', $html);
        $this->assertStringContainsString('Jobs', $html);
        $this->assertStringContainsString('Candidates', $html);
        $this->assertStringContainsString('Test Company', $html);
    }

    public function test_sidebar_hides_create_job_for_hiring_manager()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        
        // Assign Hiring Manager role
        $hiringManagerRole = TenantRole::forTenant($tenant->id)->where('name', 'Hiring Manager')->first();
        $user->assignRole($hiringManagerRole);
        
        $this->actingAs($user);
        
        $view = View::make('components.sidebar', ['tenant' => $tenant]);
        $html = $view->render();
        
        $this->assertStringNotContainsString('Create Job', $html);
        $this->assertStringNotContainsString('Settings', $html);
    }

    public function test_sidebar_shows_create_job_for_recruiter()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        
        // Assign Recruiter role
        $recruiterRole = TenantRole::forTenant($tenant->id)->where('name', 'Recruiter')->first();
        $user->assignRole($recruiterRole);
        
        $this->actingAs($user);
        
        $view = View::make('components.sidebar', ['tenant' => $tenant]);
        $html = $view->render();
        
        $this->assertStringContainsString('Create Job', $html);
        $this->assertStringNotContainsString('Settings', $html);
    }

    public function test_sidebar_shows_all_items_for_owner()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        
        // Assign Owner role
        $ownerRole = TenantRole::forTenant($tenant->id)->where('name', 'Owner')->first();
        $user->assignRole($ownerRole);
        
        $this->actingAs($user);
        
        $view = View::make('components.sidebar', ['tenant' => $tenant]);
        $html = $view->render();
        
        $this->assertStringContainsString('Create Job', $html);
        $this->assertStringContainsString('Settings', $html);
        $this->assertStringContainsString('Analytics', $html);
    }
}
