<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(string $tenant)
    {
        $user = Auth::user();
        $currentTenant = tenant();
        
        // Get user's role for this tenant
        $userRole = $user->getRolesForTenant($currentTenant->id)->first();
        $roleName = $userRole ? $userRole->name : 'No Role';
        
        return view('tenant.account.profile', [
            'tenant' => $currentTenant,
            'user' => $user,
            'roleName' => $roleName,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, string $tenant)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the user's password.
     */
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

    /**
     * Update the user's email address.
     */
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

    /**
     * Update notification preferences.
     */
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
}
