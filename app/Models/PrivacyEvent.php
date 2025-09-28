<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivacyEvent extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'candidate_id',
        'type',
        'status',
        'payload',
        'created_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }
}
