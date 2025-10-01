<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplicationQuestion extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $table = 'job_application_question';

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

    protected $keyType = 'string';
    public $incrementing = false;

    public function job(): BelongsTo
    {
        return $this->belongsTo(JobOpening::class, 'job_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(ApplicationQuestion::class, 'question_id');
    }
}