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
use Illuminate\Support\Facades\Schema;

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

            // Determine job title sort direction with safe defaults
            $jobTitleSort = strtolower($request->input('job_title_sort', ''));
            if (!in_array($jobTitleSort, ['asc', 'desc'], true)) {
                $jobTitleSort = null;
            }

            // Use the new Requisition model
            $query = Requisition::query();

            // Apply sorting - priority: job_title_sort > headcount_sort > created_sort
            if ($jobTitleSort) {
                $query->orderBy('job_title', $jobTitleSort);
                // Secondary sort by created_at for consistent ordering
                $query->orderBy('created_at', 'desc');
            } elseif ($headcountSort) {
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

            if ($request->filled('contract_type')) {
                $contractType = $request->contract_type;
                $query->where('contract_type', $contractType);
            }

            // Get per page value with safe defaults
            $perPage = $request->input('per_page', 20);
            $perPage = in_array($perPage, [10, 20, 50, 100], true) ? (int)$perPage : 20;

            $requisitions = $query->paginate($perPage)->appends($request->query());

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
                'per_page' => $perPage,
                'filters' => $request->only(['status', 'department', 'keyword', 'contract_type', 'created_sort', 'job_title_sort', 'headcount_sort']),
                'created_sort_applied' => $createdSort,
                'job_title_sort_applied' => $jobTitleSort,
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
     * Display success page after requisition creation (Tasks 82-85)
     */
    public function success($id, string $tenant = null)
    {
        try {
            $requisition = Requisition::findOrFail($id);

            Log::info('RequisitionController@success', [
                'requisition_id' => $id,
                'user_id' => auth()->id(),
            ]);

            return view('tenant.requisitions.success', compact('requisition'));
        } catch (\Exception $e) {
            Log::error('RequisitionController@success error', [
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
            
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;
            
            return redirect(tenantRoute('tenant.requisitions.index', $tenantSlug))
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
        try {
            // Log incoming request for debugging
            Log::info('Requisition store request received', [
                'user_id' => auth()->id(),
                'tenant_id' => tenant_id(),
                'tenant_slug' => $tenant,
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'request_data_keys' => array_keys($request->all()),
                'has_skills' => $request->has('skills'),
                'skills_value' => $request->input('skills'),
                'has_attachments' => $request->hasFile('attachments'),
                'all_input' => $request->except(['_token', 'password']), // Exclude sensitive data
            ]);

            // Validate all required fields (Task 14)
            // Note: FormData sends everything as strings, so we need flexible validation
            Log::debug('Starting requisition validation', [
                'user_id' => auth()->id(),
                'input_fields' => $request->only([
                    'department', 'job_title', 'justification', 'budget_min', 'budget_max',
                    'contract_type', 'duration', 'skills', 'experience_min', 'experience_max',
                    'headcount', 'priority', 'location'
                ]),
            ]);
            
            $validated = $request->validate([
                'department' => 'required|string|max:150',
                'job_title' => 'required|string|max:200',
                'justification' => 'required|string',
                'budget_min' => 'required|numeric|min:0',
                'budget_max' => 'required|numeric|min:0|gte:budget_min',
                'contract_type' => 'required|string|in:Full-time,Part-time,Contract,Intern,Temporary',
                'duration' => 'nullable|numeric|min:1|max:60',
                'skills' => 'required|string', // Accept as string, we'll parse it
                'experience_min' => 'required|numeric|min:0|max:50',
                'experience_max' => 'nullable|numeric|min:0|max:50|gte:experience_min',
                'headcount' => 'required|numeric|min:1',
                'priority' => 'required|string|in:Low,Medium,High',
                'location' => 'required|string|max:200',
                'additional_notes' => 'nullable|string',
                'attachments.*' => 'nullable|file|mimes:pdf,doc,docx|max:5120', // 5MB max (Task 68, 69)
            ], [
                'budget_max.gte' => 'Maximum budget must be greater than or equal to minimum budget.',
                'experience_max.gte' => 'Maximum experience must be greater than or equal to minimum experience.',
                'headcount.min' => 'Headcount must be at least 1.',
                'skills.required' => 'At least one skill is required.',
                'attachments.*.max' => 'Each attachment must not exceed 5MB.',
                'attachments.*.mimes' => 'Attachments must be PDF or DOC files.',
            ]);

            // Convert numeric strings to integers
            $validated['budget_min'] = (int) $validated['budget_min'];
            $validated['budget_max'] = (int) $validated['budget_max'];
            $validated['experience_min'] = (int) $validated['experience_min'];
            if (isset($validated['experience_max']) && $validated['experience_max'] !== null) {
                $validated['experience_max'] = (int) $validated['experience_max'];
            }
            if (isset($validated['duration']) && $validated['duration'] !== null) {
                $validated['duration'] = (int) $validated['duration'];
            }
            $validated['headcount'] = (int) $validated['headcount'];

            // Parse skills JSON - handle both JSON string and array
            $skillsInput = $validated['skills'];
            $skills = null;
            
            // Try to parse as JSON first
            if (is_string($skillsInput)) {
                $decoded = json_decode($skillsInput, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $skills = $decoded;
                } else {
                    // If not valid JSON, try to handle as comma-separated string
                    $skillsString = trim($skillsInput, ' ,');
                    if (!empty($skillsString)) {
                        $skillsArray = array_map('trim', explode(',', $skillsString));
                        $skillsArray = array_filter($skillsArray, function($skill) {
                            return !empty($skill) && trim($skill) !== '';
                        });
                        if (!empty($skillsArray)) {
                            $skills = array_values($skillsArray);
                        }
                    }
                }
            } elseif (is_array($skillsInput)) {
                $skills = $skillsInput;
            }
            
            // Validate skills array
            if (!is_array($skills) || count($skills) === 0) {
                Log::warning('Requisition validation failed: No skills provided', [
                    'skills_input' => $skillsInput,
                    'user_id' => auth()->id(),
                    'tenant_id' => tenant_id(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'At least one skill is required.',
                    'errors' => ['skills' => ['At least one skill is required.']]
                ], 422);
            }
            
            // Store skills as JSON string
            $validated['skills'] = json_encode($skills);
            
            Log::debug('Requisition validation passed', [
                'user_id' => auth()->id(),
                'validated_fields' => array_keys($validated),
                'skills_count' => count($skills),
            ]);

            // Set status to Draft initially (user must submit for approval separately)
            $validated['status'] = 'Draft';
            $validated['approval_status'] = 'Draft';
            $validated['approval_level'] = 0;
            $validated['tenant_id'] = tenant_id();
            $validated['created_by'] = auth()->id();

            DB::beginTransaction();
            try {
                // Create requisition
                $requisition = Requisition::create($validated);

                // Handle file uploads (Task 52)
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $this->storeAttachment($requisition, $file);
                    }
                }

                // Log creation (Task 48)
                RequisitionAuditLog::create([
                    'requisition_id' => $requisition->id,
                    'user_id' => auth()->id(),
                    'action' => 'created',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::commit();

                Log::info('Requisition created', [
                    'requisition_id' => $requisition->id,
                    'user_id' => auth()->id(),
                    'tenant_id' => tenant_id(),
                ]);

                $tenantModel = tenant();
                $tenantSlug = $tenantModel ? $tenantModel->slug : $tenant;

                // Return JSON for AJAX requests, redirect for regular form submissions
                if ($request->expectsJson() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Requisition created successfully.',
                        'requisition_id' => $requisition->id,
                        'redirect_url' => tenantRoute('tenant.requisitions.success', [$tenantSlug, $requisition->id]),
                    ]);
                }

                // Redirect to success page (Task 82)
                return redirect(tenantRoute('tenant.requisitions.success', [$tenantSlug, $requisition->id]));
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Requisition validation failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Requisition creation failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create requisition. Please try again.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to create requisition. Please try again.')
                ->withInput();
        }
    }

    /**
     * Store attachment for requisition
     */
    private function storeAttachment(Requisition $requisition, $file)
    {
        try {
            $extension = $file->getClientOriginalExtension();
            $filename = \Illuminate\Support\Str::uuid() . '.' . $extension;
            $path = "requisitions/{$requisition->tenant_id}/{$requisition->id}/{$filename}";

            // Store file
            \Illuminate\Support\Facades\Storage::disk('public')->put($path, file_get_contents($file));

            // Create attachment record
            \App\Models\Attachment::create([
                'tenant_id' => $requisition->tenant_id,
                'attachable_type' => Requisition::class,
                'attachable_id' => $requisition->id,
                'disk' => 'public',
                'path' => $path,
                'filename' => $file->getClientOriginalName(),
                'mime' => $file->getMimeType(),
                'size' => $file->getSize(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store requisition attachment', [
                'requisition_id' => $requisition->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get job title suggestions for autocomplete (Task 50)
     * Security: Only authenticated users with create_jobs permission (Task 78)
     */
    public function getJobTitleSuggestions(Request $request, string $tenant = null)
    {
        try {
            // Enhanced logging for route debugging
            Log::info('getJobTitleSuggestions called', [
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'query_param' => $request->input('q'),
                'route_name' => optional($request->route())->getName(),
            ]);

            // Permission check (Task 78)
            if (!auth()->check()) {
                Log::warning('getJobTitleSuggestions: Unauthorized access attempt', [
                    'tenant_slug' => $tenant,
                    'request_path' => $request->path(),
                ]);
                return response()->json(['suggestions' => []], 401);
            }

            $query = $request->input('q', '');
            
            if (strlen($query) < 2) {
                Log::debug('getJobTitleSuggestions: Query too short', [
                    'query_length' => strlen($query),
                    'query' => $query,
                ]);
                return response()->json(['suggestions' => []]);
            }

            // Get suggestions from existing requisitions and job openings
            $suggestions = Requisition::where('job_title', 'like', "%{$query}%")
                ->distinct()
                ->pluck('job_title')
                ->take(10)
                ->toArray();

            // Also check job openings
            $jobTitles = \App\Models\JobOpening::where('title', 'like', "%{$query}%")
                ->distinct()
                ->pluck('title')
                ->take(10)
                ->toArray();

            $suggestions = array_unique(array_merge($suggestions, $jobTitles));
            $suggestions = array_slice($suggestions, 0, 10);

            Log::info('getJobTitleSuggestions: Success', [
                'query' => $query,
                'suggestions_count' => count($suggestions),
                'tenant_id' => tenant_id(),
            ]);

            return response()->json(['suggestions' => $suggestions]);
        } catch (\Exception $e) {
            Log::error('Job title autocomplete error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['suggestions' => []], 500);
        }
    }

    /**
     * Get skill suggestions for autocomplete (Task 51)
     * Security: Only authenticated users with create_jobs permission (Task 78)
     */
    public function getSkillSuggestions(Request $request, string $tenant = null)
    {
        try {
            // Enhanced logging for route debugging
            Log::info('getSkillSuggestions called', [
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'query_param' => $request->input('q'),
                'route_name' => optional($request->route())->getName(),
            ]);

            // Permission check (Task 78)
            if (!auth()->check()) {
                Log::warning('getSkillSuggestions: Unauthorized access attempt', [
                    'tenant_slug' => $tenant,
                    'request_path' => $request->path(),
                ]);
                return response()->json(['suggestions' => []], 401);
            }

            $query = $request->input('q', '');
            
            if (strlen($query) < 2) {
                Log::debug('getSkillSuggestions: Query too short', [
                    'query_length' => strlen($query),
                    'query' => $query,
                ]);
                return response()->json(['suggestions' => []]);
            }

            // Get suggestions from existing requisitions
            $suggestions = Requisition::whereNotNull('skills')
                ->get()
                ->flatMap(function ($req) {
                    $skills = json_decode($req->skills, true);
                    return is_array($skills) ? $skills : [];
                })
                ->filter(function ($skill) use ($query) {
                    return stripos($skill, $query) !== false;
                })
                ->unique()
                ->take(10)
                ->values()
                ->toArray();

            // Also check tags table if it exists
            if (Schema::hasTable('tags')) {
                $tagSuggestions = \App\Models\Tag::where('name', 'like', "%{$query}%")
                    ->pluck('name')
                    ->take(10)
                    ->toArray();
                $suggestions = array_unique(array_merge($suggestions, $tagSuggestions));
                $suggestions = array_slice($suggestions, 0, 10);
            }

            Log::info('getSkillSuggestions: Success', [
                'query' => $query,
                'suggestions_count' => count($suggestions),
                'tenant_id' => tenant_id(),
            ]);

            return response()->json(['suggestions' => $suggestions]);
        } catch (\Exception $e) {
            Log::error('Skill autocomplete error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json(['suggestions' => []], 500);
        }
    }

    /**
     * Save draft requisition (Tasks 38-42)
     * Security: Validate token/session before saving (Task 80)
     * Idempotent: Updates existing draft or creates new one
     */
    public function saveDraft(Request $request, string $tenant = null)
    {
        try {
            // Enhanced logging for route debugging
            Log::info('saveDraft called', [
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'route_name' => optional($request->route())->getName(),
                'has_draft_id' => $request->has('draft_id'),
            ]);

            // Permission and session validation (Task 80)
            if (!auth()->check()) {
                Log::warning('saveDraft: Unauthorized access attempt', [
                    'tenant_slug' => $tenant,
                    'request_path' => $request->path(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $userId = auth()->id();
            $draftId = $request->input('draft_id');
            
            // Convert is_new from string to boolean before validation
            // FormData sends everything as strings, so "true" needs to be converted
            $isNewInput = $request->input('is_new');
            if ($isNewInput === 'true' || $isNewInput === '1' || $isNewInput === true || $isNewInput === 1) {
                $request->merge(['is_new' => true]);
                $isNewRequisition = true;
            } else {
                $request->merge(['is_new' => false]);
                $isNewRequisition = false;
            }
            
            Log::info('Draft save request received', [
                'user_id' => $userId,
                'tenant_id' => tenant_id(),
                'draft_id' => $draftId,
                'is_new' => $isNewRequisition,
                'has_job_title' => $request->filled('job_title'),
                'job_title' => $request->input('job_title'),
                'request_data_keys' => array_keys($request->except(['_token', '_method'])),
            ]);
            
            // Normalize contract_type before validation (handle case variations)
            // Get original value for logging
            $originalContractType = $request->input('contract_type');
            
            if ($request->has('contract_type') && $originalContractType) {
                $contractType = strtolower(trim($originalContractType));
                $contractTypeMap = [
                    'full-time' => 'Full-time',
                    'fulltime' => 'Full-time',
                    'full_time' => 'Full-time',
                    'part-time' => 'Part-time',
                    'parttime' => 'Part-time',
                    'part_time' => 'Part-time',
                    'contract' => 'Contract',
                    'intern' => 'Intern',
                    'internship' => 'Intern',
                    'temporary' => 'Temporary',
                    'temp' => 'Temporary',
                ];
                
                $normalizedType = null;
                if (isset($contractTypeMap[$contractType])) {
                    $normalizedType = $contractTypeMap[$contractType];
                } else {
                    // If not in map, try to capitalize first letter of each word
                    $normalizedType = ucwords(str_replace(['-', '_'], ' ', $contractType));
                }
                
                // Replace in request data directly to ensure it's used in validation
                $request->merge(['contract_type' => $normalizedType]);
                
                // Also update the request's input array directly
                $request->request->set('contract_type', $normalizedType);
                
                if ($originalContractType !== $normalizedType) {
                    Log::debug('Contract type normalized', [
                        'original' => $originalContractType,
                        'normalized' => $normalizedType,
                    ]);
                }
            }
            
            // Validate required fields for draft (more flexible for drafts)
            // Note: FormData sends everything as strings, so we need to handle conversion
            $validated = $request->validate([
                'department' => 'nullable|string|max:150',
                'job_title' => 'nullable|string|max:200',
                'justification' => 'nullable|string',
                'budget_min' => 'nullable|numeric|min:0',
                'budget_max' => 'nullable|numeric|min:0',
                'contract_type' => 'nullable|string|in:Full-time,Part-time,Contract,Intern,Temporary',
                'duration' => 'nullable|numeric|min:1|max:60',
                'skills' => 'nullable|string', // Accept any string, we'll parse it
                'experience_min' => 'nullable|numeric|min:0|max:50',
                'experience_max' => 'nullable|numeric|min:0|max:50',
                'headcount' => 'nullable|numeric|min:1',
                'priority' => 'nullable|string|in:Low,Medium,High',
                'location' => 'nullable|string|max:200',
                'additional_notes' => 'nullable|string',
                'draft_id' => 'nullable|integer|exists:requisitions,id',
                // Note: is_new is handled before validation, so we don't need to validate it here
            ]);
            
            // Convert numeric strings to integers for database
            $numericFields = ['budget_min', 'budget_max', 'duration', 'experience_min', 'experience_max', 'headcount'];
            foreach ($numericFields as $field) {
                if (isset($validated[$field]) && $validated[$field] !== null && $validated[$field] !== '') {
                    $validated[$field] = (int) $validated[$field];
                }
            }

            // Parse and normalize skills - handle both JSON string and array
            if (isset($validated['skills']) && $validated['skills'] !== null && $validated['skills'] !== '') {
                $skills = null;
                
                // Try to parse as JSON first
                if (is_string($validated['skills'])) {
                    // Remove any leading/trailing whitespace and quotes
                    $skillsInput = trim($validated['skills'], ' "\'');
                    
                    // Try to parse as JSON
                    $decoded = json_decode($skillsInput, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $skills = $decoded;
                    } else {
                        // If not valid JSON, try to handle as comma-separated string
                        // This handles legacy data or malformed JSON
                        $skillsString = trim($skillsInput, ' ,');
                        if (!empty($skillsString)) {
                            // Try to split by comma and clean up
                            $skillsArray = array_map('trim', explode(',', $skillsString));
                            $skillsArray = array_filter($skillsArray, function($skill) {
                                return !empty($skill) && trim($skill) !== '';
                            });
                            if (!empty($skillsArray)) {
                                $skills = array_values($skillsArray);
                            }
                        }
                    }
                } elseif (is_array($validated['skills'])) {
                    $skills = $validated['skills'];
                }
                
                // Store as JSON string if we have valid skills, otherwise use empty array
                $validated['skills'] = !empty($skills) ? json_encode($skills) : json_encode([]);
            } else {
                // Always set skills to empty JSON array, never null (database requires NOT NULL)
                $validated['skills'] = json_encode([]);
            }

            DB::beginTransaction();
            try {
                $requisition = null;

                // Only update existing draft if:
                // 1. draft_id is explicitly provided (user is continuing to edit a specific draft)
                // 2. AND is_new flag is NOT set (user is not creating a new requisition)
                if ($draftId && !$isNewRequisition) {
                    Log::debug('Checking for existing draft by ID', [
                        'draft_id' => $draftId,
                        'user_id' => $userId,
                    ]);
                    
                    $requisition = Requisition::where('id', $draftId)
                        ->where('created_by', $userId)
                        ->where('status', 'Draft')
                        ->first();

                    if ($requisition) {
                        // Filter out null/empty values that would violate NOT NULL constraints
                        $updateData = array_filter($validated, function($value) {
                            return $value !== null && $value !== '';
                        });
                        $requisition->update($updateData);
                        Log::info('Draft updated by explicit draft_id', [
                            'draft_id' => $draftId,
                            'user_id' => $userId,
                            'updated_fields' => array_keys($updateData),
                        ]);
                    } else {
                        Log::warning('Draft ID provided but not found or not owned by user', [
                            'draft_id' => $draftId,
                            'user_id' => $userId,
                        ]);
                    }
                } else {
                    if ($isNewRequisition) {
                        Log::debug('Creating new requisition - skipping draft update logic', [
                            'user_id' => $userId,
                            'draft_id_provided' => $draftId,
                        ]);
                    } else {
                        Log::debug('No draft_id provided - will create new draft', [
                            'user_id' => $userId,
                        ]);
                    }
                }

                // If no draft exists, create new one
                if (!$requisition) {
                    // Get tenant_id - ensure it's not null
                    $tenantId = tenant_id();
                    if (!$tenantId) {
                        // Try to get from tenant() helper
                        $tenant = tenant();
                        $tenantId = $tenant ? $tenant->id : null;
                    }
                    
                    if (!$tenantId) {
                        DB::rollBack();
                        Log::error('Draft save failed: No tenant_id', [
                            'user_id' => $userId,
                            'request_path' => $request->path(),
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Unable to determine tenant. Please refresh the page and try again.',
                        ], 400);
                    }
                    
                    // Ensure required fields have default values for drafts
                    $validated['tenant_id'] = $tenantId;
                    $validated['status'] = 'Draft';
                    $validated['approval_status'] = 'Draft';
                    $validated['approval_level'] = 0;
                    $validated['created_by'] = $userId;
                    
                    // Set defaults for fields that are NOT NULL in database but might be empty in draft
                    if (empty($validated['department'])) {
                        $validated['department'] = 'Draft';
                    }
                    if (empty($validated['job_title'])) {
                        $validated['job_title'] = 'Draft Requisition';
                    }
                    if (empty($validated['justification'])) {
                        $validated['justification'] = 'Draft';
                    }
                    if (!isset($validated['budget_min']) || $validated['budget_min'] === null || $validated['budget_min'] === '') {
                        $validated['budget_min'] = 0;
                    } else {
                        $validated['budget_min'] = (int) $validated['budget_min'];
                    }
                    if (!isset($validated['budget_max']) || $validated['budget_max'] === null || $validated['budget_max'] === '') {
                        $validated['budget_max'] = 0;
                    } else {
                        $validated['budget_max'] = (int) $validated['budget_max'];
                    }
                    if (empty($validated['contract_type'])) {
                        $validated['contract_type'] = 'Full-time';
                    }
                    if (empty($validated['skills'])) {
                        $validated['skills'] = json_encode([]);
                    }
                    if (!isset($validated['experience_min']) || $validated['experience_min'] === null || $validated['experience_min'] === '') {
                        $validated['experience_min'] = 0;
                    } else {
                        $validated['experience_min'] = (int) $validated['experience_min'];
                    }
                    if (empty($validated['headcount'])) {
                        $validated['headcount'] = 1;
                    } else {
                        $validated['headcount'] = (int) $validated['headcount'];
                    }
                    if (empty($validated['priority'])) {
                        $validated['priority'] = 'Medium';
                    }
                    if (empty($validated['location'])) {
                        $validated['location'] = 'TBD';
                    }

                    // Log before creation for debugging
                    Log::info('Creating new draft requisition', [
                        'user_id' => $userId,
                        'tenant_id' => $tenantId,
                        'is_new' => $isNewRequisition,
                        'validated_fields' => array_keys($validated),
                        'job_title' => $validated['job_title'] ?? 'N/A',
                    ]);

                    try {
                        $requisition = Requisition::create($validated);
                    } catch (\Exception $createException) {
                        DB::rollBack();
                        Log::error('Draft creation failed', [
                            'user_id' => $userId,
                            'tenant_id' => $tenantId,
                            'error' => $createException->getMessage(),
                            'trace' => $createException->getTraceAsString(),
                            'validated_data' => $validated,
                        ]);
                        throw $createException;
                    }

                    // Log creation
                    RequisitionAuditLog::create([
                        'requisition_id' => $requisition->id,
                        'user_id' => $userId,
                        'action' => 'created',
                        'changes' => ['draft' => true],
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);

                    Log::info('Draft created', [
                        'draft_id' => $requisition->id,
                        'user_id' => $userId,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Draft saved successfully.',
                    'draft_id' => $requisition->id,
                    'draft' => $requisition,
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Draft validation failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['_token', '_method']),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_map(function($errors) {
                    return is_array($errors) ? implode(', ', $errors) : $errors;
                }, $e->errors())),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Draft save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Load draft requisition (Task 40)
     * Security: Validate token/session before loading (Task 80)
     * Loads from database instead of session
     */
    public function loadDraft(Request $request, string $tenant = null)
    {
        try {
            // Enhanced logging for route debugging
            Log::info('loadDraft called', [
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'route_name' => optional($request->route())->getName(),
            ]);

            // Permission and session validation (Task 80)
            if (!auth()->check()) {
                Log::warning('loadDraft: Unauthorized access attempt', [
                    'tenant_slug' => $tenant,
                    'request_path' => $request->path(),
                ]);
                return response()->json([
                    'success' => false,
                    'draft' => null,
                ], 401);
            }

            $userId = auth()->id();
            
            // Load latest draft from database for this user
            $draft = Requisition::where('created_by', $userId)
                ->where('status', 'Draft')
                ->latest()
                ->first();

            if ($draft) {
                // Normalize contract_type if it exists (handle case variations from old drafts)
                $contractType = $draft->contract_type;
                if ($contractType) {
                    $contractTypeLower = strtolower(trim($contractType));
                    $contractTypeMap = [
                        'full-time' => 'Full-time',
                        'fulltime' => 'Full-time',
                        'full_time' => 'Full-time',
                        'part-time' => 'Part-time',
                        'parttime' => 'Part-time',
                        'part_time' => 'Part-time',
                        'contract' => 'Contract',
                        'intern' => 'Intern',
                        'internship' => 'Intern',
                        'temporary' => 'Temporary',
                        'temp' => 'Temporary',
                    ];
                    
                    if (isset($contractTypeMap[$contractTypeLower])) {
                        $contractType = $contractTypeMap[$contractTypeLower];
                    } elseif ($contractTypeLower !== strtolower($contractType)) {
                        // Auto-normalize if different case
                        $contractType = ucwords(str_replace(['-', '_'], ' ', $contractTypeLower));
                    }
                }
                
                Log::info('Draft loaded', [
                    'draft_id' => $draft->id,
                    'user_id' => $userId,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'draft' => $draft ? [
                    'id' => $draft->id,
                    'draft_id' => $draft->id, // For frontend compatibility
                    'department' => $draft->department,
                    'job_title' => $draft->job_title,
                    'justification' => $draft->justification,
                    'budget_min' => $draft->budget_min,
                    'budget_max' => $draft->budget_max,
                    'contract_type' => $contractType ?? $draft->contract_type,
                    'duration' => $draft->duration,
                    'skills' => $draft->skills,
                    'experience_min' => $draft->experience_min,
                    'experience_max' => $draft->experience_max,
                    'headcount' => $draft->headcount,
                    'priority' => $draft->priority,
                    'location' => $draft->location,
                    'additional_notes' => $draft->additional_notes,
                ] : null,
            ]);
        } catch (\Exception $e) {
            Log::error('Draft load error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'draft' => null,
            ], 500);
        }
    }

    /**
     * Delete draft requisition
     * Security: Validate token/session before deleting (Task 80)
     * Deletes from database instead of session
     */
    public function deleteDraft(Request $request, string $tenant = null)
    {
        try {
            // Enhanced logging for route debugging
            Log::info('deleteDraft called', [
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'request_method' => $request->method(),
                'route_name' => optional($request->route())->getName(),
                'draft_id' => $request->input('draft_id'),
            ]);

            // Permission and session validation (Task 80)
            if (!auth()->check()) {
                Log::warning('deleteDraft: Unauthorized access attempt', [
                    'tenant_slug' => $tenant,
                    'request_path' => $request->path(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized.',
                ], 401);
            }

            $userId = auth()->id();
            $draftId = $request->input('draft_id');

            DB::beginTransaction();
            try {
                if ($draftId) {
                    // Delete specific draft
                    $draft = Requisition::where('id', $draftId)
                        ->where('created_by', $userId)
                        ->where('status', 'Draft')
                        ->first();

                    if ($draft) {
                        $draft->delete(); // Soft delete
                        Log::info('Draft deleted', [
                            'draft_id' => $draftId,
                            'user_id' => $userId,
                        ]);
                    }
                } else {
                    // Delete all drafts for user (optional - for cleanup)
                    $deleted = Requisition::where('created_by', $userId)
                        ->where('status', 'Draft')
                        ->delete();

                    Log::info('All drafts deleted', [
                        'user_id' => $userId,
                        'deleted_count' => $deleted,
                    ]);
                }

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Draft deleted successfully.',
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            Log::error('Draft delete error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'tenant_slug' => $tenant,
                'tenant_id' => tenant_id(),
                'user_id' => auth()->id(),
                'request_path' => $request->path(),
                'request_url' => $request->fullUrl(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete draft.',
            ], 500);
        }
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

        if ($request->filled('contract_type')) {
            $contractType = $request->contract_type;
            $query->where('contract_type', $contractType);
        }

        // Apply sorting - priority: job_title_sort > headcount_sort > created_sort
        $jobTitleSort = strtolower($request->input('job_title_sort', ''));
        if (in_array($jobTitleSort, ['asc', 'desc'], true)) {
            $query->orderBy('job_title', $jobTitleSort);
            // Secondary sort by created_at for consistent ordering
            $query->orderBy('created_at', 'desc');
        } else {
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

