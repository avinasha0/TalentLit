<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use App\Models\AssetRequest;
use App\Services\OnboardingViewLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class EmployeeOnboardingController extends Controller
{
    public function index(Request $request, string $tenant = null)
    {
        // Get the tenant model from the middleware
        $tenantModel = tenant();
        
        // If tenant is null, it means we're using subdomain routing
        // The tenant is already set by the middleware
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        // Check if using subdomain routing
        $currentRoute = request()->route()->getName();
        $isSubdomain = str_starts_with($currentRoute, 'subdomain.');
        
        // Redirect to All Onboardings by default
        if ($isSubdomain) {
            return redirect()->route('subdomain.employee-onboarding.all');
        } else {
            return redirect()->route('tenant.employee-onboarding.all', $tenantModel->slug);
        }
    }

    public function all(Request $request, string $tenant = null)
    {
        // Get tenant from middleware (tenant is resolved from route parameter)
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        // Log Page.View event
        try {
            $queryParams = $request->only(['search', 'department', 'manager', 'status', 'joiningMonth']);
            OnboardingViewLogService::log(
                'Page.View',
                $request,
                array_filter($queryParams), // Only include non-empty query params
                null,
                'page'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the page
            Log::warning('Failed to log Page.View event', ['error' => $e->getMessage()]);
        }
        
        // Fetch onboarding candidates from candidates table (same approach as API)
        $query = Candidate::where(function($q) use ($tenantModel) {
            if ($tenantModel) {
                $q->where('tenant_id', $tenantModel->id);
            }
        })
        ->where(function($q) {
            $q->where('source', 'Onboarding')
              ->orWhere('source', 'Onboarding Import');
        });
        
        $candidates = $query->orderBy('created_at', 'desc')->get();
        
        // Transform candidates to match view expectations
        $onboardings = $candidates->map(function($candidate) {
            // Get actual values from database, with fallback defaults
            $designation = $candidate->designation ?? 'Not Assigned';
            $department = $candidate->department ?? 'Not Assigned';
            $joiningDate = $candidate->joining_date ?? $candidate->created_at;
            
            // Calculate progress from completed_steps and total_steps
            $completed = intval($candidate->completed_steps ?? 0);
            $total = intval($candidate->total_steps ?? 5);
            $progressPercent = $total > 0 ? intval(($completed / $total) * 100) : 0;
            
            // Status mapping - use database status or calculate from progress
            $status = $candidate->status;
            if (!$status) {
                if ($progressPercent >= 100) {
                    $status = 'Completed';
                } elseif ($progressPercent >= 80) {
                    $status = 'Joining Soon';
                } elseif ($progressPercent >= 50) {
                    $status = 'IT Pending';
                } elseif ($progressPercent >= 20) {
                    $status = 'Pending Docs';
                } else {
                    $status = 'Pre-boarding';
                }
            }
            
            // Create a simple object that matches view expectations
            return (object) [
                'id' => $candidate->id,
                'first_name' => $candidate->first_name ?? '',
                'last_name' => $candidate->last_name ?? '',
                'email' => $candidate->primary_email ?? '',
                'role' => $designation,
                'department' => $department,
                'joining_date' => $joiningDate,
                'progress' => $progressPercent . '%',
                'status' => $status,
            ];
        });
        
        return view('tenant.employee-onboarding.all', compact('onboardings', 'tenantModel'));
    }

    public function dashboard(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }

        // Check RBAC - only Owner, Admin, Recruiter, Hiring Manager can access
        $user = auth()->user();
        $permissionService = app(\App\Services\PermissionService::class);
        $userRole = $permissionService->getUserRole($user->id, $tenantModel->id);
        
        $allowedRoles = ['Owner', 'Admin', 'Recruiter', 'Hiring Manager'];
        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        // Log dashboard view
        try {
            $queryParams = $request->only(['status', 'department', 'manager', 'dateRange']);
            $currentRoute = request()->route()->getName();
            $isSubdomain = str_starts_with($currentRoute ?? '', 'subdomain.');
            \Log::info('Onboarding.Dashboard.View', [
                'user' => $user->id,
                'tenant' => $tenantModel->id,
                'tenant_slug' => $tenantModel->slug,
                'role' => $userRole,
                'filters' => json_encode(array_filter($queryParams)),
                'is_subdomain' => $isSubdomain,
            ]);
        } catch (\Exception $e) {
            \Log::warning('Failed to log dashboard view', ['error' => $e->getMessage()]);
        }

        return view('tenant.employee-onboarding.dashboard', [
            'tenantModel' => $tenantModel,
            'userRole' => $userRole,
            'isSubdomain' => $isSubdomain ?? false,
        ]);
    }

    public function new(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.new', [
            'tenant' => $tenantModel,
        ]);
    }

    public function tasks(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.tasks', [
            'tenant' => $tenantModel,
        ]);
    }

    public function documents(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.documents', [
            'tenant' => $tenantModel,
        ]);
    }

    public function itAssets(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.it-assets', [
            'tenant' => $tenantModel,
        ]);
    }

    public function approvals(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.approvals', [
            'tenant' => $tenantModel,
        ]);
    }

    /**
     * API: Get all onboardings with filters and pagination
     */
    public function apiIndex(Request $request)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Check if using mock data (set USE_MOCK_ONBOARDING_DATA=true in .env)
        $useMock = env('USE_MOCK_ONBOARDING_DATA', false);
        
        if ($useMock) {
            return $this->getMockOnboardings($request);
        }

        // Use Candidate model since Onboarding uses candidates table
        $query = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            });
        
        // Log for debugging
        $countBeforeFilters = $query->count();
        
        // Also log all candidates with their sources for debugging
        $allCandidates = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->select('id', 'first_name', 'last_name', 'primary_email', 'source', 'created_at', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        
        \Log::info('Employee Onboarding API Query', [
            'tenant_id' => $tenantModel->id,
            'tenant_slug' => $tenantModel->slug,
            'count_before_filters' => $countBeforeFilters,
            'request_params' => $request->all(),
            'recent_candidates' => $allCandidates->map(function($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->first_name . ' ' . $c->last_name,
                    'email' => $c->primary_email,
                    'source' => $c->source,
                    'created_at' => $c->created_at,
                    'updated_at' => $c->updated_at,
                ];
            })->toArray(),
        ]);

        // SEARCH (name, email)
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('primary_email', 'like', "%{$search}%");
            });
        }

        // SORTING
        $allowedSort = ['created_at', 'first_name'];
        $sortBy = in_array($request->input('sortBy'), $allowedSort) ? $request->input('sortBy') : 'created_at';
        if ($request->input('sortBy') === 'joiningDate') {
            $sortBy = 'created_at';
        }
        $sortDir = $request->input('sortDir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Fetch all records (we'll paginate after filtering)
        $allOnboardings = $query->get();

        // Transform candidates to onboarding format
        $allOnboardings->transform(function($item) {
            // Set default values for non-existent columns
            $item->designation = $item->designation ?? 'Not Assigned';
            $item->department = $item->department ?? 'Not Assigned';
            $item->manager = $item->manager ?? 'Not Assigned';
            $item->joining_date = $item->created_at; // Use created_at as joining_date
            
            // Calculate progress
            $completed = intval($item->completed_steps ?? 0);
            $total = intval($item->total_steps ?? 5); // default 5 steps
            $item->progress_percent = $total > 0 ? intval(($completed / $total) * 100) : 0;

            // Status mapping
            if ($item->progress_percent >= 100) {
                $item->status = $item->status ?? 'Completed';
            } else {
                $item->status = $item->status ?? 'Pre-boarding';
            }

            return $item;
        });

        // Apply in-memory filters
        $filteredCollection = $allOnboardings;
        
        if ($department = $request->input('department')) {
            if ($department !== 'all' && $department !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($department) {
                    return ($item->department ?? 'Not Assigned') === $department;
                });
            }
        }
        
        if ($manager = $request->input('manager')) {
            if ($manager !== 'all' && $manager !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($manager) {
                    return ($item->manager ?? 'Not Assigned') === $manager;
                });
            }
        }
        
        if ($status = $request->input('status')) {
            if ($status !== 'All' && $status !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($status) {
                    return ($item->status ?? 'Pre-boarding') === $status;
                });
            }
        }
        
        if ($joiningMonth = $request->input('joiningMonth')) {
            try {
                [$year, $month] = explode('-', $joiningMonth);
                $filteredCollection = $filteredCollection->filter(function($item) use ($year, $month) {
                    $date = $item->joining_date ?? $item->created_at;
                    if ($date instanceof \Carbon\Carbon) {
                        return $date->year == $year && $date->month == $month;
                    }
                    return false;
                });
            } catch (\Exception $e) {}
        }

        // Pagination
        $page = (int) $request->input('page', 1);
        $pageSize = (int) $request->input('pageSize', 10);
        $total = $filteredCollection->count();
        $items = $filteredCollection->forPage($page, $pageSize)->values();

        // Format data for JavaScript
        $formattedData = $items->map(function($item) {
            return [
                'id' => $item->id,
                'firstName' => $item->first_name,
                'lastName' => $item->last_name,
                'fullName' => $item->full_name ?? trim($item->first_name . ' ' . $item->last_name),
                'email' => $item->primary_email,
                'avatarUrl' => null,
                'designation' => $item->designation ?? 'Not Assigned',
                'department' => $item->department ?? 'Not Assigned',
                'manager' => $item->manager ?? 'Not Assigned',
                'joiningDate' => $item->joining_date ? $item->joining_date->format('Y-m-d') : ($item->created_at ? $item->created_at->format('Y-m-d') : ''),
                'progressPercent' => $item->progress_percent ?? 0,
                'pendingItems' => max(0, 5 - intval($item->completed_steps ?? 0)),
                'status' => $item->status ?? 'Pre-boarding',
                'lastUpdated' => $item->updated_at ? $item->updated_at->toIso8601String() : ($item->created_at ? $item->created_at->toIso8601String() : '')
            ];
        });

        return response()->json([
            'data' => $formattedData->toArray(),
            'meta' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'total' => $total,
            ],
        ]);
    }

    /**
     * API: Get single onboarding details
     */
    public function apiShow(Request $request, $id = null)
    {
        // Get ID from route parameter first (method injection is getting wrong value)
        // Route parameter is the most reliable source
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        \Log::info('apiShow called', [
            'method_param_id' => $id,
            'route_id' => $request->route('id'),
            'request_id' => $request->input('id'),
            'final_candidate_id' => $candidateId,
            'request_url' => $request->fullUrl(),
            'request_path' => $request->path(),
            'route_params' => $request->route()->parameters(),
            'tenant_id' => tenant()?->id,
            'tenant_slug' => tenant()?->slug
        ]);
        
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            \Log::error('Tenant not resolved in apiShow');
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }
        
        // Log Onboarding.SlideOver.Open event
        try {
            OnboardingViewLogService::log(
                'Onboarding.SlideOver.Open',
                $request,
                [],
                $candidateId,
                'onboarding'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log SlideOver.Open event', ['error' => $e->getMessage()]);
        }

        // First, try to find the candidate without source filter (in case source wasn't set)
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)->find($candidateId);
        
        // If found but doesn't have onboarding source, still allow it (might be a data issue)
        if ($candidate && !in_array($candidate->source, ['Onboarding', 'Onboarding Import'])) {
            \Log::info('Candidate found but source is not Onboarding', [
                'id' => $candidateId,
                'source' => $candidate->source,
                'allowing anyway'
            ]);
        }
        
        // If not found, try with source filter
        if (!$candidate) {
            $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->find($candidateId);
        }
        
        \Log::info('Candidate lookup result', [
            'id' => $candidateId,
            'found' => !!$candidate,
            'candidate_id' => $candidate?->id,
            'candidate_source' => $candidate?->source,
            'candidate_name' => $candidate ? ($candidate->first_name . ' ' . $candidate->last_name) : null,
            'tenant_id' => $tenantModel->id,
            'total_candidates' => \App\Models\Candidate::where('tenant_id', $tenantModel->id)->count(),
            'total_onboarding_candidates' => \App\Models\Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })->count()
        ]);
        
        if (!$candidate) {
            \Log::warning('Candidate not found', [
                'id' => $candidateId,
                'tenant_id' => $tenantModel->id,
                'tenant_slug' => $tenantModel->slug,
                'sample_candidate_ids' => \App\Models\Candidate::where('tenant_id', $tenantModel->id)
                    ->limit(5)->pluck('id')->toArray()
            ]);
            return response()->json(['error' => 'Onboarding not found', 'id' => $candidateId], 404);
        }

        // Calculate progress
        $completed = intval($candidate->completed_steps ?? 0);
        $total = intval($candidate->total_steps ?? 5);
        $progressPercent = $total > 0 ? intval(($completed / $total) * 100) : 0;

        // Format response
        $onboarding = [
            'id' => $candidate->id,
            'firstName' => $candidate->first_name ?? '',
            'lastName' => $candidate->last_name ?? '',
            'email' => $candidate->primary_email ?? '',
            'phone' => $candidate->primary_phone ?? null,
            'designation' => $candidate->designation ?? 'Not Assigned',
            'department' => $candidate->department ?? 'Not Assigned',
            'manager' => $candidate->manager ?? 'Not Assigned',
            'joiningDate' => $candidate->joining_date ? $candidate->joining_date->format('Y-m-d') : null,
            'status' => $candidate->status ?? 'Pre-boarding',
            'progressPercent' => $progressPercent,
        ];

        return response()->json($onboarding);
    }

    /**
     * API: Bulk send reminders
     */
    public function apiBulkRemind(Request $request)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        // TODO: Implement actual reminder sending
        // For now, just return success
        return response()->json(['ok' => true]);
    }

    /**
     * API: Send reminder to a single candidate
     */
    public function apiSendReminder(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        try {
            // TODO: Implement actual reminder sending logic
            // For now, this is a placeholder that returns success
            // The reminder message should be: "Please complete your pending onboarding steps."
            
            // Example implementation (commented out until email service is configured):
            // Mail::to($candidate->primary_email)->send(new OnboardingReminderMail($candidate));
            
            Log::info('Onboarding reminder sent', [
                'candidate_id' => $candidateId,
                'tenant_id' => $tenantModel->id,
                'email' => $candidate->primary_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reminder sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send onboarding reminder', [
                'candidate_id' => $candidateId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send reminder'
            ], 500);
        }
    }

    /**
     * API: Get documents for a candidate
     */
    public function apiGetDocuments(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }
        
        // Log Onboarding.Tab.View event
        try {
            OnboardingViewLogService::log(
                'Onboarding.Tab.View',
                $request,
                ['tab' => 'Documents'],
                $candidateId,
                'tab'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log Tab.View event', ['error' => $e->getMessage()]);
        }

        // For now, return mock document data
        // In a real implementation, this would fetch from a documents table
        $documents = [
            [
                'id' => 'doc-1',
                'name' => 'ID Proof',
                'status' => 'Uploaded',
                'uploaded_at' => now()->subDays(2)->toIso8601String(),
                'file_url' => '#'
            ],
            [
                'id' => 'doc-2',
                'name' => 'Address Proof',
                'status' => 'Pending',
                'uploaded_at' => null,
                'file_url' => null
            ],
            [
                'id' => 'doc-3',
                'name' => 'Educational Certificates',
                'status' => 'Missing',
                'uploaded_at' => null,
                'file_url' => null
            ],
            [
                'id' => 'doc-4',
                'name' => 'Employment Contract',
                'status' => 'Uploaded',
                'uploaded_at' => now()->subDays(1)->toIso8601String(),
                'file_url' => '#'
            ],
            [
                'id' => 'doc-5',
                'name' => 'Medical Certificate',
                'status' => 'Pending',
                'uploaded_at' => null,
                'file_url' => null
            ]
        ];

        return response()->json([
            'documents' => $documents
        ]);
    }

    /**
     * API: Get tasks for a candidate
     */
    public function apiGetTasks(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Log Onboarding.Tab.View event
        try {
            OnboardingViewLogService::log(
                'Onboarding.Tab.View',
                $request,
                ['tab' => 'Tasks'],
                $candidateId,
                'tab'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log Tab.View event', ['error' => $e->getMessage()]);
        }

        // For now, return mock task data
        // In a real implementation, this would fetch from a tasks table
        $tasks = [
            [
                'id' => 'task-1',
                'title' => 'Complete background verification',
                'assigned_to' => 'HR Team',
                'due_date' => now()->addDays(5)->toIso8601String(),
                'status' => 'Pending'
            ],
            [
                'id' => 'task-2',
                'title' => 'Submit bank account details',
                'assigned_to' => 'Finance Team',
                'due_date' => now()->addDays(3)->toIso8601String(),
                'status' => 'Pending'
            ],
            [
                'id' => 'task-3',
                'title' => 'Attend orientation session',
                'assigned_to' => 'People Ops',
                'due_date' => now()->addDays(7)->toIso8601String(),
                'status' => 'Completed'
            ],
            [
                'id' => 'task-4',
                'title' => 'Complete IT setup form',
                'assigned_to' => 'IT Team',
                'due_date' => now()->addDays(2)->toIso8601String(),
                'status' => 'Pending'
            ],
            [
                'id' => 'task-5',
                'title' => 'Review employee handbook',
                'assigned_to' => 'HR Team',
                'due_date' => now()->addDays(4)->toIso8601String(),
                'status' => 'Pending'
            ]
        ];

        return response()->json([
            'tasks' => $tasks
        ]);
    }

    /**
     * API: Mark task as completed
     */
    public function apiMarkTaskComplete(Request $request, $id = null, $taskId = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get IDs from route parameters
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        $taskIdParam = $request->route('taskId') ?? $request->input('taskId') ?? $taskId;
        
        if (!$candidateId || !$taskIdParam) {
            return response()->json(['error' => 'Candidate ID and Task ID are required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Detect tenant format for logging
        $isSubdomain = str_starts_with(request()->route()->getName() ?? '', 'subdomain.');
        $tenantFormat = $isSubdomain ? 'subdomain' : 'slug';
        
        try {
            // TODO: Implement actual task completion logic
            // For now, this is a placeholder that returns success
            // In a real implementation, this would update a tasks table
            
            // Log task completion
            Log::info('Onboarding task marked complete', [
                'candidate_id' => $candidateId,
                'task_id' => $taskIdParam,
                'tenant_id' => $tenantModel->id,
                'tenant_slug' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'operation' => 'mark_task_complete',
                'status' => 'success'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Task marked as completed'
            ]);
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to mark task complete', [
                'candidate_id' => $candidateId,
                'task_id' => $taskIdParam,
                'tenant_id' => $tenantModel->id,
                'tenant_slug' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'operation' => 'mark_task_complete',
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to mark task as completed'
            ], 500);
        }
    }

    /**
     * API: Send document-specific reminder
     */
    public function apiSendDocumentReminder(Request $request, $id = null, $documentId = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get IDs from route parameters
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        $docId = $request->route('documentId') ?? $request->input('documentId') ?? $documentId;
        $documentName = $request->input('document_name', 'document');
        
        if (!$candidateId || !$docId) {
            return response()->json(['error' => 'Candidate ID and Document ID are required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        try {
            // TODO: Implement actual reminder sending logic
            // The reminder message should be: "Please upload your pending document: {document_name}."
            
            // Example implementation (commented out until email service is configured):
            // $message = "Please upload your pending document: {$documentName}.";
            // Mail::to($candidate->primary_email)->send(new DocumentReminderMail($candidate, $documentName, $message));
            
            Log::info('Document reminder sent', [
                'candidate_id' => $candidateId,
                'document_id' => $docId,
                'document_name' => $documentName,
                'tenant_id' => $tenantModel->id,
                'email' => $candidate->primary_email
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Reminder sent successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send document reminder', [
                'candidate_id' => $candidateId,
                'document_id' => $docId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to send reminder'
            ], 500);
        }
    }

    /**
     * API: Check preconditions for converting onboarding to employee
     */
    public function apiCheckConvert(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Check preconditions
        $preconditionsData = $this->checkPreconditions($candidate);

        return response()->json($preconditionsData);
    }

    /**
     * API: Convert onboarding to employee
     */
    public function apiConvert(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            Log::error('Convert.Error', [
                'candidateID' => $id,
                'tenant' => 'unknown',
                'error' => 'Tenant not resolved'
            ]);
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            Log::error('Convert.Error', [
                'candidateID' => 'missing',
                'tenant' => $tenantModel->slug,
                'error' => 'Candidate ID is required'
            ]);
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            Log::error('Convert.Error', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'error' => 'Candidate not found'
            ]);
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Check permissions
        $user = auth()->user();
        if (!$this->hasConvertPermission($user, $tenantModel)) {
            Log::warning('Convert.Unauthorized', [
                'user' => $user->id,
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug
            ]);
            return response()->json(['error' => 'Unauthorized. You do not have permission to convert onboardings.'], 403);
        }

        // Check preconditions
        $preconditionsData = $this->checkPreconditions($candidate);
        
        if (!$preconditionsData['canConvert']) {
            $missing = [];
            if ($preconditionsData['missingProgress']) $missing[] = 'progress incomplete';
            if ($preconditionsData['missingApprovals'] > 0) $missing[] = "{$preconditionsData['missingApprovals']} approval(s) pending";
            if ($preconditionsData['missingDocuments'] > 0) $missing[] = "{$preconditionsData['missingDocuments']} document(s) pending";
            if ($preconditionsData['missingAssets'] > 0) $missing[] = "{$preconditionsData['missingAssets']} asset(s) pending";
            
            Log::info('Convert.Blocked', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'missing' => implode(', ', $missing)
            ]);
            
            return response()->json([
                'error' => 'Preconditions not met. Cannot convert.',
                'missing' => $missing
            ], 400);
        }

        // Log conversion attempt
        Log::info('Convert.Attempt', [
            'candidateID' => $candidateId,
            'tenant' => $tenantModel->slug,
            'user' => $user->id,
            'preconditions' => 'passed'
        ]);

        try {
            // Create employee (User) from candidate
            $employee = \App\Models\User::create([
                'name' => trim(($candidate->first_name ?? '') . ' ' . ($candidate->last_name ?? '')),
                'email' => $candidate->primary_email,
                'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(12)), // Temporary password
                'email_verified_at' => null, // Will need email verification
            ]);

            // Add user to tenant
            $employee->tenants()->attach($tenantModel->id);

            // Assign default employee role if exists (or basic permissions)
            // For now, we'll just mark the candidate as converted
            $candidate->update([
                'status' => 'Converted',
                'source' => 'Onboarding Converted' // Mark as converted
            ]);

            // Log success
            Log::info('Convert.Success', [
                'candidateID' => $candidateId,
                'employeeID' => $employee->id,
                'tenant' => $tenantModel->slug,
                'user' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'employeeId' => $employee->id,
                'message' => 'Onboarding converted to employee successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Convert.Error', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'user' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Conversion failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper: Check preconditions for conversion
     */
    private function checkPreconditions($candidate)
    {
        // Check progress
        $completed = intval($candidate->completed_steps ?? 0);
        $total = intval($candidate->total_steps ?? 5);
        $progressPercent = $total > 0 ? intval(($completed / $total) * 100) : 0;
        $isProgressComplete = $progressPercent >= 100;

        // Check approvals
        $approvals = $this->getApprovalsForCandidate($candidate->id);
        $missingApprovals = 0;
        foreach ($approvals as $approval) {
            if ($approval['status'] !== 'Approved') {
                $missingApprovals++;
            }
        }

        // Check documents
        $documents = $this->getDocumentsForCandidate($candidate->id);
        $mandatoryDocs = array_filter($documents, function($doc) {
            return in_array($doc['name'], ['ID Proof', 'Address Proof', 'Educational Certificates', 'Employment Contract']);
        });
        $missingDocuments = 0;
        foreach ($mandatoryDocs as $doc) {
            if (!in_array($doc['status'], ['Uploaded', 'Verified'])) {
                $missingDocuments++;
            }
        }

        // Check IT assets
        $assets = $this->getAssetRequestsForCandidate($candidate->id);
        $criticalAssets = array_filter($assets, function($asset) {
            return in_array($asset['asset_type'], ['Laptop', 'Access Card']); // Critical assets
        });
        $missingAssets = 0;
        foreach ($criticalAssets as $asset) {
            if (!in_array($asset['status'], ['Approved', 'Assigned'])) {
                $missingAssets++;
            }
        }

        $canConvert = $isProgressComplete && $missingApprovals === 0 && $missingDocuments === 0 && $missingAssets === 0;

        return [
            'canConvert' => $canConvert,
            'missingProgress' => !$isProgressComplete,
            'missingApprovals' => $missingApprovals,
            'missingDocuments' => $missingDocuments,
            'missingAssets' => $missingAssets,
        ];
    }

    /**
     * Helper: Check if user has convert permission
     */
    private function hasConvertPermission($user, $tenant)
    {
        // Check custom permissions
        $userRole = \Illuminate\Support\Facades\DB::table('custom_user_roles')
            ->where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->value('role_name');

        if (!$userRole) {
            return false;
        }

        $rolePermissions = \Illuminate\Support\Facades\DB::table('custom_tenant_roles')
            ->where('tenant_id', $tenant->id)
            ->where('name', $userRole)
            ->value('permissions');

        if (!$rolePermissions) {
            return false;
        }

        $permissions = json_decode($rolePermissions, true);
        
        // Check for explicit permission or HR/Admin roles
        return in_array('can_convert_onboarding', $permissions) 
            || in_array($userRole, ['Owner', 'Admin']) 
            || in_array('manage_users', $permissions);
    }

    /**
     * Helper: Get approvals for candidate
     */
    private function getApprovalsForCandidate($candidateId)
    {
        // Use the same logic as apiGetApprovals but return data directly
        // For now, return mock data - in production, fetch from approvals table
        $mockApprovals = [
            [
                'id' => 'approval-1',
                'step_name' => 'HR Manager Approval',
                'approver_name' => 'Sarah Johnson',
                'status' => 'Approved',
                'timestamp' => now()->subDays(2)->toIso8601String(),
                'comments' => 'All documents verified. Approved for onboarding.'
            ],
            [
                'id' => 'approval-2',
                'step_name' => 'Department Head Approval',
                'approver_name' => 'Michael Chen',
                'status' => 'Approved',
                'timestamp' => now()->subDays(1)->toIso8601String(),
                'comments' => 'Role requirements confirmed.'
            ],
            [
                'id' => 'approval-3',
                'step_name' => 'Finance Approval',
                'approver_name' => 'Emily Davis',
                'status' => 'Pending',
                'timestamp' => null,
                'comments' => ''
            ],
        ];
        
        // TODO: Replace with actual database query when approvals table exists
        // $approvals = Approval::where('candidate_id', $candidateId)->get();
        // return $approvals->toArray();
        
        return $mockApprovals;
    }

    /**
     * Helper: Get documents for candidate
     */
    private function getDocumentsForCandidate($candidateId)
    {
        // Use the same logic as apiGetDocuments but return data directly
        // For now, return mock data - in production, fetch from documents table
        $mockDocuments = [
            [
                'id' => 'doc-1',
                'name' => 'ID Proof',
                'status' => 'Uploaded',
                'uploaded_at' => now()->subDays(2)->toIso8601String(),
                'file_url' => '#'
            ],
            [
                'id' => 'doc-2',
                'name' => 'Address Proof',
                'status' => 'Pending',
                'uploaded_at' => null,
                'file_url' => null
            ],
            [
                'id' => 'doc-3',
                'name' => 'Educational Certificates',
                'status' => 'Missing',
                'uploaded_at' => null,
                'file_url' => null
            ],
            [
                'id' => 'doc-4',
                'name' => 'Employment Contract',
                'status' => 'Uploaded',
                'uploaded_at' => now()->subDays(1)->toIso8601String(),
                'file_url' => '#'
            ],
        ];
        
        // TODO: Replace with actual database query when documents table exists
        // $documents = Document::where('candidate_id', $candidateId)->get();
        // return $documents->toArray();
        
        return $mockDocuments;
    }

    /**
     * Helper: Get asset requests for candidate
     */
    private function getAssetRequestsForCandidate($candidateId)
    {
        $tenantModel = tenant();
        if (!$tenantModel) {
            return [];
        }

        $assetRequests = AssetRequest::where('tenant_id', $tenantModel->id)
            ->where('candidate_id', $candidateId)
            ->get();

        return $assetRequests->map(function($asset) {
            return [
                'id' => $asset->id,
                'asset_type' => $asset->asset_type,
                'status' => $asset->status,
            ];
        })->toArray();
    }

    /**
     * API: Export CSV of all filtered onboardings
     */
    public function apiExportCSV(Request $request)
    {
        \Log::info('CSV Export: Method called', [
            'request_params' => $request->all(),
            'url' => $request->fullUrl()
        ]);
        
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            \Log::error('CSV Export: Tenant not resolved');
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }
        
        \Log::info('CSV Export: Tenant resolved', [
            'tenant_id' => $tenantModel->id,
            'tenant_slug' => $tenantModel->slug
        ]);

        // Use Candidate model since Onboarding uses candidates table
        $query = \App\Models\Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            });
        
        // SEARCH (name, email)
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('primary_email', 'like', "%{$search}%");
            });
        }

        // SORTING
        $allowedSort = ['created_at', 'first_name'];
        $sortBy = in_array($request->input('sortBy'), $allowedSort) ? $request->input('sortBy') : 'created_at';
        if ($request->input('sortBy') === 'joiningDate') {
            $sortBy = 'created_at';
        }
        $sortDir = $request->input('sortDir') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Fetch all records (no pagination for export)
        $allOnboardings = $query->get();

        // Transform candidates to onboarding format
        $allOnboardings->transform(function($item) {
            // Set default values for non-existent columns
            $item->designation = $item->designation ?? 'Not Assigned';
            $item->department = $item->department ?? 'Not Assigned';
            $item->manager = $item->manager ?? 'Not Assigned';
            $item->joining_date = $item->joining_date ?? $item->created_at;
            
            // Calculate progress
            $completed = intval($item->completed_steps ?? 0);
            $total = intval($item->total_steps ?? 5); // default 5 steps
            $item->progress_percent = $total > 0 ? intval(($completed / $total) * 100) : 0;

            // Status mapping
            if ($item->progress_percent >= 100) {
                $item->status = $item->status ?? 'Completed';
            } else {
                $item->status = $item->status ?? 'Pre-boarding';
            }

            return $item;
        });

        // Apply in-memory filters
        $filteredCollection = $allOnboardings;
        
        if ($department = $request->input('department')) {
            if ($department !== 'all' && $department !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($department) {
                    return ($item->department ?? 'Not Assigned') === $department;
                });
            }
        }
        
        if ($manager = $request->input('manager')) {
            if ($manager !== 'all' && $manager !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($manager) {
                    return ($item->manager ?? 'Not Assigned') === $manager;
                });
            }
        }
        
        if ($status = $request->input('status')) {
            if ($status !== 'All' && $status !== '') {
                $filteredCollection = $filteredCollection->filter(function($item) use ($status) {
                    return ($item->status ?? 'Pre-boarding') === $status;
                });
            }
        }
        
        if ($joiningMonth = $request->input('joiningMonth')) {
            try {
                [$year, $month] = explode('-', $joiningMonth);
                $filteredCollection = $filteredCollection->filter(function($item) use ($year, $month) {
                    $date = $item->joining_date ?? $item->created_at;
                    if ($date instanceof \Carbon\Carbon) {
                        return $date->year == $year && $date->month == $month;
                    }
                    return false;
                });
            } catch (\Exception $e) {}
        }

        // Format data for CSV export
        $formattedData = $filteredCollection->map(function($item) {
            return [
                'fullName' => trim($item->first_name . ' ' . $item->last_name),
                'email' => $item->primary_email ?? '',
                'designation' => $item->designation ?? 'Not Assigned',
                'department' => $item->department ?? 'Not Assigned',
                'manager' => $item->manager ?? 'Not Assigned',
                'joiningDate' => $item->joining_date ? $item->joining_date->format('Y-m-d') : ($item->created_at ? $item->created_at->format('Y-m-d') : ''),
                'progressPercent' => $item->progress_percent ?? 0,
                'status' => $item->status ?? 'Pre-boarding',
                'lastUpdated' => $item->updated_at ? $item->updated_at->format('Y-m-d H:i:s') : ($item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '')
            ];
        })->values();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="onboardings-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($formattedData) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 (helps Excel recognize UTF-8 encoding)
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'Candidate Name',
                'Email',
                'Role',
                'Department',
                'Manager',
                'Joining Date',
                'Progress (%)',
                'Status',
                'Last Updated'
            ]);
            
            // Data rows
            foreach ($formattedData as $onboarding) {
                fputcsv($file, [
                    $onboarding['fullName'],
                    $onboarding['email'],
                    $onboarding['designation'],
                    $onboarding['department'],
                    $onboarding['manager'],
                    $onboarding['joiningDate'],
                    $onboarding['progressPercent'],
                    $onboarding['status'],
                    $onboarding['lastUpdated']
                ]);
            }
            
            fclose($file);
        };

        \Log::info('CSV Export: Generating CSV file', [
            'record_count' => $formattedData->count(),
            'filename' => 'onboardings-' . date('Y-m-d') . '.csv'
        ]);

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import candidates for onboarding
     */
    public function importCandidates(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved. Please ensure you have access to a tenant.'], 500);
        }

        $request->validate([
            'file' => [
                'required',
                'file',
                'mimetypes:text/plain,text/csv,application/csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel.sheet.macroEnabled.12',
                'mimes:csv,txt,xlsx,xls',
                'max:10240', // 10MB max
            ],
        ]);

        try {
            // Get count before import to verify new records
            $countBefore = \App\Models\Candidate::where('tenant_id', tenant_id())
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->count();
            
            // Use database transaction to ensure all data is saved atomically
            $import = null;
            \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$import) {
                // Create a simple import class for onboarding
                $import = new OnboardingCandidateImport(tenant_id(), 'Onboarding Import');
                
                // Import the file - this will save to database
                Excel::import($import, $request->file('file'));
            });
            
            // Get counts from import class after transaction
            $importedCount = $import->getRowCount();
            $createdCount = $import->getCreatedCount();
            $updatedCount = $import->getUpdatedCount();
            $skippedRows = $import->getSkippedRows();
            
            // After transaction commits, verify data was actually saved to database
            // Small delay to ensure database is updated
            usleep(100000); // 0.1 second
            
            $countAfter = \App\Models\Candidate::where('tenant_id', tenant_id())
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->count();
            
            // Get the actual number of new records
            $actualNewCount = $countAfter - $countBefore;
            $totalSaved = $createdCount + $updatedCount;
            
            // Also check for recently updated records (in case all were updates)
            $recentlyUpdated = \App\Models\Candidate::where('tenant_id', tenant_id())
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->where('updated_at', '>=', now()->subMinutes(2))
                ->count();
            
            \Log::info('Onboarding Import Completed', [
                'tenant_id' => tenant_id(),
                'rows_processed' => $importedCount,
                'count_before' => $countBefore,
                'count_after' => $countAfter,
                'actual_new_count' => $actualNewCount,
                'created_count' => $createdCount,
                'updated_count' => $updatedCount,
                'total_saved' => $totalSaved,
                'skipped_rows_count' => count($skippedRows),
            ]);

            // Only return success if data was actually saved/updated in database
            // Accept success if: rows were processed AND (records were created/updated OR database count increased OR records were recently updated)
            // This handles cases where all rows were updates (no new records, but data was saved)
            $hasDataSaved = ($totalSaved > 0 || $actualNewCount > 0 || $countAfter > $countBefore || $recentlyUpdated > 0);
            
            if ($importedCount > 0 && $hasDataSaved) {
                $message = "Successfully imported {$importedCount} candidate" . ($importedCount !== 1 ? 's' : '') . " for onboarding.";
                if ($createdCount > 0) {
                    $message .= " {$createdCount} new candidate" . ($createdCount !== 1 ? 's' : '') . " created.";
                }
                if ($updatedCount > 0) {
                    $message .= " {$updatedCount} candidate" . ($updatedCount !== 1 ? 's' : '') . " updated.";
                }
                if (count($skippedRows) > 0) {
                    $message .= " " . count($skippedRows) . " row" . (count($skippedRows) !== 1 ? 's' : '') . " skipped (missing or invalid email).";
                }
                
                $responseData = [
                    'success' => true,
                    'data_saved' => true,
                    'message' => $message,
                    'count' => $importedCount,
                    'created_count' => $createdCount,
                    'updated_count' => $updatedCount,
                    'total_saved' => $totalSaved,
                    'verified' => true
                ];
                
                \Log::info('Onboarding Import: Sending success response', [
                    'response_data' => $responseData,
                    'success_type' => gettype($responseData['success']),
                    'success_value' => $responseData['success']
                ]);
                
                return response()->json($responseData);
            } else {
                // Build helpful error message
                $errorMessage = 'No candidates were saved to the database. ';
                
                if ($importedCount === 0 && count($skippedRows) > 0) {
                    $errorMessage .= 'All rows were skipped because they are missing a valid email address. ';
                    $errorMessage .= 'Please ensure your Excel file has a column with email addresses (e.g., "email", "primary_email", "e-mail"). ';
                } elseif ($importedCount === 0) {
                    $errorMessage .= 'No valid rows were found in the file. ';
                    $errorMessage .= 'Please check that your file has: ';
                    $errorMessage .= '1) A header row with column names, ';
                    $errorMessage .= '2) At least one data row, ';
                    $errorMessage .= '3) A column containing email addresses. ';
                } else {
                    $errorMessage .= 'Rows were processed but could not be saved. ';
                }
                
                $errorMessage .= 'Please download the template and ensure your file format matches it.';
                
                // Log detailed error for debugging
                \Log::error('Onboarding Import Failed', [
                    'tenant_id' => tenant_id(),
                    'imported_count' => $importedCount,
                    'total_saved' => $totalSaved,
                    'actual_new_count' => $actualNewCount,
                    'count_before' => $countBefore,
                    'count_after' => $countAfter,
                    'created_count' => $createdCount,
                    'updated_count' => $updatedCount,
                    'skipped_rows_count' => count($skippedRows),
                    'skipped_rows_sample' => array_slice($skippedRows, 0, 3),
                ]);
                
                $errorResponse = [
                    'success' => false,
                    'data_saved' => false,
                    'message' => $errorMessage,
                    'count' => $importedCount,
                    'skipped_count' => count($skippedRows),
                    'debug' => [
                        'rows_processed' => $importedCount,
                        'created' => $createdCount,
                        'updated' => $updatedCount,
                        'total_saved' => $totalSaved,
                        'count_before' => $countBefore,
                        'count_after' => $countAfter,
                        'skipped_rows' => count($skippedRows) > 0 ? array_slice($skippedRows, 0, 5) : [],
                    ]
                ];
                
                \Log::info('Onboarding Import: Sending error response', [
                    'response_data' => $errorResponse,
                    'success_type' => gettype($errorResponse['success']),
                    'success_value' => $errorResponse['success']
                ]);
                
                return response()->json($errorResponse, 400);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errors = [];
            foreach ($failures as $failure) {
                $errors[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
            }
            return response()->json([
                'success' => false,
                'message' => 'Import validation failed',
                'errors' => $errors
            ], 400);
        } catch (\Throwable $e) {
            Log::error('Onboarding import error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . ($e->getMessage() ?? 'Unknown error')
            ], 400);
        }
    }

    /**
     * Download import template
     */
    public function downloadImportTemplate(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="onboarding_candidates_import_template.csv"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'first_name',
                'last_name', 
                'primary_email',
                'primary_phone',
                'designation',
                'department',
                'manager',
                'joining_date',
                'source',
                'tags'
            ]);
            
            // Sample data
            fputcsv($file, [
                'John',
                'Doe',
                'john.doe@example.com',
                '+1234567890',
                'Software Engineer',
                'Engineering',
                'Jane Manager',
                '2025-12-01',
                'Onboarding',
                'New Hire,Full-time'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * API: Get asset requests for a candidate
     */
    public function apiGetAssetRequests(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Log Onboarding.Tab.View event
        try {
            OnboardingViewLogService::log(
                'Onboarding.Tab.View',
                $request,
                ['tab' => 'ITAssets'],
                $candidateId,
                'tab'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log Tab.View event', ['error' => $e->getMessage()]);
        }

        try {
            // Get asset requests for this candidate
            $assetRequests = AssetRequest::where('tenant_id', $tenantModel->id)
                ->where('candidate_id', $candidateId)
                ->orderBy('requested_on', 'desc')
                ->get();

            // Format response
            $formattedAssets = $assetRequests->map(function($asset) {
                return [
                    'id' => $asset->id,
                    'asset_type' => $asset->asset_type,
                    'requested_on' => $asset->requested_on ? $asset->requested_on->format('Y-m-d') : null,
                    'assigned_to' => $asset->assigned_to,
                    'serial_tag' => $asset->serial_tag,
                    'status' => $asset->status,
                    'notes' => $asset->notes,
                ];
            });

            // Log fetch success
            Log::info('ITTab.Fetch.Success', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'count' => $formattedAssets->count(),
            ]);

            return response()->json([
                'assets' => $formattedAssets
            ]);
        } catch (\Exception $e) {
            // Log fetch error
            Log::error('ITTab.Fetch.Error', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to load asset requests',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Log slide-over close event
     */
    public function apiLogSlideOverClose(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        // Log Onboarding.SlideOver.Close event
        try {
            OnboardingViewLogService::log(
                'Onboarding.SlideOver.Close',
                $request,
                [],
                $candidateId,
                'onboarding'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log SlideOver.Close event', ['error' => $e->getMessage()]);
        }
        
        // Return success (fire-and-forget endpoint)
        return response()->json(['success' => true], 200);
    }

    /**
     * API: Log tab view event (for Overview tab which doesn't have its own API endpoint)
     */
    public function apiLogTabView(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        $tabName = $request->input('tab', 'Overview');
        
        // Log Onboarding.Tab.View event
        try {
            OnboardingViewLogService::log(
                'Onboarding.Tab.View',
                $request,
                ['tab' => $tabName],
                $candidateId,
                'tab'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log Tab.View event', ['error' => $e->getMessage()]);
        }
        
        // Return success (fire-and-forget endpoint)
        return response()->json(['success' => true], 200);
    }

    /**
     * API: Get approvals for a candidate
     */
    public function apiGetApprovals(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Find the candidate
        $candidate = Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Log Onboarding.Tab.View event
        try {
            OnboardingViewLogService::log(
                'Onboarding.Tab.View',
                $request,
                ['tab' => 'Approvals'],
                $candidateId,
                'tab'
            );
        } catch (\Exception $e) {
            // Logging failure should not break the API
            Log::warning('Failed to log Tab.View event', ['error' => $e->getMessage()]);
        }

        try {
            // For now, return mock approval data
            // In a real implementation, this would fetch from an approvals table
            $approvals = [
                [
                    'id' => 'approval-1',
                    'step_name' => 'HR Manager Approval',
                    'approver_name' => 'Sarah Johnson',
                    'status' => 'Approved',
                    'timestamp' => now()->subDays(2)->toIso8601String(),
                    'comments' => 'All documents verified. Approved for onboarding.'
                ],
                [
                    'id' => 'approval-2',
                    'step_name' => 'Department Head Approval',
                    'approver_name' => 'Michael Chen',
                    'status' => 'Approved',
                    'timestamp' => now()->subDays(1)->toIso8601String(),
                    'comments' => 'Role requirements confirmed.'
                ],
                [
                    'id' => 'approval-3',
                    'step_name' => 'Finance Approval',
                    'approver_name' => 'Emily Davis',
                    'status' => 'Pending',
                    'timestamp' => null,
                    'comments' => ''
                ],
                [
                    'id' => 'approval-4',
                    'step_name' => 'IT Security Clearance',
                    'approver_name' => 'David Wilson',
                    'status' => 'Pending',
                    'timestamp' => null,
                    'comments' => ''
                ]
            ];

            // Log fetch success
            Log::info('ApprovalsTab.Fetch.Success', [
                'count' => count($approvals),
            ]);

            return response()->json([
                'approvals' => $approvals
            ]);
        } catch (\Exception $e) {
            // Log fetch error
            Log::error('ApprovalsTab.Fetch.Error', [
                'error' => $e->getMessage(),
                'tenant' => $tenantModel->slug,
                'candidateID' => $candidateId,
            ]);

            return response()->json([
                'error' => 'Failed to load approvals',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Create asset request
     */
    public function apiCreateAssetRequest(Request $request, $id = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get ID from route parameter
        $candidateId = $request->route('id') ?? $request->input('id') ?? $id;
        
        if (!$candidateId) {
            return response()->json(['error' => 'Candidate ID is required'], 400);
        }

        // Validate request
        $request->validate([
            'asset_type' => 'required|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Find the candidate
        $candidate = Candidate::where('tenant_id', $tenantModel->id)
            ->where(function($q) {
                $q->where('source', 'Onboarding')
                  ->orWhere('source', 'Onboarding Import');
            })
            ->find($candidateId);

        if (!$candidate) {
            return response()->json(['error' => 'Candidate not found'], 404);
        }

        // Detect tenant format for logging
        $isSubdomain = str_starts_with(request()->route()->getName() ?? '', 'subdomain.');
        $tenantFormat = $isSubdomain ? 'subdomain' : 'slug';

        try {
            // Create asset request
            $assetRequest = AssetRequest::create([
                'tenant_id' => $tenantModel->id,
                'candidate_id' => $candidateId,
                'asset_type' => $request->input('asset_type'),
                'notes' => $request->input('notes'),
                'status' => 'Requested',
                'requested_on' => now(),
            ]);

            // Log submit success
            Log::info('ITTab.Request.Submit', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'assetType' => $request->input('asset_type'),
                'result' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'asset' => [
                    'id' => $assetRequest->id,
                    'asset_type' => $assetRequest->asset_type,
                    'requested_on' => $assetRequest->requested_on ? $assetRequest->requested_on->format('Y-m-d') : null,
                    'assigned_to' => $assetRequest->assigned_to,
                    'serial_tag' => $assetRequest->serial_tag,
                    'status' => $assetRequest->status,
                    'notes' => $assetRequest->notes,
                ]
            ]);
        } catch (\Exception $e) {
            // Log submit error
            Log::error('ITTab.Request.Submit', [
                'candidateID' => $candidateId,
                'tenant' => $tenantModel->slug,
                'tenant_format' => $tenantFormat,
                'assetType' => $request->input('asset_type'),
                'result' => 'error',
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Failed to submit asset request',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get mock onboardings data
     */
    private function getMockOnboardings(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $pageSize = (int) $request->get('pageSize', 10);
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $department = $request->get('department', '');
        $manager = $request->get('manager', '');
        $joiningMonth = $request->get('joiningMonth', '');
        $sortBy = $request->get('sortBy', 'joiningDate');
        $sortDir = $request->get('sortDir', 'asc');

        // Sample data
        $sampleData = [
            [
                'id' => 123,
                'firstName' => 'Rahul',
                'lastName' => 'Sharma',
                'fullName' => 'Rahul Sharma',
                'email' => 'rahul@company.com',
                'avatarUrl' => null,
                'designation' => 'Product Manager',
                'department' => 'Product',
                'manager' => 'Priya Patel',
                'joiningDate' => '2025-12-01',
                'progressPercent' => 78,
                'pendingItems' => 5,
                'status' => 'Pre-boarding',
                'lastUpdated' => '2025-11-20T10:30:00+05:30',
            ],
            [
                'id' => 124,
                'firstName' => 'Ananya',
                'lastName' => 'Roy',
                'fullName' => 'Ananya Roy',
                'email' => 'ananya@company.com',
                'avatarUrl' => null,
                'designation' => 'HR Executive',
                'department' => 'People Ops',
                'manager' => 'Rajesh Kumar',
                'joiningDate' => '2025-11-28',
                'progressPercent' => 100,
                'pendingItems' => 0,
                'status' => 'Completed',
                'lastUpdated' => '2025-11-25T16:00:00+05:30',
            ],
            [
                'id' => 125,
                'firstName' => 'Vikram',
                'lastName' => 'Singh',
                'fullName' => 'Vikram Singh',
                'email' => 'vikram@company.com',
                'avatarUrl' => null,
                'designation' => 'DevOps Engineer',
                'department' => 'Infrastructure',
                'manager' => 'Amit Verma',
                'joiningDate' => '2025-11-30',
                'progressPercent' => 50,
                'pendingItems' => 8,
                'status' => 'IT Pending',
                'lastUpdated' => '2025-11-19T09:15:00+05:30',
            ],
            [
                'id' => 126,
                'firstName' => 'Meera',
                'lastName' => 'Nair',
                'fullName' => 'Meera Nair',
                'email' => 'meera@company.com',
                'avatarUrl' => null,
                'designation' => 'Sales Manager',
                'department' => 'Sales',
                'manager' => 'Suresh Menon',
                'joiningDate' => '2025-12-05',
                'progressPercent' => 20,
                'pendingItems' => 10,
                'status' => 'Pending Docs',
                'lastUpdated' => '2025-11-18T11:20:00+05:30',
            ],
            [
                'id' => 127,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'fullName' => 'John Doe',
                'email' => 'john@company.com',
                'avatarUrl' => null,
                'designation' => 'Designer',
                'department' => 'Product',
                'manager' => 'Priya Patel',
                'joiningDate' => '2025-11-20',
                'progressPercent' => 60,
                'pendingItems' => 2,
                'status' => 'Overdue',
                'lastUpdated' => '2025-11-15T14:45:00+05:30',
            ],
            [
                'id' => 128,
                'firstName' => 'Priya',
                'lastName' => 'Patel',
                'fullName' => 'Priya Patel',
                'email' => 'priya@company.com',
                'avatarUrl' => null,
                'designation' => 'Engineering Manager',
                'department' => 'Engineering',
                'manager' => 'CEO',
                'joiningDate' => '2025-12-10',
                'progressPercent' => 90,
                'pendingItems' => 1,
                'status' => 'Joining Soon',
                'lastUpdated' => '2025-11-22T13:10:00+05:30',
            ],
        ];

        // Apply filters
        $filtered = collect($sampleData);
        
        if ($search) {
            $searchLower = strtolower($search);
            $filtered = $filtered->filter(function ($item) use ($searchLower) {
                return str_contains(strtolower($item['fullName']), $searchLower) ||
                       str_contains(strtolower($item['email']), $searchLower) ||
                       str_contains(strtolower($item['department']), $searchLower) ||
                       str_contains(strtolower($item['designation']), $searchLower);
            });
        }
        
        if ($status && $status !== 'All') {
            $filtered = $filtered->where('status', $status);
        }
        
        if ($department) {
            $filtered = $filtered->where('department', $department);
        }
        
        if ($manager) {
            $filtered = $filtered->where('manager', $manager);
        }
        
        if ($joiningMonth) {
            $filtered = $filtered->filter(function ($item) use ($joiningMonth) {
                return date('Y-m', strtotime($item['joiningDate'])) === $joiningMonth;
            });
        }

        // Apply sorting
        if ($sortBy === 'joiningDate') {
            $filtered = $filtered->sortBy('joiningDate', SORT_REGULAR, $sortDir === 'desc');
        } elseif ($sortBy === 'fullName') {
            $filtered = $filtered->sortBy('fullName', SORT_REGULAR, $sortDir === 'desc');
        }

        $total = $filtered->count();
        $filtered = $filtered->values();
        
        // Apply pagination
        $offset = ($page - 1) * $pageSize;
        $paginated = $filtered->slice($offset, $pageSize)->values();

        return response()->json([
            'data' => $paginated->toArray(),
            'meta' => [
                'page' => $page,
                'pageSize' => $pageSize,
                'total' => $total,
            ],
        ]);
    }

    /**
     * Get all onboardings for export (no pagination)
     */
    private function getAllOnboardingsForExport(Request $request)
    {
        $search = $request->get('search', '');
        $status = $request->get('status', '');
        $department = $request->get('department', '');
        $manager = $request->get('manager', '');
        $joiningMonth = $request->get('joiningMonth', '');

        // Sample data (same as getMockOnboardings)
        $sampleData = [
            [
                'id' => 123,
                'firstName' => 'Rahul',
                'lastName' => 'Sharma',
                'fullName' => 'Rahul Sharma',
                'email' => 'rahul@company.com',
                'avatarUrl' => null,
                'designation' => 'Product Manager',
                'department' => 'Product',
                'manager' => 'Priya Patel',
                'joiningDate' => '2025-12-01',
                'progressPercent' => 78,
                'pendingItems' => 5,
                'status' => 'Pre-boarding',
                'lastUpdated' => '2025-11-20T10:30:00+05:30',
            ],
            [
                'id' => 124,
                'firstName' => 'Ananya',
                'lastName' => 'Roy',
                'fullName' => 'Ananya Roy',
                'email' => 'ananya@company.com',
                'avatarUrl' => null,
                'designation' => 'HR Executive',
                'department' => 'People Ops',
                'manager' => 'Rajesh Kumar',
                'joiningDate' => '2025-11-28',
                'progressPercent' => 100,
                'pendingItems' => 0,
                'status' => 'Completed',
                'lastUpdated' => '2025-11-25T16:00:00+05:30',
            ],
            [
                'id' => 125,
                'firstName' => 'Vikram',
                'lastName' => 'Singh',
                'fullName' => 'Vikram Singh',
                'email' => 'vikram@company.com',
                'avatarUrl' => null,
                'designation' => 'DevOps Engineer',
                'department' => 'Infrastructure',
                'manager' => 'Amit Verma',
                'joiningDate' => '2025-11-30',
                'progressPercent' => 50,
                'pendingItems' => 8,
                'status' => 'IT Pending',
                'lastUpdated' => '2025-11-19T09:15:00+05:30',
            ],
            [
                'id' => 126,
                'firstName' => 'Meera',
                'lastName' => 'Nair',
                'fullName' => 'Meera Nair',
                'email' => 'meera@company.com',
                'avatarUrl' => null,
                'designation' => 'Sales Manager',
                'department' => 'Sales',
                'manager' => 'Suresh Menon',
                'joiningDate' => '2025-12-05',
                'progressPercent' => 20,
                'pendingItems' => 10,
                'status' => 'Pending Docs',
                'lastUpdated' => '2025-11-18T11:20:00+05:30',
            ],
            [
                'id' => 127,
                'firstName' => 'John',
                'lastName' => 'Doe',
                'fullName' => 'John Doe',
                'email' => 'john@company.com',
                'avatarUrl' => null,
                'designation' => 'Designer',
                'department' => 'Product',
                'manager' => 'Priya Patel',
                'joiningDate' => '2025-11-20',
                'progressPercent' => 60,
                'pendingItems' => 2,
                'status' => 'Overdue',
                'lastUpdated' => '2025-11-15T14:45:00+05:30',
            ],
            [
                'id' => 128,
                'firstName' => 'Priya',
                'lastName' => 'Patel',
                'fullName' => 'Priya Patel',
                'email' => 'priya@company.com',
                'avatarUrl' => null,
                'designation' => 'Engineering Manager',
                'department' => 'Engineering',
                'manager' => 'CEO',
                'joiningDate' => '2025-12-10',
                'progressPercent' => 90,
                'pendingItems' => 1,
                'status' => 'Joining Soon',
                'lastUpdated' => '2025-11-22T13:10:00+05:30',
            ],
        ];

        // Apply filters (same logic as getMockOnboardings)
        $filtered = collect($sampleData);
        
        if ($search) {
            $searchLower = strtolower($search);
            $filtered = $filtered->filter(function ($item) use ($searchLower) {
                return str_contains(strtolower($item['fullName']), $searchLower) ||
                       str_contains(strtolower($item['email']), $searchLower) ||
                       str_contains(strtolower($item['department']), $searchLower) ||
                       str_contains(strtolower($item['designation']), $searchLower);
            });
        }
        
        if ($status && $status !== 'All') {
            $filtered = $filtered->where('status', $status);
        }
        
        if ($department) {
            $filtered = $filtered->where('department', $department);
        }
        
        if ($manager) {
            $filtered = $filtered->where('manager', $manager);
        }
        
        if ($joiningMonth) {
            $filtered = $filtered->filter(function ($item) use ($joiningMonth) {
                return date('Y-m', strtotime($item['joiningDate'])) === $joiningMonth;
            });
        }

        return $filtered->values()->toArray();
    }

    /**
     * API: Get dashboard KPIs
     */
    public function apiDashboardKPIs(Request $request)
    {
        \Log::info('apiDashboardKPIs called', [
            'path' => $request->path(),
            'url' => $request->url(),
            'route' => $request->route() ? $request->route()->getName() : 'none',
        ]);
        
        $startTime = microtime(true);
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            \Log::error('apiDashboardKPIs: Tenant not resolved');
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        try {
            // Get filters
            $filters = $this->getDashboardFilters($request);
            
            // Base query for onboarding candidates
            $baseQuery = Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                });

            // Apply filters
            $this->applyDashboardFilters($baseQuery, $filters);

            // Active Onboardings (status != Converted/Completed)
            $activeOnboardings = (clone $baseQuery)
                ->whereNotIn('status', ['Converted', 'Completed'])
                ->count();

            // Pending Documents - count candidates with missing required documents
            // For now, we'll use a simple heuristic: candidates with low progress or specific status
            $pendingDocuments = (clone $baseQuery)
                ->where(function($q) {
                    $q->where('status', 'Pending Docs')
                      ->orWhere(function($q2) {
                          $q2->whereRaw('(COALESCE(completed_steps, 0) / NULLIF(COALESCE(total_steps, 5), 0)) < 0.5');
                      });
                })
                ->count();

            // Overdue Tasks - count tasks past due and not completed
            // Using activities table for tasks
            $overdueTasks = \App\Models\Activity::where('tenant_id', $tenantModel->id)
                ->where('type', 'task')
                ->whereNotNull('due_at')
                ->where('due_at', '<', now())
                ->whereNull('completed_at')
                ->whereHas('candidate', function($q) use ($baseQuery) {
                    $candidateIds = (clone $baseQuery)->pluck('id');
                    $q->whereIn('id', $candidateIds);
                })
                ->count();

            // Approvals Pending - count pending approval steps
            // For now, using a simple count based on status
            $approvalsPending = (clone $baseQuery)
                ->where('status', 'IT Pending')
                ->count();

            // Calculate trends (7 days ago)
            $sevenDaysAgo = now()->subDays(7);
            $previousActive = Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->whereNotIn('status', ['Converted', 'Completed'])
                ->where('created_at', '<', $sevenDaysAgo)
                ->count();

            $activeTrend = $previousActive > 0 
                ? round((($activeOnboardings - $previousActive) / $previousActive) * 100, 1)
                : 0;

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            
            if ($responseTime > 500) {
                \Log::warning('Onboarding.Dashboard.SlowQuery', [
                    'tenant' => $tenantModel->id,
                    'response_time_ms' => $responseTime,
                    'endpoint' => 'kpis',
                ]);
            }

            return response()->json([
                'active_onboardings' => $activeOnboardings,
                'active_trend' => $activeTrend,
                'pending_documents' => $pendingDocuments,
                'overdue_tasks' => $overdueTasks,
                'approvals_pending' => $approvalsPending,
                'last_updated' => now()->toIso8601String(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Onboarding.Dashboard.FetchError', [
                'tenant' => $tenantModel->id,
                'endpoint' => 'kpis',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['error' => 'Failed to fetch KPIs'], 500);
        }
    }

    /**
     * API: Get bottleneck lists
     */
    public function apiDashboardBottlenecks(Request $request)
    {
        \Log::info('apiDashboardBottlenecks called', [
            'path' => $request->path(),
            'url' => $request->url(),
            'route' => $request->route() ? $request->route()->getName() : 'none',
        ]);
        
        $startTime = microtime(true);
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            \Log::error('apiDashboardBottlenecks: Tenant not resolved');
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        try {
            $user = auth()->user();
            $permissionService = app(\App\Services\PermissionService::class);
            $userRole = $permissionService->getUserRole($user->id, $tenantModel->id);
            $isHiringManager = $userRole === 'Hiring Manager';

            $filters = $this->getDashboardFilters($request);
            $baseQuery = Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                });

            $this->applyDashboardFilters($baseQuery, $filters);

            // A. Candidates missing documents
            $missingDocsQuery = (clone $baseQuery)
                ->where(function($q) {
                    $q->where('status', 'Pending Docs')
                      ->orWhere(function($q2) {
                          $q2->whereRaw('(COALESCE(total_steps, 5) - COALESCE(completed_steps, 0)) > 0')
                             ->whereRaw('(COALESCE(completed_steps, 0) / NULLIF(COALESCE(total_steps, 5), 0)) < 0.5');
                      });
                });
            
            // Use orderBy with DB::raw for better compatibility
            $missingDocs = $missingDocsQuery
                ->orderBy(DB::raw('(COALESCE(total_steps, 5) - COALESCE(completed_steps, 0))'), 'desc')
                ->limit(10)
                ->get()
                ->map(function($candidate) use ($isHiringManager) {
                    $total = $candidate->total_steps ?? 5;
                    $completed = $candidate->completed_steps ?? 0;
                    $missingCount = max(0, $total - $completed);
                    $name = trim(($candidate->first_name ?? '') . ' ' . ($candidate->last_name ?? ''));
                    if (empty($name)) {
                        $name = 'Unknown';
                    }
                    $email = $candidate->primary_email ?? '';
                    $joiningDate = '';
                    if ($candidate->joining_date) {
                        $joiningDate = $candidate->joining_date->format('Y-m-d');
                    } elseif ($candidate->created_at) {
                        $joiningDate = $candidate->created_at->format('Y-m-d');
                    }
                    return [
                        'id' => $candidate->id,
                        'name' => $name,
                        'email' => $isHiringManager ? $this->maskEmail($email) : $email,
                        'missing_docs_count' => $missingCount,
                        'joining_date' => $joiningDate,
                    ];
                });

            // B. Candidates with overdue tasks
            $overdueTasksQuery = (clone $baseQuery);
            
            // Check if activities relationship exists
            if (method_exists(Candidate::class, 'activities')) {
                $overdueTasks = $overdueTasksQuery
                    ->whereHas('activities', function($q) {
                        $q->where('type', 'task')
                          ->whereNotNull('due_at')
                          ->where('due_at', '<', now())
                          ->whereNull('completed_at');
                    })
                    ->with(['activities' => function($q) {
                        $q->where('type', 'task')
                          ->whereNotNull('due_at')
                          ->where('due_at', '<', now())
                          ->whereNull('completed_at')
                          ->orderBy('due_at', 'asc');
                    }])
                    ->get()
                    ->map(function($candidate) use ($isHiringManager) {
                        $overdueCount = $candidate->activities ? $candidate->activities->count() : 0;
                        $oldestDue = $candidate->activities && $candidate->activities->count() > 0 
                            ? $candidate->activities->first()->due_at 
                            : null;
                        $name = trim(($candidate->first_name ?? '') . ' ' . ($candidate->last_name ?? ''));
                        if (empty($name)) {
                            $name = 'Unknown';
                        }
                        return [
                            'id' => $candidate->id,
                            'name' => $name,
                            'email' => $isHiringManager ? $this->maskEmail($candidate->primary_email ?? '') : ($candidate->primary_email ?? ''),
                            'overdue_task_count' => $overdueCount,
                            'oldest_overdue_date' => $oldestDue ? $oldestDue->format('Y-m-d') : null,
                        ];
                    })
                    ->sortByDesc('overdue_task_count')
                    ->take(10)
                    ->values();
            } else {
                // Fallback if activities relationship doesn't exist
                $overdueTasks = collect([]);
            }

            // C. Candidates waiting for approvals
            $pendingApprovals = (clone $baseQuery)
                ->where('status', 'IT Pending')
                ->orderBy('created_at', 'asc')
                ->limit(10)
                ->get()
                ->map(function($candidate) use ($isHiringManager) {
                    $name = trim(($candidate->first_name ?? '') . ' ' . ($candidate->last_name ?? ''));
                    if (empty($name)) {
                        $name = 'Unknown';
                    }
                    return [
                        'id' => $candidate->id,
                        'name' => $name,
                        'email' => $isHiringManager ? $this->maskEmail($candidate->primary_email ?? '') : ($candidate->primary_email ?? ''),
                        'pending_approvals_count' => 1, // Simplified for now
                        'first_pending_approver' => $candidate->manager ?? 'Not Assigned',
                    ];
                });

            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            if ($responseTime > 500) {
                \Log::warning('Onboarding.Dashboard.SlowQuery', [
                    'tenant' => $tenantModel->id,
                    'response_time_ms' => $responseTime,
                    'endpoint' => 'bottlenecks',
                ]);
            }

            return response()->json([
                'missing_documents' => $missingDocs,
                'overdue_tasks' => $overdueTasks,
                'pending_approvals' => $pendingApprovals,
            ]);
        } catch (\Exception $e) {
            \Log::error('Onboarding.Dashboard.FetchError', [
                'tenant' => $tenantModel->id ?? 'unknown',
                'tenant_slug' => $tenantModel->slug ?? 'unknown',
                'endpoint' => 'bottlenecks',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['error' => 'Failed to fetch bottlenecks: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Get dashboard charts data
     */
    public function apiDashboardCharts(Request $request)
    {
        \Log::info('apiDashboardCharts called', [
            'path' => $request->path(),
            'url' => $request->url(),
            'route' => $request->route() ? $request->route()->getName() : 'none',
        ]);
        
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            \Log::error('apiDashboardCharts: Tenant not resolved');
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        try {
            $filters = $this->getDashboardFilters($request);
            $baseQuery = Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                });

            $this->applyDashboardFilters($baseQuery, $filters);

            // Last 30 days data
            $thirtyDaysAgo = now()->subDays(30);
            
            // Onboardings created vs converted - daily counts
            $created = (clone $baseQuery)
                ->where('created_at', '>=', $thirtyDaysAgo)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function($item) {
                    // Ensure date is formatted as string Y-m-d
                    $date = is_string($item->date) ? $item->date : $item->date->format('Y-m-d');
                    return [$date => (int)$item->count];
                });

            $converted = (clone $baseQuery)
                ->where('status', 'Converted')
                ->where('updated_at', '>=', $thirtyDaysAgo)
                ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->mapWithKeys(function($item) {
                    // Ensure date is formatted as string Y-m-d
                    $date = is_string($item->date) ? $item->date : $item->date->format('Y-m-d');
                    return [$date => (int)$item->count];
                });

            // Avg days to convert - rolling 30-day average
            $convertedCandidates = (clone $baseQuery)
                ->where('status', 'Converted')
                ->where('updated_at', '>=', $thirtyDaysAgo)
                ->get();

            $avgDaysToConvert = 0;
            if ($convertedCandidates->count() > 0) {
                $days = $convertedCandidates->map(function($c) {
                    if ($c->created_at && $c->updated_at) {
                        return $c->created_at->diffInDays($c->updated_at);
                    }
                    return 0;
                })->filter(function($days) {
                    return $days > 0;
                });
                
                if ($days->count() > 0) {
                    $avgDaysToConvert = round($days->avg(), 1);
                }
            }

            return response()->json([
                'created_vs_converted' => [
                    'created' => $created->toArray(),
                    'converted' => $converted->toArray(),
                ],
                'avg_days_to_convert' => $avgDaysToConvert,
            ]);
        } catch (\Exception $e) {
            \Log::error('Onboarding.Dashboard.FetchError', [
                'tenant' => $tenantModel->id ?? 'unknown',
                'tenant_slug' => $tenantModel->slug ?? 'unknown',
                'endpoint' => 'charts',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json(['error' => 'Failed to fetch charts: ' . $e->getMessage()], 500);
        }
    }

    /**
     * API: Export dashboard data to CSV
     */
    public function apiDashboardExport(Request $request)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        $user = auth()->user();
        $permissionService = app(\App\Services\PermissionService::class);
        $userRole = $permissionService->getUserRole($user->id, $tenantModel->id);
        
        // Hiring Manager cannot export
        if ($userRole === 'Hiring Manager') {
            abort(403, 'You do not have permission to export data.');
        }

        try {
            $filters = $this->getDashboardFilters($request);
            
            // Log export
            \Log::info('Onboarding.Dashboard.Export', [
                'user' => $user->id,
                'tenant' => $tenantModel->id,
                'tenant_slug' => $tenantModel->slug,
                'role' => $userRole,
                'filters' => json_encode($filters),
            ]);

            // Get bottleneck data
            $baseQuery = Candidate::where('tenant_id', $tenantModel->id)
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                });

            $this->applyDashboardFilters($baseQuery, $filters);

            $candidates = (clone $baseQuery)->limit(1000)->get();

            $filename = 'onboarding_dashboard_' . date('Y-m-d_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($candidates) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, [
                    'Candidate Name',
                    'Email',
                    'Department',
                    'Manager',
                    'Joining Date',
                    'Status',
                    'Progress %',
                    'Missing Docs',
                    'Overdue Tasks',
                ]);

                foreach ($candidates as $candidate) {
                    $missingDocs = max(0, ($candidate->total_steps ?? 5) - ($candidate->completed_steps ?? 0));
                    $overdueTasks = $candidate->activities()
                        ->where('type', 'task')
                        ->whereNotNull('due_at')
                        ->where('due_at', '<', now())
                        ->whereNull('completed_at')
                        ->count();

                    fputcsv($file, [
                        $candidate->first_name . ' ' . $candidate->last_name,
                        $candidate->primary_email,
                        $candidate->department ?? 'Not Assigned',
                        $candidate->manager ?? 'Not Assigned',
                        $candidate->joining_date ? $candidate->joining_date->format('Y-m-d') : '',
                        $candidate->status ?? 'Pre-boarding',
                        round((($candidate->completed_steps ?? 0) / max(1, ($candidate->total_steps ?? 5))) * 100),
                        $missingDocs,
                        $overdueTasks,
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            \Log::error('Onboarding.Dashboard.ExportError', [
                'tenant' => $tenantModel->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json(['error' => 'Failed to export data'], 500);
        }
    }

    /**
     * Helper: Get dashboard filters from request
     */
    private function getDashboardFilters(Request $request): array
    {
        return [
            'status' => $request->input('status'),
            'department' => $request->input('department'),
            'manager' => $request->input('manager'),
            'dateRange' => $request->input('dateRange'),
            'startDate' => $request->input('startDate'),
            'endDate' => $request->input('endDate'),
        ];
    }

    /**
     * Helper: Apply dashboard filters to query
     */
    private function applyDashboardFilters($query, array $filters): void
    {
        if (!empty($filters['status']) && $filters['status'] !== 'All') {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        if (!empty($filters['manager'])) {
            $query->where('manager', $filters['manager']);
        }

        if (!empty($filters['dateRange'])) {
            switch ($filters['dateRange']) {
                case '7':
                    $query->where('created_at', '>=', now()->subDays(7));
                    break;
                case '30':
                    $query->where('created_at', '>=', now()->subDays(30));
                    break;
                case '90':
                    $query->where('created_at', '>=', now()->subDays(90));
                    break;
            }
        }

        if (!empty($filters['startDate'])) {
            $query->where('created_at', '>=', $filters['startDate']);
        }

        if (!empty($filters['endDate'])) {
            $query->where('created_at', '<=', $filters['endDate']);
        }
    }

    /**
     * Helper: Mask email for Hiring Manager
     */
    private function maskEmail(string $email): string
    {
        if (strpos($email, '@') === false) {
            return $email;
        }
        
        [$local, $domain] = explode('@', $email, 2);
        $maskedLocal = substr($local, 0, 1) . str_repeat('*', max(0, strlen($local) - 1));
        
        return $maskedLocal . '@' . $domain;
    }
}

/**
 * Onboarding Candidate Import Class
 */
class OnboardingCandidateImport implements ToModel, WithHeadingRow, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $tenantId;
    private $source;
    private $rowCount = 0;
    private $createdCount = 0;
    private $updatedCount = 0;
    private $skippedRows = [];
    private $firstRowProcessed = false;

    public function __construct($tenantId, $source = 'Onboarding Import')
    {
        $this->tenantId = $tenantId;
        $this->source = $source;
    }

    public function model(array $row)
    {
        // Log first row to see what columns we're getting
        if (!$this->firstRowProcessed) {
            $this->firstRowProcessed = true;
            \Log::info('Onboarding Import: First row columns', [
                'columns' => array_keys($row),
                'sample_values' => array_map(function($v) {
                    return is_string($v) ? substr($v, 0, 50) : $v;
                }, $row)
            ]);
        }

        // Normalize column names (handle case-insensitive and spaces)
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            if ($key === null || $key === '') {
                continue; // Skip null or empty keys
            }
            $normalizedKey = strtolower(trim(str_replace([' ', '_', '-'], '', (string)$key)));
            $normalizedRow[$normalizedKey] = $value;
        }
        
        // Try multiple possible column names for email (most common variations)
        $email = null;
        $emailKeys = ['primaryemail', 'email', 'e-mail', 'mail', 'emailaddress', 'email_address'];
        foreach ($emailKeys as $key) {
            if (isset($normalizedRow[$key])) {
                $value = $normalizedRow[$key];
                if (!empty($value) && is_string($value)) {
                    $email = trim($value);
                    if (!empty($email)) {
                        break;
                    }
                }
            }
        }
        
        // Also try original keys (case-sensitive)
        if (empty($email)) {
            $originalKeys = ['primary_email', 'email', 'e-mail', 'mail', 'Email', 'EMAIL', 'Primary Email', 'Primary_Email'];
            foreach ($originalKeys as $key) {
                if (isset($row[$key])) {
                    $value = $row[$key];
                    if (!empty($value) && is_string($value)) {
                        $email = trim($value);
                        if (!empty($email)) {
                            break;
                        }
                    }
                }
            }
        }
        
        // Try to find email in any column if still not found (last resort)
        if (empty($email)) {
            foreach ($row as $key => $value) {
                if (is_string($value) && filter_var(trim($value), FILTER_VALIDATE_EMAIL)) {
                    $email = trim($value);
                    \Log::info('Onboarding Import: Found email in unexpected column', [
                        'column' => $key,
                        'email' => $email
                    ]);
                    break;
                }
            }
        }
        
        // Skip if email is empty or invalid
        if (empty($email)) {
            $this->skippedRows[] = [
                'reason' => 'Empty email',
                'columns' => array_keys($row),
                'row_data' => $row
            ];
            \Log::warning('Onboarding Import: Skipping row with empty email', [
                'row_keys' => array_keys($row),
                'row_sample' => array_slice($row, 0, 5)
            ]);
            return null;
        }
        
        // Validate email format
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->skippedRows[] = [
                'reason' => 'Invalid email format',
                'email' => $email,
                'columns' => array_keys($row)
            ];
            \Log::warning('Onboarding Import: Skipping row with invalid email format', [
                'email' => $email,
                'row_keys' => array_keys($row)
            ]);
            return null;
        }

        // Only count valid rows that have email
        $this->rowCount++;

        // Normalize email
        $email = strtolower(trim($email));

        // Check if candidate already exists
        $existingCandidate = Candidate::where('tenant_id', $this->tenantId)
            ->where('primary_email', $email)
            ->first();

        if ($existingCandidate) {
            // Extract other fields with flexible column names
            $firstName = $this->getFieldValue($row, $normalizedRow, ['firstname', 'first_name', 'fname', 'name', 'first'], $existingCandidate->first_name);
            $lastName = $this->getFieldValue($row, $normalizedRow, ['lastname', 'last_name', 'lname', 'surname', 'last'], $existingCandidate->last_name);
            $phone = $this->getFieldValue($row, $normalizedRow, ['primaryphone', 'primary_phone', 'phone', 'mobile', 'contact', 'phonenumber', 'phone_number'], $existingCandidate->primary_phone);
            $designation = $this->getFieldValue($row, $normalizedRow, ['designation', 'role', 'position', 'job_title'], $existingCandidate->designation);
            $department = $this->getFieldValue($row, $normalizedRow, ['department', 'dept'], $existingCandidate->department);
            $manager = $this->getFieldValue($row, $normalizedRow, ['manager', 'reporting_manager', 'manager_name'], $existingCandidate->manager);
            $joiningDate = $this->getFieldValue($row, $normalizedRow, ['joining_date', 'joiningdate', 'join_date', 'joindate', 'start_date'], $existingCandidate->joining_date ? $existingCandidate->joining_date->format('Y-m-d') : null);
            
            // Always set source to 'Onboarding Import' for onboarding imports (regardless of what's in the file)
            // This ensures the candidate will show up in the onboarding list
            $source = $this->source; // Always use 'Onboarding Import'
            
            // Update existing candidate - always set source to 'Onboarding Import' for onboarding imports
            $updateData = [
                'first_name' => $firstName ?? $existingCandidate->first_name,
                'last_name' => $lastName ?? $existingCandidate->last_name,
                'primary_phone' => $phone ?? $existingCandidate->primary_phone,
                'source' => $source, // Always set to 'Onboarding Import'
            ];
            
            // Add onboarding fields if provided
            if ($designation) $updateData['designation'] = $designation;
            if ($department) $updateData['department'] = $department;
            if ($manager) $updateData['manager'] = $manager;
            if ($joiningDate) {
                try {
                    $updateData['joining_date'] = \Carbon\Carbon::parse($joiningDate);
                } catch (\Exception $e) {
                    // Invalid date, skip
                }
            }
            
            $existingCandidate->update($updateData);
            
            $this->updatedCount++;
            
            \Log::debug('Onboarding Import: Updated existing candidate', [
                'email' => $email,
                'tenant_id' => $this->tenantId,
                'old_source' => $existingCandidate->getOriginal('source'),
                'new_source' => $source,
                'candidate_id' => $existingCandidate->id
            ]);
            
            // Return the updated model so Excel knows something was processed
            return $existingCandidate;
        }

        // Extract other fields with flexible column names
        $firstName = $this->getFieldValue($row, $normalizedRow, ['firstname', 'first_name', 'fname', 'name', 'first']);
        $lastName = $this->getFieldValue($row, $normalizedRow, ['lastname', 'last_name', 'lname', 'surname', 'last']);
        $phone = $this->getFieldValue($row, $normalizedRow, ['primaryphone', 'primary_phone', 'phone', 'mobile', 'contact', 'phonenumber', 'phone_number']);
        $designation = $this->getFieldValue($row, $normalizedRow, ['designation', 'role', 'position', 'job_title']);
        $department = $this->getFieldValue($row, $normalizedRow, ['department', 'dept']);
        $manager = $this->getFieldValue($row, $normalizedRow, ['manager', 'reporting_manager', 'manager_name']);
        $joiningDate = $this->getFieldValue($row, $normalizedRow, ['joining_date', 'joiningdate', 'join_date', 'joindate', 'start_date']);
        $source = $this->getFieldValue($row, $normalizedRow, ['source'], $this->source);
        
        // Parse joining date if provided
        $parsedJoiningDate = null;
        if ($joiningDate) {
            try {
                $parsedJoiningDate = \Carbon\Carbon::parse($joiningDate);
            } catch (\Exception $e) {
                // Invalid date, will remain null
            }
        }
        
        // Create new candidate and explicitly save to database
        $candidate = new Candidate([
            'tenant_id' => $this->tenantId,
            'first_name' => $firstName ?? '',
            'last_name' => $lastName ?? '',
            'primary_email' => $email,
            'primary_phone' => $phone ?? null,
            'source' => $source ?? $this->source,
            'designation' => $designation,
            'department' => $department,
            'manager' => $manager,
            'joining_date' => $parsedJoiningDate,
        ]);
        
        // Explicitly save to ensure it's committed to database
        $candidate->save();
        
        $this->createdCount++;

        \Log::debug('Onboarding Import: Created new candidate', [
            'email' => $email,
            'tenant_id' => $this->tenantId,
            'candidate_id' => $candidate->id
        ]);

        return $candidate;
    }

    public function getRowCount()
    {
        return $this->rowCount;
    }
    
    public function getCreatedCount()
    {
        return $this->createdCount;
    }
    
    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }
    
    public function getSkippedRows()
    {
        return $this->skippedRows;
    }
    
    /**
     * Helper method to get field value with flexible column name matching
     */
    private function getFieldValue($row, $normalizedRow, $possibleKeys, $default = null)
    {
        // Try normalized keys first
        foreach ($possibleKeys as $key) {
            $normalizedKey = strtolower(trim(str_replace([' ', '_', '-'], '', (string)$key)));
            if (isset($normalizedRow[$normalizedKey])) {
                $value = $normalizedRow[$normalizedKey];
                if (!empty($value) && is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
        }
        
        // Try original keys (case-sensitive)
        foreach ($possibleKeys as $key) {
            if (isset($row[$key])) {
                $value = $row[$key];
                if (!empty($value) && is_string($value) && trim($value) !== '') {
                    return trim($value);
                }
            }
        }
        
        return $default;
    }
    
    public function getImportedCount()
    {
        // Count actual candidates created/updated
        return Candidate::where('tenant_id', $this->tenantId)
            ->where('source', $this->source)
            ->where('created_at', '>=', now()->subMinutes(5)) // Candidates created in last 5 minutes
            ->count();
    }
}
