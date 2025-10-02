<?php

namespace App\Http\Controllers;

use App\Models\Waitlist;
use App\Models\WaitlistOtp;
use App\Mail\WaitlistOtpMail;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class WaitlistController extends Controller
{
    /**
     * Send OTP to email for verification.
     */
    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->email;

        // Check if email is already on waitlist
        if (Waitlist::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already on our waitlist.',
            ], 422);
        }

        try {
            // Generate OTP
            $otpRecord = WaitlistOtp::generateOtp($email);

            // Send OTP email
            Mail::to($email)->send(new WaitlistOtpMail($otpRecord->otp, $email));

            return response()->json([
                'success' => true,
                'message' => 'OTP sent to your email address. Please check your inbox.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send OTP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.',
            ], 500);
        }
    }

    /**
     * Verify OTP and add user to waitlist.
     */
    public function verifyOtpAndStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid OTP.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = session('waitlist_email');
        $waitlistData = session('waitlist_data');
        $otp = $request->otp;

        if (!$email || !$waitlistData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start the process again.',
            ], 400);
        }

        // Verify OTP
        if (!WaitlistOtp::verifyOtp($email, $otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP. Please request a new one.',
            ], 422);
        }

        // Check if email is already on waitlist
        if (Waitlist::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already on our waitlist.',
            ], 422);
        }

        try {
            $waitlist = Waitlist::create([
                'email' => $email,
                'name' => $waitlistData['name'],
                'company' => $waitlistData['company'],
                'plan_slug' => $waitlistData['plan_slug'],
                'message' => $waitlistData['message'],
            ]);

            // Clear session data
            session()->forget(['waitlist_email', 'waitlist_data']);

            return response()->json([
                'success' => true,
                'message' => 'Thank you! You\'ve been added to our waitlist. We\'ll notify you when the plan becomes available.',
                'data' => $waitlist
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to add to waitlist: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    /**
     * Add logged-in user to waitlist.
     */
    public function store(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                Log::warning('Unauthenticated user attempted to join waitlist');
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You must be logged in to join the waitlist.',
                    ], 401);
                } else {
                    return redirect()->route('login')->with('error', 'You must be logged in to join the waitlist.');
                }
            }

            Log::info('Waitlist request received', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'request_data' => $request->all()
            ]);

            $validator = Validator::make($request->all(), [
                'plan_slug' => 'required|string|in:pro,enterprise',
                'company' => 'nullable|string|max:255',
                'message' => 'nullable|string|max:1000',
                'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
            ]);

            if ($validator->fails()) {
                Log::warning('Waitlist validation failed', [
                    'user_id' => auth()->id(),
                    'errors' => $validator->errors()->toArray()
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please check your input and try again.',
                        'errors' => $validator->errors()
                    ], 422);
                } else {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
            }

            $user = auth()->user();
            $email = $user->email;

            // Check if email is already on waitlist
            if (Waitlist::where('email', $email)->exists()) {
                Log::info('User already on waitlist', ['email' => $email]);
                return response()->json([
                    'success' => false,
                    'message' => 'You are already on our waitlist.',
                ], 422);
            }

            $waitlist = Waitlist::create([
                'email' => $email,
                'name' => $user->name,
                'company' => $request->company ?? null,
                'plan_slug' => $request->plan_slug,
                'message' => $request->message,
            ]);

            Log::info('User successfully added to waitlist', [
                'user_id' => auth()->id(),
                'email' => $email,
                'plan_slug' => $request->plan_slug
            ]);

            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Thank you! You\'ve been added to our waitlist. We\'ll notify you when the plan becomes available.',
                    'data' => $waitlist
                ]);
            } else {
                // Regular form submission - redirect with success message
                return redirect()->back()->with('success', 'Thank you! You\'ve been added to our waitlist. We\'ll notify you when the plan becomes available.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to add to waitlist: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }

    /**
     * Resend OTP to the email address in session.
     */
    public function resendOtp(Request $request)
    {
        $email = session('waitlist_email');
        
        if (!$email) {
            return response()->json([
                'success' => false,
                'message' => 'No email found in session. Please start the process again.',
            ], 400);
        }

        // Check if email is already on waitlist
        if (Waitlist::where('email', $email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'This email is already on our waitlist.',
            ], 422);
        }

        try {
            // Generate new OTP
            $otpRecord = WaitlistOtp::generateOtp($email);

            // Send OTP email
            Mail::to($email)->send(new WaitlistOtpMail($otpRecord->otp, $email));

            return response()->json([
                'success' => true,
                'message' => 'New OTP sent to your email address. Please check your inbox.',
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again later.',
            ], 500);
        }
    }

    /**
     * Check if email is already on waitlist.
     */
    public function check(Request $request)
    {
        $email = $request->query('email');
        
        if (!$email) {
            return response()->json(['exists' => false]);
        }

        $exists = Waitlist::where('email', $email)->exists();
        
        return response()->json(['exists' => $exists]);
    }
}