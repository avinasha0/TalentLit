<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    public function show($token)
    {
        $user = User::where('invitation_token', $token)
            ->where('is_invited', true)
            ->whereNull('invitation_accepted_at')
            ->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid or expired invitation link.');
        }

        return view('auth.invitation', compact('user', 'token'));
    }

    public function accept(Request $request, $token)
    {
        $user = User::where('invitation_token', $token)
            ->where('is_invited', true)
            ->whereNull('invitation_accepted_at')
            ->first();

        if (!$user) {
            return redirect('/login')->with('error', 'Invalid or expired invitation link.');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        // Accept the invitation and set the password
        $user->acceptInvitation($request->password);

        // Log the user in
        Auth::login($user);

        // Redirect to the appropriate dashboard based on tenant
        $tenant = $user->tenants->first();
        if ($tenant) {
            return redirect("/{$tenant->slug}/dashboard")->with('success', 'Welcome! Your account has been set up successfully.');
        }

        return redirect('/dashboard')->with('success', 'Welcome! Your account has been set up successfully.');
    }
}