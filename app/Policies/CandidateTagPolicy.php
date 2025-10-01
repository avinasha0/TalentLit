<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;

class CandidateTagPolicy
{
    /**
     * Determine whether the user can create tags.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Determine whether the user can attach/detach tags.
     */
    public function attach(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }
}
