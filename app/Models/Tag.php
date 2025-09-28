<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'name',
        'color',
    ];

    public function candidates(): BelongsToMany
    {
        return $this->belongsToMany(Candidate::class, 'candidate_tags')
            ->using(CandidateTag::class)
            ->withTimestamps()
            ->withPivot('tenant_id')
            ->when(tenant_id(), fn ($q) => $q->where('candidate_tags.tenant_id', tenant_id()))
            ->withPivot('id'); // Include the UUID id field
    }
}
