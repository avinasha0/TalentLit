<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStageEvent extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'application_id',
        'from_stage_id',
        'to_stage_id',
        'moved_by_user_id',
        'note',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function fromStage(): BelongsTo
    {
        return $this->belongsTo(JobStage::class, 'from_stage_id');
    }

    public function toStage(): BelongsTo
    {
        return $this->belongsTo(JobStage::class, 'to_stage_id');
    }

    public function movedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by_user_id');
    }
}
