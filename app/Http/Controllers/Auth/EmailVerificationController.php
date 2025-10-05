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
            'email' => 'required|email'
        ]);

        $email = $request->email;
        
        // Check if we have pending registration data for this email
        $pendingRegistration = $request->session()->get('pending_registration');
        if (!$pendingRegistration || $pendingRegistration['email'] !== $email) {
            return redirect()->route('register')->with('error', 'No pending registration found for this email.');
        }

        // Check if registration data is not older than 10 minutes
        $registrationTime = \Carbon\Carbon::parse($pendingRegistration['created_at']);
        if ($registrationTime->diffInMinutes(now()) > 10) {
            // Clear expired session data
            $request->session()->forget('pending_registration');
            $request->session()->forget('pending_verification_email');
            
            return redirect()->route('register')->with('error', 'Registration session expired. Please register again.');
        }

        // Check if user already exists and is verified
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->email_verified_at) {
            return redirect()->route('register')->with('error', 'Email already verified. Please login instead.');
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
        try {
            \Log::info('OTP verification attempt', [
                'email' => $request->email,
                'otp_length' => strlen($request->otp ?? ''),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip()
            ]);

            $request->validate([
                'email' => 'required|email',
                'otp' => 'required|string|size:6'
            ]);

            $email = $request->email;
            $otp = $request->otp;

            // Verify OTP
            if (!EmailVerificationOtp::verify($email, $otp)) {
                \Log::warning('OTP verification failed', [
                    'email' => $email,
                    'otp' => $otp,
                    'ip' => $request->ip()
                ]);
                return back()->with('error', 'Invalid or expired verification code.');
            }

            // Check if we have pending registration data in session
            $pendingRegistration = $request->session()->get('pending_registration');
            if (!$pendingRegistration || $pendingRegistration['email'] !== $email) {
                \Log::error('No pending registration data found for email during OTP verification', [
                    'email' => $email,
                    'has_pending_data' => !empty($pendingRegistration)
                ]);
                return redirect()->route('register')->with('error', 'Registration session expired. Please register again.');
            }

            // Check if registration data is not older than 10 minutes
            $registrationTime = \Carbon\Carbon::parse($pendingRegistration['created_at']);
            if ($registrationTime->diffInMinutes(now()) > 10) {
                \Log::warning('Registration session expired during OTP verification', [
                    'email' => $email,
                    'registration_time' => $registrationTime,
                    'minutes_elapsed' => $registrationTime->diffInMinutes(now())
                ]);
                
                // Clear expired session data
                $request->session()->forget('pending_registration');
                $request->session()->forget('pending_verification_email');
                
                return redirect()->route('register')->with('error', 'Registration session expired. Please register again.');
            }

            // Create the user now that OTP is verified
            $user = User::create([
                'name' => $pendingRegistration['name'],
                'email' => $pendingRegistration['email'],
                'password' => $pendingRegistration['password'],
                'email_verified_at' => now(), // Mark as verified immediately
            ]);

            // Log the user in first
            Auth::login($user);
            
            // Clear session data
            $request->session()->forget('pending_verification_email');
            $request->session()->forget('pending_registration');
            
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

        } catch (\Exception $e) {
            \Log::error('OTP verification error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'email' => $request->email ?? 'unknown'
            ]);
            
            return back()->with('error', 'An error occurred during verification. Please try again.');
        }
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
