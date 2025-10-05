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
        return app('App\\Support\\CustomPermissionChecker')->check('edit_candidates', tenant());
    }

    /**
     * Determine whether the user can attach/detach tags.
     */
    public function attach(User $user): bool
    {
        return app('App\\Support\\CustomPermissionChecker')->check('edit_candidates', tenant());
    }
}
