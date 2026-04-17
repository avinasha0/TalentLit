<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Onboarding extends Model
{
    use HasFactory;

    protected $table = 'onboardings';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'role',
        'department',
        'joining_date',
        'progress',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
    ];
}

