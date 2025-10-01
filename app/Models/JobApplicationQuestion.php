<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicationQuestion extends Model
{
    use HasUuids;

    protected $table = 'job_application_question';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'tenant_id',
        'job_id',
        'question_id',
        'sort_order',
        'required_override',
    ];

    protected $casts = [
        'required_override' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class, 'job_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ApplicationQuestion::class, 'question_id');
    }
}