<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all tenants
        $tenants = DB::table('tenants')->get();
        
        foreach ($tenants as $tenant) {
            // Update Owner role
            $this->updateRolePermissions($tenant->id, 'Owner', [
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'delete_jobs', 'publish_jobs', 'close_jobs',
                'manage_stages', 'view_stages', 'create_stages', 'edit_stages', 'delete_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'delete_candidates', 'move_candidates', 'import_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews',
                'view_analytics', 'manage_users', 'manage_settings', 'manage_email_templates'
            ]);
            
            // Update Admin role
            $this->updateRolePermissions($tenant->id, 'Admin', [
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'delete_jobs', 'publish_jobs', 'close_jobs',
                'manage_stages', 'view_stages', 'create_stages', 'edit_stages', 'delete_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'delete_candidates', 'move_candidates', 'import_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]);
            
            // Update Recruiter role
            $this->updateRolePermissions($tenant->id, 'Recruiter', [
                'view_dashboard', 'view_jobs', 'create_jobs', 'edit_jobs', 'publish_jobs', 'close_jobs',
                'view_stages', 'reorder_stages',
                'view_candidates', 'create_candidates', 'edit_candidates', 'move_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]);
            
            // Update Hiring Manager role
            $this->updateRolePermissions($tenant->id, 'Hiring Manager', [
                'view_dashboard', 'view_jobs', 'view_stages', 'view_candidates',
                'view_interviews', 'create_interviews', 'edit_interviews', 'delete_interviews'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is not reversible as we're adding permissions
        // that should remain for data integrity
    }
    
    private function updateRolePermissions($tenantId, $roleName, $permissions)
    {
        $existingRole = DB::table('custom_tenant_roles')
            ->where('tenant_id', $tenantId)
            ->where('name', $roleName)
            ->first();
            
        if ($existingRole) {
            // Merge with existing permissions to avoid losing any
            $existingPermissions = json_decode($existingRole->permissions, true) ?? [];
            $mergedPermissions = array_unique(array_merge($existingPermissions, $permissions));
            
            DB::table('custom_tenant_roles')
                ->where('id', $existingRole->id)
                ->update([
                    'permissions' => json_encode($mergedPermissions),
                    'updated_at' => now()
                ]);
        }
    }
};