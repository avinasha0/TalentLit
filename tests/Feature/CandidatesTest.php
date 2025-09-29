<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidatesTest extends TestCase
{
    use RefreshDatabase;
    public function test_candidates_index_returns_200()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/{$tenant->slug}/candidates");

        $response->assertStatus(200);
        $response->assertSee('Candidates');
    }

    public function test_candidates_index_shows_empty_state_when_no_candidates()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get("/{$tenant->slug}/candidates");

        $response->assertStatus(200);
        $response->assertSee('No candidates found');
    }

    public function test_candidates_index_shows_candidates_when_they_exist()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $candidate = Candidate::factory()->forTenant($tenant)->create();

        $response = $this->actingAs($user)->get("/{$tenant->slug}/candidates");

        $response->assertStatus(200);
        $response->assertSee($candidate->full_name);
    }
}
