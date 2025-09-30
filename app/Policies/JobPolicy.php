<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;

class JobPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view jobs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('view jobs');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create jobs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('edit jobs');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('delete jobs');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('publish jobs');
    }

    /**
     * Determine whether the user can close the model.
     */
    public function close(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('close jobs');
    }

    /**
     * Determine whether the user can manage stages.
     */
    public function manageStages(User $user, JobOpening $jobOpening): bool
    {
        return $user->can('manage stages');
    }
}