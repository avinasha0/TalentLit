<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class TenantRole extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'tenant_id',
    ];

    protected $casts = [
        'tenant_id' => 'integer',
    ];

    /**
     * Scope roles by tenant
     */
    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Create a role for a specific tenant
     */
    public static function createForTenant(array $attributes, $tenantId)
    {
        $attributes['tenant_id'] = $tenantId;
        return static::create($attributes);
    }
}