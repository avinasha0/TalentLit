<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'currency',
        'billing_cycle',
        'is_active',
        'is_popular',
        'max_users',
        'max_job_openings',
        'max_candidates',
        'max_applications_per_month',
        'max_interviews_per_month',
        'max_storage_gb',
        'analytics_enabled',
        'custom_branding',
        'api_access',
        'priority_support',
        'advanced_reporting',
        'integrations',
        'white_label',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'max_users' => 'integer',
        'max_job_openings' => 'integer',
        'max_candidates' => 'integer',
        'max_applications_per_month' => 'integer',
        'max_interviews_per_month' => 'integer',
        'max_storage_gb' => 'integer',
        'analytics_enabled' => 'boolean',
        'custom_branding' => 'boolean',
        'api_access' => 'boolean',
        'priority_support' => 'boolean',
        'advanced_reporting' => 'boolean',
        'integrations' => 'boolean',
        'white_label' => 'boolean',
    ];

    /**
     * Get the tenant subscriptions for this plan.
     */
    public function tenantSubscriptions(): HasMany
    {
        return $this->hasMany(TenantSubscription::class);
    }

    /**
     * Scope to get active plans only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get popular plans.
     */
    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    /**
     * Get formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return $this->currency . ' ' . number_format($this->price, 2);
    }

    /**
     * Check if plan is free.
     */
    public function isFree(): bool
    {
        return $this->price == 0;
    }

    /**
     * Check if plan requires contact for pricing.
     */
    public function requiresContactForPricing(): bool
    {
        return $this->price == -1;
    }
}
