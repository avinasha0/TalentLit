<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $table = 'indian_cities';

    protected $fillable = [
        'name',
        'state',
        'state_code',
        'country',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get job openings for this city
     */
    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class, 'city_id');
    }

    /**
     * Scope to get only active cities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by state
     */
    public function scopeByState($query, $state)
    {
        return $query->where('state', $state);
    }

    /**
     * Get formatted location string
     */
    public function getFormattedLocationAttribute(): string
    {
        return "{$this->name}, {$this->state}";
    }

    /**
     * Get cities grouped by state
     */
    public static function getGroupedByState()
    {
        return self::active()
            ->orderBy('state')
            ->orderBy('name')
            ->get()
            ->groupBy('state');
    }

}
