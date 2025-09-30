<?php

namespace Tests\Feature;

use App\Mail\StageChanged;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Location;
use App\Models\Tenant;
use App\Models\TenantRole;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Tests\Traits\AssignsRolesToUsers;

class PipelineEmailTest extends TestCase
{
    use RefreshDatabase, AssignsRolesToUsers;

    private Tenant $tenant;
    private User $user;
    private JobOpening $job;
    private JobStage $stage1;
    private JobStage $stage2;
    private Application $application;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant and user
        $this->tenant = Tenant::factory()->create(['slug' => 'acme']);
        $this->user = User::factory()->create();
        $this->assignRoleToUser($this->user, $this->tenant, 'Owner');
        
        // Ensure user has the required permissions
        $this->user->givePermissionTo('edit jobs');
        $this->user->givePermissionTo('view jobs');

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
                'department_id' => $department->id,
                'location_id' => $location->id,
                'employment_type' => 'full_time',
                'openings_count' => 2,
                'description' => 'We are looking for a Senior PHP Developer with Laravel experience.',
            ]);
        
        // Set the slug after creation to ensure it's correct
        $this->job->update(['slug' => 'senior-php-developer']);
        

        // Create job stages
        $this->stage1 = JobStage::factory()
            ->forTenant($this->tenant)
            ->forJob($this->job)
            ->create([
                'name' => 'Applied',
                'sort_order' => 1,
            ]);

        $this->stage2 = JobStage::factory()
            ->forTenant($this->tenant)
            ->forJob($this->job)
            ->create([
                'name' => 'Interview',
                'sort_order' => 2,
            ]);

        // Create candidate and application
        $candidate = Candidate::factory()
            ->forTenant($this->tenant)
            ->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'primary_email' => 'john.doe@example.com',
            ]);

        $this->application = Application::factory()
            ->forTenant($this->tenant)
            ->forJob($this->job)
            ->forCandidate($candidate)
            ->create([
                'current_stage_id' => $this->stage1->id,
                'stage_position' => 0,
            ]);
    }

    public function test_stage_change_sends_email_notification()
    {
        Mail::fake();

        $response = $this->actingAs($this->user)
            ->postJson("/{$this->tenant->slug}/jobs/{$this->job->id}/pipeline/move", [
                'application_id' => $this->application->id,
                'from_stage_id' => $this->stage1->id,
                'to_stage_id' => $this->stage2->id,
                'to_position' => 0,
                'note' => 'Moving to interview stage',
            ]);


        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        // Assert StageChanged email was queued for candidate
        Mail::assertQueued(StageChanged::class, function ($mail) {
            return $mail->candidate->primary_email === 'john.doe@example.com' &&
                   $mail->job->title === 'Senior PHP Developer' &&
                   $mail->stage->name === 'Interview' &&
                   $mail->message === 'Moving to interview stage' &&
                   $mail->tenant->id === $this->tenant->id;
        });
    }

    public function test_stage_change_without_note_sends_email_notification()
    {
        Mail::fake();

        $response = $this->actingAs($this->user)
            ->postJson("/{$this->tenant->slug}/jobs/{$this->job->id}/pipeline/move", [
                'application_id' => $this->application->id,
                'from_stage_id' => $this->stage1->id,
                'to_stage_id' => $this->stage2->id,
                'to_position' => 0,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        // Assert StageChanged email was queued for candidate
        Mail::assertQueued(StageChanged::class, function ($mail) {
            return $mail->candidate->primary_email === 'john.doe@example.com' &&
                   $mail->job->title === 'Senior PHP Developer' &&
                   $mail->stage->name === 'Interview' &&
                   $mail->message === null &&
                   $mail->tenant->id === $this->tenant->id;
        });
    }

    public function test_reordering_within_same_stage_does_not_send_email()
    {
        Mail::fake();

        $response = $this->actingAs($this->user)
            ->postJson("/{$this->tenant->slug}/jobs/{$this->job->id}/pipeline/move", [
                'application_id' => $this->application->id,
                'from_stage_id' => $this->stage1->id,
                'to_stage_id' => $this->stage1->id, // Same stage
                'to_position' => 1,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        // Assert no StageChanged email was queued
        Mail::assertNotQueued(StageChanged::class);
    }

    public function test_stage_change_without_candidate_email_does_not_send_email()
    {
        Mail::fake();

        // Create a candidate with email for this test
        $candidateWithEmail = Candidate::factory()
            ->forTenant($this->tenant)
            ->create([
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'primary_email' => 'jane.doe@example.com',
            ]);

        $applicationWithEmail = Application::factory()
            ->forTenant($this->tenant)
            ->forJob($this->job)
            ->forCandidate($candidateWithEmail)
            ->create([
                'current_stage_id' => $this->stage1->id,
                'stage_position' => 0,
            ]);

        // Temporarily disable mail sending by setting mail.default to null
        config(['mail.default' => null]);

        $response = $this->actingAs($this->user)
            ->postJson("/{$this->tenant->slug}/jobs/{$this->job->id}/pipeline/move", [
                'application_id' => $applicationWithEmail->id,
                'from_stage_id' => $this->stage1->id,
                'to_stage_id' => $this->stage2->id,
                'to_position' => 0,
            ]);

        $response->assertStatus(200);
        $response->assertJson(['ok' => true]);

        // Assert no StageChanged email was queued (because mail is disabled)
        Mail::assertNotQueued(StageChanged::class);

        // Restore mail configuration
        config(['mail.default' => 'smtp']);
    }
}
