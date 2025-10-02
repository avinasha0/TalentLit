<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Waitlist extends Model
{
    protected $table = 'waitlist';
    
    protected $fillable = [
        'email',
        'name',
        'company',
        'plan_slug',
        'message',
        'notified',
    ];

    protected $casts = [
        'notified' => 'boolean',
    ];

    /**
     * Scope to get entries for a specific plan.
     */
    public function scopeForPlan($query, $planSlug)
    {
        return $query->where('plan_slug', $planSlug);
    }

    /**
     * Scope to get unnotified entries.
     */
    public function scopeUnnotified($query)
    {
        return $query->where('notified', false);
    }
}