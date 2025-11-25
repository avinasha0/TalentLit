<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $tenantModel = tenant();
        
        if (!$tenantModel) {
            abort(500, 'Tenant not resolved. Please check your subdomain configuration.');
        }
        
        return view('tenant.employee-onboarding.all', [
            'tenant' => $tenantModel,
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
        $useMock = env('USE_MOCK_ONBOARDING_DATA', true);
        
        if ($useMock) {
            return $this->getMockOnboardings($request);
        }

        // TODO: Implement real database queries here
        // For now, return mock data
        return $this->getMockOnboardings($request);
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
}

