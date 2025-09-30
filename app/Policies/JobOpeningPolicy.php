<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobOpeningPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter', 'Hiring Manager']);
    }

    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter', 'Hiring Manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin']);
    }

    public function restore(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin']);
    }

    public function forceDelete(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin']);
    }

    public function publish(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }

    public function close(User $user, JobOpening $jobOpening): bool
    {
        return $user->hasAnyRole(['Owner', 'Admin', 'Recruiter']);
    }
}
