<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class NewsletterOtp extends Model
{
    protected $fillable = [
        'email',
        'otp',
        'expires_at',
        'verified',
        'verified_at',
        'source',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
        'metadata' => 'array',
    ];

    /**
     * Generate a random 6-digit OTP
     */
    public static function generateOtp(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP for email verification
     */
    public static function createForEmail(string $email, string $source = 'website', array $metadata = []): self
    {
        // Clean up any existing unverified OTPs for this email
        self::where('email', $email)
            ->where('verified', false)
            ->where('expires_at', '>', now())
            ->delete();

        return self::create([
            'email' => $email,
            'otp' => self::generateOtp(),
            'expires_at' => now()->addMinutes(10), // OTP expires in 10 minutes
            'source' => $source,
            'metadata' => $metadata,
        ]);
    }

    /**
     * Verify an OTP
     */
    public function verify(string $otp): bool
    {
        if ($this->verified) {
            return false; // Already verified
        }

        if ($this->expires_at < now()) {
            return false; // Expired
        }

        if ($this->otp !== $otp) {
            return false; // Invalid OTP
        }

        $this->update([
            'verified' => true,
            'verified_at' => now(),
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
     * Scope to get valid (non-expired) OTPs
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get verified OTPs
     */
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    /**
     * Scope to get unverified OTPs
     */
    public function scopeUnverified($query)
    {
        return $query->where('verified', false);
    }
}
