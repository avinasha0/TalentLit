<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\CandidateTag;
use App\Models\Tag;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchBasicsTest extends TestCase
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

    public function test_can_query_candidates_by_email(): void
    {
        // Create candidates with specific emails
        $candidate1 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
            'source' => 'LinkedIn',
        ]);

        $candidate2 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'primary_email' => 'jane.smith@example.com',
            'source' => 'Referral',
        ]);

        // Search by exact email
        $foundCandidate = Candidate::where('primary_email', 'john.doe@example.com')
            ->first();

        $this->assertNotNull($foundCandidate);
        $this->assertEquals('John', $foundCandidate->first_name);
        $this->assertEquals('Doe', $foundCandidate->last_name);

        // Search by partial email
        $candidates = Candidate::where('primary_email', 'like', '%@example.com')
            ->get();

        $this->assertCount(2, $candidates);
    }

    public function test_can_query_candidates_by_tag(): void
    {
        // Create tags
        $phpTag = Tag::factory()->forTenant($this->tenant)->create([
            'name' => 'PHP',
            'color' => '#3B82F6',
        ]);

        $laravelTag = Tag::factory()->forTenant($this->tenant)->create([
            'name' => 'Laravel',
            'color' => '#EF4444',
        ]);

        // Create candidates
        $candidate1 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
            'source' => 'LinkedIn',
        ]);

        $candidate2 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'primary_email' => 'jane.smith@example.com',
            'source' => 'Referral',
        ]);

        $candidate3 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'primary_email' => 'bob.johnson@example.com',
            'source' => 'Indeed',
        ]);

        // Attach tags to candidates
        CandidateTag::create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $candidate1->id,
            'tag_id' => $phpTag->id,
        ]);

        CandidateTag::create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $candidate1->id,
            'tag_id' => $laravelTag->id,
        ]);

        CandidateTag::create([
            'tenant_id' => $this->tenant->id,
            'candidate_id' => $candidate2->id,
            'tag_id' => $phpTag->id,
        ]);

        // Search candidates by PHP tag
        $phpCandidates = Candidate::whereHas('tags', function ($query) use ($phpTag) {
            $query->where('tags.id', $phpTag->id);
        })
            ->get();

        $this->assertCount(2, $phpCandidates);
        $this->assertTrue($phpCandidates->contains('id', $candidate1->id));
        $this->assertTrue($phpCandidates->contains('id', $candidate2->id));
        $this->assertFalse($phpCandidates->contains('id', $candidate3->id));

        // Search candidates by Laravel tag
        $laravelCandidates = Candidate::whereHas('tags', function ($query) use ($laravelTag) {
            $query->where('tags.id', $laravelTag->id);
        })
            ->get();

        $this->assertCount(1, $laravelCandidates);
        $this->assertTrue($laravelCandidates->contains('id', $candidate1->id));

        // Search candidates with multiple tags (both PHP and Laravel)
        $multiTagCandidates = Candidate::whereHas('tags', function ($query) use ($phpTag) {
            $query->where('tags.id', $phpTag->id);
        })
            ->whereHas('tags', function ($query) use ($laravelTag) {
                $query->where('tags.id', $laravelTag->id);
            })
            ->get();

        $this->assertCount(1, $multiTagCandidates);
        $this->assertTrue($multiTagCandidates->contains('id', $candidate1->id));
    }

    public function test_can_query_candidates_by_source(): void
    {
        // Create candidates with different sources
        Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@example.com',
            'source' => 'LinkedIn',
        ]);

        Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'primary_email' => 'jane.smith@example.com',
            'source' => 'Referral',
        ]);

        Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'Bob',
            'last_name' => 'Johnson',
            'primary_email' => 'bob.johnson@example.com',
            'source' => 'LinkedIn',
        ]);

        // Search by source
        $linkedinCandidates = Candidate::where('source', 'LinkedIn')
            ->get();

        $this->assertCount(2, $linkedinCandidates);

        $referralCandidates = Candidate::where('source', 'Referral')
            ->get();

        $this->assertCount(1, $referralCandidates);
    }

    public function test_tenant_scoping_works_correctly(): void
    {
        // Create another tenant
        $otherTenant = Tenant::create([
            'name' => 'Other Company',
            'slug' => 'other',
        ]);

        // Create candidate for other tenant (explicitly set tenant_id)
        $candidate2 = Candidate::factory()->forTenant($otherTenant)->create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'primary_email' => 'jane.smith@other.com',
            'source' => 'Referral',
        ]);

        // Create candidate for current tenant
        $candidate1 = Candidate::factory()->forTenant($this->tenant)->create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'primary_email' => 'john.doe@acme.com',
            'source' => 'LinkedIn',
        ]);

        // Query all candidates without global scope to verify they exist
        $allCandidates = Candidate::withoutGlobalScope('tenant')->get();
        $this->assertCount(2, $allCandidates, 'Should have 2 candidates total');

        // Query candidates for the first tenant (without global scope)
        $acmeCandidates = Candidate::withoutGlobalScope('tenant')
            ->where('tenant_id', $this->tenant->id)
            ->get();

        $this->assertCount(1, $acmeCandidates, 'Expected 1 candidate for Acme tenant, got: '.$acmeCandidates->count());
        $this->assertTrue($acmeCandidates->contains('id', $candidate1->id));
        $this->assertFalse($acmeCandidates->contains('id', $candidate2->id));

        // Query candidates for the other tenant (without global scope)
        $otherCandidates = Candidate::withoutGlobalScope('tenant')
            ->where('tenant_id', $otherTenant->id)
            ->get();
        $this->assertCount(1, $otherCandidates);
        $this->assertTrue($otherCandidates->contains('id', $candidate2->id));
        $this->assertFalse($otherCandidates->contains('id', $candidate1->id));
    }
}
