<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\RequisitionApproval;
use App\Models\Task;
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
            $requisition = Requisition::findOrFail($id);
            
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
                
                // Get first approver
                $firstApproverId = $this->workflowService->getFirstApprover($requisition);
                
                if (!$firstApproverId) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'No approver found for this requisition. Please configure approval workflow.',
                    ], 400);
                }

                // Update requisition
                $requisition->approval_status = 'Pending';
                $requisition->approval_level = 1;
                $requisition->current_approver_id = $firstApproverId;
                $requisition->status = 'Pending'; // Keep legacy status for backward compatibility
                $requisition->save();

                // Create audit log entry
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $firstApproverId,
                    'action' => 'Pending',
                    'approval_level' => 1,
                    'comments' => 'Requisition submitted for approval',
                ]);

                // Create task for approver
                $this->createApprovalTask($requisition, $firstApproverId);

                // Send notification email
                $this->sendApprovalRequestNotification($requisition, $firstApproverId);

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
                $query->where('current_approver_id', $user->id);
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
            $requisition = Requisition::findOrFail($id);
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
                
                if ($requisition->approval_status !== 'Pending' || $requisition->current_approver_id !== $user->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments', '');

                // Create audit log
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'action' => 'Approved',
                    'approval_level' => $requisition->approval_level,
                    'comments' => $comments,
                ]);

                // Check if there are more approval levels
                if ($this->workflowService->hasMoreLevels($requisition)) {
                    // Move to next level
                    $nextApprover = $this->workflowService->getNextApprover($requisition);
                    
                    if ($nextApprover) {
                        $requisition->approval_level = $nextApprover['level'];
                        $requisition->current_approver_id = $nextApprover['user_id'];
                        $requisition->approval_status = 'Pending';
                        
                        // Create task for next approver
                        $this->createApprovalTask($requisition, $nextApprover['user_id']);
                        
                        // Send notification to next approver
                        $this->sendApprovalRequestNotification($requisition, $nextApprover['user_id']);
                    } else {
                        // No approver found for next level, mark as approved
                        $requisition->approval_status = 'Approved';
                        $requisition->status = 'Approved';
                        $requisition->current_approver_id = null;
                        $requisition->approved_at = now();
                        
                        // Notify requester
                        $this->sendFinalApprovalNotification($requisition);
                    }
                } else {
                    // Final approval
                    $requisition->approval_status = 'Approved';
                    $requisition->status = 'Approved';
                    $requisition->current_approver_id = null;
                    $requisition->approved_at = now();
                    
                    // Notify requester
                    $this->sendFinalApprovalNotification($requisition);
                }

                // Complete task
                $this->completeApprovalTask($requisition, $user->id);

                $requisition->save();

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
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve requisition.',
            ], 500);
        }
    }

    /**
     * Reject a requisition
     * POST /api/requisitions/{id}/reject
     */
    public function reject(Request $request, $id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);
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
                
                if ($requisition->approval_status !== 'Pending' || $requisition->current_approver_id !== $user->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments');

                // Create audit log
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'action' => 'Rejected',
                    'approval_level' => $requisition->approval_level,
                    'comments' => $comments,
                ]);

                // Update requisition
                $requisition->approval_status = 'Rejected';
                $requisition->status = 'Rejected';
                $requisition->current_approver_id = null;
                $requisition->save();

                // Complete task
                $this->completeApprovalTask($requisition, $user->id);

                // Notify requester
                $this->sendRejectionNotification($requisition, $comments);

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
            $requisition = Requisition::findOrFail($id);
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
                
                if ($requisition->approval_status !== 'Pending' || $requisition->current_approver_id !== $user->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
                    ], 409);
                }

                $comments = $request->input('comments');

                // Create audit log
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'action' => 'RequestedChanges',
                    'approval_level' => $requisition->approval_level,
                    'comments' => $comments,
                ]);

                // Update requisition
                $requisition->approval_status = 'ChangesRequested';
                $requisition->current_approver_id = null;
                $requisition->save();

                // Complete current task
                $this->completeApprovalTask($requisition, $user->id);

                // Create task for requester to edit
                $this->createEditTask($requisition);

                // Notify requester
                $this->sendChangesRequestedNotification($requisition, $comments);

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
            $requisition = Requisition::findOrFail($id);
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
                'delegate_to_user_id' => 'required|exists:users,id',
                'comments' => 'nullable|string',
            ]);

            DB::beginTransaction();
            try {
                // Reload to check current state
                $requisition->refresh();
                
                if ($requisition->approval_status !== 'Pending' || $requisition->current_approver_id !== $user->id) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Requisition status has changed. Please refresh and try again.',
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

                // Create audit log
                RequisitionApproval::create([
                    'requisition_id' => $requisition->id,
                    'approver_id' => $user->id,
                    'action' => 'Delegated',
                    'approval_level' => $requisition->approval_level,
                    'comments' => $comments,
                    'delegate_to' => $delegateToId,
                ]);

                // Update requisition
                $requisition->current_approver_id = $delegateToId;
                $requisition->save();

                // Complete current task
                $this->completeApprovalTask($requisition, $user->id);

                // Create task for delegate
                $this->createApprovalTask($requisition, $delegateToId);

                // Notify delegate
                $this->sendDelegationNotification($requisition, $delegateToId, $user->id);

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
                $query->where('current_approver_id', $user->id);
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
            $requisition = Requisition::with(['creator', 'currentApprover', 'attachments'])
                ->findOrFail($id);
            
            $user = Auth::user();

            // Verify user can view this approval
            if ($requisition->current_approver_id !== $user->id && !$this->hasHRAdminPermission()) {
                abort(403, 'You are not authorized to view this approval.');
            }

            // Get approval history
            $approvals = RequisitionApproval::where('requisition_id', $id)
                ->with(['approver', 'delegate'])
                ->orderBy('created_at', 'asc')
                ->get();

            return view('tenant.requisitions.approval-detail', compact('requisition', 'approvals'));
        } catch (\Exception $e) {
            Log::error('Failed to load approval detail', [
                'requisition_id' => $id,
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
            $requisition = Requisition::findOrFail($id);
            
            $approvals = RequisitionApproval::where('requisition_id', $id)
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
        // Current approver can approve
        if ($requisition->current_approver_id === $user->id) {
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
    private function createApprovalTask(Requisition $requisition, int $approverId): void
    {
        try {
            $tenant = tenant();
            $tenantSlug = $tenant ? $tenant->slug : 'tenant';
            
            $task = Task::create([
                'user_id' => $approverId,
                'task_type' => 'Requisition Approval',
                'title' => "Approve Requisition – {$requisition->job_title}",
                'requisition_id' => $requisition->id,
                'status' => 'Pending',
                'due_at' => now()->addDays(2), // Configurable: +2 days
                'link' => "/{$tenantSlug}/requisitions/{$requisition->id}/approval",
                'created_by' => Auth::id(),
            ]);

            // Send notification
            $this->sendTaskCreatedNotification($task);

            Log::info('Approval task created', [
                'task_id' => $task->id,
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to create approval task', [
                'requisition_id' => $requisition->id,
                'approver_id' => $approverId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
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
            
            $task = Task::create([
                'user_id' => $requisition->created_by,
                'task_type' => 'Requisition Edit Needed',
                'title' => "Update Requisition – {$requisition->job_title}",
                'requisition_id' => $requisition->id,
                'status' => 'Pending',
                'due_at' => now()->addDays(3),
                'link' => "/{$tenantSlug}/requisitions/{$requisition->id}/edit",
                'created_by' => Auth::id(),
            ]);

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
     * Send approval request notification
     */
    private function sendApprovalRequestNotification(Requisition $requisition, int $approverId): void
    {
        try {
            $approver = \App\Models\User::find($approverId);
            if (!$approver || !$approver->email) {
                return;
            }

            Mail::to($approver->email)->send(new RequisitionApprovalRequest($requisition));
            
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
}
