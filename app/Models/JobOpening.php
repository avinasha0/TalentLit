<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOpening extends Model
{
    use HasFactory, HasUuids, SoftDeletes, TenantScoped;

    protected $fillable = [
        'requisition_id',
        'title',
        'slug',
        'department_id',
        'location_id',
        'employment_type',
        'status',
        'openings_count',
        'description',
        'published_at',
    ];

    protected $casts = [
        'employment_type' => 'string',
        'status' => 'string',
        'published_at' => 'datetime',
    ];

    public function requisition(): BelongsTo
    {
        return $this->belongsTo(JobRequisition::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function jobStages(): HasMany
    {
        return $this->hasMany(JobStage::class)->orderBy('sort_order');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeNotClosed($query)
    {
        return $query->where('status', '!=', 'closed');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeWithApplicationsCount($query)
    {
        return $query->withCount('applications');
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($job) {
            $job->createDefaultStages();
        });
    }

    /**
     * Create default stages for this job
     */
    public function createDefaultStages(): void
    {
        $defaultStages = [
            ['name' => 'Applied', 'sort_order' => 1],
            ['name' => 'Screen', 'sort_order' => 2],
            ['name' => 'Interview', 'sort_order' => 3],
            ['name' => 'Offer', 'sort_order' => 4],
            ['name' => 'Hired', 'sort_order' => 5],
        ];

        foreach ($defaultStages as $stage) {
            JobStage::create([
                'tenant_id' => $this->tenant_id,
                'job_opening_id' => $this->id,
                'name' => $stage['name'],
                'sort_order' => $stage['sort_order'],
            ]);
        }
    }
}
