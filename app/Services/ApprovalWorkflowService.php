<?php

namespace App\Services;

use App\Models\Requisition;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ApprovalWorkflowService
{
    /**
     * Evaluate and set approval workflow for a requisition
     * 
     * @param Requisition $requisition
     * @return array Workflow steps
     */
    public function evaluateWorkflow(Requisition $requisition): array
    {
        try {
            $tenant = $requisition->tenant;
            $workflow = $this->getDefaultWorkflow($tenant, $requisition);
            
            // Store workflow in requisition
            $requisition->approval_workflow = $workflow;
            $requisition->save();
            
            Log::info('Approval workflow evaluated', [
                'requisition_id' => $requisition->id,
                'workflow' => $workflow,
            ]);
            
            return $workflow;
        } catch (\Exception $e) {
            Log::error('Failed to evaluate approval workflow', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get default workflow based on department and requisition attributes
     * 
     * @param Tenant $tenant
     * @param Requisition $requisition
     * @return array
     */
    private function getDefaultWorkflow(Tenant $tenant, Requisition $requisition): array
    {
        $workflow = [];
        
        // Level 1: Department Head (always required)
        $workflow[] = [
            'level' => 1,
            'role' => 'DepartmentHead',
            'condition' => null,
        ];
        
        // Level 2: HR Manager (always required)
        $workflow[] = [
            'level' => 2,
            'role' => 'HRManager',
            'condition' => null,
        ];
        
        // Level 3: Finance (if budget > 500000)
        if ($requisition->budget_max > 500000) {
            $workflow[] = [
                'level' => 3,
                'role' => 'Finance',
                'condition' => [
                    'budget_min_gt' => 500000,
                ],
            ];
        }
        
        // Level 4: CEO (if senior role or high priority)
        if ($requisition->priority === 'High' || $this->isSeniorRole($requisition)) {
            $workflow[] = [
                'level' => 4,
                'role' => 'CEO',
                'condition' => [
                    'senior_role' => true,
                    'priority' => 'High',
                ],
            ];
        }
        
        return $workflow;
    }

    /**
     * Check if requisition is for a senior role
     * 
     * @param Requisition $requisition
     * @return bool
     */
    private function isSeniorRole(Requisition $requisition): bool
    {
        $seniorKeywords = ['director', 'vp', 'vice president', 'chief', 'head of', 'senior manager', 'executive'];
        $title = strtolower($requisition->job_title);
        
        foreach ($seniorKeywords as $keyword) {
            if (strpos($title, $keyword) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Get approver user ID for a specific workflow level
     * 
     * @param Tenant $tenant
     * @param array $workflowStep
     * @return int|null User ID or null if not found
     */
    public function getApproverForLevel(Tenant $tenant, array $workflowStep): ?int
    {
        try {
            $roleName = $workflowStep['role'];
            
            // Map role names to actual role names in the system
            $roleMapping = [
                'DepartmentHead' => 'DepartmentHead',
                'HRManager' => 'HRManager',
                'Finance' => 'Finance',
                'CEO' => 'CEO',
            ];
            
            $actualRoleName = $roleMapping[$roleName] ?? $roleName;
            
            // Find user with this role for the tenant
            $user = DB::table('custom_user_roles')
                ->join('tenant_user', function($join) use ($tenant) {
                    $join->on('custom_user_roles.user_id', '=', 'tenant_user.user_id')
                         ->where('tenant_user.tenant_id', '=', $tenant->id);
                })
                ->where('custom_user_roles.tenant_id', $tenant->id)
                ->where('custom_user_roles.role_name', $actualRoleName)
                ->select('custom_user_roles.user_id')
                ->first();
            
            if ($user) {
                return $user->user_id;
            }
            
            // Fallback: If role doesn't exist, try to find Owner or Admin
            $fallbackUser = DB::table('custom_user_roles')
                ->join('tenant_user', function($join) use ($tenant) {
                    $join->on('custom_user_roles.user_id', '=', 'tenant_user.user_id')
                         ->where('tenant_user.tenant_id', '=', $tenant->id);
                })
                ->where('custom_user_roles.tenant_id', $tenant->id)
                ->whereIn('custom_user_roles.role_name', ['Owner', 'Admin'])
                ->select('custom_user_roles.user_id')
                ->first();
            
            if ($fallbackUser) {
                Log::warning('Approver role not found, using fallback', [
                    'tenant_id' => $tenant->id,
                    'required_role' => $actualRoleName,
                    'fallback_user_id' => $fallbackUser->user_id,
                ]);
                return $fallbackUser->user_id;
            }
            
            Log::error('No approver found for workflow level', [
                'tenant_id' => $tenant->id,
                'role' => $actualRoleName,
            ]);
            
            return null;
        } catch (\Exception $e) {
            Log::error('Failed to get approver for level', [
                'tenant_id' => $tenant->id,
                'workflow_step' => $workflowStep,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get the first approver for a requisition
     * 
     * @param Requisition $requisition
     * @return int|null User ID or null if not found
     */
    public function getFirstApprover(Requisition $requisition): ?int
    {
        $workflow = $requisition->approval_workflow ?? $this->evaluateWorkflow($requisition);
        
        if (empty($workflow)) {
            return null;
        }
        
        // Get first level approver
        $firstStep = $workflow[0] ?? null;
        if (!$firstStep) {
            return null;
        }
        
        return $this->getApproverForLevel($requisition->tenant, $firstStep);
    }

    /**
     * Get the next approver after current level
     * 
     * @param Requisition $requisition
     * @return array|null ['user_id' => int, 'level' => int] or null if no more levels
     */
    public function getNextApprover(Requisition $requisition): ?array
    {
        $workflow = $requisition->approval_workflow ?? [];
        $currentLevel = $requisition->approval_level ?? 0;
        
        // Find next level
        $nextStep = null;
        foreach ($workflow as $step) {
            if ($step['level'] > $currentLevel) {
                $nextStep = $step;
                break;
            }
        }
        
        if (!$nextStep) {
            return null; // No more levels
        }
        
        $userId = $this->getApproverForLevel($requisition->tenant, $nextStep);
        
        if (!$userId) {
            return null;
        }
        
        return [
            'user_id' => $userId,
            'level' => $nextStep['level'],
        ];
    }

    /**
     * Check if requisition has more approval levels
     * 
     * @param Requisition $requisition
     * @return bool
     */
    public function hasMoreLevels(Requisition $requisition): bool
    {
        $workflow = $requisition->approval_workflow ?? [];
        $currentLevel = $requisition->approval_level ?? 0;
        
        foreach ($workflow as $step) {
            if ($step['level'] > $currentLevel) {
                return true;
            }
        }
        
        return false;
    }
}

