<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\RequisitionApproval;
use App\Models\RequisitionAuditLog;
use App\Models\Task;
use App\Models\User;
use App\Models\InAppNotification;
use App\Services\ApprovalWorkflowService;
use App\Mail\RequisitionApprovalRequest;
use App\Mail\RequisitionApproved;
use App\Mail\RequisitionRejected;
use App\Mail\RequisitionChangesRequested;
use App\Mail\RequisitionDelegated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ApprovalController extends Controller
{
    protected $workflowService;

    public function __construct(ApprovalWorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    /**
     * Submit requisition for approval
     * POST /api/requisitions/{id}/submit
     */
    public function submit(Request $request, $id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            // Get requisition with tenant scope
            $tenantModel = tenant();
            $requisition = Requisition::where('tenant_id', $tenantModel->id)
                ->findOrFail($requisitionId);
            
            // Verify user is the creator or has permission
            if ($requisition->created_by !== Auth::id() && !$this->hasHRAdminPermission()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only the creator or HR Admin can submit requisitions.',
                ], 403);
            }

            // Check if already submitted
            if ($requisition->approval_status !== 'Draft' && $requisition->approval_status !== 'ChangesRequested') {
                return response()->json([
                    'success' => false,
                    'message' => 'Requisition is already in approval workflow.',
                ], 400);
            }

            DB::beginTransaction();
            try {
                // Evaluate workflow
                $workflow = $this->workflowService->evaluateWorkflow($requisition);
                
                Log::info('Workflow evaluated for requisition', [
                    'requisition_id' => $requisition->id,
                    'workflow' => $workflow,
                ]);

                // Guard: If Finance stage exists, a Finance approver must be configured.
                $financeStep = collect($workflow)->first(function ($step) {
                    return ($step['role'] ?? null) === 'Finance';
                });
                if ($financeStep && empty($this->workflowService->getApproversForLevel($tenantModel, $financeStep))) {
                    DB::rollBack();
                    Log::error('Finance approver missing for requisition workflow', [
                        'requisition_id' => $requisition->id,
                        'tenant_id' => tenant_id(),
                        'workflow' => $workflow,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Finance approval stage is configured, but no Finance approver is assigned for this tenant. Please assign a user with Finance role and try again.',
                    ], 400);
                }
                
                $firstLevel = collect($workflow)->min('level');
                $firstLevelApproverIds = [];
                foreach ($workflow as $step) {
                    if (($step['level'] ?? null) !== $firstLevel) {
                        continue;
                    }
                    $firstLevelApproverIds = array_merge(
                        $firstLevelApproverIds,
                        $this->workflowService->getApproversForLevel($tenantModel, $step)
                    );
                }
                $firstLevelApproverIds = array_values(array_unique($firstLevelApproverIds));
                $firstApproverId = $firstLevelApproverIds[0] ?? null;

                Log::info('Approver lookup for requisition submit', [
                    'requisition_id' => $requisition->id,
                    'first_approver_id' => $firstApproverId,
                    'first_level_approver_ids' => $firstLevelApproverIds,
                    'workflow_steps' => count($workflow),
                ]);

                if (!$firstApproverId) {
                    DB::rollBack();
                    Log::error('No approver found for requisition', [
                        'requisition_id' => $requisition->id,
                        'tenant_id' => tenant_id(),
                        'workflow' => $workflow,
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'No approver found for this requisition. Please configure approval workflow or ensure you have users with Owner/Admin roles.',
                    ], 400);
                }

                // Update requisition
                $requisition->approval_status = 'Pending';
                $requisition->approval_level = 1;
                $requisition->current_approver_id = $firstApproverId;
                $requisition->status = 'Pending'; // Keep legacy status for backward compatibility
                $requisition->save();

                // Create pending approval for first level only.
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $firstApproverId,
                    'action' => 'Pending',
                    'approval_level' => $firstLevel,
                    'comments' => 'Requisition submitted for approval',
                ]);

                foreach ($firstLevelApproverIds as $firstLevelApproverId) {
                    if ((int) $firstLevelApproverId === (int) $firstApproverId) {
                        continue;
                    }
                    RequisitionApproval::create([
                        'requisition_id' => $requisition->id,
                        'approver_id' => $firstLevelApproverId,
                        'action' => 'Pending',
                        'approval_level' => $firstLevel,
                        'comments' => 'Requisition submitted for approval',
                    ]);
                }

                // Create audit log entry
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => Auth::id(),
                    'action' => 'Submitted',
                    'field_name' => 'approval_status',
                    'old_value' => 'Draft',
                    'new_value' => 'Pending',
                    'changes' => [
                        'approval_status' => 'Pending',
                        'approval_level' => $firstLevel,
                        'current_approver_id' => $firstApproverId,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Get tenant for notifications
                $tenantModel = tenant();
                $tenantSlug = $tenantModel ? $tenantModel->slug : ($tenant ?? 'tenant');
                
                // Notify first approver only; next levels are notified when routed.
                foreach ($firstLevelApproverIds as $firstLevelApproverId) {
                    $this->createApprovalTask($requisition, $firstLevelApproverId);
                    $this->createInAppNotification(
                        $firstLevelApproverId,
                        'requisition_approval_request',
                        "New Requisition Requires Approval",
                        "Requisition '{$requisition->job_title}' requires your approval.",
                        "/{$tenantSlug}/requisitions/{$requisition->id}/approval",
                        ['requisition_id' => $requisition->id]
                    );
                    $this->sendApprovalRequestNotification($requisition, $firstLevelApproverId, false);
                }

                DB::commit();

                Log::info('Requisition submitted for approval', [
                    'requisition_id' => $requisition->id,
                    'approver_id' => $firstApproverId,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Requisition submitted for approval successfully.',
                    'data' => [
                        'requisition_id' => $requisition->id,
                        'approval_status' => $requisition->approval_status,
                        'current_approver_id' => $requisition->current_approver_id,
                    ],
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Failed to submit requisition for approval', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit requisition for approval.',
            ], 500);
        }
    }

    /**
     * List pending approvals for current user
     * GET /api/approvals/pending
     */
    public function pending(Request $request, string $tenant = null)
    {
        try {
            $user = Auth::user();
            $limit = min((int) $request->input('limit', 20), 100);
            $page = max((int) $request->input('page', 1), 1);

            // Get pending requisitions for current user or HR Admin can see all
            $query = Requisition::with(['creator', 'currentApprover'])
                ->where('approval_status', 'Pending');

            if (!$this->hasHRAdminPermission()) {
                $query->whereHas('approvals', function ($q) use ($user) {
                    $q->where('approver_id', $user->id)
                        ->where('action', 'Pending')
                        ->whereColumn('requisition_approvals.approval_level', 'requisitions.approval_level');
                });
            }

            $requisitions = $query->orderBy('created_at', 'desc')
                ->paginate($limit, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $requisitions->items(),
                'total' => $requisitions->total(),
                'per_page' => $requisitions->perPage(),
                'current_page' => $requisitions->currentPage(),
                'last_page' => $requisitions->lastPage(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch pending approvals', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending approvals.',
            ], 500);
        }
    }

    /**
     * Approve a requisition
     * POST /api/requisitions/{id}/approve
     */
    public function approve(Request $request, $id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            $requisition = Requisition::findOrFail($requisitionId);
            $user = Auth::user();

            // Verify permissions
            if (!$this->canApprove($requisition, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You are not the current approver for this requisition.',
                ], 403);
            }

            // Check for concurrent approval attempts
            DB::beginTransaction();
            try {
                // Reload to check current state
                $requisition->refresh();
                
                if ($requisition->approval_status !== 'Pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments', '');

                $pendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('approver_id', $user->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->first();
                if (!$pendingApproval) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already taken action on this request or are not an approver.',
                    ], 409);
                }
                $pendingApproval->action = 'Approved';
                $pendingApproval->comments = $comments;
                $pendingApproval->approval_level = $pendingApproval->approval_level ?: $requisition->approval_level;
                $pendingApproval->save();

                // Create audit log entry
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => $user->id,
                    'action' => 'Approved',
                    'field_name' => 'approval_status',
                    'old_value' => 'Pending',
                    'new_value' => $this->workflowService->hasMoreLevels($requisition) ? 'Pending' : 'Approved',
                    'changes' => [
                        'approval_level' => $requisition->approval_level,
                        'comments' => $comments,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Get tenant for notifications
                $tenant = tenant();
                $tenantSlug = $tenant ? $tenant->slug : 'tenant';
                
                // Complete task for the approver who took action.
                $this->completeApprovalTask($requisition, $user->id);

                // Same-level rule: one approval is enough, so close remaining pending approvals in this level.
                $sameLevelPendingApproverIds = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->pluck('approver_id')
                    ->all();

                // Use only values allowed by requisition_approvals.action enum (MySQL reports invalid
                // enum values as "Data truncated", not the literal value, so never write NotRequired here).
                RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->update([
                        'action' => 'Approved',
                        'comments' => 'Waived: same-level approval already completed by another approver.',
                        'updated_at' => now(),
                    ]);

                foreach ($sameLevelPendingApproverIds as $sameLevelPendingApproverId) {
                    $this->completeApprovalTask($requisition, (int) $sameLevelPendingApproverId);
                }

                // Legacy submit-on-create used sequential levels (1,2,3…) per approver; void orphan Pending rows
                // above the current workflow level so Finance is not duplicated when routing to the real stage.
                RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', '>', $requisition->approval_level)
                    ->update([
                        'action' => 'Approved',
                        'comments' => 'Voided: incorrect stage queue (legacy).',
                        'updated_at' => now(),
                    ]);

                // Route to next approval level if present; otherwise finalize approval.
                $nextStage = $this->workflowService->getNextApproverStage($requisition);
                if ($nextStage) {
                    $nextApproverIds = array_values(array_unique($nextStage['approver_ids']));
                    $nextCurrentApproverId = $nextApproverIds[0] ?? null;
                    if (!$nextCurrentApproverId) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'No approver found for the next approval stage.',
                        ], 400);
                    }

                    $isMovedToFinance = collect($requisition->approval_workflow ?? [])
                        ->contains(function ($step) use ($nextStage) {
                            return ($step['level'] ?? null) === $nextStage['level']
                                && ($step['role'] ?? null) === 'Finance';
                        });

                    $requisition->approval_level = $nextStage['level'];
                    $requisition->current_approver_id = $nextCurrentApproverId;
                    $requisition->approval_status = 'Pending';
                    // After L1/L2 (same-level) approval, route to Finance: surface in legacy status for lists/filters.
                    $requisition->status = $isMovedToFinance ? 'Moved To Finance' : 'Pending';
                    $requisition->save();

                    foreach ($nextApproverIds as $nextApproverId) {
                        $nextComment = $isMovedToFinance
                            ? 'Moved to Finance for approval'
                            : 'Routed to next approval level';

                        // Reuse an existing Pending row for this approver (e.g. wrong level from legacy create)
                        // instead of inserting a second Finance / next-stage approval.
                        $existingPending = RequisitionApproval::where('requisition_id', $requisition->id)
                            ->where('approver_id', $nextApproverId)
                            ->where('action', 'Pending')
                            ->orderBy('id')
                            ->first();

                        if ($existingPending) {
                            $existingPending->approval_level = $nextStage['level'];
                            $existingPending->comments = $nextComment;
                            $existingPending->save();

                            RequisitionApproval::where('requisition_id', $requisition->id)
                                ->where('approver_id', $nextApproverId)
                                ->where('action', 'Pending')
                                ->where('id', '!=', $existingPending->id)
                                ->delete();
                        } else {
                            RequisitionApproval::create([
                                'requisition_id' => $requisition->id,
                                'approver_id' => $nextApproverId,
                                'action' => 'Pending',
                                'approval_level' => $nextStage['level'],
                                'comments' => $nextComment,
                            ]);
                        }

                        $this->createApprovalTask(
                            $requisition,
                            $nextApproverId,
                            $isMovedToFinance ? 'Finance Approval' : null
                        );
                        $this->createInAppNotification(
                            $nextApproverId,
                            'requisition_approval_request',
                            $isMovedToFinance ? "Finance Approval Required" : "New Requisition Requires Approval",
                            $isMovedToFinance
                                ? "Requisition '{$requisition->job_title}' has been moved to Finance and requires your approval."
                                : "Requisition '{$requisition->job_title}' requires your approval.",
                            "/{$tenantSlug}/requisitions/{$requisition->id}/approval",
                            ['requisition_id' => $requisition->id, 'approval_level' => $nextStage['level']]
                        );
                        $this->sendApprovalRequestNotification($requisition, $nextApproverId, $isMovedToFinance);
                    }
                } else {
                    $requisition->approval_status = 'Approved';
                    $requisition->status = 'Approved';
                    $requisition->current_approver_id = null;
                    $requisition->approved_at = now();
                    $requisition->save();

                    $this->sendFinalApprovalNotification($requisition);
                    $this->createInAppNotification(
                        $requisition->created_by,
                        'requisition_approved',
                        "Requisition Approved",
                        "Your requisition '{$requisition->job_title}' has been approved.",
                        "/{$tenantSlug}/requisitions/{$requisition->id}",
                        ['requisition_id' => $requisition->id]
                    );
                }

                DB::commit();

                Log::info('Requisition approved', [
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'approval_level' => $requisition->approval_level,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Requisition approved successfully.',
                    'data' => [
                        'status' => 'ok',
                        'next_approver_id' => $requisition->current_approver_id,
                        'approval_status' => $requisition->approval_status,
                    ],
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Failed to approve requisition', [
                'requisition_id' => $id,
                'exception' => get_class($e),
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $payload = [
                'success' => false,
                'message' => 'Failed to approve requisition.',
            ];

            if (config('app.debug')) {
                $payload['debug'] = [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ];
            }

            return response()->json($payload, 500);
        }
    }

    /**
     * Reject a requisition
     * POST /api/requisitions/{id}/reject
     */
    public function reject(Request $request, $id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            $requisition = Requisition::findOrFail($requisitionId);
            $user = Auth::user();

            // Verify permissions
            if (!$this->canApprove($requisition, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You are not the current approver for this requisition.',
                ], 403);
            }

            // Validate comments are required
            $request->validate([
                'comments' => 'required|string|min:10',
            ], [
                'comments.required' => 'Comments are required when rejecting a requisition.',
                'comments.min' => 'Comments must be at least 10 characters.',
            ]);

            DB::beginTransaction();
            try {
                // Reload to check current state
                $requisition->refresh();
                
                if ($requisition->approval_status !== 'Pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments');

                $pendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('approver_id', $user->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->first();
                if (!$pendingApproval) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already taken action on this request or are not an approver.',
                    ], 409);
                }
                $pendingApproval->action = 'Rejected';
                $pendingApproval->comments = $comments;
                $pendingApproval->save();

                // Create audit log entry
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => $user->id,
                    'action' => 'Rejected',
                    'field_name' => 'approval_status',
                    'old_value' => 'Pending',
                    'new_value' => 'Rejected',
                    'changes' => [
                        'approval_level' => $requisition->approval_level,
                        'comments' => $comments,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Update requisition
                $requisition->approval_status = 'Rejected';
                $requisition->status = 'Rejected';
                $requisition->current_approver_id = null;
                $requisition->save();

                // Complete tasks (current + all other pending approval tasks)
                $this->completeApprovalTask($requisition, $user->id);
                $this->completeAllPendingApprovalTasks($requisition);

                // Notify requester
                $this->sendRejectionNotification($requisition, $comments);
                
                // Create in-app notification for requester
                $tenant = tenant();
                $tenantSlug = $tenant ? $tenant->slug : 'tenant';
                $this->createInAppNotification(
                    $requisition->created_by,
                    'requisition_rejected',
                    "Requisition Rejected",
                    "Your requisition '{$requisition->job_title}' has been rejected. Comments: " . substr($comments, 0, 100),
                    "/{$tenantSlug}/requisitions/{$requisition->id}",
                    ['requisition_id' => $requisition->id, 'comments' => $comments]
                );

                DB::commit();

                Log::info('Requisition rejected', [
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Requisition rejected successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to reject requisition', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject requisition.',
            ], 500);
        }
    }

    /**
     * Request changes to a requisition
     * POST /api/requisitions/{id}/request-changes
     */
    public function requestChanges(Request $request, $id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            $requisition = Requisition::findOrFail($requisitionId);
            $user = Auth::user();

            // Verify permissions
            if (!$this->canApprove($requisition, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You are not the current approver for this requisition.',
                ], 403);
            }

            // Validate comments are required
            $request->validate([
                'comments' => 'required|string|min:10',
            ], [
                'comments.required' => 'Comments are required when requesting changes.',
                'comments.min' => 'Comments must be at least 10 characters.',
            ]);

            DB::beginTransaction();
            try {
                // Reload to check current state
                $requisition->refresh();
                
                if ($requisition->approval_status !== 'Pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments');

                $pendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('approver_id', $user->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->first();
                if (!$pendingApproval) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already taken action on this request or are not an approver.',
                    ], 409);
                }
                $pendingApproval->action = 'RequestedChanges';
                $pendingApproval->comments = $comments;
                $pendingApproval->save();

                // Create audit log entry
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => $user->id,
                    'action' => 'RequestedChanges',
                    'field_name' => 'approval_status',
                    'old_value' => 'Pending',
                    'new_value' => 'ChangesRequested',
                    'changes' => [
                        'approval_level' => $requisition->approval_level,
                        'comments' => $comments,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Update requisition
                $requisition->approval_status = 'ChangesRequested';
                $requisition->current_approver_id = null;
                $requisition->save();

                // Complete tasks (current + all other pending approval tasks)
                $this->completeApprovalTask($requisition, $user->id);
                $this->completeAllPendingApprovalTasks($requisition);

                // Create task for requester to edit
                $this->createEditTask($requisition);

                // Notify requester
                $this->sendChangesRequestedNotification($requisition, $comments);
                
                // Create in-app notification for requester
                $tenant = tenant();
                $tenantSlug = $tenant ? $tenant->slug : 'tenant';
                $this->createInAppNotification(
                    $requisition->created_by,
                    'requisition_changes_requested',
                    "Changes Requested",
                    "Changes have been requested for requisition '{$requisition->job_title}'. Comments: " . substr($comments, 0, 100),
                    "/{$tenantSlug}/requisitions/{$requisition->id}/edit",
                    ['requisition_id' => $requisition->id, 'comments' => $comments]
                );

                DB::commit();

                Log::info('Changes requested for requisition', [
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Changes requested successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to request changes', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to request changes.',
            ], 500);
        }
    }

    /**
     * Delegate approval to another user
     * POST /api/requisitions/{id}/delegate
     */
    public function delegate(Request $request, $id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            $requisition = Requisition::findOrFail($requisitionId);
            $user = Auth::user();

            // Verify permissions
            if (!$this->canApprove($requisition, $user)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. You are not the current approver for this requisition.',
                ], 403);
            }

            // Validate delegate user
            $request->validate([
                'delegate_to_user_id' => [
                    'required',
                    'integer',
                    'exists:users,id',
                    Rule::notIn([(int) $user->id]),
                ],
                'comments' => 'nullable|string',
            ]);

            DB::beginTransaction();
            try {
                // Reload to check current state
                $requisition->refresh();
                
                if ($requisition->approval_status !== 'Pending') {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $pendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('approver_id', $user->id)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->first();
                if (!$pendingApproval) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'You have already taken action on this request or are not an approver.',
                    ], 409);
                }

                $delegateToId = $request->input('delegate_to_user_id');
                $comments = $request->input('comments', 'Delegated for approval');

                // Verify delegate belongs to tenant
                $delegate = \App\Models\User::findOrFail($delegateToId);
                if (!$delegate->belongsToTenant(tenant_id())) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Delegate user must belong to the same tenant.',
                    ], 400);
                }

                $delegateAlreadyPending = RequisitionApproval::where('requisition_id', $requisition->id)
                    ->where('approver_id', $delegateToId)
                    ->where('action', 'Pending')
                    ->where('approval_level', $requisition->approval_level)
                    ->exists();
                if ($delegateAlreadyPending) {
                    $pendingApproval->delete();
                } else {
                    $pendingApproval->approver_id = $delegateToId;
                    $pendingApproval->comments = $comments;
                    $pendingApproval->save();
                }

                // Create approval record
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'action' => 'Delegated',
                    'approval_level' => $requisition->approval_level,
                    'comments' => $comments,
                    'delegate_to' => $delegateToId,
                ]);

                // Create audit log entry
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => $user->id,
                    'action' => 'Delegated',
                    'field_name' => 'current_approver_id',
                    'old_value' => (string) $user->id,
                    'new_value' => (string) $delegateToId,
                    'changes' => [
                        'delegated_from' => $user->id,
                        'delegated_to' => $delegateToId,
                        'comments' => $comments,
                    ],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Update requisition
                $requisition->current_approver_id = $delegateToId;
                $requisition->save();

                // Complete current task
                $this->completeApprovalTask($requisition, $user->id);

                // Delegate already had a pending row at this level — keep their existing approval task.
                if (!$delegateAlreadyPending) {
                    $this->createApprovalTask($requisition, $delegateToId);
                }

                // Notify delegate
                $this->sendDelegationNotification($requisition, $delegateToId, $user->id);
                
                // Create in-app notification for delegate
                $tenant = tenant();
                $tenantSlug = $tenant ? $tenant->slug : 'tenant';
                $delegator = \App\Models\User::find($user->id);
                $this->createInAppNotification(
                    $delegateToId,
                    'requisition_delegated',
                    "Approval Delegated to You",
                    "{$delegator->name} has delegated approval of requisition '{$requisition->job_title}' to you.",
                    "/{$tenantSlug}/requisitions/{$requisition->id}/approval",
                    ['requisition_id' => $requisition->id, 'delegated_from' => $user->id]
                );

                DB::commit();

                Log::info('Approval delegated', [
                    'requisition_id' => $requisition->id,
                    'from_user_id' => $user->id,
                    'to_user_id' => $delegateToId,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Approval delegated successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Failed to delegate approval', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to delegate approval.',
            ], 500);
        }
    }

    /**
     * Display pending approvals page
     * GET /requisitions/pending-approvals
     */
    public function pendingApprovalsPage(Request $request, string $tenant = null)
    {
        try {
            $user = Auth::user();
            
            // Get pending requisitions for current user or HR Admin can see all
            $query = Requisition::with(['creator', 'currentApprover'])
                ->where('approval_status', 'Pending');

            if (!$this->hasHRAdminPermission()) {
                $query->whereHas('approvals', function ($q) use ($user) {
                    $q->where('approver_id', $user->id)
                        ->where('action', 'Pending')
                        ->whereColumn('requisition_approvals.approval_level', 'requisitions.approval_level');
                });
            }

            // Apply filters
            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->where('job_title', 'like', '%'.$keyword.'%')
                        ->orWhere('department', 'like', '%'.$keyword.'%');
                });
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            $requisitions = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('tenant.requisitions.pending-approvals', compact('requisitions'));
        } catch (\Exception $e) {
            Log::error('Failed to load pending approvals page', [
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Failed to load pending approvals.');
        }
    }

    /**
     * Display approval detail page
     * GET /requisitions/{id}/approval
     */
    public function approvalDetail($id, string $tenant = null)
    {
        try {
            // Resolve requisition ID safely for both path-based and subdomain routes.
            $routeParams = request()->route()?->parameters() ?? [];
            $requisitionId = null;

            if (isset($routeParams['id']) && is_numeric($routeParams['id'])) {
                $requisitionId = (int) $routeParams['id'];
            } elseif (is_numeric(request()->route('id'))) {
                $requisitionId = (int) request()->route('id');
            } elseif (is_numeric($id)) {
                $requisitionId = (int) $id;
            } elseif (is_numeric($tenant)) {
                // Subdomain parameter binding fallback.
                $requisitionId = (int) $tenant;
            }

            if (!$requisitionId) {
                throw new \Exception('Invalid requisition ID');
            }

            $requisition = Requisition::with(['creator', 'currentApprover', 'attachments', 'tenant'])
                ->findOrFail($requisitionId);
            
            $user = Auth::user();

            // Verify user can view this approval (pending row must match current workflow level)
            $hasPendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
                ->where('approver_id', $user->id)
                ->where('action', 'Pending')
                ->where('approval_level', $requisition->approval_level)
                ->exists();
            if (!$hasPendingApproval && !$this->hasHRAdminPermission()) {
                abort(403, 'You are not authorized to view this approval.');
            }

            // Get approval history
            $approvals = RequisitionApproval::where('requisition_id', $requisitionId)
                ->with(['approver', 'delegate'])
                ->orderBy('created_at', 'asc')
                ->get();

            $canTakeAction = $hasPendingApproval && $requisition->approval_status === 'Pending';
            $requiredApproverChain = $this->workflowService->buildRequiredApproverChain($requisition);

            $delegateUserOptions = collect();
            if ($canTakeAction) {
                $tid = tenant_id();
                if ($tid) {
                    $delegateUserOptions = User::query()
                        ->whereHas('tenants', fn ($q) => $q->where('tenants.id', $tid))
                        ->where('id', '!=', $user->id)
                        ->orderBy('name')
                        ->get(['id', 'name', 'email']);
                }
            }

            return view('tenant.requisitions.approval-detail', compact(
                'requisition',
                'approvals',
                'canTakeAction',
                'requiredApproverChain',
                'delegateUserOptions'
            ));
        } catch (\Exception $e) {
            Log::error('Failed to load approval detail', [
                'requisition_id' => $id,
                'route_params' => request()->route()?->parameters() ?? [],
                'error' => $e->getMessage(),
            ]);

            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.pending-approvals', $tenantSlug))
                ->with('error', 'Failed to load approval details.');
        }
    }

    /**
     * Get approval history for a requisition
     * GET /api/requisitions/{id}/approvals
     */
    public function history($id, string $tenant = null)
    {
        try {
            $requisitionId = $this->resolveRequisitionId($id, $tenant);
            $requisition = Requisition::findOrFail($requisitionId);
            
            $approvals = RequisitionApproval::where('requisition_id', $requisitionId)
                ->with(['approver', 'delegate'])
                ->orderBy('created_at', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $approvals->map(function ($approval) {
                    return [
                        'id' => $approval->id,
                        'action' => $approval->action,
                        'approver_id' => $approval->approver_id,
                        'approver_name' => $approval->approver->name ?? 'N/A',
                        'comments' => $approval->comments,
                        'approval_level' => $approval->approval_level,
                        'delegate_to' => $approval->delegate_to,
                        'delegate_name' => $approval->delegate->name ?? null,
                        'created_at' => $approval->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch approval history', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch approval history.',
            ], 500);
        }
    }

    /**
     * Check if user can approve this requisition
     */
    private function canApprove(Requisition $requisition, $user): bool
    {
        $hasPendingApproval = RequisitionApproval::where('requisition_id', $requisition->id)
            ->where('approver_id', $user->id)
            ->where('action', 'Pending')
            ->where('approval_level', $requisition->approval_level)
            ->exists();
        if ($hasPendingApproval) {
            return true;
        }

        // HR Admin can approve
        if ($this->hasHRAdminPermission()) {
            return true;
        }

        return false;
    }

    /**
     * Check if user has HR Admin permission
     */
    private function hasHRAdminPermission(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return $user->hasAnyRole(['Owner', 'Admin'], tenant_id());
    }

    /**
     * Create approval task for approver
     */
    private function createApprovalTask(Requisition $requisition, int $approverId, ?string $stageLabel = null): void
    {
        try {
            $tenant = tenant();
            $tenantSlug = $tenant ? $tenant->slug : 'tenant';
            $taskTitlePrefix = $stageLabel ?: 'Approve Requisition';
            $taskType = $stageLabel ? "Requisition {$stageLabel}" : 'Requisition Approval';
            
            // Check if due_at column exists (it may not exist in some databases)
            $hasDueAtColumn = Schema::hasColumn('tasks', 'due_at');
            
            $taskData = [
                'user_id' => $approverId,
                'task_type' => $taskType,
                'title' => "{$taskTitlePrefix} - {$requisition->job_title}",
                'requisition_id' => $requisition->id,
                'status' => 'Pending',
                'link' => "/{$tenantSlug}/requisitions/{$requisition->id}/approval",
                'created_by' => Auth::id(),
            ];
            
            // Add tenant_id if available
            $currentTenantId = tenant_id();
            if ($currentTenantId) {
                $taskData['tenant_id'] = $currentTenantId;
            }
            
            // Only add due_at if column exists
            if ($hasDueAtColumn) {
                $taskData['due_at'] = now()->addDays(2);
            }
            
            $task = Task::create($taskData);

            // Send notification
            $this->sendTaskCreatedNotification($task);

            Log::info('Approval task created', [
                'task_id' => $task->id,
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
                'task_user_id' => $task->user_id,
                'task_type' => $task->task_type,
                'has_due_at' => $hasDueAtColumn,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create approval task', [
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    /**
     * Create edit task for requester
     */
    private function createEditTask(Requisition $requisition): void
    {
        try {
            $tenant = tenant();
            $tenantSlug = $tenant ? $tenant->slug : 'tenant';
            
            // Check if due_at column exists (it may not exist in some databases)
            $hasDueAtColumn = Schema::hasColumn('tasks', 'due_at');
            
            $taskData = [
                'user_id' => $requisition->created_by,
                'task_type' => 'Requisition Edit Needed',
                'title' => "Update Requisition – {$requisition->job_title}",
                'requisition_id' => $requisition->id,
                'status' => 'Pending',
                'link' => "/{$tenantSlug}/requisitions/{$requisition->id}/edit",
                'created_by' => Auth::id(),
            ];
            
            // Add tenant_id if available
            $currentTenantId = tenant_id();
            if ($currentTenantId) {
                $taskData['tenant_id'] = $currentTenantId;
            }
            
            // Only add due_at if column exists
            if ($hasDueAtColumn) {
                $taskData['due_at'] = now()->addDays(3);
            }
            
            $task = Task::create($taskData);

            // Send notification
            $this->sendTaskCreatedNotification($task);

            Log::info('Edit task created', [
                'task_id' => $task->id,
                'requisition_id' => $requisition->id,
                'user_id' => $requisition->created_by,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create edit task', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Complete approval task
     */
    private function completeApprovalTask(Requisition $requisition, int $userId): void
    {
        try {
            Task::where('requisition_id', $requisition->id)
                ->where('user_id', $userId)
                ->where('status', 'Pending')
                ->update(['status' => 'Completed']);
        } catch (\Exception $e) {
            Log::error('Failed to complete approval task', [
                'requisition_id' => $requisition->id,
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Complete all remaining pending approval tasks for a requisition.
     */
    private function completeAllPendingApprovalTasks(Requisition $requisition): void
    {
        try {
            Task::where('requisition_id', $requisition->id)
                ->where(function ($query) {
                    $query->where('task_type', 'Requisition Approval')
                        ->orWhere('task_type', 'Requisition Finance Approval');
                })
                ->where('status', 'Pending')
                ->update(['status' => 'Completed']);
        } catch (\Exception $e) {
            Log::error('Failed to complete all approval tasks', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send approval request notification
     */
    private function sendApprovalRequestNotification(Requisition $requisition, int $approverId, bool $isFinanceStage = false): void
    {
        try {
            $approver = \App\Models\User::find($approverId);
            if (!$approver || !$approver->email) {
                return;
            }

            Mail::to($approver->email)->send(new RequisitionApprovalRequest($requisition, $isFinanceStage));
            
            Log::info('Approval request notification sent', [
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send approval request notification', [
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send final approval notification
     */
    private function sendFinalApprovalNotification(Requisition $requisition): void
    {
        try {
            $creator = $requisition->creator;
            if (!$creator || !$creator->email) {
                return;
            }

            $approver = Auth::user();
            Mail::to($creator->email)->send(new RequisitionApproved($requisition, $approver));
            
            Log::info('Final approval notification sent', [
                'requisition_id' => $requisition->id,
                'creator_id' => $requisition->created_by,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send final approval notification', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send rejection notification
     */
    private function sendRejectionNotification(Requisition $requisition, string $comments): void
    {
        try {
            $creator = $requisition->creator;
            if (!$creator || !$creator->email) {
                return;
            }

            Mail::to($creator->email)->send(new RequisitionRejected($requisition, $comments));
            
            Log::info('Rejection notification sent', [
                'requisition_id' => $requisition->id,
                'creator_id' => $requisition->created_by,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send rejection notification', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send changes requested notification
     */
    private function sendChangesRequestedNotification(Requisition $requisition, string $comments): void
    {
        try {
            $creator = $requisition->creator;
            if (!$creator || !$creator->email) {
                return;
            }

            Mail::to($creator->email)->send(new RequisitionChangesRequested($requisition, $comments));
            
            Log::info('Changes requested notification sent', [
                'requisition_id' => $requisition->id,
                'creator_id' => $requisition->created_by,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send changes requested notification', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send delegation notification
     */
    private function sendDelegationNotification(Requisition $requisition, int $delegateId, int $delegatorId): void
    {
        try {
            $delegate = \App\Models\User::find($delegateId);
            if (!$delegate || !$delegate->email) {
                return;
            }

            Mail::to($delegate->email)->send(new RequisitionDelegated($requisition, $delegatorId));
            
            Log::info('Delegation notification sent', [
                'requisition_id' => $requisition->id,
                'delegate_id' => $delegateId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delegation notification', [
                'requisition_id' => $requisition->id,
                'delegate_id' => $delegateId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send task created notification
     */
    private function sendTaskCreatedNotification(Task $task): void
    {
        try {
            $assignee = $task->assignee;
            if (!$assignee || !$assignee->email) {
                return;
            }

            Mail::to($assignee->email)->queue(new \App\Mail\TaskCreated($task));
            
            Log::info('Task created notification sent', [
                'task_id' => $task->id,
                'assignee_id' => $task->user_id,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send task created notification', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Create in-app notification
     */
    private function createInAppNotification(int $userId, string $type, string $title, string $message, string $link = null, array $data = []): void
    {
        try {
            InAppNotification::create([
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'data' => $data,
                'read' => false,
            ]);

            Log::info('In-app notification created', [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create in-app notification', [
                'user_id' => $userId,
                'type' => $type,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * Resolve requisition ID safely for both path-based and subdomain routes.
     */
    private function resolveRequisitionId($id, ?string $tenant = null): int
    {
        $routeParams = request()->route()?->parameters() ?? [];

        if (isset($routeParams['id']) && is_numeric($routeParams['id'])) {
            return (int) $routeParams['id'];
        }

        $routeId = request()->route('id');
        if (is_numeric($routeId)) {
            return (int) $routeId;
        }

        if (is_numeric($id)) {
            return (int) $id;
        }

        if (is_numeric($tenant)) {
            return (int) $tenant;
        }

        throw new \InvalidArgumentException('Invalid requisition ID');
    }
}
