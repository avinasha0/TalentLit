<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        // Simple method: fetch all onboarding records from database
        $onboardings = \App\Models\Onboarding::orderBy('created_at', 'desc')->get();
        
        return view('freeplan.employee-onboarding.all', compact('onboardings'));
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
        \Log::info('Employee Onboarding API Query', [
            'tenant_id' => $tenantModel->id,
            'tenant_slug' => $tenantModel->slug,
            'count_before_filters' => $countBeforeFilters,
            'request_params' => $request->all(),
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
    public function apiShow(Request $request, $id)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Mock response
        $mockData = $this->getMockOnboardings($request);
        $onboarding = collect($mockData['data'])->firstWhere('id', (int)$id);
        
        if (!$onboarding) {
            return response()->json(['error' => 'Onboarding not found'], 404);
        }

        // Add detailed tabs data
        $onboarding['tabs'] = [
            'overview' => [
                'startedAt' => '2025-11-15T10:00:00+05:30',
                'lastActivity' => '2025-11-20T14:30:00+05:30',
                'manager' => $onboarding['manager'],
                'department' => $onboarding['department'],
            ],
            'tasks' => [
                'total' => 12,
                'completed' => $onboarding['progressPercent'] / 100 * 12,
                'pending' => $onboarding['pendingItems'],
            ],
            'documents' => [],
            'itAssets' => [],
            'approvals' => [],
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
     * API: Convert onboarding to employee
     */
    public function apiConvert(Request $request, $id)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // TODO: Implement actual conversion logic
        // For now, return mock success
        return response()->json([
            'ok' => true,
            'employeeId' => rand(900, 999), // Mock employee ID
        ]);
    }

    /**
     * API: Export CSV of all filtered onboardings
     */
    public function apiExportCSV(Request $request)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
        }

        // Get filtered data (all pages, no pagination)
        $useMock = env('USE_MOCK_ONBOARDING_DATA', true);
        
        if ($useMock) {
            $onboardings = $this->getAllOnboardingsForExport($request);
        } else {
            // TODO: Implement real database queries
            $onboardings = [];
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="onboardings-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($onboardings) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'candidate_name',
                'email',
                'role',
                'department',
                'manager',
                'joining_date',
                'progress_percent',
                'status',
                'last_updated'
            ]);
            
            // Data rows
            foreach ($onboardings as $onboarding) {
                fputcsv($file, [
                    $onboarding['fullName'] ?? '',
                    $onboarding['email'] ?? '',
                    $onboarding['designation'] ?? '',
                    $onboarding['department'] ?? '',
                    $onboarding['manager'] ?? '',
                    $onboarding['joiningDate'] ?? '',
                    $onboarding['progressPercent'] ?? 0,
                    $onboarding['status'] ?? '',
                    $onboarding['lastUpdated'] ?? ''
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import candidates for onboarding
     */
    public function importCandidates(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            return response()->json(['error' => 'Tenant not resolved'], 500);
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
            // Create a simple import class for onboarding
            $import = new OnboardingCandidateImport(tenant_id(), 'Onboarding Import');
            
            Excel::import($import, $request->file('file'));

            $importedCount = $import->getRowCount();
            
            // Verify candidates were actually created/updated
            $actualCount = \App\Models\Candidate::where('tenant_id', tenant_id())
                ->where(function($q) {
                    $q->where('source', 'Onboarding')
                      ->orWhere('source', 'Onboarding Import');
                })
                ->count();
            
            \Log::info('Onboarding Import Completed', [
                'tenant_id' => tenant_id(),
                'rows_processed' => $importedCount,
                'actual_candidates_count' => $actualCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$importedCount} candidates for onboarding.",
                'count' => $importedCount,
                'actual_count' => $actualCount
            ]);
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
    public function downloadImportTemplate(string $tenant = null)
    {
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
                'source',
                'tags'
            ]);
            
            // Sample data
            fputcsv($file, [
                'John',
                'Doe',
                'john.doe@example.com',
                '+1234567890',
                'Onboarding',
                'New Hire,Full-time'
            ]);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
}

/**
 * Onboarding Candidate Import Class
 */
class OnboardingCandidateImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    private $tenantId;
    private $source;
    private $rowCount = 0;

    public function __construct($tenantId, $source = 'Onboarding Import')
    {
        $this->tenantId = $tenantId;
        $this->source = $source;
    }

    public function model(array $row)
    {
        $this->rowCount++;

        // Skip if email is empty
        if (empty($row['primary_email'])) {
            return null;
        }

        // Check if candidate already exists
        $existingCandidate = Candidate::where('tenant_id', $this->tenantId)
            ->where('primary_email', $row['primary_email'])
            ->first();

        if ($existingCandidate) {
            // Update existing candidate - always set source to 'Onboarding Import' for onboarding imports
            $existingCandidate->update([
                'first_name' => $row['first_name'] ?? $existingCandidate->first_name,
                'last_name' => $row['last_name'] ?? $existingCandidate->last_name,
                'primary_phone' => $row['primary_phone'] ?? $existingCandidate->primary_phone,
                'source' => $row['source'] ?? $this->source, // Use import source if not provided in CSV
            ]);
            return null; // Don't create new model
        }

        // Create new candidate
        return new Candidate([
            'tenant_id' => $this->tenantId,
            'first_name' => $row['first_name'] ?? '',
            'last_name' => $row['last_name'] ?? '',
            'primary_email' => $row['primary_email'],
            'primary_phone' => $row['primary_phone'] ?? null,
            'source' => $row['source'] ?? $this->source,
        ]);
    }

    public function rules(): array
    {
        return [
            'primary_email' => 'required|email',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'primary_phone' => 'nullable|string|max:255',
            'source' => 'nullable|string|max:255',
        ];
    }

    public function getRowCount()
    {
        return $this->rowCount;
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

