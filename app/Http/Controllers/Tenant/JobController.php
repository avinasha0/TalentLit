<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\JobOpening;
use App\Models\Department;
use App\Models\Location;
use App\Models\GlobalDepartment;
use App\Models\GlobalLocation;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;

class JobController extends Controller
{
    public function index(Request $request, string $tenant)
    {
        $query = JobOpening::with(['department', 'globalDepartment', 'location', 'globalLocation', 'city', 'jobStages'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('department')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('department', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->department.'%');
                })->orWhereHas('globalDepartment', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->department.'%');
                });
            });
        }

        if ($request->filled('location')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('globalLocation', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->location.'%');
                })->orWhereHas('city', function ($subQ) use ($request) {
                    $subQ->where('name', 'like', '%'.$request->location.'%');
                });
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

        $statuses = ['draft', 'published', 'closed'];

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
        $globalDepartments = GlobalDepartment::active()->orderBy('name')->get();
        $globalLocations = GlobalLocation::active()->orderBy('name')->get();
        $cities = City::active()->orderBy('state')->orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'internship'];

        return view('tenant.jobs.create', compact('departments', 'locations', 'globalDepartments', 'globalLocations', 'cities', 'employmentTypes'));
    }

    public function store(Request $request, string $tenant)
    {
        Gate::authorize('create', JobOpening::class);
        
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
            'global_department_id' => 'nullable|exists:global_departments,id',
            'location_id' => 'required|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'status' => 'required|in:draft,published,closed',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Ensure at least one department and location is selected
        if (!$validated['department_id'] && !$validated['global_department_id']) {
            return back()->withErrors(['department_id' => 'Please select a department.']);
        }

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure uniqueness within tenant
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (JobOpening::where('slug', $validated['slug'])
                ->where('tenant_id', tenant_id())
                ->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        $validated['tenant_id'] = tenant_id();
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $job = JobOpening::create($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job created successfully.');
    }

    public function show(string $tenant, JobOpening $job)
    {
        $job->load(['department', 'globalDepartment', 'location', 'globalLocation', 'city', 'jobStages', 'applications.candidate']);

        return view('tenant.jobs.show', compact('job'));
    }

    public function edit(string $tenant, JobOpening $job)
    {
        Gate::authorize('update', $job);
        
        $departments = Department::orderBy('name')->get();
        $globalDepartments = GlobalDepartment::active()->orderBy('name')->get();
        $cities = City::active()->orderBy('state')->orderBy('name')->get();
        $employmentTypes = ['full_time', 'part_time', 'contract', 'internship'];

        return view('tenant.jobs.edit', compact('job', 'departments', 'globalDepartments', 'cities', 'employmentTypes'));
    }

    public function update(Request $request, string $tenant, JobOpening $job)
    {
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
            'global_department_id' => 'nullable|exists:global_departments,id',
            'location_id' => 'required|exists:locations,id',
            'employment_type' => 'required|in:full_time,part_time,contract,internship',
            'status' => 'required|in:draft,published,closed',
            'openings_count' => 'required|integer|min:1',
            'description' => 'required|string',
        ]);

        // Ensure at least one department and location is selected
        if (!$validated['department_id'] && !$validated['global_department_id']) {
            return back()->withErrors(['department_id' => 'Please select a department.']);
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

        // Set published_at if status changed to published
        if ($validated['status'] === 'published' && $job->status !== 'published') {
            $validated['published_at'] = now();
        }

        $job->update($validated);

        return redirect()
            ->route('tenant.jobs.show', ['tenant' => $tenant, 'job' => $job])
            ->with('success', 'Job updated successfully.');
    }

    public function publish(string $tenant, JobOpening $job)
    {
        Gate::authorize('publish', $job);
        
        $job->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Job published successfully.');
    }

    public function close(string $tenant, JobOpening $job)
    {
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
}
