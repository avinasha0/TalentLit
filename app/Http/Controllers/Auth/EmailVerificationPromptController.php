<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if ($request->user()->hasVerifiedEmail()) {
            // Check if user has tenants, if not redirect to onboarding
            if ($request->user()->tenants->count() === 0) {
                return redirect()->route('onboarding.organization');
            }
            return redirect()->intended(route('dashboard', absolute: false));
        }
        
        return view('auth.verify-email');
    }
}
