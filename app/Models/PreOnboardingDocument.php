<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreOnboardingDocument extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'application_id',
        'candidate_id',
        'document_key',
        'title',
        'is_required',
        'status',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'uploaded_at',
        'uploaded_by_candidate_account_id',
        'uploaded_by_user_id',
        'verified_at',
        'verified_by_user_id',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'uploaded_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function verifiedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function hasFile(): bool
    {
        return $this->file_path !== null && $this->file_path !== '';
    }
}
