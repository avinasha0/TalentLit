<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WaitlistOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
    ];

    /**
     * Generate a new OTP for the given email.
     */
    public static function generateOtp(string $email): self
    {
        // Delete any existing OTPs for this email
        self::where('email', $email)->delete();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Create new OTP record (expires in 10 minutes)
        return self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);
    }

    /**
     * Verify the OTP for the given email.
     */
    public static function verifyOtp(string $email, string $otp): bool
    {
        $otpRecord = self::where('email', $email)
            ->where('otp', $otp)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if ($otpRecord) {
            $otpRecord->update([
                'is_verified' => true,
                'verified_at' => Carbon::now(),
            ]);
            return true;
        }

        return false;
    }

    /**
     * Check if email has a valid verified OTP.
     */
    public static function isEmailVerified(string $email): bool
    {
        return self::where('email', $email)
            ->where('is_verified', true)
            ->where('verified_at', '>', Carbon::now()->subHours(24)) // Valid for 24 hours
            ->exists();
    }

    /**
     * Clean up expired OTPs.
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }
}