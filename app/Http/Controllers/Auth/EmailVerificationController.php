<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\EmailVerificationOtpMail;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class EmailVerificationController extends Controller
{
    /**
     * Show the email verification form
     */
    public function show(Request $request)
    {
        $email = $request->session()->get('pending_verification_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'No pending verification found.');
        }

        return view('auth.verify-email', compact('email'));
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user || $user->email_verified_at) {
            return redirect()->route('register')->with('error', 'Invalid email or already verified.');
        }

        // Generate OTP
        $otpRecord = EmailVerificationOtp::generateForEmail($email);

        // Send email
        try {
            Mail::to($email)->send(new EmailVerificationOtpMail($otpRecord->otp, $email));
            
            $request->session()->put('pending_verification_email', $email);
            
            return back()->with('success', 'Verification code sent to your email address.');
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Verify OTP and complete registration
     */
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $email = $request->email;
        $otp = $request->otp;

        // Verify OTP
        if (!EmailVerificationOtp::verify($email, $otp)) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        // Get user and mark email as verified
        $user = User::where('email', $email)->first();
        if (!$user) {
            return redirect()->route('register')->with('error', 'User not found.');
        }

        $user->update(['email_verified_at' => now()]);

        // Log the user in first
        Auth::login($user);
        
        // Clear session
        $request->session()->forget('pending_verification_email');
        
        // Set flag to indicate user just verified email (for onboarding flow)
        $request->session()->put('just_verified_email', true);
        
        // Regenerate session ID for security
        $request->session()->regenerate();

        // Log the successful verification
        \Log::info('User email verified successfully', [
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        return redirect()->route('onboarding.organization')
            ->with('success', 'Email verified successfully! Welcome to TalentLit.');
    }

    /**
     * Resend OTP
     */
    public function resend(Request $request)
    {
        $email = $request->session()->get('pending_verification_email');
        
        if (!$email) {
            return redirect()->route('register')->with('error', 'No pending verification found.');
        }

        return $this->sendOtp($request->merge(['email' => $email]));
    }


}
