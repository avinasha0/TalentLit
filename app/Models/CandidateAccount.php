<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Applicant / career-site login identity (per organization tenant).
 * Separate from {@see User} (staff) and stored in {@code candidate_accounts}.
 */
class CandidateAccount extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $table = 'candidate_accounts';

    protected $fillable = [
        'tenant_id',
        'email',
        'name',
        'password',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(Candidate::class, 'candidate_account_id');
    }
}
