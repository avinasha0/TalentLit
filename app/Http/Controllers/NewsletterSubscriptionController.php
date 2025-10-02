<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterOtpMail;
use App\Models\NewsletterOtp;
use App\Models\NewsletterSubscription;

class NewsletterSubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        // Basic validation
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'g-recaptcha-response' => 'nullable',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid email address.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return view('newsletter.subscribe', [
                'errors' => $validator->errors(),
                'old_email' => $request->input('email')
            ]);
        }

        // Verify reCAPTCHA with Google (optional)
        $recaptchaToken = $request->input('g-recaptcha-response');
        if (!empty($recaptchaToken)) {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('recaptcha.secret_key'),
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ])->json();

            if (empty($response['success']) || !$response['success']) {
                // Log the failure but don't block the subscription
                \Log::warning('reCAPTCHA verification failed but allowing subscription', [
                    'email' => $request->input('email'),
                    'response' => $response
                ]);
            }
        }

        $email = $request->input('email');

        // Check if already subscribed
        $existingSubscription = NewsletterSubscription::where('email', $email)->first();
        if ($existingSubscription && $existingSubscription->is_verified) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.',
                ], 422);
            }
            return view('newsletter.subscribe', [
                'errors' => collect(['email' => 'This email is already subscribed to our newsletter.']),
                'old_email' => $email
            ]);
        }

        // Generate and send OTP
        $otpRecord = NewsletterOtp::createForEmail($email);
        
        try {
            Mail::to($email)->send(new NewsletterOtpMail($otpRecord->otp, $email));
            
            \Log::info('Newsletter OTP sent', [
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'requires_verification' => true
                ]);
            }
            
            return view('newsletter.subscribe', [
                'old_email' => $email
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to send newsletter OTP email: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send verification code. Please try again.',
                ], 500);
            }
            
            return view('newsletter.subscribe', [
                'errors' => collect(['email' => 'Failed to send verification code. Please try again.']),
                'old_email' => $email
            ]);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid email and 6-digit verification code.',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');
        $otp = $request->input('otp');

        // Find and verify OTP
        $otpRecord = NewsletterOtp::where('email', $email)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->first();
            
        if (!$otpRecord || !$otpRecord->verify($otp)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code. Please try again.',
                ], 422);
            }
            return back()->withErrors(['otp' => 'Invalid or expired verification code.'])->withInput();
        }

        // Create or update subscription
        NewsletterSubscription::updateOrCreate(
            ['email' => $email],
            [
                'email' => $email,
                'is_verified' => true,
                'verified_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'active',
                'subscribed_at' => now(),
                'source' => 'website'
            ]
        );

        // Clean up OTP record
        NewsletterOtp::where('email', $email)->delete();

        \Log::info('Newsletter subscription verified', [
            'email' => $email,
            'ip' => $request->ip()
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed to our newsletter! Thank you for joining us.'
            ]);
        }

        return view('newsletter.subscribe', [
            'success' => 'Successfully subscribed to our newsletter! Thank you for joining us.'
        ]);
    }

    public function resendOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please provide a valid email address.',
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $email = $request->input('email');

        // Generate new OTP
        $otpRecord = NewsletterOtp::createForEmail($email);
        
        try {
            Mail::to($email)->send(new NewsletterOtpMail($otpRecord->otp, $email));
            
            \Log::info('Newsletter OTP resent', [
                'email' => $email,
                'ip' => $request->ip()
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Verification code resent to your email address.'
                ]);
            }
            
            return back()->with('success', 'Verification code resent to your email address.');
            
        } catch (\Exception $e) {
            \Log::error('Failed to resend newsletter OTP: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resend verification code. Please try again.',
                ], 500);
            }
            
            return back()->withErrors(['email' => 'Failed to resend verification code. Please try again.']);
        }
    }
}
