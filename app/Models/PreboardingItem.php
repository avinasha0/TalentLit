<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreboardingItem extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'candidate_id',
        'application_id',
        'track',
        'item_key',
        'title',
        'status',
        'requires_esign',
        'esign_status',
        'assigned_to_name',
        'assigned_to_email',
        'due_date',
        'completed_at',
        'meta',
        'sort_order',
    ];

    protected $casts = [
        'requires_esign' => 'boolean',
        'due_date' => 'date',
        'completed_at' => 'datetime',
        'meta' => 'array',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
