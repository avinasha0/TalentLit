<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TenantRole extends Model
{
    protected $table = 'custom_tenant_roles';
    
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'tenant_id',
        'permissions'
    ];

    protected $casts = [
        'tenant_id' => 'string',
        'permissions' => 'array',
    ];

    /**
     * The tenant that owns the role
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * The users that belong to the role
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user_roles')
            ->withPivot('tenant_id')
            ->withTimestamps();
    }

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

    /**
     * Check if the role has a specific permission
     */
    public function hasPermissionTo($permission)
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions);
    }
}