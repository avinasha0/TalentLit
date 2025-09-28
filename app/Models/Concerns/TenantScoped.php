<?php

namespace App\Models\Concerns;

use App\Support\Tenancy;
use Illuminate\Database\Eloquent\Builder;

trait TenantScoped
{
    public static function bootTenantScoped(): void
    {
        // Global scope that uses fully-qualified column name
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenantId = Tenancy::id();
            if ($tenantId) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $tenantId);
            }
        });

        // On create: set tenant_id ONLY if not explicitly provided
        // Note: Pivot models don't have creating events, so we handle this in the relationship
        if (static::class !== \App\Models\CandidateTag::class) {
            static::creating(function ($model) {
                if ($model->getAttribute('tenant_id') === null) {
                    $tid = Tenancy::id();
                    if ($tid) {
                        $model->setAttribute('tenant_id', $tid);
                    }
                }
            });
        }
    }

    // Handy scope to query for an arbitrary tenant id, bypassing global scope
    public function scopeForTenant(Builder $query, $tenant): Builder
    {
        $tenantId = is_string($tenant) ? $tenant : $tenant->id;

        return $query->withoutGlobalScope('tenant')
            ->where($this->getTable().'.tenant_id', $tenantId);
    }
}
