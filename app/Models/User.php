<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'is_invited',
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
     * Get roles for a specific tenant
     */
    public function getRolesForTenant($tenantId)
    {
        return \App\Models\TenantRole::where('tenant_id', $tenantId)
            ->whereHas('users', function ($query) {
                $query->where('model_id', $this->id);
            })
            ->get();
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
}
