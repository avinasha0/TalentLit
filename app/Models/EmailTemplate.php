<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailTemplate extends Model
{
    use HasFactory, HasUuids, SoftDeletes, TenantScoped;

    protected $fillable = [
        'tenant_id',
        'name',
        'subject',
        'body',
        'type',
        'is_active',
        'variables',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'variables' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    // Template types
    const TYPE_APPLICATION_RECEIVED = 'application_received';
    const TYPE_STAGE_CHANGED = 'stage_changed';
    const TYPE_INTERVIEW_SCHEDULED = 'interview_scheduled';
    const TYPE_INTERVIEW_CANCELED = 'interview_canceled';
    const TYPE_INTERVIEW_UPDATED = 'interview_updated';
    const TYPE_CUSTOM = 'custom';

    public static function getTypes(): array
    {
        return [
            self::TYPE_APPLICATION_RECEIVED => 'Application Received',
            self::TYPE_STAGE_CHANGED => 'Stage Changed',
            self::TYPE_INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::TYPE_INTERVIEW_CANCELED => 'Interview Canceled',
            self::TYPE_INTERVIEW_UPDATED => 'Interview Updated',
            self::TYPE_CUSTOM => 'Custom',
        ];
    }

    public static function getVariablesForType(string $type): array
    {
        $variables = [
            self::TYPE_APPLICATION_RECEIVED => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
                'application_date' => 'Application Date',
            ],
            self::TYPE_STAGE_CHANGED => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
                'stage_name' => 'New Stage Name',
                'previous_stage' => 'Previous Stage Name',
                'message' => 'Custom Message',
            ],
            self::TYPE_INTERVIEW_SCHEDULED => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
                'interview_date' => 'Interview Date',
                'interview_time' => 'Interview Time',
                'interview_location' => 'Interview Location',
                'interviewer_name' => 'Interviewer Name',
                'interview_notes' => 'Interview Notes',
            ],
            self::TYPE_INTERVIEW_CANCELED => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
                'interview_date' => 'Interview Date',
                'cancellation_reason' => 'Cancellation Reason',
            ],
            self::TYPE_INTERVIEW_UPDATED => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
                'interview_date' => 'Interview Date',
                'interview_time' => 'Interview Time',
                'interview_location' => 'Interview Location',
                'interviewer_name' => 'Interviewer Name',
                'interview_notes' => 'Interview Notes',
            ],
            self::TYPE_CUSTOM => [
                'candidate_name' => 'Candidate Name',
                'candidate_email' => 'Candidate Email',
                'job_title' => 'Job Title',
                'company_name' => 'Company Name',
            ],
        ];

        return $variables[$type] ?? [];
    }

    public function getTypeNameAttribute(): string
    {
        return self::getTypes()[$this->type] ?? 'Unknown';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
