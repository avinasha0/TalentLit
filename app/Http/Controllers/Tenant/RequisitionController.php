<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Requisition;
use App\Models\JobRequisition;
use App\Models\Department;
use App\Models\Location;
use App\Models\GlobalDepartment;
use App\Models\GlobalLocation;
use App\Models\RequisitionAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    /**
     * Display all requisitions
     */
    public function index(Request $request, string $tenant = null)
    {
        try {
            // Determine created date sort direction with safe defaults
            $createdSort = strtolower($request->input('created_sort', 'desc'));
            if (!in_array($createdSort, ['asc', 'desc'], true)) {
                Log::warning('Invalid created_sort parameter provided, falling back to desc.', [
                    'provided_value' => $request->input('created_sort'),
                    'tenant_id' => tenant_id(),
                    'user_id' => optional(auth()->user())->id,
                ]);
                $createdSort = 'desc';
            }

            // Determine headcount sort direction with safe defaults
            $headcountSort = strtolower($request->input('headcount_sort', ''));
            if (!in_array($headcountSort, ['asc', 'desc'], true)) {
                $headcountSort = null;
            }

            // Use the new Requisition model
            $query = Requisition::query();

            // Apply sorting - headcount sort takes priority if specified
            if ($headcountSort) {
                $query->orderBy('headcount', $headcountSort);
                // Secondary sort by created_at for consistent ordering
                $query->orderBy('created_at', 'desc');
            } else {
                $query->orderBy('created_at', $createdSort);
            }

            // Apply filters
            if ($request->filled('status')) {
                // Map lowercase status to capitalized format
                $statusMap = [
                    'draft' => 'Draft',
                    'pending' => 'Pending',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                ];
                $status = $statusMap[strtolower($request->status)] ?? $request->status;
                $query->where('status', $status);
            }

            if ($request->filled('department')) {
                $query->where('department', 'like', '%'.$request->department.'%');
            }

            if ($request->filled('keyword')) {
                $keyword = $request->keyword;
                $query->where(function ($q) use ($keyword) {
                    $q->where('job_title', 'like', '%'.$keyword.'%')
                        ->orWhere('justification', 'like', '%'.$keyword.'%')
                        ->orWhere('department', 'like', '%'.$keyword.'%');
                });
            }

            $requisitions = $query->paginate(20);

            // Get filter options for departments (from existing tables for backward compatibility)
            $departments = Department::orderBy('name')->pluck('name')->unique()->values();
            $globalDepartments = GlobalDepartment::orderBy('name')->pluck('name')->unique()->values();
            $allDepartments = $departments->merge($globalDepartments)->unique()->sort()->values();

            // Also get unique departments from requisitions table
            $requisitionDepartments = Requisition::distinct()->pluck('department')->filter()->sort()->values();
            $allDepartments = $allDepartments->merge($requisitionDepartments)->unique()->sort()->values();

            $locations = Location::orderBy('name')->pluck('name')->unique()->values();
            $globalLocations = GlobalLocation::orderBy('name')->pluck('name')->unique()->values();
            $allLocations = $locations->merge($globalLocations)->unique()->sort()->values();

            // Status options matching the new table enum
            $statuses = ['Draft', 'Pending', 'Approved', 'Rejected'];

            // Log for debugging
            Log::info('RequisitionController@index', [
                'total_requisitions' => $requisitions->total(),
                'current_page' => $requisitions->currentPage(),
                'filters' => $request->only(['status', 'department', 'keyword', 'created_sort']),
                'created_sort_applied' => $createdSort,
            ]);

            return view('tenant.requisitions.index', compact('requisitions', 'allDepartments', 'allLocations', 'statuses'));
        } catch (\Exception $e) {
            Log::error('RequisitionController@index error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Display the specified requisition
     */
    public function show($id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);

            Log::info('RequisitionController@show', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return view('tenant.requisitions.show', compact('requisition'));
        } catch (\Exception $e) {
            Log::error('RequisitionController@show error', [
                'requisition_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect(tenantRoute('tenant.requisitions.index', $tenant))
                ->with('error', 'Requisition not found.');
        }
    }

    /**
     * Show the form for creating a new requisition
     */
    public function create(string $tenant = null)
    {
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $globalDepartments = GlobalDepartment::orderBy('name')->get();
        $globalLocations = GlobalLocation::orderBy('name')->get();

        return view('tenant.requisitions.create', compact('departments', 'locations', 'globalDepartments', 'globalLocations'));
    }

    /**
     * Store a newly created requisition
     */
    public function store(Request $request, string $tenant = null)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'headcount' => 'required|integer|min:1',
            'department_id' => 'nullable|exists:departments,id',
            'location_id' => 'nullable|exists:locations,id',
            'global_department_id' => 'nullable|exists:global_departments,id',
            'global_location_id' => 'nullable|exists:global_locations,id',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $validated['status'] = $validated['status'] ?? 'pending';
        $validated['tenant_id'] = tenant_id();

        JobRequisition::create($validated);

        // Use tenantRoute helper which automatically handles both slug and subdomain routes
        $tenantModel = tenant();
        $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
        
        return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
            ->with('success', 'Requisition created successfully.');
    }

    /**
     * Display pending requisitions
     */
    public function pending(Request $request, string $tenant = null)
    {
        $query = Requisition::query()
            ->where('status', 'Pending')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('job_title', 'like', '%'.$keyword.'%')
                    ->orWhere('justification', 'like', '%'.$keyword.'%')
                    ->orWhere('department', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.pending', compact('requisitions'));
    }

    /**
     * Display approved requisitions
     */
    public function approved(Request $request, string $tenant = null)
    {
        $query = Requisition::query()
            ->where('status', 'Approved')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('job_title', 'like', '%'.$keyword.'%')
                    ->orWhere('justification', 'like', '%'.$keyword.'%')
                    ->orWhere('department', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.approved', compact('requisitions'));
    }

    /**
     * Display rejected requisitions
     */
    public function rejected(Request $request, string $tenant = null)
    {
        $query = Requisition::query()
            ->where('status', 'Rejected')
            ->orderBy('created_at', 'desc');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('job_title', 'like', '%'.$keyword.'%')
                    ->orWhere('justification', 'like', '%'.$keyword.'%')
                    ->orWhere('department', 'like', '%'.$keyword.'%');
            });
        }

        $requisitions = $query->paginate(20);

        return view('tenant.requisitions.rejected', compact('requisitions'));
    }

    /**
     * Approve a requisition
     */
    public function approve(Request $request, $id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);
            $oldStatus = $requisition->status;
            $requisition->status = 'Approved';
            $requisition->save();

            // Log the status change
            RequisitionAuditLog::create([
                'requisition_id' => $id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'field_name' => 'status',
                'old_value' => $oldStatus,
                'new_value' => 'Approved',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Log::info('Requisition approved', ['requisition_id' => $id, 'user_id' => auth()->id()]);

            return redirect()->back()->with('success', 'Requisition approved successfully.');
        } catch (\Exception $e) {
            Log::error('Requisition approval failed', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to approve requisition.');
        }
    }

    /**
     * Reject a requisition
     */
    public function reject(Request $request, $id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);
            $oldStatus = $requisition->status;
            $requisition->status = 'Rejected';
            $requisition->save();

            // Log the status change
            RequisitionAuditLog::create([
                'requisition_id' => $id,
                'user_id' => auth()->id(),
                'action' => 'status_changed',
                'field_name' => 'status',
                'old_value' => $oldStatus,
                'new_value' => 'Rejected',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            Log::info('Requisition rejected', ['requisition_id' => $id, 'user_id' => auth()->id()]);

            return redirect()->back()->with('success', 'Requisition rejected successfully.');
        } catch (\Exception $e) {
            Log::error('Requisition rejection failed', [
                'requisition_id' => $id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Failed to reject requisition.');
        }
    }

    /**
     * Show the form for editing the specified requisition
     */
    public function edit($id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);

            Log::info('RequisitionController@edit', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
            ]);

            // For now, redirect to show page since update logic is not needed
            // This allows navigation to work without implementing full edit functionality
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.show', [$tenantSlug, $id]))
                ->with('info', 'Edit functionality will be available soon.');
        } catch (\Exception $e) {
            Log::error('RequisitionController@edit error', [
                'requisition_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
                ->with('error', 'Requisition not found.');
        }
    }

    /**
     * Export requisitions to CSV
     */
    public function exportCsv(Request $request, string $tenant = null)
    {
        try {
            $query = $this->buildFilteredQuery($request);
            $requisitions = $query->get();

            $filename = 'requisitions_export_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($requisitions) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8 (helps Excel recognize UTF-8 encoding)
                fwrite($file, "\xEF\xBB\xBF");
                
                // Headers
                fputcsv($file, [
                    'Job Title',
                    'Department',
                    'Headcount',
                    'Contract Type',
                    'Status',
                    'Created Date',
                ]);
                
                // Data rows
                foreach ($requisitions as $requisition) {
                    fputcsv($file, [
                        $requisition->job_title ?? '',
                        $requisition->department ?? 'N/A',
                        $requisition->headcount ?? 0,
                        $requisition->contract_type ?? 'N/A',
                        $requisition->status ?? 'Draft',
                        $requisition->created_at ? $requisition->created_at->format('Y-m-d') : '',
                    ]);
                }
                
                fclose($file);
            };

            Log::info('RequisitionController@exportCsv', [
                'exported_count' => $requisitions->count(),
                'user_id' => auth()->id(),
            ]);

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('RequisitionController@exportCsv error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
                ->with('error', 'Failed to export requisitions. Please try again.');
        }
    }

    /**
     * Export requisitions to Excel
     */
    public function exportExcel(Request $request, string $tenant = null)
    {
        try {
            $query = $this->buildFilteredQuery($request);
            $requisitions = $query->get();

            $filename = 'requisitions_export_' . date('Y-m-d_His') . '.xlsx';

            // Check if Maatwebsite\Excel is available
            if (class_exists(\Maatwebsite\Excel\Facades\Excel::class)) {
                $export = new class($requisitions) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                    protected $requisitions;
                    
                    public function __construct($requisitions) {
                        $this->requisitions = $requisitions;
                    }
                    
                    public function collection() {
                        return $this->requisitions->map(function ($requisition) {
                            return [
                                $requisition->job_title ?? '',
                                $requisition->department ?? 'N/A',
                                $requisition->headcount ?? 0,
                                $requisition->contract_type ?? 'N/A',
                                $requisition->status ?? 'Draft',
                                $requisition->created_at ? $requisition->created_at->format('Y-m-d') : '',
                            ];
                        });
                    }
                    
                    public function headings(): array {
                        return [
                            'Job Title',
                            'Department',
                            'Headcount',
                            'Contract Type',
                            'Status',
                            'Created Date',
                        ];
                    }
                };
                
                return \Maatwebsite\Excel\Facades\Excel::download($export, $filename);
            } else {
                // Fallback to CSV if Excel package is not available
                Log::warning('Excel package not available, falling back to CSV export');
                return $this->exportCsv($request, $tenant);
            }
        } catch (\Exception $e) {
            Log::error('RequisitionController@exportExcel error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
                ->with('error', 'Failed to export requisitions. Please try again.');
        }
    }

    /**
     * Build filtered query based on request parameters
     */
    private function buildFilteredQuery(Request $request)
    {
        $query = Requisition::query();

        // Apply filters
        if ($request->filled('status')) {
            $statusMap = [
                'draft' => 'Draft',
                'pending' => 'Pending',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
            ];
            $status = $statusMap[strtolower($request->status)] ?? $request->status;
            $query->where('status', $status);
        }

        if ($request->filled('department')) {
            $query->where('department', 'like', '%'.$request->department.'%');
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('job_title', 'like', '%'.$keyword.'%')
                    ->orWhere('justification', 'like', '%'.$keyword.'%')
                    ->orWhere('department', 'like', '%'.$keyword.'%');
            });
        }

        // Apply sorting - headcount sort takes priority if specified
        $headcountSort = strtolower($request->input('headcount_sort', ''));
        if (in_array($headcountSort, ['asc', 'desc'], true)) {
            $query->orderBy('headcount', $headcountSort);
            // Secondary sort by created_at for consistent ordering
            $query->orderBy('created_at', 'desc');
        } else {
            $createdSort = strtolower($request->input('created_sort', 'desc'));
            if (!in_array($createdSort, ['asc', 'desc'], true)) {
                $createdSort = 'desc';
            }
            $query->orderBy('created_at', $createdSort);
        }

        return $query;
    }

    /**
     * Bulk delete requisitions (soft delete)
     */
    public function bulkDelete(Request $request, string $tenant = null)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:requisitions,id',
            ]);

            $ids = $request->input('ids');
            $deletedCount = 0;

            DB::beginTransaction();
            try {
                foreach ($ids as $id) {
                    $requisition = Requisition::find($id);
                    if ($requisition && !$requisition->trashed()) {
                        $requisition->delete(); // Soft delete
                        
                        // Log the deletion
                        RequisitionAuditLog::create([
                            'requisition_id' => $id,
                            'user_id' => auth()->id(),
                            'action' => 'deleted',
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);
                        
                        $deletedCount++;
                    }
                }
                DB::commit();

                Log::info('Bulk delete requisitions', [
                    'deleted_count' => $deletedCount,
                    'user_id' => auth()->id(),
                    'ids' => $ids,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} requisition(s) deleted successfully.",
                    'deleted_count' => $deletedCount,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Bulk delete validation failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_map(fn($err) => implode(', ', $err), $e->errors())),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk delete requisitions failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete requisitions. Please try again.',
            ], 500);
        }
    }

    /**
     * Bulk update status to Pending
     */
    public function bulkStatusUpdate(Request $request, string $tenant = null)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:requisitions,id',
                'status' => 'required|in:Pending',
            ]);

            $ids = $request->input('ids');
            $status = $request->input('status');
            $updatedCount = 0;

            DB::beginTransaction();
            try {
                foreach ($ids as $id) {
                    $requisition = Requisition::find($id);
                    if ($requisition && $requisition->status !== $status) {
                        $oldStatus = $requisition->status;
                        $requisition->status = $status;
                        $requisition->save();

                        // Log the status change
                        RequisitionAuditLog::create([
                            'requisition_id' => $id,
                            'user_id' => auth()->id(),
                            'action' => 'status_changed',
                            'field_name' => 'status',
                            'old_value' => $oldStatus,
                            'new_value' => $status,
                            'ip_address' => $request->ip(),
                            'user_agent' => $request->userAgent(),
                        ]);

                        $updatedCount++;
                    }
                }
                DB::commit();

                Log::info('Bulk status update requisitions', [
                    'updated_count' => $updatedCount,
                    'status' => $status,
                    'user_id' => auth()->id(),
                    'ids' => $ids,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "Status updated successfully for {$updatedCount} requisition(s).",
                    'updated_count' => $updatedCount,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Bulk status update validation failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_map(fn($err) => implode(', ', $err), $e->errors())),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Bulk status update requisitions failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status. Please try again.',
            ], 500);
        }
    }

    /**
     * Duplicate a requisition
     */
    public function duplicate(Request $request, $id, string $tenant = null)
    {
        try {
            $original = Requisition::findOrFail($id);

            DB::beginTransaction();
            try {
                $duplicate = $original->replicate();
                $duplicate->status = 'Draft';
                $duplicate->created_by = auth()->id();
                $duplicate->save();

                // Log the duplication
                RequisitionAuditLog::create([
                    'requisition_id' => $duplicate->id,
                    'user_id' => auth()->id(),
                    'action' => 'created',
                    'changes' => ['duplicated_from' => $original->id],
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                Log::info('Requisition duplicated', [
                    'original_id' => $id,
                    'duplicate_id' => $duplicate->id,
                    'user_id' => auth()->id(),
                ]);

                $tenantModel = tenant();
                $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;

                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Requisition duplicated successfully.',
                        'redirect_url' => tenantRoute('tenant.requisitions.show', [$tenantSlug, $duplicate->id]),
                    ]);
                }

                return redirect(tenantRoute('tenant.requisitions.show', [$tenantSlug, $duplicate->id]))
                    ->with('success', 'Requisition duplicated successfully. You can now edit the draft.');
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Requisition duplication failed', [
                'requisition_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;

            return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
                ->with('error', 'Failed to duplicate requisition. Please try again.');
        }
    }

    /**
     * Get audit log for a requisition
     */
    public function auditLog($id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);
            $auditLogs = RequisitionAuditLog::where('requisition_id', $id)
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'requisition' => [
                    'id' => $requisition->id,
                    'job_title' => $requisition->job_title,
                    'status' => $requisition->status,
                    'created_at' => $requisition->created_at?->format('Y-m-d H:i:s'),
                    'created_by' => $requisition->creator?->name ?? 'N/A',
                ],
                'audit_logs' => $auditLogs->map(function ($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'field_name' => $log->field_name,
                        'old_value' => $log->old_value,
                        'new_value' => $log->new_value,
                        'changes' => $log->changes,
                        'user' => $log->user ? [
                            'id' => $log->user->id,
                            'name' => $log->user->name,
                            'email' => $log->user->email,
                        ] : null,
                        'created_at' => $log->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
            ]);
        } catch (\Exception $e) {
            Log::error('Get audit log failed', [
                'requisition_id' => $id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load audit log.',
            ], 500);
        }
    }
}

