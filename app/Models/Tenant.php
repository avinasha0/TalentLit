<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'website',
        'location',
        'company_size',
        'logo',
        'primary_color',
        'careers_enabled',
        'careers_intro',
        'consent_text',
        'timezone',
        'locale',
        'currency',
        'smtp_host',
        'smtp_port',
        'smtp_username',
        'smtp_password',
        'smtp_encryption',
        'smtp_from_address',
        'smtp_from_name',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The users that belong to the tenant.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user');
    }

    /**
     * Get the tenant branding.
     */
    public function branding(): HasOne
    {
        return $this->hasOne(TenantBranding::class);
    }

    /**
     * Get the job openings for this tenant.
     */
    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class);
    }

    /**
     * Get the candidates for this tenant.
     */
    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class);
    }

    /**
     * Get the applications for this tenant.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the interviews for this tenant.
     */
    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    /**
     * Get the tenant's subscription.
     */
    public function subscription(): HasOne
    {
        return $this->hasOne(TenantSubscription::class);
    }

    /**
     * Get the tenant's active subscription.
     */
    public function activeSubscription(): HasOne
    {
        return $this->hasOne(TenantSubscription::class)->where('status', 'active')
                    ->where('starts_at', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Get the current subscription plan.
     */
    public function currentPlan()
    {
        return $this->activeSubscription?->plan;
    }

    /**
     * Check if tenant has Free plan.
     */
    public function hasFreePlan(): bool
    {
        $subscription = $this->activeSubscription;
        return $subscription && $subscription->plan && $subscription->plan->slug === 'free';
    }

    /**
     * Check if tenant has a specific feature.
     */
    public function hasFeature(string $feature): bool
    {
        $plan = $this->currentPlan();
        
        if (!$plan) {
            return false;
        }

        return $plan->$feature ?? false;
    }

    /**
     * Check if tenant is within a specific limit.
     */
    public function withinLimit(string $limit, int $currentCount): bool
    {
        $plan = $this->currentPlan();
        
        if (!$plan) {
            return false;
        }

        $maxLimit = $plan->$limit ?? 0;
        return $currentCount < $maxLimit;
    }

    /**
     * Get remaining limit for a specific feature.
     */
    public function getRemainingLimit(string $limit, int $currentCount): int
    {
        $plan = $this->currentPlan();
        
        if (!$plan) {
            return 0;
        }

        $maxLimit = $plan->$limit ?? 0;
        return max(0, $maxLimit - $currentCount);
    }
}
