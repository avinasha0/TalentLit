<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobPolicy
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
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $this->hasCustomPermission('view_jobs');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('view_jobs');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasCustomPermission('create_jobs');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('edit_jobs');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('delete_jobs');
    }

    /**
     * Determine whether the user can publish the model.
     */
    public function publish(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('publish_jobs');
    }

    /**
     * Determine whether the user can close the model.
     */
    public function close(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('close_jobs');
    }

    /**
     * Determine whether the user can manage stages.
     */
    public function manageStages(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('manage_stages');
    }
}