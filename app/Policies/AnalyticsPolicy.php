<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AnalyticsPolicy
{
    /**
     * Determine whether the user can view analytics.
     */
    public function view(User $user, string $ability): bool
    {
        return $user->hasPermissionTo('view analytics');
    }
}
