<?php

namespace App\Http\Controllers\Tenant\EmployeeOnboarding;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OnboardingApiController extends Controller
{
    /**
     * Get all onboardings with filters and pagination
     * 
     * GET /api/{tenant}/onboardings
     */
    public function index(Request $request)
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Check if using mock data (set USE_MOCK_ONBOARDING_DATA=true in .env)
        $useMock = env('USE_MOCK_ONBOARDING_DATA', true);

        if ($useMock) {
            return $this->getMockData($request);
        }

        // TODO: Implement real database queries here
        // For now, return mock data
        return $this->getMockData($request);
    }

    /**
     * Get single onboarding details for slide-over
     * 
     * GET /api/{tenant}/onboardings/{id}
     */
    public function show(Request $request, $id)
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Mock data for single onboarding
        $mockOnboardings = $this->getMockOnboardings();
        $onboarding = collect($mockOnboardings)->firstWhere('id', (int)$id);

        if (!$onboarding) {
            return response()->json(['error' => 'Onboarding not found'], 404);
        }

        // Add detailed sections for slide-over
        $onboarding['overview'] = [
            'startedAt' => '2025-11-15T10:00:00+05:30',
            'expectedCompletion' => '2025-12-01T18:00:00+05:30',
            'completedTasks' => $onboarding['progressPercent'],
            'totalTasks' => 100,
        ];

        $onboarding['tasks'] = [
            ['id' => 1, 'title' => 'Complete background check', 'status' => 'completed', 'dueDate' => '2025-11-20'],
            ['id' => 2, 'title' => 'Submit tax forms', 'status' => 'pending', 'dueDate' => '2025-11-25'],
            ['id' => 3, 'title' => 'IT equipment setup', 'status' => 'in_progress', 'dueDate' => '2025-11-28'],
        ];

        $onboarding['documents'] = [
            ['id' => 1, 'name' => 'Employment Contract', 'status' => 'signed', 'uploadedAt' => '2025-11-16'],
            ['id' => 2, 'name' => 'ID Proof', 'status' => 'pending', 'uploadedAt' => null],
        ];

        $onboarding['itAssets'] = [
            ['id' => 1, 'item' => 'Laptop', 'status' => 'allocated', 'serialNumber' => 'LT-12345'],
            ['id' => 2, 'item' => 'Monitor', 'status' => 'pending', 'serialNumber' => null],
        ];

        $onboarding['approvals'] = [
            ['id' => 1, 'type' => 'Manager Approval', 'status' => 'approved', 'approvedBy' => 'Priya Patel', 'approvedAt' => '2025-11-16'],
            ['id' => 2, 'type' => 'HR Approval', 'status' => 'pending', 'approvedBy' => null, 'approvedAt' => null],
        ];

        return response()->json($onboarding);
    }

    /**
     * Send reminder to selected onboardings
     * 
     * POST /api/{tenant}/onboardings/bulk/remind
     */
    public function bulkRemind(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // TODO: Implement actual reminder sending logic
        // For now, just return success
        Log::info('Bulk reminder sent', [
            'tenant_id' => $tenant->id,
            'ids' => $request->ids,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Mark selected onboardings as complete
     * 
     * POST /api/{tenant}/onboardings/bulk/complete
     */
    public function bulkComplete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // TODO: Implement actual completion logic
        Log::info('Bulk complete', [
            'tenant_id' => $tenant->id,
            'ids' => $request->ids,
        ]);

        return response()->json(['ok' => true]);
    }

    /**
     * Convert onboarding to employee
     * 
     * POST /api/{tenant}/onboardings/{id}/convert
     */
    public function convert(Request $request, $id)
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // TODO: Implement actual conversion logic
        // For now, return mock employee ID
        Log::info('Onboarding converted to employee', [
            'tenant_id' => $tenant->id,
            'onboarding_id' => $id,
        ]);

        return response()->json([
            'ok' => true,
            'employeeId' => rand(900, 999), // Mock employee ID
        ]);
    }

    /**
     * Export onboardings to CSV
     * 
     * GET /api/{tenant}/onboardings/export
     */
    public function export(Request $request)
    {
        $tenant = tenant();
        
        if (!$tenant) {
            return response()->json(['error' => 'Tenant not found'], 404);
        }

        // Get filtered data
        $data = $this->getMockData($request, false); // Get all, not paginated
        
        // Generate CSV
        $filename = 'onboardings_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
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

            // CSV rows
            foreach ($data['data'] as $row) {
                fputcsv($file, [
                    $row['fullName'],
                    $row['email'],
                    $row['designation'],
                    $row['department'],
                    $row['manager'],
                    $row['joiningDate'],
                    $row['progressPercent'],
                    $row['status'],
                    $row['lastUpdated'],
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get mock data for onboardings
     */
    private function getMockData(Request $request, $paginate = true)
    {
        $onboardings = $this->getMockOnboardings();

        // Apply search filter
        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $onboardings = array_filter($onboardings, function($item) use ($search) {
                return str_contains(strtolower($item['fullName']), $search) ||
                       str_contains(strtolower($item['email']), $search) ||
                       str_contains(strtolower($item['department']), $search) ||
                       str_contains(strtolower($item['designation']), $search);
            });
        }

        // Apply status filter
        if ($request->filled('status') && $request->status !== 'All') {
            $onboardings = array_filter($onboardings, function($item) use ($request) {
                return $item['status'] === $request->status;
            });
        }

        // Apply department filter
        if ($request->filled('department')) {
            $onboardings = array_filter($onboardings, function($item) use ($request) {
                return $item['department'] === $request->department;
            });
        }

        // Apply manager filter
        if ($request->filled('manager')) {
            $onboardings = array_filter($onboardings, function($item) use ($request) {
                return $item['manager'] === $request->manager;
            });
        }

        // Apply joining month filter
        if ($request->filled('joiningMonth')) {
            $onboardings = array_filter($onboardings, function($item) use ($request) {
                $joiningMonth = date('Y-m', strtotime($item['joiningDate']));
                return $joiningMonth === $request->joiningMonth;
            });
        }

        // Reset array keys
        $onboardings = array_values($onboardings);

        // Apply sorting
        $sortBy = $request->get('sortBy', 'joiningDate');
        $sortDir = $request->get('sortDir', 'asc');
        
        usort($onboardings, function($a, $b) use ($sortBy, $sortDir) {
            $aVal = $a[$sortBy] ?? '';
            $bVal = $b[$sortBy] ?? '';
            
            if ($sortBy === 'joiningDate') {
                $aVal = strtotime($aVal);
                $bVal = strtotime($bVal);
            } elseif ($sortBy === 'fullName') {
                $aVal = strtolower($aVal);
                $bVal = strtolower($bVal);
            }
            
            if ($sortDir === 'asc') {
                return $aVal <=> $bVal;
            } else {
                return $bVal <=> $aVal;
            }
        });

        // Pagination
        if ($paginate) {
            $page = (int) $request->get('page', 1);
            $pageSize = (int) $request->get('pageSize', 10);
            $total = count($onboardings);
            $offset = ($page - 1) * $pageSize;
            $paginated = array_slice($onboardings, $offset, $pageSize);

            return response()->json([
                'data' => $paginated,
                'meta' => [
                    'page' => $page,
                    'pageSize' => $pageSize,
                    'total' => $total,
                    'totalPages' => ceil($total / $pageSize),
                ],
            ]);
        }

        return ['data' => $onboardings];
    }

    /**
     * Get mock onboardings data
     */
    private function getMockOnboardings()
    {
        return [
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
                'lastUpdated' => '2025-11-25T14:20:00+05:30',
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
                'lastUpdated' => '2025-11-22T09:15:00+05:30',
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
                'lastUpdated' => '2025-11-18T16:45:00+05:30',
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
                'lastUpdated' => '2025-11-15T11:00:00+05:30',
            ],
            [
                'id' => 128,
                'firstName' => 'Priya',
                'lastName' => 'Patel',
                'fullName' => 'Priya Patel',
                'email' => 'priya.patel@company.com',
                'avatarUrl' => null,
                'designation' => 'Engineering Manager',
                'department' => 'Engineering',
                'manager' => 'Ravi Shankar',
                'joiningDate' => '2025-12-10',
                'progressPercent' => 90,
                'pendingItems' => 1,
                'status' => 'Joining Soon',
                'lastUpdated' => '2025-11-24T13:30:00+05:30',
            ],
        ];
    }
}

