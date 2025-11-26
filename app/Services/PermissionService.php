<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    /**
     * Permission matrix - exact mapping from requirements
     * Role => [permissions]
     */
    private const PERMISSION_MATRIX = [
        'Owner' => [
            'view_jobs',
            'create_jobs',
            'edit_jobs',
            'delete_jobs',
            'publish_jobs',
            'close_jobs',
            'manage_stages',
            'view_stages',
            'create_stages',
            'edit_stages',
            'delete_stages',
            'reorder_stages',
            'view_candidates',
            'create_candidates',
            'edit_candidates',
            'delete_candidates',
            'move_candidates',
            'import_candidates',
            'view_interviews',
            'create_interviews',
            'edit_interviews',
            'delete_interviews',
            'view_analytics',
            'view_dashboard',
            'manage_users',
            'manage_settings',
            'manage_email_templates',
            'role_management',
        ],
        'Admin' => [
            'view_jobs',
            'create_jobs',
            'edit_jobs',
            'delete_jobs',
            'publish_jobs',
            'close_jobs',
            'manage_stages',
            'view_stages',
            'create_stages',
            'edit_stages',
            'delete_stages',
            'reorder_stages',
            'view_candidates',
            'create_candidates',
            'edit_candidates',
            'delete_candidates',
            'move_candidates',
            'import_candidates',
            'view_interviews',
            'create_interviews',
            'edit_interviews',
            'delete_interviews',
            'view_analytics',
            'view_dashboard',
            'manage_settings',
            'manage_email_templates',
        ],
        'Recruiter' => [
            'view_jobs',
            'create_jobs',
            'edit_jobs',
            'delete_jobs',
            'publish_jobs',
            'close_jobs',
            'view_stages',
            'view_candidates',
            'create_candidates',
            'edit_candidates',
            'delete_candidates',
            'move_candidates',
            'import_candidates',
            'view_interviews',
            'create_interviews',
            'edit_interviews',
            'delete_interviews',
            'view_dashboard', // Needed for accessing recruiting module
        ],
        'Hiring Manager' => [
            'view_jobs',
            'view_stages',
            'view_candidates',
            'move_candidates',
            'view_interviews',
        ],
    ];

    /**
     * Check if a role has a specific permission
     */
    public function roleHasPermission(string $role, string $permission): bool
    {
        if (!isset(self::PERMISSION_MATRIX[$role])) {
            return false;
        }

        return in_array($permission, self::PERMISSION_MATRIX[$role]);
    }

    /**
     * Get all permissions for a role
     */
    public function getRolePermissions(string $role): array
    {
        return self::PERMISSION_MATRIX[$role] ?? [];
    }

    /**
     * Check if user has permission for a tenant
     * This is the centralized permission check point
     */
    public function userHasPermission(int $userId, string $tenantId, string $permission, bool $logCheck = true): bool
    {
        // Get user's role for this tenant
        $userRole = DB::table('custom_user_roles')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->value('role_name');

        if (!$userRole) {
            if ($logCheck) {
                $this->logPermissionCheck($userId, null, $tenantId, $permission, false, 'No role assigned');
            }
            return false;
        }

        // Check permission using the matrix
        $hasPermission = $this->roleHasPermission($userRole, $permission);

        if ($logCheck) {
            $reason = $hasPermission ? null : "Role '{$userRole}' does not have permission '{$permission}'";
            $this->logPermissionCheck($userId, $userRole, $tenantId, $permission, $hasPermission, $reason);
        }

        return $hasPermission;
    }

    /**
     * Get user's role for a tenant
     */
    public function getUserRole(int $userId, string $tenantId): ?string
    {
        return DB::table('custom_user_roles')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenantId)
            ->value('role_name');
    }

    /**
     * Log permission check
     */
    private function logPermissionCheck(int $userId, ?string $role, string $tenantId, string $permission, bool $allowed, ?string $reason = null): void
    {
        $logMessage = sprintf(
            'Permission.Check user=%d role=%s tenant=%s action=%s result=%s',
            $userId,
            $role ?? 'none',
            $tenantId,
            $permission,
            $allowed ? 'allowed' : 'denied'
        );

        if ($reason && !$allowed) {
            $logMessage .= ' reason=' . $reason;
        }

        Log::info($logMessage);
    }

    /**
     * Log role assignment
     */
    public function logRoleAssignment(int $actorId, int $targetUserId, string $role, string $tenantId): void
    {
        $logMessage = sprintf(
            'Role.Assign actor=%d targetUser=%d role=%s tenant=%s',
            $actorId,
            $targetUserId,
            $role,
            $tenantId
        );

        Log::info($logMessage);
    }

    /**
     * Log role removal
     */
    public function logRoleRemoval(int $actorId, int $targetUserId, string $role, string $tenantId): void
    {
        $logMessage = sprintf(
            'Role.Remove actor=%d targetUser=%d role=%s tenant=%s',
            $actorId,
            $targetUserId,
            $role,
            $tenantId
        );

        Log::info($logMessage);
    }

    /**
     * Ensure tenant has all required roles with correct permissions
     * This should be called when creating a new tenant or updating roles
     */
    public function ensureTenantRoles(string $tenantId): void
    {
        foreach (self::PERMISSION_MATRIX as $roleName => $permissions) {
            $exists = DB::table('custom_tenant_roles')
                ->where('tenant_id', $tenantId)
                ->where('name', $roleName)
                ->exists();

            if ($exists) {
                // Update existing role
                DB::table('custom_tenant_roles')
                    ->where('tenant_id', $tenantId)
                    ->where('name', $roleName)
                    ->update([
                        'permissions' => json_encode($permissions),
                        'updated_at' => now(),
                    ]);
            } else {
                // Create new role
                DB::table('custom_tenant_roles')->insert([
                    'tenant_id' => $tenantId,
                    'name' => $roleName,
                    'permissions' => json_encode($permissions),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Check if user can assign roles (only Owner)
     */
    public function canAssignRoles(int $userId, string $tenantId): bool
    {
        $userRole = $this->getUserRole($userId, $tenantId);
        return $userRole === 'Owner';
    }

    /**
     * Check if user can manage users (Owner or Admin)
     */
    public function canManageUsers(int $userId, string $tenantId): bool
    {
        $userRole = $this->getUserRole($userId, $tenantId);
        return in_array($userRole, ['Owner', 'Admin']);
    }

    /**
     * Check if user can manage roles (only Owner)
     */
    public function canManageRoles(int $userId, string $tenantId): bool
    {
        return $this->canAssignRoles($userId, $tenantId);
    }

    /**
     * Count owners for a tenant
     */
    public function countOwners(string $tenantId): int
    {
        return DB::table('custom_user_roles')
            ->where('tenant_id', $tenantId)
            ->where('role_name', 'Owner')
            ->count();
    }

    /**
     * Check if user is the last owner
     */
    public function isLastOwner(int $userId, string $tenantId): bool
    {
        $userRole = $this->getUserRole($userId, $tenantId);
        if ($userRole !== 'Owner') {
            return false;
        }

        $ownerCount = $this->countOwners($tenantId);
        return $ownerCount <= 1;
    }
}

