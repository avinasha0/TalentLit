<?php

namespace App\Models;

use App\Models\Concerns\TenantScoped;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory, HasUuids, TenantScoped;

    protected $fillable = [
        'attachable_type',
        'attachable_id',
        'disk',
        'path',
        'filename',
        'mime',
        'size',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
