<?php

namespace App\Policies;

use App\Models\JobOpening;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobOpeningPolicy
{
    use HandlesAuthorization;

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

    public function viewAny(User $user): bool
    {
        return $this->hasCustomPermission('view_jobs');
    }

    public function view(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('view_jobs');
    }

    public function create(User $user): bool
    {
        return $this->hasCustomPermission('create_jobs');
    }

    public function update(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('edit_jobs');
    }

    public function delete(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('delete_jobs');
    }

    public function restore(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('delete_jobs');
    }

    public function forceDelete(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('delete_jobs');
    }

    public function publish(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('publish_jobs');
    }

    public function close(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('close_jobs');
    }

    public function moveApplications(User $user, JobOpening $jobOpening): bool
    {
        return $this->hasCustomPermission('edit_jobs');
    }
}
