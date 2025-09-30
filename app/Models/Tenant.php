<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * The users that belong to the tenant.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_user');
    }
}
