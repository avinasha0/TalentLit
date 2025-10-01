<?php

namespace Tests\Feature;

use App\Mail\InterviewScheduled;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\JobOpening;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InterviewTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;
    protected $candidate;
    protected $job;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant
        $this->tenant = Tenant::factory()->create();

        // Create user with Recruiter role
        $this->user = User::factory()->create();
        $this->user->tenants()->attach($this->tenant->id);

        // Create and assign Recruiter role for this tenant
        $recruiterRole = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Recruiter',
            'guard_name' => 'web',
            'tenant_id' => $this->tenant->id
        ]);
        $this->user->assignRole($recruiterRole);

        // Assign interview permissions to the user
        $this->user->givePermissionTo([
            'view interviews',
            'create interviews',
            'edit interviews',
            'delete interviews'
        ]);

        // Create candidate
        $this->candidate = Candidate::factory()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com'
        ]);

        // Don't create a job for now - test without job requirement
        $this->job = null;

        // Set tenant context
        \App\Support\Tenancy::set($this->tenant);
    }

    public function test_recruiter_can_schedule_interview()
    {
        Mail::fake();

        $this->actingAs($this->user);

        $interviewData = [
            'scheduled_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'duration_minutes' => 60,
            'mode' => 'onsite',
            'location' => 'Conference Room A',
            'notes' => 'Technical interview for software developer position',
            'panelists' => [$this->user->id]
        ];

        $response = $this->post(route('tenant.interviews.store', [
            'tenant' => $this->tenant->slug,
            'candidate' => $this->candidate
        ]), $interviewData);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Interview scheduled successfully');

        // Assert interview was created
        $this->assertDatabaseHas('interviews', [
            'candidate_id' => $this->candidate->id,
            'mode' => 'onsite',
            'location' => 'Conference Room A',
            'status' => 'scheduled'
        ]);

        // Assert email was queued
        Mail::assertQueued(InterviewScheduled::class, function ($mail) {
            return $mail->interview->candidate_id === $this->candidate->id;
        });
    }

    public function test_hiring_manager_cannot_schedule_interview()
    {
        $hiringManager = User::factory()->create();
        $hiringManager->tenants()->attach($this->tenant->id);
        
        // Create and assign Hiring Manager role for this tenant
        $hiringManagerRole = \Spatie\Permission\Models\Role::firstOrCreate([
            'name' => 'Hiring Manager',
            'guard_name' => 'web',
            'tenant_id' => $this->tenant->id
        ]);
        $hiringManager->assignRole($hiringManagerRole);

        $this->actingAs($hiringManager);

        $interviewData = [
            'scheduled_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'duration_minutes' => 60,
            'mode' => 'onsite',
            'location' => 'Conference Room A'
        ];

        $response = $this->post(route('tenant.interviews.store', [
            'tenant' => $this->tenant->slug,
            'candidate' => $this->candidate
        ]), $interviewData);

        $response->assertStatus(403);
    }

    public function test_interview_can_be_canceled()
    {
        Mail::fake();

        $this->actingAs($this->user);

        // Create interview
        $interview = Interview::factory()->create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'scheduled'
        ]);

        $response = $this->patch(route('tenant.interviews.cancel', [
            'tenant' => $this->tenant->slug,
            'interview' => $interview
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Interview canceled successfully');

        // Assert interview status was updated
        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'status' => 'canceled'
        ]);

        // Assert email was queued
        Mail::assertQueued(\App\Mail\InterviewCanceled::class);
    }

    public function test_interview_can_be_completed()
    {
        $this->actingAs($this->user);

        // Create interview
        $interview = Interview::factory()->create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $this->candidate->id,
            'status' => 'scheduled'
        ]);

        $response = $this->patch(route('tenant.interviews.complete', [
            'tenant' => $this->tenant->slug,
            'interview' => $interview
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Interview marked as completed');

        // Assert interview status was updated
        $this->assertDatabaseHas('interviews', [
            'id' => $interview->id,
            'status' => 'completed'
        ]);
    }

    public function test_interview_listing_shows_upcoming_interviews()
    {
        $this->actingAs($this->user);

        // Create past interview
        Interview::factory()->create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $this->candidate->id,
            'scheduled_at' => now()->subDays(1),
            'status' => 'scheduled'
        ]);

        // Create future interview
        $futureInterview = Interview::factory()->create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $this->candidate->id,
            'scheduled_at' => now()->addDays(7),
            'status' => 'scheduled'
        ]);

        $response = $this->get(route('tenant.interviews.index', [
            'tenant' => $this->tenant->slug
        ]));

        $response->assertStatus(200);
        $response->assertSee($futureInterview->candidate->first_name);
    }

    public function test_interview_validation_requires_future_date()
    {
        $this->actingAs($this->user);

        $interviewData = [
            'scheduled_at' => now()->subDays(1)->format('Y-m-d\TH:i'), // Past date
            'duration_minutes' => 60,
            'mode' => 'onsite',
            'location' => 'Conference Room A'
        ];

        $response = $this->post(route('tenant.interviews.store', [
            'tenant' => $this->tenant->slug,
            'candidate' => $this->candidate
        ]), $interviewData);

        $response->assertSessionHasErrors(['scheduled_at']);
    }

    public function test_interview_validation_requires_valid_duration()
    {
        $this->actingAs($this->user);

        $interviewData = [
            'scheduled_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'duration_minutes' => 5, // Too short
            'mode' => 'onsite',
            'location' => 'Conference Room A'
        ];

        $response = $this->post(route('tenant.interviews.store', [
            'tenant' => $this->tenant->slug,
            'candidate' => $this->candidate
        ]), $interviewData);

        $response->assertSessionHasErrors(['duration_minutes']);
    }

    public function test_interview_validation_requires_valid_mode()
    {
        $this->actingAs($this->user);

        $interviewData = [
            'scheduled_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'duration_minutes' => 60,
            'mode' => 'invalid_mode',
            'location' => 'Conference Room A'
        ];

        $response = $this->post(route('tenant.interviews.store', [
            'tenant' => $this->tenant->slug,
            'candidate' => $this->candidate
        ]), $interviewData);

        $response->assertSessionHasErrors(['mode']);
    }
}