<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobStage extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'job_opening_id',
        'name',
        'sort_order',
        'is_terminal',
    ];

    protected $casts = [
        'is_terminal' => 'boolean',
    ];

    public function jobOpening(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class, 'current_stage_id');
    }

    public function stageEventsFrom(): HasMany
    {
        return $this->hasMany(ApplicationStageEvent::class, 'from_stage_id');
    }

    public function stageEventsTo(): HasMany
    {
        return $this->hasMany(ApplicationStageEvent::class, 'to_stage_id');
    }
}
