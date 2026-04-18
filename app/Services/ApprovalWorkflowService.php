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
        
        // Level 1: L1 Manager (Admin) - parallel with L2
        $workflow[] = [
            'level' => 1,
            'role' => 'Admin',
            'condition' => null,
        ];
        
        // Level 1: L2 Manager (Owner) - parallel with L1
        $workflow[] = [
            'level' => 1,
            'role' => 'Owner',
            'condition' => null,
        ];
        
        // Level 2: Finance (always required after manager approvals)
        $workflow[] = [
            'level' => 2,
            'role' => 'Finance',
            'condition' => null,
        ];
        
        // Level 3: CEO (if senior role or high priority)
        if ($requisition->priority === 'High' || $this->isSeniorRole($requisition)) {
            $workflow[] = [
                'level' => 3,
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
                // Legacy workflow role aliases
                'DepartmentHead' => 'Admin',
                'HRManager' => 'Owner',
                // Current workflow roles
                'Admin' => 'Admin',
                'Owner' => 'Owner',
                // Optional roles used in extended flows
                'Finance' => 'Finance',
                'CEO' => 'Owner',
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
                ->orderByRaw("CASE WHEN custom_user_roles.role_name = 'Owner' THEN 1 ELSE 2 END")
                ->first();
            
            if ($fallbackUser) {
                Log::warning('Approver role not found, using fallback', [
                    'tenant_id' => $tenant->id,
                    'required_role' => $actualRoleName,
                    'fallback_user_id' => $fallbackUser->user_id,
                    'fallback_role' => 'Owner/Admin',
                ]);
                return $fallbackUser->user_id;
            }
            
            // Last resort: Get any user from the tenant (shouldn't happen in production)
            $anyUser = DB::table('tenant_user')
                ->where('tenant_id', $tenant->id)
                ->select('user_id')
                ->first();
            
            if ($anyUser) {
                Log::error('No approver role found, using any tenant user as last resort', [
                    'tenant_id' => $tenant->id,
                    'required_role' => $actualRoleName,
                    'fallback_user_id' => $anyUser->user_id,
                ]);
                return $anyUser->user_id;
            }
            
            Log::error('No approver found for workflow level - no users in tenant', [
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
     * Get all approver user IDs for a specific workflow level.
     * For Finance level, return all Finance users in the tenant.
     *
     * @param Tenant $tenant
     * @param array $workflowStep
     * @return array<int>
     */
    public function getApproversForLevel(Tenant $tenant, array $workflowStep): array
    {
        try {
            $roleName = $workflowStep['role'] ?? null;
            if (!$roleName) {
                return [];
            }

            $roleMapping = [
                'DepartmentHead' => 'Admin',
                'HRManager' => 'Owner',
                'Admin' => 'Admin',
                'Owner' => 'Owner',
                'Finance' => 'Finance',
                'CEO' => 'Owner',
            ];
            $actualRoleName = $roleMapping[$roleName] ?? $roleName;

            // Finance stage can have multiple members.
            if ($actualRoleName === 'Finance') {
                return DB::table('custom_user_roles')
                    ->join('tenant_user', function ($join) use ($tenant) {
                        $join->on('custom_user_roles.user_id', '=', 'tenant_user.user_id')
                            ->where('tenant_user.tenant_id', '=', $tenant->id);
                    })
                    ->where('custom_user_roles.tenant_id', $tenant->id)
                    ->where('custom_user_roles.role_name', 'Finance')
                    ->pluck('custom_user_roles.user_id')
                    ->unique()
                    ->values()
                    ->all();
            }

            $singleApproverId = $this->getApproverForLevel($tenant, $workflowStep);
            return $singleApproverId ? [$singleApproverId] : [];
        } catch (\Exception $e) {
            Log::error('Failed to get approvers for level', [
                'tenant_id' => $tenant->id,
                'workflow_step' => $workflowStep,
                'error' => $e->getMessage(),
            ]);
            return [];
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
     * Get next approval stage with all approvers for that level.
     *
     * @param Requisition $requisition
     * @return array|null ['level' => int, 'approver_ids' => array<int>] or null
     */
    public function getNextApproverStage(Requisition $requisition): ?array
    {
        $workflow = $requisition->approval_workflow ?? [];
        $currentLevel = $requisition->approval_level ?? 0;
        $nextLevel = null;
        foreach ($workflow as $step) {
            $stepLevel = $step['level'] ?? 0;
            if ($stepLevel > $currentLevel && ($nextLevel === null || $stepLevel < $nextLevel)) {
                $nextLevel = $stepLevel;
            }
        }

        if ($nextLevel === null) {
            return null;
        }

        $approverIds = [];
        foreach ($workflow as $step) {
            if (($step['level'] ?? 0) !== $nextLevel) {
                continue;
            }
            $approverIds = array_merge($approverIds, $this->getApproversForLevel($requisition->tenant, $step));
        }
        $approverIds = array_values(array_unique($approverIds));

        if (empty($approverIds)) {
            return null;
        }

        return [
            'level' => $nextLevel,
            'approver_ids' => $approverIds,
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

    /**
     * Stored workflow on the requisition, or a non-persisted default preview when not stored yet.
     *
     * @return array<int, array<string, mixed>>
     */
    public function resolveWorkflowSteps(Requisition $requisition): array
    {
        if (!empty($requisition->approval_workflow)) {
            return $requisition->approval_workflow;
        }

        $tenant = $requisition->tenant;
        if (!$tenant) {
            return [];
        }

        return $this->getDefaultWorkflow($tenant, $requisition);
    }

    /**
     * Full list of approver groups by workflow level (for UI: "who must act before completion").
     *
     * @return array<int, array{level: int, groups: array<int, array{role: string, label: string, users: array<int, array{id: int, name: string, email: ?string}>}>}>
     */
    public function buildRequiredApproverChain(Requisition $requisition): array
    {
        $tenant = $requisition->tenant;
        if (!$tenant) {
            return [];
        }

        $workflow = $this->resolveWorkflowSteps($requisition);
        if (empty($workflow)) {
            return [];
        }

        $sortedLevels = collect($workflow)
            ->pluck('level')
            ->map(fn ($l) => (int) $l)
            ->filter()
            ->unique()
            ->sort()
            ->values();

        $chainDraft = [];
        foreach ($sortedLevels as $level) {
            $groups = [];
            foreach ($workflow as $step) {
                if ((int) ($step['level'] ?? 0) !== (int) $level) {
                    continue;
                }
                $role = (string) ($step['role'] ?? 'Approver');
                $ids = array_values(array_unique(array_map('intval', $this->getApproversForLevel($tenant, $step))));
                $groups[] = [
                    'role' => $role,
                    'label' => $this->approverGroupLabel($role),
                    'user_ids' => $ids,
                ];
            }
            $chainDraft[] = [
                'level' => (int) $level,
                'groups' => $groups,
            ];
        }

        $allIds = collect($chainDraft)
            ->pluck('groups')
            ->flatten(1)
            ->pluck('user_ids')
            ->flatten()
            ->unique()
            ->values()
            ->all();

        $usersById = $allIds === []
            ? collect()
            : User::whereIn('id', $allIds)->get()->keyBy('id');

        $chain = [];
        foreach ($chainDraft as $stage) {
            $groups = [];
            foreach ($stage['groups'] as $group) {
                $users = [];
                foreach ($group['user_ids'] as $userId) {
                    $user = $usersById->get($userId);
                    if ($user) {
                        $users[] = [
                            'id' => (int) $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                        ];
                    }
                }
                $groups[] = [
                    'role' => $group['role'],
                    'label' => $group['label'],
                    'users' => $users,
                ];
            }
            $chain[] = [
                'level' => $stage['level'],
                'groups' => $groups,
            ];
        }

        return $chain;
    }

    private function approverGroupLabel(string $role): string
    {
        return match ($role) {
            'Admin' => 'L1 Manager',
            'Owner' => 'L2 Manager',
            'Finance' => 'Finance Approver',
            'CEO' => 'CEO',
            default => $role,
        };
    }
}

