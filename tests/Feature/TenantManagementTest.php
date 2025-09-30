<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\CandidateTag;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Location;
use App\Models\Tag;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Traits\AssignsRolesToUsers;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase, AssignsRolesToUsers;

    private Tenant $tenant;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant and user
        $this->tenant = Tenant::factory()->create(['slug' => 'acme']);
        $this->user = User::factory()->create();

        // Assign Owner role to user
        $this->assignRoleToUser($this->user, $this->tenant, 'Owner');

        // Set tenant context
        Tenancy::set($this->tenant);

        // Create test data
        $department = Department::factory()->forTenant($this->tenant)->create(['name' => 'Engineering']);
        $location = Location::factory()->forTenant($this->tenant)->create(['name' => 'Chennai']);

        $this->job = JobOpening::factory()
            ->forTenant($this->tenant)
            ->published()
            ->create([
                'title' => 'Senior PHP Developer',
                'department_id' => $department->id,
                'location_id' => $location->id,
            ]);

        $this->candidate = Candidate::factory()
            ->forTenant($this->tenant)
            ->create([
                'first_name' => 'John',
                'last_name' => 'Doe',
                'primary_email' => 'john.doe@example.com',
                'source' => 'Referral',
            ]);

        $this->tag = Tag::factory()
            ->forTenant($this->tenant)
            ->create(['name' => 'PHP']);

        // Create pivot record manually to ensure tenant_id is set
        CandidateTag::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $this->candidate->id,
            'tag_id' => $this->tag->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_jobs_listing_returns_200()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
    }

    public function test_jobs_listing_json_response()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs", [
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'jobs' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'department',
                    'location',
                    'employment_type',
                    'status',
                    'openings_count',
                    'published_at',
                    'created_at',
                ],
            ],
            'pagination' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);
    }

    public function test_jobs_listing_filters_work()
    {
        // Create another job with different status
        JobOpening::factory()
            ->forTenant($this->tenant)
            ->create([
                'title' => 'Draft Job',
                'status' => 'draft',
            ]);

        // Test status filter
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs?status=published");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('Draft Job');

        // Test keyword search
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs?keyword=PHP");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
    }

    public function test_job_detail_page_loads_correctly()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs/{$this->job->id}");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertSee('Engineering');
        $response->assertSee('Chennai');
    }

    public function test_candidates_listing_returns_200()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('john.doe@example.com');
    }

    public function test_candidates_listing_json_response()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates", [
                'Accept' => 'application/json',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'candidates' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'source',
                    'tags' => [
                        '*' => [
                            'name',
                            'color',
                        ],
                    ],
                    'applications_count',
                    'created_at',
                ],
            ],
            'pagination' => [
                'current_page',
                'last_page',
                'per_page',
                'total',
            ],
        ]);
    }

    public function test_candidates_listing_filters_work()
    {
        // Create another candidate
        Candidate::factory()
            ->forTenant($this->tenant)
            ->create([
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'primary_email' => 'jane.smith@example.com',
                'source' => 'LinkedIn',
            ]);

        // Test search filter
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates?search=John");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');

        // Test source filter
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates?source=Referral");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');

        // Test tag filter
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates?tag=PHP");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Jane Smith');
    }

    public function test_candidate_detail_page_loads_correctly()
    {
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates/{$this->candidate->id}");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('john.doe@example.com');
        $response->assertSee('PHP');
    }

    public function test_candidate_detail_shows_applications()
    {
        // Create an application
        Application::factory()
            ->forTenant($this->tenant)
            ->create([
                'candidate_id' => $this->candidate->id,
                'job_opening_id' => $this->job->id,
            ]);

        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates/{$this->candidate->id}");

        $response->assertStatus(200);
        $response->assertSee('Applications (1)');
        $response->assertSee('Senior PHP Developer');
    }

    public function test_tenant_isolation_jobs_listing()
    {
        // Create another tenant with a job
        $otherTenant = Tenant::factory()->create(['slug' => 'beta']);
        $otherDept = Department::factory()->forTenant($otherTenant)->create();
        $otherLoc = Location::factory()->forTenant($otherTenant)->create();

        $otherJob = JobOpening::factory()
            ->forTenant($otherTenant)
            ->create(['title' => 'Other Company Job']);

        // Test that other tenant's job doesn't appear
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs");

        $response->assertStatus(200);
        $response->assertSee('Senior PHP Developer');
        $response->assertDontSee('Other Company Job');
    }

    public function test_tenant_isolation_candidates_listing()
    {
        // Create another tenant with a candidate
        $otherTenant = Tenant::factory()->create(['slug' => 'beta']);
        $otherCandidate = Candidate::factory()
            ->forTenant($otherTenant)
            ->create([
                'first_name' => 'Other',
                'last_name' => 'Candidate',
                'primary_email' => 'other@example.com',
            ]);

        // Test that other tenant's candidate doesn't appear
        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/candidates");

        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertDontSee('Other Candidate');
    }

    public function test_unauthorized_access_redirects_to_login()
    {
        // Test without authentication
        $response = $this->get("/{$this->tenant->slug}/jobs");
        $response->assertRedirect('/login');

        $response = $this->get("/{$this->tenant->slug}/candidates");
        $response->assertRedirect('/login');
    }

    public function test_job_detail_with_stages_displays_correctly()
    {
        // Create job stages
        $this->job->jobStages()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Sourced',
            'sort_order' => 1,
        ]);

        $this->job->jobStages()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Interview',
            'sort_order' => 2,
        ]);

        $response = $this->actingAs($this->user)
            ->get("/{$this->tenant->slug}/jobs/{$this->job->id}");

        $response->assertStatus(200);
        $response->assertSee('Pipeline Stages');
        $response->assertSee('Sourced');
        $response->assertSee('Interview');
    }
}
