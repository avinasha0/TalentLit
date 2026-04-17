<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnboardingEmailTemplate extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'template_key',
        'name',
        'purpose',
        'subject',
        'body',
        'tenant_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the tenant that owns this template (if tenant-specific)
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Check if template is global (not tenant-specific)
     */
    public function isGlobal(): bool
    {
        return $this->tenant_id === null;
    }

    /**
     * Get the scope (tenant or global)
     */
    public function getScopeAttribute(): string
    {
        return $this->isGlobal() ? 'Global' : 'Tenant';
    }

    /**
     * Get available tokens for onboarding email templates
     */
    public static function getAvailableTokens(): array
    {
        return [
            'candidate_name' => 'Full name of the candidate',
            'first_name' => 'First name of the candidate',
            'last_name' => 'Last name of the candidate',
            'email' => 'Email address of the candidate',
            'joining_date' => 'Expected joining date',
            'tenant_name' => 'Name of the organization/tenant',
            'onboarding_link' => 'Link to the onboarding portal',
        ];
    }

    /**
     * Scope to get templates for a specific tenant (including global templates)
     */
    public function scopeForTenant($query, $tenantId = null)
    {
        if ($tenantId === null) {
            $tenantId = tenant_id();
        }

        return $query->where(function ($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId)
              ->orWhereNull('tenant_id'); // Include global templates
        });
    }

    /**
     * Scope to get only global templates
     */
    public function scopeGlobal($query)
    {
        return $query->whereNull('tenant_id');
    }

    /**
     * Scope to get only tenant-specific templates
     */
    public function scopeTenantSpecific($query, $tenantId = null)
    {
        if ($tenantId === null) {
            $tenantId = tenant_id();
        }
        return $query->where('tenant_id', $tenantId);
    }
}
