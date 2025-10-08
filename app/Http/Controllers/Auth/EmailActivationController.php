<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmailActivationController extends Controller
{
    /**
     * Activate user account via email link
     */
    public function activate(Request $request, $token)
    {
        // Find user with the activation token
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Invalid activation link. Please check your email or register again.');
        }

        // Check if user is already activated
        if ($user->email_verified_at) {
            return redirect()->route('login')
                ->with('info', 'Your account is already activated. Please log in.');
        }

        // Activate the user account
        $user->email_verified_at = now();
        $user->activation_token = null; // Clear the token
        $user->save();

        \Log::info('User account activated successfully', [
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        // Auto-login the user
        Auth::login($user);

        // Set flag to indicate user just verified email (for onboarding flow)
        $request->session()->put('just_verified_email', true);
        
        // Regenerate session ID for security
        $request->session()->regenerate();

        return redirect()->route('onboarding.organization')
            ->with('success', 'Account activated successfully! Welcome to TalentLit. Let\'s set up your organization.');
    }

    /**
     * Resend activation email
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return back()->with('info', 'This account is already activated.');
        }

        // Generate new activation token
        $activationToken = \Str::random(60);
        $user->activation_token = $activationToken;
        $user->save();

        // Generate activation URL
        $activationUrl = route('auth.activate', ['token' => $activationToken]);

        // Send activation email
        try {
            \Mail::to($user->email)->send(new \App\Mail\EmailActivationMail([
                'name' => $user->name,
                'email' => $user->email,
            ], $activationUrl));

            return back()->with('success', 'Activation email sent! Please check your inbox.');
        } catch (\Exception $e) {
            \Log::error('Failed to resend activation email', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to send activation email. Please try again.');
        }
    }
}
