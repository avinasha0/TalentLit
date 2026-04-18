<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskController extends Controller
{
    /**
     * Extract task ID from route parameters (handles subdomain route parameter binding issues)
     */
    private function extractTaskId($methodParam, Request $request): ?int
    {
        $routeParams = $request->route()->parameters();
        $taskId = null;
        
        // First, try to get from route parameters array (most reliable)
        if (isset($routeParams['id']) && is_numeric($routeParams['id'])) {
            $taskId = (int) $routeParams['id'];
        } else {
            // Fallback: check if route('id') works
            $routeId = $request->route('id');
            if ($routeId && is_numeric($routeId)) {
                $taskId = (int) $routeId;
            }
        }
        
        // For subdomain routes, Laravel swaps parameters, so check method parameter
        // If method parameter is numeric, it's likely the actual task ID
        if (!$taskId || !is_numeric($taskId)) {
            if (is_numeric($methodParam)) {
                $taskId = (int) $methodParam;
            }
        }
        
        return $taskId && $taskId > 0 ? $taskId : null;
    }
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
                ->with([
                    'requisition' => function ($q) {
                        // Requisition is tenant-scoped, so this will automatically filter by tenant
                        // If requisition doesn't exist or belongs to different tenant, it will be null
                    },
                    'jobOpening:id,title,tenant_id',
                    'creator',
                    'assignee',
                ]);

            if ($tenantId = tenant_id()) {
                $query->where('tenant_id', $tenantId);
            }

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
                        ->orWhere('requisition_id', 'like', "%{$search}%")
                        ->orWhere('job_opening_id', 'like', "%{$search}%")
                        ->orWhereHas('requisition', function ($rq) use ($search) {
                            // Requisition is tenant-scoped, so this will only search within current tenant
                            $rq->where('id', 'like', "%{$search}%");
                        })
                        ->orWhereHas('jobOpening', function ($jq) use ($search) {
                            $jq->where('title', 'like', "%{$search}%");
                        });
                });
            }

            // Sort
            $sortBy = $request->input('sort_by', 'created_at'); // Default to created_at instead of due_at
            $sortOrder = strtolower($request->input('sort_order', 'asc'));
            if (!in_array($sortOrder, ['asc', 'desc'], true)) {
                $sortOrder = 'asc';
            }

            // Check if due_at column exists (cache check per request)
            static $hasDueAtColumn = null;
            if ($hasDueAtColumn === null) {
                $hasDueAtColumn = Schema::hasColumn('tasks', 'due_at');
            }

            if ($sortBy === 'due_at' && $hasDueAtColumn) {
                // Handle null values - put nulls last
                $query->orderByRaw('due_at IS NULL')
                      ->orderBy('due_at', $sortOrder);
            } elseif ($sortBy === 'due_at' && !$hasDueAtColumn) {
                // Column doesn't exist, fallback to created_at
                Log::warning('due_at column not found in tasks table, falling back to created_at');
                $query->orderBy('created_at', $sortOrder);
            } elseif ($sortBy === 'created_at') {
                $query->orderBy('created_at', $sortOrder);
            } else {
                $query->orderBy('status');
                if ($hasDueAtColumn) {
                    $query->orderByRaw('due_at IS NULL')
                          ->orderBy('due_at', 'asc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
            }

            // Pagination
            $perPage = min((int) $request->input('per_page', 20), 100);
            $tasks = $query->paginate($perPage);

            // Extract task IDs and check structure for logging
            $taskIds = [];
            $taskData = [];
            
            if ($tasks->items()) {
                foreach ($tasks->items() as $task) {
                    $taskIds[] = $task->id;
                    $taskData[] = [
                        'id' => $task->id,
                        'title' => $task->title,
                        'user_id' => $task->user_id,
                        'status' => $task->status,
                    ];
                }
            }

            Log::info('Tasks fetched', [
                'user_id' => $user->id,
                'total' => $tasks->total(),
                'task_ids' => $taskIds,
                'count' => count($taskIds),
                'sample_task' => $taskData[0] ?? null,
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
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get task details
     * GET /api/tasks/{id}
     */
    public function show($id, Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            // Extract task ID from route parameters (handles subdomain route binding issues)
            $taskId = $this->extractTaskId($id, $request);
            
            if (!$taskId) {
                $routeParams = $request->route()->parameters();
                Log::warning('Invalid task ID - route parameter binding issue', [
                    'method_param_id' => $id,
                    'method_param_type' => gettype($id),
                    'route_params' => $routeParams,
                    'route_id' => $request->route('id'),
                    'request_url' => $request->fullUrl(),
                    'user_id' => $user->id,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid task ID format: ' . $id,
                    'debug' => [
                        'method_param' => $id,
                        'route_params' => $routeParams,
                    ],
                ], 400);
            }

            Log::info('Fetching task details', [
                'task_id' => $taskId,
                'method_param_id' => $id,
                'user_id' => $user->id,
            ]);

            // First check if task exists at all
            $task = Task::find($taskId);
            
            if (!$task) {
                // Also check if any tasks exist for debugging
                $totalTasks = Task::count();
                $userTasks = Task::where('user_id', $user->id)->count();
                
                Log::warning('Task not found in database', [
                    'task_id' => $taskId,
                    'user_id' => $user->id,
                    'total_tasks' => $totalTasks,
                    'user_tasks_count' => $userTasks,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Task not found.',
                ], 404);
            }

            // Load relationships
            $task->load([
                'requisition' => function ($q) {
                    // Requisition is tenant-scoped, so this will automatically filter by tenant
                    // If requisition doesn't exist or belongs to different tenant, it will be null
                },
                'jobOpening:id,title,tenant_id',
                'creator:id,name,email',
                'assignee:id,name,email',
            ]);

            // Permission check
            $this->authorize('view', $task);

            // Ensure title is set (fallback if null)
            if (empty($task->title)) {
                $task->title = 'Untitled Task';
            }

            Log::info('Task fetched successfully', [
                'task_id' => $taskId,
                'user_id' => $user->id,
                'task_user_id' => $task->user_id,
                'task_title' => $task->title,
                'created_by' => $task->created_by,
                'creator_name' => $task->creator ? $task->creator->name : 'N/A',
                'assignee_name' => $task->assignee ? $task->assignee->name : 'N/A',
            ]);

            return response()->json([
                'success' => true,
                'data' => $task,
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::warning('Unauthorized task access attempt', [
                'task_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to view this task.',
            ], 403);
        } catch (\Exception $e) {
            Log::error('Failed to fetch task', [
                'task_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch task: ' . $e->getMessage(),
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
            $taskId = $this->extractTaskId($id, $request);
            if (!$taskId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid task ID.',
                ], 400);
            }
            
            $task = Task::findOrFail($taskId);

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
            $taskId = $this->extractTaskId($id, $request);
            if (!$taskId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid task ID.',
                ], 400);
            }
            
            $task = Task::findOrFail($taskId);

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

            $taskId = $this->extractTaskId($id, $request);
            if (!$taskId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid task ID.',
                ], 400);
            }
            
            $task = Task::findOrFail($taskId);

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

