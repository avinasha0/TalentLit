<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'skills',
        'experience_min',
        'experience_max',
        'headcount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'budget_min' => 'integer',
        'budget_max' => 'integer',
        'experience_min' => 'integer',
        'experience_max' => 'integer',
        'headcount' => 'integer',
        'status' => 'string',
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
}

