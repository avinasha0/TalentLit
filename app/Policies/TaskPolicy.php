<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view the task.
     */
    public function view(User $user, Task $task): bool
    {
        // Assignee can view
        if ($task->user_id === $user->id) {
            return true;
        }

        // Creator can view
        if ($task->created_by === $user->id) {
            return true;
        }

        // HR Admin or SuperAdmin can view
        if ($user->hasAnyRole(['Owner', 'Admin'], tenant_id())) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can update the task.
     */
    public function update(User $user, Task $task): bool
    {
        // Assignee can update
        if ($task->user_id === $user->id) {
            return true;
        }

        // HR Admin or SuperAdmin can update
        if ($user->hasAnyRole(['Owner', 'Admin'], tenant_id())) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can reassign the task.
     */
    public function reassign(User $user, Task $task): bool
    {
        // Assignee can reassign
        if ($task->user_id === $user->id) {
            return true;
        }

        // HR Admin or SuperAdmin can reassign
        if ($user->hasAnyRole(['Owner', 'Admin'], tenant_id())) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can complete the task.
     */
    public function complete(User $user, Task $task): bool
    {
        return $this->update($user, $task);
    }
}

