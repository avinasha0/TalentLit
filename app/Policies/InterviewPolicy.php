<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    /**
     * Determine whether the user can view any interviews.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter', 'Hiring Manager']);
    }

    /**
     * Determine whether the user can view the interview.
     */
    public function view(User $user, Interview $interview): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter', 'Hiring Manager']);
    }

    /**
     * Determine whether the user can create interviews.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Determine whether the user can update the interview.
     */
    public function update(User $user, Interview $interview): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Determine whether the user can delete the interview.
     */
    public function delete(User $user, Interview $interview): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Determine whether the user can cancel the interview.
     */
    public function cancel(User $user, Interview $interview): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    /**
     * Determine whether the user can complete the interview.
     */
    public function complete(User $user, Interview $interview): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }
}