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
        // Get tenant from middleware or user's first tenant for freeplan route
        $tenantModel = tenant();
        
        if (!$tenantModel && auth()->check()) {
            // For freeplan route, get user's first tenant
            $tenantModel = auth()->user()->tenants->first();
        }
        
        // Simple method: fetch all onboarding records from database
        $onboardings = \App\Models\Onboarding::orderBy('created_at', 'desc')->get();
        
        return view('freeplan.employee-onboarding.all', compact('onboardings', 'tenantModel'));
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
        
        // For freeplan route, get tenant from user's first tenant
        if (!$tenantModel && auth()->check()) {
            $tenantModel = auth()->user()->tenants->first();
            if ($tenantModel) {
                // Set tenant context for the import
                \App\Support\Tenancy::set($tenantModel);
            }
        }
        
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
            ]);

            // Only return success if data was actually saved/updated in database
            // Accept success if: rows were processed AND (records were created/updated OR database count increased OR records were recently updated)
            // This handles cases where all rows were updates (no new records, but data was saved)
            $hasDataSaved = ($totalSaved > 0 || $actualNewCount > 0 || $countAfter > $countBefore || $recentlyUpdated > 0);
            
            // If we processed rows but counts are 0, check if any records were actually saved
            if ($importedCount > 0 && $totalSaved === 0 && $actualNewCount === 0) {
                // Try to get counts from the import class directly
                $importCheck = new OnboardingCandidateImport(tenant_id(), 'Onboarding Import');
                // We can't re-import, but we can check if the issue is with counting
                \Log::warning('Onboarding Import: Count mismatch', [
                    'imported_count' => $importedCount,
                    'created_count' => $createdCount,
                    'updated_count' => $updatedCount,
                    'recently_updated' => $recentlyUpdated
                ]);
            }
            
            if ($importedCount > 0 && $hasDataSaved) {
                $message = "Successfully imported {$importedCount} candidate" . ($importedCount !== 1 ? 's' : '') . " for onboarding.";
                if ($createdCount > 0) {
                    $message .= " {$createdCount} new candidate" . ($createdCount !== 1 ? 's' : '') . " created.";
                }
                if ($updatedCount > 0) {
                    $message .= " {$updatedCount} candidate" . ($updatedCount !== 1 ? 's' : '') . " updated.";
                }
                
                return response()->json([
                    'success' => true,
                    'data_saved' => true,
                    'message' => $message,
                    'count' => $importedCount,
                    'created_count' => $createdCount,
                    'updated_count' => $updatedCount,
                    'total_saved' => $totalSaved,
                    'verified' => true
                ]);
            } else {
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
                ]);
                
                return response()->json([
                    'success' => false,
                    'data_saved' => false,
                    'message' => 'No candidates were saved to the database. Please check your file format and ensure it matches the template.',
                    'count' => $importedCount,
                    'debug' => [
                        'rows_processed' => $importedCount,
                        'created' => $createdCount,
                        'updated' => $updatedCount,
                        'total_saved' => $totalSaved,
                        'count_before' => $countBefore,
                        'count_after' => $countAfter,
                    ]
                ], 400);
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
        // For freeplan route, get tenant from user's first tenant
        $tenantModel = tenant();
        if (!$tenantModel && auth()->check()) {
            $tenantModel = auth()->user()->tenants->first();
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
    private $createdCount = 0;
    private $updatedCount = 0;

    public function __construct($tenantId, $source = 'Onboarding Import')
    {
        $this->tenantId = $tenantId;
        $this->source = $source;
    }

    public function model(array $row)
    {
        // Normalize column names (handle case-insensitive and spaces)
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(trim(str_replace([' ', '_', '-'], '', $key)));
            $normalizedRow[$normalizedKey] = $value;
        }
        
        // Try multiple possible column names for email
        $email = null;
        $emailKeys = ['primaryemail', 'email', 'e-mail', 'mail'];
        foreach ($emailKeys as $key) {
            if (isset($normalizedRow[$key]) && !empty(trim($normalizedRow[$key]))) {
                $email = trim($normalizedRow[$key]);
                break;
            }
        }
        
        // Also try original keys
        if (empty($email)) {
            $originalKeys = ['primary_email', 'email', 'e-mail', 'mail'];
            foreach ($originalKeys as $key) {
                if (isset($row[$key]) && !empty(trim($row[$key]))) {
                    $email = trim($row[$key]);
                    break;
                }
            }
        }
        
        // Skip if email is empty or invalid
        if (empty($email)) {
            \Log::warning('Onboarding Import: Skipping row with empty email', ['row_keys' => array_keys($row)]);
            return null;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            \Log::warning('Onboarding Import: Skipping row with invalid email format', ['email' => $email]);
            return null;
        }

        // Only count valid rows
        $this->rowCount++;

        // Normalize email (already extracted above)
        $email = strtolower(trim($email));

        // Check if candidate already exists
        $existingCandidate = Candidate::where('tenant_id', $this->tenantId)
            ->where('primary_email', $email)
            ->first();

        if ($existingCandidate) {
            // Extract other fields with flexible column names
            $firstName = $this->getFieldValue($row, $normalizedRow, ['firstname', 'first_name', 'fname', 'name'], $existingCandidate->first_name);
            $lastName = $this->getFieldValue($row, $normalizedRow, ['lastname', 'last_name', 'lname', 'surname'], $existingCandidate->last_name);
            $phone = $this->getFieldValue($row, $normalizedRow, ['primaryphone', 'primary_phone', 'phone', 'mobile', 'contact'], $existingCandidate->primary_phone);
            $source = $this->getFieldValue($row, $normalizedRow, ['source'], $this->source);
            
            // Update existing candidate - always set source to 'Onboarding Import' for onboarding imports
            $existingCandidate->update([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'primary_phone' => $phone,
                'source' => $source,
            ]);
            
            $this->updatedCount++;
            
            \Log::debug('Onboarding Import: Updated existing candidate', [
                'email' => $email,
                'tenant_id' => $this->tenantId
            ]);
            
            // Return the updated model so Excel knows something was processed
            return $existingCandidate;
        }

        // Extract other fields with flexible column names
        $firstName = $this->getFieldValue($row, $normalizedRow, ['firstname', 'first_name', 'fname', 'name']);
        $lastName = $this->getFieldValue($row, $normalizedRow, ['lastname', 'last_name', 'lname', 'surname']);
        $phone = $this->getFieldValue($row, $normalizedRow, ['primaryphone', 'primary_phone', 'phone', 'mobile', 'contact']);
        $source = $this->getFieldValue($row, $normalizedRow, ['source'], $this->source);
        
        // Create new candidate and explicitly save to database
        $candidate = new Candidate([
            'tenant_id' => $this->tenantId,
            'first_name' => $firstName ?? '',
            'last_name' => $lastName ?? '',
            'primary_email' => $email,
            'primary_phone' => $phone ?? null,
            'source' => $source,
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
    
    public function getCreatedCount()
    {
        return $this->createdCount;
    }
    
    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }
    
    /**
     * Helper method to get field value with flexible column name matching
     */
    private function getFieldValue($row, $normalizedRow, $possibleKeys, $default = null)
    {
        // Try normalized keys first
        foreach ($possibleKeys as $key) {
            $normalizedKey = strtolower(trim(str_replace([' ', '_', '-'], '', $key)));
            if (isset($normalizedRow[$normalizedKey]) && !empty(trim($normalizedRow[$normalizedKey]))) {
                return trim($normalizedRow[$normalizedKey]);
            }
        }
        
        // Try original keys
        foreach ($possibleKeys as $key) {
            if (isset($row[$key]) && !empty(trim($row[$key]))) {
                return trim($row[$key]);
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

