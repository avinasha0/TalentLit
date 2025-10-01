<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Location;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create tenant and user
        $this->tenant = Tenant::factory()->create();
        $this->user = User::factory()->create();
        $this->user->tenants()->attach($this->tenant->id);
        
        // Create analytics permission if it doesn't exist
        \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => 'view analytics',
            'guard_name' => 'web'
        ]);
        
        // Assign analytics permission
        $this->user->givePermissionTo('view analytics');
    }

    public function test_analytics_dashboard_requires_authentication()
    {
        $response = $this->get("/{$this->tenant->slug}/analytics");
        $response->assertRedirect('/login');
    }

    public function test_analytics_dashboard_requires_permission()
    {
        $userWithoutPermission = User::factory()->create();
        $userWithoutPermission->tenants()->attach($this->tenant->id);
        
        $this->actingAs($userWithoutPermission);
        
        $response = $this->get("/{$this->tenant->slug}/analytics");
        $response->assertStatus(403);
    }

    public function test_analytics_dashboard_loads_successfully()
    {
        $this->actingAs($this->user);
        
        $response = $this->get("/{$this->tenant->slug}/analytics");
        $response->assertStatus(200);
        $response->assertViewIs('tenant.analytics.index');
    }

    public function test_analytics_data_endpoint_returns_json()
    {
        $this->actingAs($this->user);
        
        // Test with empty data first
        $response = $this->get("/{$this->tenant->slug}/analytics/data");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'applications_over_time',
            'hires_over_time',
            'stage_funnel',
            'time_to_hire_summary',
            'source_effectiveness',
            'open_jobs_by_dept',
            'pipeline_snapshot'
        ]);
    }

    public function test_analytics_data_with_date_range()
    {
        $this->actingAs($this->user);
        
        $from = now()->subDays(30)->format('Y-m-d');
        $to = now()->format('Y-m-d');
        
        $response = $this->get("/{$this->tenant->slug}/analytics/data?from={$from}&to={$to}");
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'applications_over_time',
            'hires_over_time',
            'stage_funnel',
            'time_to_hire_summary',
            'source_effectiveness',
            'open_jobs_by_dept',
            'pipeline_snapshot'
        ]);
    }

    public function test_analytics_export_returns_csv()
    {
        $this->actingAs($this->user);
        
        $response = $this->get("/{$this->tenant->slug}/analytics/export");
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }
}
