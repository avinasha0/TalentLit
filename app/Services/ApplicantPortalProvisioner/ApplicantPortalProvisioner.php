<?php

namespace App\Services\ApplicantPortalProvisioner;

use App\Mail\ApplicantPortalCredentials;
use App\Models\Candidate;
use App\Models\CandidateAccount;
use App\Models\JobOpening;
use App\Models\Tenant;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ApplicantPortalProvisioner
{
    public function provision(
        Tenant $tenant,
        JobOpening $job,
        Candidate $candidate,
        string $firstName,
        string $lastName,
        string $email,
    ): ApplicantPortalProvisioningResult {
        $email = strtolower(trim($email));
        $name = trim($firstName.' '.$lastName);

        $account = CandidateAccount::query()
            ->where('tenant_id', $tenant->id)
            ->where('email', $email)
            ->first();

        if (! $account) {
            $plainPassword = Str::password(14);
            $account = CandidateAccount::create([
                'tenant_id' => $tenant->id,
                'email' => $email,
                'name' => $name,
                'password' => $plainPassword,
                'email_verified_at' => now(),
            ]);

            $this->sendCredentialsMail($tenant, $email, $plainPassword, isReturningApplicant: false);

            $this->linkCandidatesToAccount($tenant, $email, $account->id);

            return new ApplicantPortalProvisioningResult(
                credentialsEmailQueued: true,
                canAccessApplicantPortal: true,
            );
        }

        // Existing portal account: issue a fresh auto-generated password so every application email includes sign-in details.
        $plainPassword = Str::password(14);
        $account->forceFill([
            'name' => $name,
            'password' => $plainPassword,
        ])->save();

        $this->linkCandidatesToAccount($tenant, $email, $account->id);

        $this->sendCredentialsMail($tenant, $email, $plainPassword, isReturningApplicant: true);

        return new ApplicantPortalProvisioningResult(
            credentialsEmailQueued: true,
            canAccessApplicantPortal: true,
        );
    }

    private function linkCandidatesToAccount(Tenant $tenant, string $email, string $accountId): void
    {
        Candidate::query()
            ->where('tenant_id', $tenant->id)
            ->where('primary_email', $email)
            ->update(['candidate_account_id' => $accountId]);
    }

    private function sendCredentialsMail(Tenant $tenant, string $email, string $plainPassword, bool $isReturningApplicant): void
    {
        try {
            Mail::to($email)->send(new ApplicantPortalCredentials($tenant, $email, $plainPassword, $isReturningApplicant));
        } catch (\Throwable $e) {
            \Log::warning('Failed to send applicant portal credentials email', [
                'tenant_id' => $tenant->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
