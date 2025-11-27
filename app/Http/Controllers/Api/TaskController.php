<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * List my tasks
     * GET /api/tasks/my
     */
    public function myTasks(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $query = Task::where('user_id', $user->id)
                ->with(['requisition', 'creator', 'assignee']);

            // Filter by status
            if ($request->filled('status')) {
                $status = $request->input('status');
                if (in_array($status, ['Pending', 'InProgress', 'Completed', 'Cancelled'], true)) {
                    $query->where('status', $status);
                }
            }

            // Filter by task type
            if ($request->filled('task_type')) {
                $query->where('task_type', $request->input('task_type'));
            }

            // Search by title or requisition ID
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhereHas('requisition', function ($rq) use ($search) {
                            $rq->where('id', 'like', "%{$search}%");
                        });
                });
            }

            // Sort
            $sortBy = $request->input('sort_by', 'due_at');
            $sortOrder = strtolower($request->input('sort_order', 'asc'));
            if (!in_array($sortOrder, ['asc', 'desc'], true)) {
                $sortOrder = 'asc';
            }

            if ($sortBy === 'due_at') {
                $query->orderBy('due_at', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('created_at', $sortOrder);
            } else {
                $query->orderBy('status')->orderBy('due_at', 'asc');
            }

            // Pagination
            $perPage = min((int) $request->input('per_page', 20), 100);
            $tasks = $query->paginate($perPage);

            Log::info('Tasks fetched', [
                'user_id' => $user->id,
                'total' => $tasks->total(),
            ]);

            return response()->json([
                'success' => true,
                'data' => $tasks->items(),
                'pagination' => [
                    'total' => $tasks->total(),
                    'per_page' => $tasks->perPage(),
                    'current_page' => $tasks->currentPage(),
                    'last_page' => $tasks->lastPage(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to fetch tasks', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks.',
            ], 500);
        }
    }

    /**
     * Get task details
     * GET /api/tasks/{id}
     */
    public function show($id)
    {
        try {
            $task = Task::with(['requisition', 'creator', 'assignee'])
                ->findOrFail($id);

            // Permission check
            $this->authorize('view', $task);

            return response()->json([
                'success' => true,
                'data' => $task,
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this task.',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Failed to fetch task', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task.',
            ], 500);
        }
    }

    /**
     * Start a task (mark as InProgress)
     * POST /api/tasks/{id}/start
     */
    public function start(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            // Permission check
            $this->authorize('update', $task);

            if ($task->status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending tasks can be started.',
                ], 400);
            }

            DB::beginTransaction();
            try {
                $task->status = 'InProgress';
                $task->save();

                DB::commit();

                Log::info('Task started', [
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Task started successfully.',
                    'data' => $task->fresh(),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this task.',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Failed to start task', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to start task.',
            ], 500);
        }
    }

    /**
     * Complete a task
     * POST /api/tasks/{id}/complete
     */
    public function complete(Request $request, $id)
    {
        try {
            $task = Task::findOrFail($id);

            // Permission check
            $this->authorize('update', $task);

            DB::beginTransaction();
            try {
                $task->status = 'Completed';
                $task->save();

                DB::commit();

                Log::info('Task completed', [
                    'task_id' => $task->id,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Task completed successfully.',
                    'data' => $task->fresh(),
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to update this task.',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Failed to complete task', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete task.',
            ], 500);
        }
    }

    /**
     * Reassign a task
     * POST /api/tasks/{id}/reassign
     */
    public function reassign(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer|exists:users,id',
            ]);

            $task = Task::findOrFail($id);

            // Permission check
            $this->authorize('reassign', $task);

            $newUserId = $request->input('user_id');

            DB::beginTransaction();
            try {
                $oldUserId = $task->user_id;
                $task->user_id = $newUserId;
                $task->save();

                // Send notification to new assignee
                $this->sendReassignmentNotification($task, $oldUserId, $newUserId);

                DB::commit();

                Log::info('Task reassigned', [
                    'task_id' => $task->id,
                    'from_user_id' => $oldUserId,
                    'to_user_id' => $newUserId,
                    'reassigned_by' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Task reassigned successfully.',
                    'data' => $task->fresh(['assignee']),
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
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to reassign this task.',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Failed to reassign task', [
                'task_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to reassign task.',
            ], 500);
        }
    }

    /**
     * Send reassignment notification
     */
    private function sendReassignmentNotification(Task $task, $oldUserId, $newUserId): void
    {
        try {
            $newAssignee = \App\Models\User::find($newUserId);
            if (!$newAssignee || !$newAssignee->email) {
                return;
            }

            // Queue email notification
            \Illuminate\Support\Facades\Mail::to($newAssignee->email)->queue(
                new \App\Mail\TaskReassigned($task, $oldUserId, $newUserId)
            );

            Log::info('Task reassignment notification sent', [
                'task_id' => $task->id,
                'to_user_id' => $newUserId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send reassignment notification', [
                'task_id' => $task->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}

