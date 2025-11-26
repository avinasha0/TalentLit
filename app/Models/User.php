<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'activation_token',
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'is_invited',
        'email_weekly_summaries',
        'notify_new_applications',
        'send_marketing_emails',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'invitation_sent_at' => 'datetime',
            'invitation_accepted_at' => 'datetime',
            'is_invited' => 'boolean',
            'email_weekly_summaries' => 'boolean',
            'notify_new_applications' => 'boolean',
            'send_marketing_emails' => 'boolean',
        ];
    }

    /**
     * The tenants that belong to the user.
     */
    public function tenants()
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user');
    }

    /**
     * Check if user belongs to a specific tenant
     */
    public function belongsToTenant($tenantId)
    {
        return $this->tenants()->where('tenant_id', $tenantId)->exists();
    }

    /**
     * The interviews that the user is a panelist for
     */
    public function interviews(): BelongsToMany
    {
        return $this->belongsToMany(Interview::class, 'interview_user')
            ->withTimestamps();
    }

    /**
     * Get roles for a specific tenant using custom permission system
     */
    public function getRolesForTenant($tenantId)
    {
        $roleName = DB::table('custom_user_roles')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->value('role_name');
        
        if (!$roleName) {
            return collect();
        }
        
        // Return a collection with a single role object that mimics the old structure
        return collect([
            (object) [
                'name' => $roleName,
                'tenant_id' => $tenantId,
                'id' => $roleName . '_' . $tenantId // Create a unique ID
            ]
        ]);
    }

    /**
     * Check if user has a specific role for the current tenant
     */
    public function hasRole($roleName, $tenantId = null)
    {
        $tenantId = $tenantId ?? tenant_id();
        if (!$tenantId) {
            return false;
        }

        $userRole = DB::table('custom_user_roles')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->value('role_name');

        return $userRole === $roleName;
    }

    /**
     * Check if user has any of the specified roles for the current tenant
     */
    public function hasAnyRole(array $roleNames, $tenantId = null)
    {
        $tenantId = $tenantId ?? tenant_id();
        if (!$tenantId) {
            return false;
        }

        $userRole = DB::table('custom_user_roles')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->value('role_name');

        return in_array($userRole, $roleNames);
    }

    /**
     * Check if user has a pending invitation
     */
    public function hasPendingInvitation()
    {
        return $this->is_invited && !$this->invitation_accepted_at;
    }

    /**
     * Generate invitation token
     */
    public function generateInvitationToken()
    {
        $this->invitation_token = Str::random(60);
        $this->invitation_sent_at = now();
        $this->is_invited = true;
        $this->save();
        
        return $this->invitation_token;
    }

    /**
     * Accept invitation and set password
     */
    public function acceptInvitation($password)
    {
        $this->password = Hash::make($password);
        $this->invitation_accepted_at = now();
        $this->invitation_token = null;
        $this->is_invited = false;
        $this->save();
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Assign a role to user for a tenant (enforces single role per tenant)
     * This replaces any existing role for this tenant
     */
    public function assignRoleForTenant(string $roleName, string $tenantId, ?int $actorId = null): void
    {
        // Remove any existing role for this tenant (enforce single role)
        DB::table('custom_user_roles')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->delete();

        // Assign new role
        DB::table('custom_user_roles')->insert([
            'user_id' => $this->id,
            'tenant_id' => $tenantId,
            'role_name' => $roleName,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log role assignment if actor is provided
        if ($actorId) {
            app(\App\Services\PermissionService::class)->logRoleAssignment(
                $actorId,
                $this->id,
                $roleName,
                $tenantId
            );
        }
    }

    /**
     * Remove role from user for a tenant
     */
    public function removeRoleForTenant(string $tenantId, ?int $actorId = null): void
    {
        $roleName = DB::table('custom_user_roles')
            ->where('user_id', $this->id)
            ->where('tenant_id', $tenantId)
            ->value('role_name');

        if ($roleName) {
            DB::table('custom_user_roles')
                ->where('user_id', $this->id)
                ->where('tenant_id', $tenantId)
                ->delete();

            // Log role removal if actor is provided
            if ($actorId) {
                app(\App\Services\PermissionService::class)->logRoleRemoval(
                    $actorId,
                    $this->id,
                    $roleName,
                    $tenantId
                );
            }
        }
    }

    /**
     * Sync role for tenant (assigns role, replacing any existing)
     * Legacy method for compatibility
     */
    public function syncRoles(array $roles, string $tenantId = null, ?int $actorId = null): void
    {
        $tenantId = $tenantId ?? tenant_id();
        if (!$tenantId) {
            throw new \Exception('Tenant ID is required for role assignment');
        }

        // Get the first role (we only support one role per tenant)
        $role = $roles[0] ?? null;
        if (!$role) {
            throw new \Exception('At least one role is required');
        }

        // Handle both TenantRole model and role name string
        $roleName = is_object($role) ? $role->name : $role;

        $this->assignRoleForTenant($roleName, $tenantId, $actorId);
    }

    /**
     * Assign role (legacy method for compatibility)
     */
    public function assignRole($role, string $tenantId = null, ?int $actorId = null): void
    {
        $tenantId = $tenantId ?? tenant_id();
        if (!$tenantId) {
            throw new \Exception('Tenant ID is required for role assignment');
        }

        $roleName = is_object($role) ? $role->name : $role;
        $this->assignRoleForTenant($roleName, $tenantId, $actorId);
    }
}
