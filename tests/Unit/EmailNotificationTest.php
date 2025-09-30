<?php

namespace Tests\Unit;

use App\Mail\ApplicationReceived;
use App\Mail\NewApplication;
use App\Mail\StageChanged;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Tenant;
use App\Services\NotificationService;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_application_received_mailable_renders_correctly()
    {
        Mail::fake();

        $tenant = Tenant::factory()->create(['name' => 'Test Company']);
        Tenancy::set($tenant);
        
        $candidate = Candidate::factory()->forTenant($tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
        ]);
        $job = JobOpening::factory()->forTenant($tenant)->create([
            'title' => 'Senior PHP Developer',
        ]);
        $application = Application::factory()->forTenant($tenant)->create([
            'candidate_id' => $candidate->id,
            'job_opening_id' => $job->id,
        ]);

        $mailable = new ApplicationReceived($candidate, $job, $application);
        $mailable->to($candidate->primary_email);

        Mail::send($mailable);

        Mail::assertSent(ApplicationReceived::class, function ($mail) use ($candidate, $job) {
            return $mail->candidate->primary_email === $candidate->primary_email &&
                   $mail->job->title === $job->title;
        });
    }

    public function test_new_application_mailable_renders_correctly()
    {
        Mail::fake();

        $tenant = Tenant::factory()->create(['name' => 'Test Company']);
        Tenancy::set($tenant);
        
        $candidate = Candidate::factory()->forTenant($tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
        ]);
        $job = JobOpening::factory()->forTenant($tenant)->create([
            'title' => 'Senior PHP Developer',
        ]);
        $application = Application::factory()->forTenant($tenant)->create([
            'candidate_id' => $candidate->id,
            'job_opening_id' => $job->id,
        ]);

        $mailable = new NewApplication($tenant, $job, $candidate, $application);
        $mailable->to('recruiter@example.com');

        Mail::send($mailable);

        Mail::assertSent(NewApplication::class, function ($mail) use ($candidate, $job, $tenant) {
            return $mail->candidate->primary_email === $candidate->primary_email &&
                   $mail->job->title === $job->title &&
                   $mail->tenant->id === $tenant->id;
        });
    }

    public function test_stage_changed_mailable_renders_correctly()
    {
        Mail::fake();

        $tenant = Tenant::factory()->create(['name' => 'Test Company']);
        Tenancy::set($tenant);
        
        $candidate = Candidate::factory()->forTenant($tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
        ]);
        $job = JobOpening::factory()->forTenant($tenant)->create([
            'title' => 'Senior PHP Developer',
        ]);
        $stage = JobStage::factory()->forTenant($tenant)->create([
            'name' => 'Interview',
        ]);
        $application = Application::factory()->forTenant($tenant)->create([
            'candidate_id' => $candidate->id,
            'job_opening_id' => $job->id,
        ]);

        $mailable = new StageChanged($tenant, $job, $candidate, $application, $stage, 'Moving to interview stage');
        $mailable->to($candidate->primary_email);

        Mail::send($mailable);

        Mail::assertSent(StageChanged::class, function ($mail) use ($candidate, $job, $stage, $tenant) {
            return $mail->candidate->primary_email === $candidate->primary_email &&
                   $mail->job->title === $job->title &&
                   $mail->stage->name === $stage->name &&
                   $mail->message === 'Moving to interview stage' &&
                   $mail->tenant->id === $tenant->id;
        });
    }

    public function test_notification_service_gets_recruiter_users()
    {
        $tenant = Tenant::factory()->create();
        
        // Create users with different roles
        $owner = \App\Models\User::factory()->create(['email' => 'owner@example.com']);
        $admin = \App\Models\User::factory()->create(['email' => 'admin@example.com']);
        $recruiter = \App\Models\User::factory()->create(['email' => 'recruiter@example.com']);
        $hiringManager = \App\Models\User::factory()->create(['email' => 'hiring@example.com']);

        // Add users to tenant
        $tenant->users()->attach([$owner->id, $admin->id, $recruiter->id, $hiringManager->id]);

        // Create roles
        $ownerRole = \App\Models\TenantRole::createForTenant([
            'name' => 'Owner',
            'guard_name' => 'web',
        ], $tenant->id);

        $adminRole = \App\Models\TenantRole::createForTenant([
            'name' => 'Admin',
            'guard_name' => 'web',
        ], $tenant->id);

        $recruiterRole = \App\Models\TenantRole::createForTenant([
            'name' => 'Recruiter',
            'guard_name' => 'web',
        ], $tenant->id);

        $hiringManagerRole = \App\Models\TenantRole::createForTenant([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
        ], $tenant->id);

        // Assign roles
        $owner->assignRole($ownerRole);
        $admin->assignRole($adminRole);
        $recruiter->assignRole($recruiterRole);
        $hiringManager->assignRole($hiringManagerRole);

        // Test getting recruiter users
        $recruiterUsers = NotificationService::getRecruiterUsers($tenant);

        $this->assertCount(3, $recruiterUsers);
        $this->assertTrue($recruiterUsers->contains('email', 'owner@example.com'));
        $this->assertTrue($recruiterUsers->contains('email', 'admin@example.com'));
        $this->assertTrue($recruiterUsers->contains('email', 'recruiter@example.com'));
        $this->assertFalse($recruiterUsers->contains('email', 'hiring@example.com'));
    }
}
