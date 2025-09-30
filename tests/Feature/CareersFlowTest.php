<?php

namespace Tests\Feature;

use App\Mail\ApplicationReceived;
use App\Mail\NewApplication;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Location;
use App\Models\Resume;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareersFlowTest extends TestCase
{
    use RefreshDatabase;

    private Tenant $tenant;

    private User $user;

    private JobOpening $job;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant and user
        $this->tenant = Tenant::factory()->create(['slug' => 'acme']);
        $this->user = User::factory()->create();

        // Set tenant context
        Tenancy::set($this->tenant);

        // Create department and location
        $department = Department::factory()->forTenant($this->tenant)->create(['name' => 'Engineering']);
        $location = Location::factory()->forTenant($this->tenant)->create(['name' => 'Chennai']);

        // Create published job
        $this->job = JobOpening::factory()
            ->forTenant($this->tenant)
            ->published()
            ->create([
                'title' => 'Senior PHP Developer',
                'slug' => 'senior-php-developer',
                'department_id' => $department->id,
                'location_id' => $location->id,
                'employment_type' => 'full_time',
                'openings_count' => 2,
                'description' => 'We are looking for a Senior PHP Developer with Laravel experience.',
            ]);
    }

    public function test_careers_listing_shows_only_published_jobs()
    {
        // Create a draft job that should not appear
        JobOpening::factory()
            ->forTenant($this->tenant)
            ->create([
                'title' => 'Draft Job',
                'status' => 'draft',
            ]);

        $response = $this->get("/{$this->tenant->slug}/careers");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('Draft Job');
    }

    public function test_careers_listing_filters_work()
    {
        // Create another department and location
        $hrDept = Department::factory()->forTenant($this->tenant)->create(['name' => 'HR']);
        $bangalore = Location::factory()->forTenant($this->tenant)->create(['name' => 'Bangalore']);

        // Create another job
        JobOpening::factory()
            ->forTenant($this->tenant)
            ->published()
            ->create([
                'title' => 'HR Manager',
                'department_id' => $hrDept->id,
                'location_id' => $bangalore->id,
                'employment_type' => 'full_time',
            ]);

        // Test department filter
        $response = $this->get("/{$this->tenant->slug}/careers?department=Engineering");
        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('HR Manager');

        // Test location filter
        $response = $this->get("/{$this->tenant->slug}/careers?location=Chennai");
        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('HR Manager');

        // Test keyword search
        $response = $this->get("/{$this->tenant->slug}/careers?keyword=PHP");
        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('HR Manager');
    }

    public function test_job_detail_page_loads_correctly()
    {
        $response = $this->get("/{$this->tenant->slug}/careers/{$this->job->slug}");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertSee('Engineering');
        $response->assertSee('Chennai');
        $response->assertSee('Full time');
        $response->assertSee('2 openings');
        $response->assertSee('Apply Now');
    }

    public function test_draft_job_detail_returns_404()
    {
        $draftJob = JobOpening::factory()
            ->forTenant($this->tenant)
            ->create([
                'title' => 'Draft Job',
                'slug' => 'draft-job',
                'status' => 'draft',
            ]);

        $response = $this->get("/{$this->tenant->slug}/careers/{$draftJob->slug}");
        $response->assertStatus(404);
    }

    public function test_application_form_loads_correctly()
    {
        $response = $this->get("/{$this->tenant->slug}/careers/{$this->job->slug}/apply");

        $response->assertStatus(200);
        $response->assertSee('Apply for Senior PHP Developer');
        $response->assertSee('First Name');
        $response->assertSee('Last Name');
        $response->assertSee('Email Address');
        $response->assertSee('Phone Number');
        $response->assertSee('Resume');
        $response->assertSee('Submit Application');
    }

    public function test_application_submission_creates_candidate_and_application()
    {
        Storage::fake('public');

        $resumeFile = UploadedFile::fake()->create('resume.pdf', 1000, 'application/pdf');

        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'resume' => $resumeFile,
            'consent' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('application_id');

        // Assert candidate was created
        $this->assertDatabaseHas('candidates', [
            'tenant_id' => $this->tenant->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
            'primary_phone' => '+1234567890',
            'source' => 'Career Site',
        ]);

        // Assert application was created
        $candidate = Candidate::where('primary_email', 'john.doe@example.com')->first();
        $this->assertDatabaseHas('applications', [
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $this->job->id,
            'candidate_id' => $candidate->id,
            'status' => 'active',
        ]);

        // Assert resume was stored
        $this->assertDatabaseHas('resumes', [
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $candidate->id,
            'filename' => 'resume.pdf',
        ]);

        // Assert file was stored
        Storage::disk('public')->assertExists("resumes/{$this->tenant->id}/".basename($candidate->resumes->first()->path));
    }

    public function test_application_submission_without_resume_fails_validation()
    {
        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'consent' => true,
        ]);

        $response->assertSessionHasErrors(['resume']);

        // Assert no candidate or application was created
        $this->assertEquals(0, Candidate::count());
        $this->assertEquals(0, Application::count());
    }

    public function test_application_submission_reuses_existing_candidate()
    {
        Storage::fake('public');

        // Create existing candidate
        $existingCandidate = Candidate::factory()
            ->forTenant($this->tenant)
            ->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'primary_email' => 'john.doe@example.com',
            ]);

        $resumeFile = UploadedFile::fake()->create('resume.pdf', 1000, 'application/pdf');

        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'resume' => $resumeFile,
            'consent' => true,
        ]);

        $response->assertRedirect();

        // Assert only one candidate exists
        $this->assertEquals(1, Candidate::where('primary_email', 'john.doe@example.com')->count());

        // Get the actual candidate that was found/created
        $actualCandidate = Candidate::where('primary_email', 'john.doe@example.com')->first();

        // Assert application exists (either created or already existed)
        $this->assertDatabaseHas('applications', [
            'tenant_id' => $this->tenant->id,
            'job_opening_id' => $this->job->id,
            'candidate_id' => $actualCandidate->id,
        ]);
    }

    public function test_application_submission_validation_errors()
    {
        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => '',
            'last_name' => '',
            'email' => 'invalid-email',
            'consent' => false,
        ]);

        $response->assertSessionHasErrors(['first_name', 'last_name', 'email', 'resume', 'consent']);

        // Assert no candidate or application was created
        $this->assertEquals(0, Candidate::count());
        $this->assertEquals(0, Application::count());
    }

    public function test_application_submission_invalid_file_type()
    {
        $invalidFile = UploadedFile::fake()->create('resume.txt', 1000, 'text/plain');

        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'resume' => $invalidFile,
            'consent' => true,
        ]);

        $response->assertSessionHasErrors(['resume']);

        // Assert no candidate or application was created
        $this->assertEquals(0, Candidate::count());
        $this->assertEquals(0, Application::count());
    }

    public function test_tenant_isolation_careers_listing()
    {
        // Create another tenant with a job
        $otherTenant = Tenant::factory()->create(['slug' => 'beta']);
        $otherDept = Department::factory()->forTenant($otherTenant)->create();
        $otherLoc = Location::factory()->forTenant($otherTenant)->create();

        $otherJob = JobOpening::factory()
            ->forTenant($otherTenant)
            ->published()
            ->create([
                'title' => 'Other Company Job',
                'department_id' => $otherDept->id,
                'location_id' => $otherLoc->id,
            ]);

        // Test that other tenant's job doesn't appear
        $response = $this->get("/{$this->tenant->slug}/careers");
        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('Other Company Job');
    }

    public function test_tenant_isolation_application_submission()
    {
        Storage::fake('public');

        // Create another tenant
        $otherTenant = Tenant::factory()->create(['slug' => 'beta']);

        $resumeFile = UploadedFile::fake()->create('resume.pdf', 1000, 'application/pdf');

        // Try to apply to a job from another tenant
        $response = $this->post("/{$otherTenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'resume' => $resumeFile,
            'consent' => true,
        ]);

        // Should fail because job doesn't belong to the tenant
        $response->assertStatus(404);
    }

    public function test_success_page_displays_correctly()
    {
        $response = $this->get("/{$this->tenant->slug}/careers/{$this->job->slug}/success");

        $response->assertStatus(200);
        $response->assertSee('Application Submitted Successfully!');
        $response->assertSee('Senior PHP Developer');
        $response->assertSee('Engineering');
        $response->assertSee('Chennai');
    }

    public function test_application_submission_sends_email_notifications()
    {
        Mail::fake();
        Storage::fake('public');

        // Create a recruiter user
        $recruiter = User::factory()->create(['email' => 'recruiter@example.com']);
        $recruiter->tenants()->attach($this->tenant->id);
        
        // Create Recruiter role and assign to user
        $recruiterRole = TenantRole::createForTenant([
            'name' => 'Recruiter',
            'guard_name' => 'web',
        ], $this->tenant->id);
        $recruiter->assignRole($recruiterRole);

        $resumeFile = UploadedFile::fake()->create('resume.pdf', 1000, 'application/pdf');

        $response = $this->post("/{$this->tenant->slug}/careers/{$this->job->slug}/apply", [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'resume' => $resumeFile,
            'consent' => true,
        ]);

        $response->assertRedirect();

        // Assert ApplicationReceived email was queued for candidate
        Mail::assertQueued(ApplicationReceived::class, function ($mail) {
            return $mail->candidate->primary_email === 'john.doe@example.com' &&
                   $mail->job->title === 'Senior PHP Developer';
        });

        // Assert NewApplication email was queued for recruiter
        Mail::assertQueued(NewApplication::class, function ($mail) {
            return $mail->candidate->primary_email === 'john.doe@example.com' &&
                   $mail->job->title === 'Senior PHP Developer' &&
                   $mail->tenant->id === $this->tenant->id;
        });
    }
}
