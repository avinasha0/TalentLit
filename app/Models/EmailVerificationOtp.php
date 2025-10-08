<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'is_used',
        'used_at',
        'last_sent_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    /**
     * Generate a new OTP for the given email
     */
    public static function generateForEmail(string $email): self
    {
        // Invalidate any existing OTPs for this email
        self::where('email', $email)
            ->where('is_used', false)
            ->update(['is_used' => true, 'used_at' => now()]);

        // Generate new OTP
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        return self::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
            'last_sent_at' => now(),
        ]);
    }

    /**
     * Verify the OTP for the given email
     */
    public static function verify(string $email, string $otp): bool
    {
        $otpRecord = self::where('email', $email)
            ->where('otp', $otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return false;
        }

        // Mark as used
        $otpRecord->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return true;
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    /**
     * Check if resend is allowed (60 seconds cooldown)
     */
    public static function canResend(string $email): bool
    {
        $lastOtp = self::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->orderBy('last_sent_at', 'desc')
            ->first();

        if (!$lastOtp) {
            return true;
        }

        return $lastOtp->last_sent_at->diffInSeconds(now()) >= 60;
    }

    /**
     * Update last sent time for existing OTP
     */
    public static function updateLastSentTime(string $email): void
    {
        self::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->update(['last_sent_at' => now()]);
    }

    /**
     * Get remaining cooldown time in seconds
     */
    public static function getRemainingCooldown(string $email): int
    {
        $lastOtp = self::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->orderBy('last_sent_at', 'desc')
            ->first();

        if (!$lastOtp) {
            return 0;
        }

        $elapsed = $lastOtp->last_sent_at->diffInSeconds(now());
        return max(0, 60 - $elapsed);
    }

    /**
     * Clean up expired OTPs
     */
    public static function cleanupExpired(): int
    {
        return self::where('expires_at', '<', now())->delete();
    }
}
