<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequisitionApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisition_id',
        'approver_id',
        'action',
        'comments',
        'approval_level',
        'delegate_to',
    ];

    protected $casts = [
        'approval_level' => 'integer',
    ];

    /**
     * Get the requisition this approval belongs to
     */
    public function requisition(): BelongsTo
    {
        return $this->belongsTo(Requisition::class);
    }

    /**
     * Get the approver user
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    /**
     * Get the delegate user (if action is Delegated)
     */
    public function delegate(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delegate_to');
    }
}
