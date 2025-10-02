<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountSettingsController extends Controller
{
    public function index(string $tenant)
    {
        $user = Auth::user();
        
        return view('tenant.account.settings', [
            'tenant' => tenant(),
            'user' => $user,
        ]);
    }

    public function updateNotifications(Request $request, string $tenant)
    {
        $request->validate([
            'email_weekly_summaries' => 'boolean',
            'notify_new_applications' => 'boolean',
            'send_marketing_emails' => 'boolean',
        ]);

        $user = Auth::user();
        $user->update([
            'email_weekly_summaries' => $request->boolean('email_weekly_summaries'),
            'notify_new_applications' => $request->boolean('notify_new_applications'),
            'send_marketing_emails' => $request->boolean('send_marketing_emails'),
        ]);

        return back()->with('success', 'Notification preferences updated successfully.');
    }

    public function updatePassword(Request $request, string $tenant)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateEmail(Request $request, string $tenant)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'password' => 'required|current_password',
        ]);

        $user = Auth::user();
        $user->update([
            'email' => $request->email,
            'email_verified_at' => null, // Require re-verification
        ]);

        // Send verification email
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email address updated successfully. Please check your new email for verification.');
    }
}
