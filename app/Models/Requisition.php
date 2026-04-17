<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Requisition extends Model
{
    use HasFactory, TenantScoped, SoftDeletes;

    protected $table = 'requisitions';

    protected $fillable = [
        'tenant_id',
        'department',
        'job_title',
        'justification',
        'budget_min',
        'budget_max',
        'contract_type',
        'duration',
        'skills',
        'experience_min',
        'experience_max',
        'headcount',
        'priority',
        'location',
        'additional_notes',
        'status',
        'created_by',
        'approval_status',
        'approval_level',
        'current_approver_id',
        'approved_at',
        'approval_workflow',
    ];

    protected $casts = [
        'budget_min' => 'integer',
        'budget_max' => 'integer',
        'experience_min' => 'integer',
        'experience_max' => 'integer',
        'headcount' => 'integer',
        'duration' => 'integer',
        'status' => 'string',
        'priority' => 'string',
        'approval_level' => 'integer',
        'approved_at' => 'datetime',
        'approval_workflow' => 'array',
    ];

    /**
     * Get the user who created this requisition
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the tenant that owns this requisition
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter pending requisitions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope to filter approved requisitions
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'Approved');
    }

    /**
     * Scope to filter rejected requisitions
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Rejected');
    }

    /**
     * Scope to filter draft requisitions
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    /**
     * Get audit logs for this requisition
     */
    public function auditLogs()
    {
        return $this->hasMany(RequisitionAuditLog::class);
    }

    /**
     * Get attachments for this requisition
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get the current approver user
     */
    public function currentApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }

    /**
     * Get all approval history for this requisition
     */
    public function approvals()
    {
        return $this->hasMany(RequisitionApproval::class);
    }

    /**
     * Get tasks related to this requisition
     */
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Scope to filter by approval status
     */
    public function scopeApprovalStatus($query, string $status)
    {
        return $query->where('approval_status', $status);
    }

    /**
     * Scope to filter pending approvals for a specific user
     */
    public function scopePendingForApprover($query, $userId)
    {
        return $query->where('approval_status', 'Pending')
            ->where('current_approver_id', $userId);
    }
}

