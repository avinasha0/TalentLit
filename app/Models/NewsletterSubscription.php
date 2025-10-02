<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsletterSubscription extends Model
{
    protected $fillable = [
        'email',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'source',
        'metadata',
    ];

    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Scope to get active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get unsubscribed subscriptions
     */
    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }
}
