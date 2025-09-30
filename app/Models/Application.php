<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, HasUuids, SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'job_opening_id',
        'candidate_id',
        'status',
        'current_stage_id',
        'stage_position',
        'applied_at',
    ];

    protected $casts = [
        'status' => 'string',
        'applied_at' => 'datetime',
    ];

    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function currentStage(): BelongsTo
    {
        return $this->belongsTo(JobStage::class, 'current_stage_id');
    }

    public function stageEvents(): HasMany
    {
        return $this->hasMany(ApplicationStageEvent::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ApplicationNote::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    // Query scopes
    public function scopeThisMonthHired($query)
    {
        return $query->where('status', 'hired')
                    ->whereMonth('applied_at', now()->month)
                    ->whereYear('applied_at', now()->year);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->with(['candidate', 'jobOpening'])
                    ->orderBy('applied_at', 'desc')
                    ->limit($limit);
    }

    public function scopeOrderedByStagePosition($query)
    {
        return $query->orderBy('stage_position', 'asc')
                    ->orderBy('created_at', 'asc');
    }
}
