<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerApplyEmailOtp extends Model
{
    public const OTP_TTL_MINUTES = 10;

    public const SESSION_KEY = 'career_apply_email_verification';

    protected $table = 'career_apply_email_otps';

    protected $fillable = [
        'tenant_id',
        'job_opening_id',
        'email',
        'otp',
        'expires_at',
        'verified_at',
        'last_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
            'last_sent_at' => 'datetime',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class, 'job_opening_id');
    }

    public static function normalizeEmail(string $email): string
    {
        return strtolower(trim($email));
    }

    public static function canResend(Tenant $tenant, JobOpening $job, string $email): bool
    {
        $email = self::normalizeEmail($email);
        $last = self::query()
            ->where('tenant_id', $tenant->id)
            ->where('job_opening_id', $job->id)
            ->where('email', $email)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('last_sent_at')
            ->first();

        if (!$last || !$last->last_sent_at) {
            return true;
        }

        return $last->last_sent_at->diffInSeconds(now()) >= 60;
    }

    public static function remainingResendCooldownSeconds(Tenant $tenant, JobOpening $job, string $email): int
    {
        $email = self::normalizeEmail($email);
        $last = self::query()
            ->where('tenant_id', $tenant->id)
            ->where('job_opening_id', $job->id)
            ->where('email', $email)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->orderByDesc('last_sent_at')
            ->first();

        if (!$last || !$last->last_sent_at) {
            return 0;
        }

        $elapsed = $last->last_sent_at->diffInSeconds(now());

        return max(0, 60 - $elapsed);
    }

    /**
     * Invalidate pending codes and create a new OTP row.
     */
    public static function issue(Tenant $tenant, JobOpening $job, string $email): self
    {
        $email = self::normalizeEmail($email);

        self::query()
            ->where('tenant_id', $tenant->id)
            ->where('job_opening_id', $job->id)
            ->where('email', $email)
            ->whereNull('verified_at')
            ->update(['expires_at' => now()]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return self::create([
            'tenant_id' => $tenant->id,
            'job_opening_id' => $job->id,
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(self::OTP_TTL_MINUTES),
            'last_sent_at' => now(),
        ]);
    }

    /**
     * Mark matching pending OTP as verified. Returns false if no valid match.
     */
    public static function verifyAndConsume(Tenant $tenant, JobOpening $job, string $email, string $otp): bool
    {
        $email = self::normalizeEmail($email);
        $otp = trim($otp);

        $record = self::query()
            ->where('tenant_id', $tenant->id)
            ->where('job_opening_id', $job->id)
            ->where('email', $email)
            ->whereNull('verified_at')
            ->where('expires_at', '>', now())
            ->where('otp', $otp)
            ->orderByDesc('id')
            ->first();

        if (!$record) {
            return false;
        }

        $record->update(['verified_at' => now()]);

        return true;
    }

    public static function putVerifiedSession(Tenant $tenant, JobOpening $job, string $email): void
    {
        session([self::SESSION_KEY => [
            'tenant_id' => (string) $tenant->id,
            'job_opening_id' => (string) $job->id,
            'email' => self::normalizeEmail($email),
        ]]);
    }

    public static function sessionMatches(Tenant $tenant, JobOpening $job, string $email): bool
    {
        $email = self::normalizeEmail($email);
        $v = session(self::SESSION_KEY);
        if (! is_array($v)) {
            return false;
        }

        return ($v['tenant_id'] ?? null) === (string) $tenant->id
            && ($v['job_opening_id'] ?? null) === (string) $job->id
            && ($v['email'] ?? null) === $email;
    }

    public static function forgetSession(): void
    {
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Remove all OTP rows for this apply context (e.g. after a successful submission so codes cannot be reused).
     */
    public static function invalidateForApplication(Tenant $tenant, JobOpening $job, string $email): void
    {
        $email = self::normalizeEmail($email);

        self::query()
            ->where('tenant_id', $tenant->id)
            ->where('job_opening_id', $job->id)
            ->where('email', $email)
            ->delete();
    }
}
