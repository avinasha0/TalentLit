<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, HasUuids, SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'primary_email',
        'primary_phone',
        'source',
        'resume_raw_text',
        'resume_json',
        'designation',
        'department',
        'manager',
        'joining_date',
        'completed_steps',
        'total_steps',
        'status',
    ];

    protected $casts = [
        'resume_json' => 'array',
        'joining_date' => 'date',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(CandidateContact::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'candidate_tags')
            ->using(CandidateTag::class)
            ->withTimestamps()
            ->withPivot('tenant_id')
            ->when(tenant_id(), fn ($q) => $q->where('candidate_tags.tenant_id', tenant_id()));
    }

    public function notes(): HasMany
    {
        return $this->hasMany(CandidateNote::class);
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class);
    }

    public function consents(): HasMany
    {
        return $this->hasMany(Consent::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function privacyEvents(): HasMany
    {
        return $this->hasMany(PrivacyEvent::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getLatestResumePathAttribute(): ?string
    {
        $latestResume = $this->resumes()->latest()->first();
        return $latestResume ? $latestResume->path : null;
    }
}
