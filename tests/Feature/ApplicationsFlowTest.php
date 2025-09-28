<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\ApplicationStageEvent;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationsFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant and user
        $this->tenant = Tenant::create([
            'name' => 'Acme',
            'slug' => 'acme',
        ]);

        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Set current tenant for the application
        Tenancy::set($this->tenant);
    }

    public function test_tenant_dashboard_returns_200(): void
    {
        $response = $this->actingAs($this->user)->get('/acme/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Welcome to Acme Dashboard');
    }

    public function test_can_create_candidate_and_application(): void
    {
        // Create a job opening
        $department = \App\Models\Department::factory()->forTenant($this->tenant)->create();
        $location = \App\Models\Location::factory()->forTenant($this->tenant)->create();

        $jobOpening = JobOpening::factory()->forTenant($this->tenant)->create([
            'title' => 'Senior PHP Developer',
            'slug' => 'senior-php-developer',
            'department_id' => $department->id,
            'location_id' => $location->id,
            'employment_type' => 'full_time',
            'status' => 'published',
            'openings_count' => 1,
            'description' => 'Test job description',
            'published_at' => now(),
        ]);

        // Create job stages
        $sourcedStage = JobStage::factory()->forTenant($this->tenant)->create([
            'job_opening_id' => $jobOpening->id,
            'name' => 'Sourced',
            'sort_order' => 1,
            'is_terminal' => false,
        ]);

        $screenStage = JobStage::factory()->forTenant($this->tenant)->create([
            'job_opening_id' => $jobOpening->id,
            'name' => 'Screen',
            'sort_order' => 2,
            'is_terminal' => false,
        ]);

        // Create a candidate
        $candidate = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
            'primary_phone' => '+1234567890',
            'source' => 'LinkedIn',
        ]);

        // Create an application
        $application = Application::create([
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $jobOpening->id,
            'candidate_id' => $candidate->id,
            'status' => 'active',
            'current_stage_id' => $sourcedStage->id,
            'applied_at' => now(),
        ]);

        // Verify the application was created
        $this->assertDatabaseHas('applications', [
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $jobOpening->id,
            'candidate_id' => $candidate->id,
            'status' => 'active',
            'current_stage_id' => $sourcedStage->id,
        ]);

        // Move the application to another stage
        $application->update(['current_stage_id' => $screenStage->id]);

        // Create stage event
        ApplicationStageEvent::create([
            'tenant_id' => $this->tenant->id,
            'application_id' => $application->id,
            'from_stage_id' => $sourcedStage->id,
            'to_stage_id' => $screenStage->id,
            'moved_by_user_id' => $this->user->id,
            'note' => 'Moved to screening stage',
            'created_at' => now(),
        ]);

        // Verify the stage event was created
        $this->assertDatabaseHas('application_stage_events', [
            'tenant_id' => $this->tenant->id,
            'application_id' => $application->id,
            'from_stage_id' => $sourcedStage->id,
            'to_stage_id' => $screenStage->id,
            'moved_by_user_id' => $this->user->id,
        ]);

        // Verify the application stage was updated
        $this->assertDatabaseHas('applications', [
            'id' => $application->id,
            'current_stage_id' => $screenStage->id,
        ]);
    }

    public function test_candidate_relationships_work_correctly(): void
    {
        // Create a candidate
        $candidate = Candidate::create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'primary_email' => 'jane.smith@example.com',
            'source' => 'Referral',
        ]);

        // Create a job opening
        $department = \App\Models\Department::factory()->create(['tenant_id' => $this->tenant->id]);
        $location = \App\Models\Location::factory()->create(['tenant_id' => $this->tenant->id]);

        $jobOpening = JobOpening::create([
            'tenant_id' => $this->tenant->id,
            'title' => 'Frontend Developer',
            'slug' => 'frontend-developer',
            'department_id' => $department->id,
            'location_id' => $location->id,
            'employment_type' => 'full_time',
            'status' => 'published',
            'openings_count' => 1,
            'description' => 'Test job description',
            'published_at' => now(),
        ]);

        // Create job stage
        $stage = JobStage::create([
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $jobOpening->id,
            'name' => 'Sourced',
            'sort_order' => 1,
            'is_terminal' => false,
        ]);

        // Create another candidate
        $candidate2 = Candidate::create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'primary_email' => 'bob.johnson@example.com',
            'source' => 'Indeed',
        ]);

        // Create applications
        $application1 = Application::create([
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $jobOpening->id,
            'candidate_id' => $candidate->id,
            'status' => 'active',
            'current_stage_id' => $stage->id,
            'applied_at' => now(),
        ]);

        $application2 = Application::create([
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $jobOpening->id,
            'candidate_id' => $candidate2->id,
            'status' => 'active',
            'current_stage_id' => $stage->id,
            'applied_at' => now()->subDays(1),
        ]);

        // Test relationships
        $this->assertEquals(1, $candidate->applications()->count());
        $this->assertEquals(1, $candidate2->applications()->count());
        $this->assertEquals($jobOpening->id, $candidate->applications()->first()->job_opening_id);
        $this->assertEquals($candidate->id, $application1->candidate_id);
        $this->assertEquals($jobOpening->id, $application1->job_opening_id);
    }
}
