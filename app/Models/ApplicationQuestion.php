<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ApplicationQuestion extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'label',
        'name',
        'type',
        'required',
        'options',
        'active',
    ];

    protected $casts = [
        'required' => 'boolean',
        'options' => 'array',
        'active' => 'boolean',
    ];

    protected $keyType = 'string';
    public $incrementing = false;

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function jobOpenings(): BelongsToMany
    {
        return $this->belongsToMany(JobOpening::class, 'job_application_question', 'question_id', 'job_id')
            ->withPivot(['sort_order', 'required_override'])
            ->orderByPivot('sort_order');
    }

    public function answers()
    {
        return $this->hasMany(ApplicationAnswer::class);
    }
}