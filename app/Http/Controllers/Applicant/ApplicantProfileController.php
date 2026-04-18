<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\CandidateAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class ApplicantProfileController extends Controller
{
    public function edit(): View
    {
        $tenant = tenant();
        if (! $tenant) {
            abort(404);
        }

        $tenant->load('branding');

        /** @var CandidateAccount $account */
        $account = auth('candidate')->user();

        $candidate = Candidate::query()
            ->where('tenant_id', $tenant->id)
            ->where('candidate_account_id', $account->id)
            ->first();

        return view('applicant.profile', [
            'tenant' => $tenant,
            'account' => $account,
            'candidate' => $candidate,
        ]);
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $tenant = tenant();
        if (! $tenant) {
            abort(404);
        }

        /** @var CandidateAccount $account */
        $account = auth('candidate')->user();

        $validated = $request->validateWithBag('updateEmail', [
            'current_password' => ['required', 'current_password:candidate'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('candidate_accounts', 'email')
                    ->where(fn ($q) => $q->where('tenant_id', $tenant->id))
                    ->ignore($account->id),
            ],
        ]);

        $newEmail = $validated['email'];

        DB::transaction(function () use ($account, $tenant, $newEmail): void {
            $account->forceFill([
                'email' => $newEmail,
                'email_verified_at' => now(),
            ])->save();

            Candidate::query()
                ->where('tenant_id', $tenant->id)
                ->where('candidate_account_id', $account->id)
                ->update(['primary_email' => $newEmail]);
        });

        return back()->with('status', 'email-updated');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $tenant = tenant();
        if (! $tenant) {
            abort(404);
        }

        /** @var CandidateAccount $account */
        $account = auth('candidate')->user();

        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password:candidate'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $account->update([
            'password' => $validated['password'],
        ]);

        return back()->with('status', 'password-updated');
    }
}
