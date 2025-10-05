<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Check if user has tenants, if not redirect to onboarding
            if ($request->user()->tenants->count() === 0) {
                return redirect()->route('onboarding.organization');
            }
            return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        // Check if user has tenants, if not redirect to onboarding
        if ($request->user()->tenants->count() === 0) {
            return redirect()->route('onboarding.organization');
        }

        return redirect()->intended(route('dashboard', absolute: false).'?verified=1');
    }
}
