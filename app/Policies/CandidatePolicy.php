<?php

namespace App\Policies;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CandidatePolicy
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
        return $this->hasCustomPermission('view_candidates');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Candidate $candidate): bool
    {
        return $this->hasCustomPermission('view_candidates');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $this->hasCustomPermission('create_candidates');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Candidate $candidate): bool
    {
        return $this->hasCustomPermission('edit_candidates');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Candidate $candidate): bool
    {
        return $this->hasCustomPermission('delete_candidates');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Candidate $candidate): bool
    {
        return $this->hasCustomPermission('delete_candidates');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Candidate $candidate): bool
    {
        return $this->hasCustomPermission('delete_candidates');
    }
}
