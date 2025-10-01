<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CandidateTagPivot extends Pivot
{
    use HasUuids, TenantScoped;

    protected $table = 'candidate_tag_candidate';

    protected $fillable = [
        'tenant_id',
        'candidate_id',
        'tag_id',
    ];

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(CandidateTag::class);
    }
}
