<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class InterviewPolicy
{
    /**
     * Check if user has custom permission
     */
    private function hasCustomPermission(string $permission): bool
    {
        $tenant = tenant();
        if (!$tenant || !Auth::check()) {
            return false;
        }

        $userRole = DB::table('custom_user_roles')
            ->where('user_id', Auth::id())
            ->where('tenant_id', $tenant->id)
            ->value('role_name');

        if (!$userRole) {
            return false;
        }

        $rolePermissions = DB::table('custom_tenant_roles')
            ->where('tenant_id', $tenant->id)
            ->where('name', $userRole)
            ->value('permissions');

        if (!$rolePermissions) {
            return false;
        }

        $permissions = json_decode($rolePermissions, true);
        return in_array($permission, $permissions);
    }

    /**
     * Determine whether the user can view any interviews.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasCustomPermission('view_interviews');
    }

    /**
     * Determine whether the user can view the interview.
     */
    public function view(User $user, Interview $interview): bool
    {
        return $this->hasCustomPermission('view_interviews');
    }

    /**
     * Determine whether the user can create interviews.
     */
    public function create(User $user): bool
    {
        return $this->hasCustomPermission('create_interviews');
    }

    /**
     * Determine whether the user can update the interview.
     */
    public function update(User $user, Interview $interview): bool
    {
        return $this->hasCustomPermission('edit_interviews');
    }

    /**
     * Determine whether the user can delete the interview.
     */
    public function delete(User $user, Interview $interview): bool
    {
        return $this->hasCustomPermission('delete_interviews');
    }

    /**
     * Determine whether the user can cancel the interview.
     */
    public function cancel(User $user, Interview $interview): bool
    {
        return $this->hasCustomPermission('edit_interviews');
    }

    /**
     * Determine whether the user can complete the interview.
     */
    public function complete(User $user, Interview $interview): bool
    {
        return $this->hasCustomPermission('edit_interviews');
    }
}