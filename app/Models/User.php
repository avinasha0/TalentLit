<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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
}
