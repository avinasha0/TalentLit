<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GlobalDepartment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'global_departments';

    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobOpenings(): HasMany
    {
        return $this->hasMany(JobOpening::class, 'global_department_id');
    }

    public function jobRequisitions(): HasMany
    {
        return $this->hasMany(JobRequisition::class, 'global_department_id');
    }

    /**
     * Scope to get only active departments
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}