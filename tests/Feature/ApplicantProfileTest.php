<?php

namespace Tests\Feature;

use App\Models\Candidate;
use App\Models\CandidateAccount;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApplicantProfileTest extends TestCase
{
    use RefreshDatabase;

    private function makeApplicantForTenant(Tenant $tenant): CandidateAccount
    {
        $account = CandidateAccount::create([
            'tenant_id' => $tenant->id,
            'email' => 'john@example.com',
            'name' => 'John Applicant',
            'password' => Hash::make('password123'),
        ]);

        Candidate::factory()->forTenant($tenant)->create([
            'candidate_account_id' => $account->id,
            'primary_email' => 'john@example.com',
        ]);

        return $account;
    }

    public function test_profile_page_renders_for_authenticated_applicant(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'acme-co']);
        $account = $this->makeApplicantForTenant($tenant);

        $response = $this->actingAs($account, 'candidate')
            ->get(route('tenant.applicant.profile', ['tenant' => $tenant->slug]));

        $response->assertOk();
        $response->assertSee('My profile', false);
        $response->assertSee('Change email', false);
        $response->assertSee('Change password', false);
    }

    public function test_applicant_can_update_email_and_syncs_candidate_primary_email(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'acme-co']);
        $account = $this->makeApplicantForTenant($tenant);

        $this->actingAs($account, 'candidate')
            ->put(route('tenant.applicant.profile.email', ['tenant' => $tenant->slug]), [
                'current_password' => 'password123',
                'email' => 'new.john@example.com',
            ])
            ->assertRedirect();

        $account->refresh();
        $this->assertSame('new.john@example.com', $account->email);

        $this->assertDatabaseHas('candidates', [
            'tenant_id' => $tenant->id,
            'candidate_account_id' => $account->id,
            'primary_email' => 'new.john@example.com',
        ]);
    }

    public function test_applicant_can_update_password(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'acme-co']);
        $account = $this->makeApplicantForTenant($tenant);

        $this->actingAs($account, 'candidate')
            ->put(route('tenant.applicant.profile.password', ['tenant' => $tenant->slug]), [
                'current_password' => 'password123',
                'password' => 'new-password-9',
                'password_confirmation' => 'new-password-9',
            ])
            ->assertRedirect();

        $account->refresh();
        $this->assertTrue(Hash::check('new-password-9', $account->password));
    }
}
