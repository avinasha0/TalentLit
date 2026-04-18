<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Mail\TaskCreated;
use App\Models\JobOpening;
use App\Models\Department;
use App\Models\Location;
use App\Models\Task;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JobController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        $query = JobOpening::with(['department', 'location', 'jobStages', 'assignedHr'])
            ->withCount('applications')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->whereHas('department', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->department.'%');
            });
        }

        if ($request->filled('location')) {
            $query->whereHas('location', function ($subQ) use ($request) {
                $subQ->where('name', 'like', '%'.$request->location.'%');
            });
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%'.$keyword.'%')
                    ->orWhere('description', 'like', '%'.$keyword.'%');
            });
        }

        $jobs = $query->paginate(20);

        // Get filter options - combine both tenant and global departments/locations
        $tenantDepartments = JobOpening::with('department')
            ->get()
            ->pluck('department.name')
            ->filter()
            ->unique()
            ->values();

        $globalDepartments = JobOpening::with('globalDepartment')
            ->get()
            ->pluck('globalDepartment.name')
            ->filter()
            ->unique()
            ->values();

        $departments = $tenantDepartments->merge($globalDepartments)->unique()->sort()->values();

        $globalLocations = JobOpening::with('globalLocation')
            ->get()
            ->pluck('globalLocation.name')
            ->filter()
            ->unique()
            ->values();

        $cities = JobOpening::with('city')
            ->get()
            ->pluck('city.formatted_location')
            ->filter()
            ->unique()
            ->values();

        $locations = $globalLocations->merge($cities)->unique()->sort()->values();

        $statuses = ['draft', 'assigned', 'published', 'closed'];

        if ($request->expectsJson()) {
            return response()->json([
                'jobs' => $jobs->map(function ($job) {
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'slug' => $job->slug,
                        'department' => $job->department?->name ?? $job->globalDepartment?->name,
                        'location' => $job->globalLocation?->name ?? $job->city?->formatted_location,
                        'employment_type' => $job->employment_type,
                        'status' => $job->status,
                        'assigned_hr_name' => $job->assignedHr?->name,
                        'openings_count' => $job->openings_count,
                        'published_at' => $job->published_at,
                        'created_at' => $job->created_at,
                    ];
                }),
                'pagination' => [
                    'current_page' => $jobs->currentPage(),
                    'last_page' => $jobs->lastPage(),
                    'per_page' => $jobs->perPage(),
                    'total' => $jobs->total(),
                ],
            ]);
        }

        // Get subscription limit information
        $tenantModel = tenant();
        $currentPlan = $tenantModel->currentPlan();
        $currentJobCount = $tenantModel->jobOpenings()->count();
        $maxJobs = $currentPlan ? $currentPlan->max_job_openings : 0;
        $canAddJobs = $maxJobs === -1 || $currentJobCount < $maxJobs;

        return view('tenant.jobs.index', compact('jobs', 'departments', 'locations', 'statuses', 'currentJobCount', 'maxJobs', 'canAddJobs'));
    }

    public function create(string $tenant)
    {
        Gate::authorize('create', JobOpening::class);
        
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'intern', 'freelancer'];

        return view('tenant.jobs.create', compact('departments', 'locations', 'employmentTypes'));
    }

    public function store(Request $request, string $tenant)
    {
        Gate::authorize('create', JobOpening::class);
        
        // Generate slug if not provided BEFORE validation
        $requestData = $request->all();
        if (empty($requestData['slug'])) {
            $requestData['slug'] = Str::slug($requestData['title']);
            
            // Ensure uniqueness within tenant - use a more robust approach
            $originalSlug = $requestData['slug'];
            $counter = 1;
            $currentSlug = $requestData['slug'];
            
            while (JobOpening::where('slug', $currentSlug)
                ->where('tenant_id', tenant_id())
                ->exists()) {
                $currentSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $requestData['slug'] = $currentSlug;
            // Update the request with the generated slug
            $request->merge(['slug' => $requestData['slug']]);
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('job_openings', 'slug')
                    ->where('tenant_id', tenant_id())
            ],
            'department_id' => 'nullable|exists:departments,id',
            'location_id' => 'nullable|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,intern,freelancer',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Ensure at least one department and location is selected
        if (!$validated['department_id']) {
            return back()->withErrors(['department_id' => 'Please select a department.']);
        }

        if (!$validated['location_id']) {
            return back()->withErrors(['location_id' => 'Please select a location.']);
        }

        $validated['tenant_id'] = tenant_id();
        $validated['status'] = 'draft';
        
        // Final check to ensure slug is unique (double safety)
        if (!empty($validated['slug'])) {
            $originalSlug = $validated['slug'];
            $counter = 1;
            $currentSlug = $validated['slug'];
            
            while (JobOpening::where('slug', $currentSlug)
                ->where('tenant_id', tenant_id())
                ->exists()) {
                $currentSlug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $currentSlug;
        }
        
        $job = JobOpening::create($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job created successfully.');
    }

    public function show(string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        $job->load(['department', 'location', 'jobStages', 'applications.candidate', 'assignedHr']);

        $assignableHrUsers = collect();
        if (auth()->check() && auth()->user()->isHrAdmin() && $job->status === 'draft') {
            $assignableHrUsers = User::query()
                ->whereHas('tenants', function ($q) {
                    $q->where('tenants.id', tenant_id());
                })
                ->orderBy('name')
                ->get()
                ->filter(function ($u) {
                    return app(PermissionService::class)->userHasPermission((int) $u->id, tenant_id(), 'edit_jobs', false);
                })
                ->values();
        }

        return view('tenant.jobs.show', compact('job', 'assignableHrUsers'));
    }

    public function edit(string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('update', $job);

        $job->load('assignedHr');
        
        $departments = Department::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'intern', 'freelancer'];

        return view('tenant.jobs.edit', compact('job', 'departments', 'locations', 'employmentTypes'));
    }

    public function update(Request $request, string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('update', $job);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('job_openings', 'slug')
                    ->ignore($job->id)
                    ->where('tenant_id', tenant_id())
            ],
            'department_id' => 'nullable|exists:departments,id',
            'location_id' => 'nullable|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,intern,freelancer',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Ensure at least one department and location is selected
        if (!$validated['department_id']) {
            return back()->withErrors(['department_id' => 'Please select a department.']);
        }

        if (!$validated['location_id']) {
            return back()->withErrors(['location_id' => 'Please select a location.']);
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness within tenant
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (JobOpening::where('slug', $validated['slug'])
                ->where('tenant_id', tenant_id())
                ->where('id', '!=', $job->id)
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $job->update($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job updated successfully.');
    }

    public function publish(string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('publish', $job);

        $assigneeId = $job->assigned_hr_user_id;

        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        if ($assigneeId) {
            Task::query()
                ->where('tenant_id', $job->tenant_id)
                ->where('job_opening_id', $job->id)
                ->where('task_type', 'Job Publish')
                ->where('user_id', $assigneeId)
                ->whereIn('status', ['Pending', 'InProgress'])
                ->update(['status' => 'Completed']);
        }

        return redirect()
            ->back()
            ->with('success', 'Job published successfully.');
    }

    public function assignHr(Request $request, string $tenant, JobOpening $job)
    {
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('assignHr', $job);

        $validated = $request->validate([
            'assigned_hr_user_id' => ['required', 'exists:users,id'],
        ]);

        $assignee = User::findOrFail($validated['assigned_hr_user_id']);
        $tenantUuid = tenant_id();

        if (! $assignee->belongsToTenant($tenantUuid)) {
            return back()->withErrors(['assigned_hr_user_id' => 'Selected user is not a member of this organization.']);
        }

        if (! app(PermissionService::class)->userHasPermission((int) $assignee->id, $tenantUuid, 'edit_jobs', false)) {
            return back()->withErrors(['assigned_hr_user_id' => 'Selected user must have job editing access.']);
        }

        $job->update([
            'assigned_hr_user_id' => $assignee->id,
            'status' => 'assigned',
        ]);

        $this->createJobPublishTaskForAssignee($job->fresh(), $assignee);

        return redirect()
            ->back()
            ->with('success', 'HR owner assigned. The job is now in Assigned status and can be published by that user.');
    }

    /**
     * Create a My Tasks item so the assigned HR user sees publish work in their task list.
     */
    private function createJobPublishTaskForAssignee(JobOpening $job, User $assignee): void
    {
        try {
            $tenantModel = tenant();
            $tenantSlug = $tenantModel ? $tenantModel->slug : 'tenant';
            $hasDueAtColumn = Schema::hasColumn('tasks', 'due_at');

            $taskData = [
                'user_id' => $assignee->id,
                'task_type' => 'Job Publish',
                'title' => 'Review & publish job: '.$job->title,
                'job_opening_id' => $job->id,
                'requisition_id' => null,
                'status' => 'Pending',
                'link' => '/'.$tenantSlug.'/jobs/'.$job->id,
                'created_by' => auth()->id(),
                'tenant_id' => $job->tenant_id,
            ];

            if ($hasDueAtColumn) {
                $taskData['due_at'] = now()->addDays(7);
            }

            $task = Task::create($taskData);

            if ($assignee->email) {
                Mail::to($assignee->email)->queue(new TaskCreated($task->load(['jobOpening', 'requisition'])));
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to create job publish task', [
                'job_id' => $job->id,
                'assignee_id' => $assignee->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function close(string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('close', $job);
        
        $job->update([
            'status' => 'closed',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job closed successfully.');
    }

    public function destroy(string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('delete', $job);
        
        $job->delete();

        return redirect()
            ->route('tenant.jobs.index', ['tenant' => $tenant])
            ->with('success', 'Job deleted successfully.');
    }

    /**
     * Generate AI-powered job description
     */
    public function aiGenerateDescription(Request $request, string $tenant)
    {
        try {
            $request->validate([
                'job_title' => 'required|string|max:255'
            ]);

            $jobTitle = $request->job_title;
            
            // Generate AI-powered job description
            $description = $this->generateJobDescription($jobTitle);

            return response()->json([
                'success' => true,
                'description' => $description
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Job Description Generation Error', [
                'error' => $e->getMessage(),
                'job_title' => $request->job_title ?? 'N/A',
                'tenant' => $tenant
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate job description. Please try again.'
            ], 500);
        }
    }

    /**
     * Generate job description using AI
     */
    private function generateJobDescription(string $jobTitle): string
    {
        // This is a mock AI generation - in production, you would integrate with OpenAI, Claude, or similar
        // For now, we'll generate a professional template based on the job title
        
        $templates = [
            'software' => [
                'intro' => 'We are seeking a talented and experienced {title} to join our dynamic team. This role offers an exciting opportunity to work on cutting-edge projects and contribute to innovative solutions.',
                'responsibilities' => [
                    'Design, develop, and maintain high-quality software applications',
                    'Collaborate with cross-functional teams to define, design, and ship new features',
                    'Write clean, maintainable, and efficient code following best practices',
                    'Participate in code reviews and provide constructive feedback',
                    'Troubleshoot, debug, and optimize application performance',
                    'Stay up-to-date with emerging technologies and industry trends'
                ],
                'requirements' => [
                    'Bachelor\'s degree in Computer Science, Engineering, or related field',
                    'Proven experience in software development',
                    'Strong programming skills in multiple languages',
                    'Experience with modern development frameworks and tools',
                    'Excellent problem-solving and analytical skills',
                    'Strong communication and teamwork abilities'
                ]
            ],
            'marketing' => [
                'intro' => 'We are looking for a creative and strategic {title} to drive our marketing initiatives and help us reach new heights. This role will play a key part in shaping our brand presence and driving growth.',
                'responsibilities' => [
                    'Develop and execute comprehensive marketing strategies',
                    'Create compelling content for various marketing channels',
                    'Manage social media presence and engagement',
                    'Analyze market trends and competitor activities',
                    'Collaborate with sales team to generate qualified leads',
                    'Track and report on marketing campaign performance'
                ],
                'requirements' => [
                    'Bachelor\'s degree in Marketing, Communications, or related field',
                    'Proven experience in marketing and brand management',
                    'Strong creative and analytical skills',
                    'Experience with digital marketing tools and platforms',
                    'Excellent written and verbal communication skills',
                    'Ability to work in a fast-paced environment'
                ]
            ],
            'sales' => [
                'intro' => 'We are seeking a results-driven {title} to join our sales team and help us achieve our revenue goals. This role offers excellent growth opportunities and competitive compensation.',
                'responsibilities' => [
                    'Identify and pursue new business opportunities',
                    'Build and maintain strong client relationships',
                    'Present products and services to potential customers',
                    'Negotiate contracts and close deals',
                    'Meet and exceed sales targets and quotas',
                    'Provide regular sales reports and forecasts'
                ],
                'requirements' => [
                    'Bachelor\'s degree in Business, Sales, or related field',
                    'Proven track record in sales and business development',
                    'Strong interpersonal and communication skills',
                    'Ability to work independently and as part of a team',
                    'Excellent negotiation and closing skills',
                    'Proficiency in CRM software and sales tools'
                ]
            ],
            'default' => [
                'intro' => 'We are seeking a qualified and motivated {title} to join our team. This position offers an excellent opportunity for professional growth and development in a supportive environment.',
                'responsibilities' => [
                    'Perform assigned duties with accuracy and attention to detail',
                    'Collaborate effectively with team members and stakeholders',
                    'Maintain high standards of quality and professionalism',
                    'Contribute to process improvements and innovation',
                    'Meet deadlines and deliver results consistently',
                    'Stay updated with industry best practices and trends'
                ],
                'requirements' => [
                    'Bachelor\'s degree or equivalent experience',
                    'Relevant experience in the field',
                    'Strong analytical and problem-solving skills',
                    'Excellent communication and interpersonal abilities',
                    'Proficiency in relevant tools and technologies',
                    'Ability to work independently and in a team environment'
                ]
            ]
        ];

        // Determine template based on job title keywords
        $titleLower = strtolower($jobTitle);
        $selectedTemplate = 'default';
        
        if (strpos($titleLower, 'software') !== false || strpos($titleLower, 'developer') !== false || strpos($titleLower, 'engineer') !== false) {
            $selectedTemplate = 'software';
        } elseif (strpos($titleLower, 'marketing') !== false || strpos($titleLower, 'content') !== false || strpos($titleLower, 'brand') !== false) {
            $selectedTemplate = 'marketing';
        } elseif (strpos($titleLower, 'sales') !== false || strpos($titleLower, 'account') !== false || strpos($titleLower, 'business') !== false) {
            $selectedTemplate = 'sales';
        }

        $template = $templates[$selectedTemplate];
        
        // Build the job description
        $description = str_replace('{title}', $jobTitle, $template['intro']) . "\n\n";
        
        $description .= "**Key Responsibilities:**\n";
        foreach ($template['responsibilities'] as $responsibility) {
            $description .= "• " . $responsibility . "\n";
        }
        
        $description .= "\n**Requirements:**\n";
        foreach ($template['requirements'] as $requirement) {
            $description .= "• " . $requirement . "\n";
        }
        
        $description .= "\n**What We Offer:**\n";
        $description .= "• Competitive salary and benefits package\n";
        $description .= "• Professional development opportunities\n";
        $description .= "• Collaborative and inclusive work environment\n";
        $description .= "• Flexible work arrangements\n";
        $description .= "• Career growth and advancement opportunities\n";
        
        $description .= "\n**How to Apply:**\n";
        $description .= "If you are passionate about this role and meet the requirements, we would love to hear from you. Please submit your application with your resume and cover letter.\n\n";
        
        $description .= "We are an equal opportunity employer and value diversity at our company. We do not discriminate on the basis of race, religion, color, national origin, gender, sexual orientation, age, marital status, veteran status, or disability status.";

        // Ensure minimum 150 words
        $wordCount = str_word_count($description);
        if ($wordCount < 150) {
            $additionalContent = "\n\n**Additional Information:**\n";
            $additionalContent .= "This position offers a unique opportunity to make a significant impact in our organization. We are looking for someone who is not only qualified but also passionate about contributing to our mission and values. The successful candidate will have the opportunity to work with a talented team and contribute to exciting projects that make a difference.\n\n";
            $additionalContent .= "We encourage applications from candidates with diverse backgrounds and experiences. If you believe you have the skills and passion to excel in this role, we encourage you to apply even if you don't meet every single requirement listed above.";
            
            $description .= $additionalContent;
        }

        return $description;
    }

    public function getHiringManager(Request $request, string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('view', $job);

        return response()->json([
            'hiring_manager_primary_name' => $job->hiring_manager_primary_name,
            'hiring_manager_primary_email' => $job->hiring_manager_primary_email,
            'hiring_manager_primary_phone' => $job->hiring_manager_primary_phone,
            'hiring_manager_secondary_name' => $job->hiring_manager_secondary_name,
            'hiring_manager_secondary_email' => $job->hiring_manager_secondary_email,
            'hiring_manager_secondary_phone' => $job->hiring_manager_secondary_phone,
        ]);
    }

    public function updateHiringManager(Request $request, string $tenant, JobOpening $job)
    {
        // Ensure the job belongs to the current tenant
        if ($job->tenant_id !== tenant_id()) {
            abort(404, 'Job not found');
        }

        Gate::authorize('update', $job);

        $validated = $request->validate([
            'hiring_manager_primary_name' => 'nullable|string|max:255',
            'hiring_manager_primary_email' => 'nullable|email|max:255',
            'hiring_manager_primary_phone' => 'nullable|string|max:20',
            'hiring_manager_secondary_name' => 'nullable|string|max:255',
            'hiring_manager_secondary_email' => 'nullable|email|max:255',
            'hiring_manager_secondary_phone' => 'nullable|string|max:20',
        ]);

        $job->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Hiring manager details updated successfully.',
        ]);
    }
}
