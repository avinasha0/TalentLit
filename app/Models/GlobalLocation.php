<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalLocation extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'global_locations';

    protected $fillable = [
        'name',
        'city',
        'state',
        'country',
        'timezone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class, 'global_location_id');
    }

    public function jobRequisitions(): HasMany
    {
        return $this->hasMany(JobRequisition::class, 'global_location_id');
    }

    /**
     * Scope to get only active locations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted location string
     */
    public function getFormattedLocationAttribute(): string
    {
        $parts = array_filter([$this->city, $this->state, $this->country]);
        return implode(', ', $parts);
    }
}