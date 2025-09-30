<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\Interview;
use App\Models\JobOpening;
use App\Models\Location;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardKpiTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant and user
        $this->tenant = Tenant::factory()->create(['slug' => 'acme']);
        $this->user = User::factory()->create();

        // Set tenant context
        Tenancy::set($this->tenant);
    }

    public function test_dashboard_returns_200()
    {
        $response = $this->actingAs($this->user)->get("/{$this->tenant->slug}/dashboard");

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Welcome back! Here');
    }

    public function test_dashboard_shows_correct_kpis()
    {
        // Create test data
        $department = Department::factory()->forTenant($this->tenant)->create();
        $location = Location::factory()->forTenant($this->tenant)->create();

        // Create published jobs
        JobOpening::factory()->forTenant($this->tenant)->published()->count(3)->create([
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);

        // Create draft job (should not count)
        JobOpening::factory()->forTenant($this->tenant)->create([
            'status' => 'draft',
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);

        // Create recent candidates (last 30 days)
        Candidate::factory()->forTenant($this->tenant)->count(5)->create([
            'created_at' => now()->subDays(10),
        ]);

        // Create old candidates (should not count)
        Candidate::factory()->forTenant($this->tenant)->count(2)->create([
            'created_at' => now()->subDays(40),
        ]);

        // Create applications with different statuses
        $candidate1 = Candidate::factory()->forTenant($this->tenant)->create();
        $candidate2 = Candidate::factory()->forTenant($this->tenant)->create();
        $candidate3 = Candidate::factory()->forTenant($this->tenant)->create();
        $job1 = JobOpening::factory()->forTenant($this->tenant)->published()->create([
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);
        $job2 = JobOpening::factory()->forTenant($this->tenant)->published()->create([
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);

        // Hired application this month
        Application::factory()->forTenant($this->tenant)->create([
            'candidate_id' => $candidate1->id,
            'job_opening_id' => $job1->id,
            'status' => 'hired',
            'applied_at' => now()->subDays(5),
        ]);

        // Active application
        Application::factory()->forTenant($this->tenant)->create([
            'candidate_id' => $candidate2->id,
            'job_opening_id' => $job2->id,
            'status' => 'active',
            'applied_at' => now()->subDays(2),
        ]);

        // Create interview this week
        $application = Application::factory()->forTenant($this->tenant)->create([
            'candidate_id' => $candidate3->id,
            'job_opening_id' => $job1->id,
        ]);

        Interview::factory()->create([
            'application_id' => $application->id,
            'scheduled_at' => now()->addDays(2),
        ]);

        $response = $this->actingAs($this->user)->get("/{$this->tenant->slug}/dashboard");

        $response->assertStatus(200);
        $response->assertSee('3'); // Open jobs count
        $response->assertSee('5'); // Active candidates count
        $response->assertSee('1'); // Interviews this week
        $response->assertSee('1'); // Hires this month
    }

    public function test_dashboard_json_endpoint_returns_correct_data()
    {
        // Create test data
        $department = Department::factory()->forTenant($this->tenant)->create();
        $location = Location::factory()->forTenant($this->tenant)->create();

        JobOpening::factory()->forTenant($this->tenant)->published()->count(2)->create([
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);

        Candidate::factory()->forTenant($this->tenant)->count(3)->create([
            'created_at' => now()->subDays(15),
        ]);

        $response = $this->actingAs($this->user)->get("/{$this->tenant->slug}/dashboard.json");

        $response->assertStatus(200);
        $response->assertJson([
            'open_jobs_count' => 2,
            'active_candidates_count' => 3,
            'interviews_this_week' => 0,
            'hires_this_month' => 0,
        ]);
    }

    public function test_dashboard_shows_recent_applications()
    {
        // Create test data
        $department = Department::factory()->forTenant($this->tenant)->create();
        $location = Location::factory()->forTenant($this->tenant)->create();
        $job = JobOpening::factory()->forTenant($this->tenant)->published()->create([
            'title' => 'Software Engineer',
            'department_id' => $department->id,
            'location_id' => $location->id,
        ]);

        $candidate = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        Application::factory()->forTenant($this->tenant)->create([
            'candidate_id' => $candidate->id,
            'job_opening_id' => $job->id,
            'status' => 'active',
            'applied_at' => now()->subHours(2),
        ]);

        $response = $this->actingAs($this->user)->get("/{$this->tenant->slug}/dashboard");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('Software Engineer');
        $response->assertSee('Active');
    }

    public function test_dashboard_tenant_isolation()
    {
        // Create another tenant with data
        $otherTenant = Tenant::factory()->create(['slug' => 'beta']);
        $otherDept = Department::factory()->forTenant($otherTenant)->create();
        $otherLoc = Location::factory()->forTenant($otherTenant)->create();

        JobOpening::factory()->forTenant($otherTenant)->published()->create([
            'title' => 'Other Company Job',
            'department_id' => $otherDept->id,
            'location_id' => $otherLoc->id,
        ]);

        Candidate::factory()->forTenant($otherTenant)->create([
            'first_name' => 'Other',
            'last_name' => 'Candidate',
        ]);

        // Test that other tenant's data doesn't appear
        $response = $this->actingAs($this->user)->get("/{$this->tenant->slug}/dashboard");

        $response->assertStatus(200);
        $response->assertDontSee('Other Company Job');
        $response->assertDontSee('Other Candidate');
    }
}