<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use App\Models\NewsletterOtp;
use App\Mail\NewsletterOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    /**
     * Request newsletter subscription (sends OTP)
     */
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');

        try {
            // Check if email already exists and is active
            $existingSubscription = NewsletterSubscription::where('email', $email)->first();

            if ($existingSubscription && $existingSubscription->status === 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.'
                ], 409);
            }

            // Create OTP for verification
            $otpRecord = NewsletterOtp::createForEmail(
                $email,
                $request->input('source', 'website'),
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'requested_at' => now()->toISOString(),
                ]
            );

            // Send OTP email
            Mail::to($email)->send(new NewsletterOtpMail(
                $otpRecord->otp,
                $email,
                $otpRecord->expires_at
            ));

            Log::info('Newsletter OTP sent', [
                'email' => $email,
                'source' => $request->input('source', 'website'),
                'ip' => $request->ip(),
                'otp_id' => $otpRecord->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Verification code sent to your email. Please check your inbox and enter the code to complete your subscription.',
                'requires_verification' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter subscription request error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Verify OTP and complete subscription
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address and 6-digit verification code.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $otp = $request->input('otp');

        try {
            // Find the OTP record
            $otpRecord = NewsletterOtp::where('email', $email)
                ->where('otp', $otp)
                ->unverified()
                ->valid()
                ->first();

            if (!$otpRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired verification code. Please request a new code.'
                ], 400);
            }

            // Verify the OTP
            if (!$otpRecord->verify($otp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid verification code. Please try again.'
                ], 400);
            }

            // Check if subscription already exists
            $existingSubscription = NewsletterSubscription::where('email', $email)->first();

            if ($existingSubscription) {
                if ($existingSubscription->status === 'unsubscribed') {
                    // Reactivate subscription
                    $existingSubscription->update([
                        'status' => 'active',
                        'subscribed_at' => now(),
                        'unsubscribed_at' => null,
                        'source' => $otpRecord->source,
                        'metadata' => array_merge($existingSubscription->metadata ?? [], [
                            'reactivated_at' => now()->toISOString(),
                            'reactivated_ip' => $request->ip(),
                        ])
                    ]);
                }
            } else {
                // Create new subscription
                NewsletterSubscription::create([
                    'email' => $email,
                    'status' => 'active',
                    'subscribed_at' => now(),
                    'source' => $otpRecord->source,
                    'metadata' => array_merge($otpRecord->metadata ?? [], [
                        'verified_at' => now()->toISOString(),
                        'verification_ip' => $request->ip(),
                    ])
                ]);
            }

            Log::info('Newsletter subscription verified and created', [
                'email' => $email,
                'source' => $otpRecord->source,
                'ip' => $request->ip(),
                'otp_id' => $otpRecord->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Email verified successfully! You are now subscribed to our newsletter.'
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter OTP verification error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');

        try {
            // Check if email is already subscribed
            $existingSubscription = NewsletterSubscription::where('email', $email)
                ->where('status', 'active')
                ->first();

            if ($existingSubscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed to our newsletter.'
                ], 409);
            }

            // Create new OTP
            $otpRecord = NewsletterOtp::createForEmail(
                $email,
                $request->input('source', 'website'),
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'resent_at' => now()->toISOString(),
                ]
            );

            // Send OTP email
            Mail::to($email)->send(new NewsletterOtpMail(
                $otpRecord->otp,
                $email,
                $otpRecord->expires_at
            ));

            Log::info('Newsletter OTP resent', [
                'email' => $email,
                'ip' => $request->ip(),
                'otp_id' => $otpRecord->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'New verification code sent to your email.'
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter OTP resend error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');

        try {
            $subscription = NewsletterSubscription::where('email', $email)->first();

            if (!$subscription) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email not found in our newsletter list.'
                ], 404);
            }

            if ($subscription->status === 'unsubscribed') {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already unsubscribed.'
                ], 409);
            }

            $subscription->update([
                'status' => 'unsubscribed',
                'unsubscribed_at' => now(),
                'metadata' => array_merge($subscription->metadata ?? [], [
                    'unsubscribed_at' => now()->toISOString(),
                    'unsubscribe_ip' => $request->ip(),
                ])
            ]);

            Log::info('Newsletter unsubscription', [
                'email' => $email,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'You have been successfully unsubscribed from our newsletter.'
            ]);

        } catch (\Exception $e) {
            Log::error('Newsletter unsubscription error', [
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get subscription status
     */
    public function status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a valid email address.',
                'errors' => $validator->errors()
            ], 422);
        }

        $email = $request->input('email');
        $subscription = NewsletterSubscription::where('email', $email)->first();

        if (!$subscription) {
            return response()->json([
                'success' => true,
                'subscribed' => false,
                'message' => 'Email not found in our newsletter list.'
            ]);
        }

        return response()->json([
            'success' => true,
            'subscribed' => $subscription->status === 'active',
            'status' => $subscription->status,
            'subscribed_at' => $subscription->subscribed_at,
            'unsubscribed_at' => $subscription->unsubscribed_at,
        ]);
    }
}
