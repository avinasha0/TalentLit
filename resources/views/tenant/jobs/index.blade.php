@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Job Openings</h1>
                        <p class="mt-1 text-sm text-black">Manage job postings and applications</p>
                        @if($maxJobs !== -1)
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-xs text-gray-600">
                                    {{ $currentJobCount }} / {{ $maxJobs }} jobs
                                </span>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" 
                                         style="width: {{ $maxJobs > 0 ? min(100, ($currentJobCount / $maxJobs) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <div>
                        @if($canAddJobs)
                            <a href="{{ route('tenant.jobs.create', $tenant->slug) }}"
                               class="bg-blue-600 text-black px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Create Job
                            </a>
                        @else
                            <button onclick="showJobLimitReached()"
                                    class="bg-gray-400 text-black px-4 py-2 rounded-md cursor-not-allowed opacity-75">
                                Create Job
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <x-card>
            <div class="py-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-black mb-1">Search</label>
                        <input type="text" 
                               name="keyword" 
                               id="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="Job title or keywords..."
                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-black mb-1">Status</label>
                        <select name="status" 
                                id="status"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-black mb-1">Department</label>
                        <select name="department" 
                                id="department"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-black mb-1">Location</label>
                        <select name="location" 
                                id="location"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="">All Locations</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-black px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter Jobs
                        </button>
                    </div>
                </form>
            </div>
        </x-card>

        <!-- Jobs Table -->
        <x-card>
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-black">
                    {{ $jobs->total() }} job{{ $jobs->total() !== 1 ? 's' : '' }} found
                </h3>
            </div>

            @if($jobs->count() > 0)
                <!-- Mobile: Horizontal scroll container -->
                <div class="block lg:hidden overflow-x-auto">
                    <div class="min-w-full">
                        @foreach($jobs as $job)
                            <div class="border-b border-gray-200 dark:border-gray-700 p-4 min-w-[320px]">
                                <!-- Mobile Job Card -->
                                <div class="space-y-3">
                                    <!-- Job Title -->
                                    <div>
                                        <h4 class="text-lg font-medium text-black">
                                            <a href="{{ route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="hover:text-blue-600">
                                                {{ $job->title }}
                                            </a>
                                        </h4>
                                    </div>
                                    
                                    <!-- Job Details -->
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-black">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="truncate">{{ $job->department->name }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-black">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate">{{ $job->location?->name ?? $job->globalLocation?->name ?? $job->city?->formatted_location ?? 'No location specified' }}</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="px-2 py-1 bg-blue-600 text-black rounded-full text-xs">
                                                {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                            </span>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                @if($job->status === 'published') bg-green-600 text-black
                                                @elseif($job->status === 'draft') bg-yellow-600 text-black
                                                @else bg-red-600 text-black
                                                @endif">
                                                {{ ucfirst($job->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Stats and Actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <div class="text-sm text-black">
                                            <div>{{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }}</div>
                                            <div>{{ $job->applications->count() }} application{{ $job->applications->count() !== 1 ? 's' : '' }}</div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id]) }}"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View
                                            </a>
                                            <a href="{{ route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id]) }}"
                                               class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Actions -->
                                    <div class="flex items-center justify-between">
                                        @if($job->published_at)
                                            <div class="text-xs text-black">
                                                Published {{ $job->published_at->format('M j, Y') }}
                                            </div>
                                        @else
                                            <div></div>
                                        @endif
                                        
                                        <div class="flex items-center space-x-2">
                                            @customCan('publish_jobs', $tenant)
                                                @if($job->status === 'draft')
                                                    <form method="POST" action="{{ route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                            Publish
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcustomCan
                                            
                                            @customCan('close_jobs', $tenant)
                                                @if($job->status === 'published')
                                                    <form method="POST" action="{{ route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                            Close
                                                        </button>
                                                    </form>
                                                @endif
                                            @endcustomCan
                                            
                                            @customCan('delete_jobs', $tenant)
                                                <form method="POST" action="{{ route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" 
                                                        class="text-gray-400 text-sm font-medium cursor-not-allowed"
                                                        title="Permission required: Delete Jobs">
                                                    Delete
                                                </button>
                                            @endcustomCan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop: Original table layout -->
                <ul class="hidden lg:block divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($jobs as $job)
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-lg font-medium text-black truncate">
                                                <a href="{{ route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id]) }}">
                                                    {{ $job->title }}
                                                </a>
                                            </h4>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-black">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    {{ $job->department->name }}
                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ $job->location?->name ?? $job->globalLocation?->name ?? $job->city?->formatted_location ?? 'No location specified' }}
                                                </span>
                                                <span class="px-2 py-1 bg-blue-600 text-black rounded-full text-xs">
                                                    {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-right">
                                                <div class="text-sm font-medium text-black">
                                                    {{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }}
                                                </div>
                                                <div class="text-sm text-black">
                                                    {{ $job->applications->count() }} application{{ $job->applications->count() !== 1 ? 's' : '' }}
                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end space-y-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($job->status === 'published') bg-green-600 text-black
                                                    @elseif($job->status === 'draft') bg-yellow-600 text-black
                                                    @else bg-red-600 text-black
                                                    @endif">
                                                    {{ ucfirst($job->status) }}
                                                </span>
                                                @if($job->published_at)
                                                    <div class="text-xs text-black">
                                                        Published {{ $job->published_at->format('M j, Y') }}
                                                    </div>
                                                @endif
                                                
                                                <!-- Action Buttons -->
                                                <div class="flex items-center space-x-2">
                                                    <a href="{{ route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id]) }}"
                                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        View
                                                    </a>
                                                    <a href="{{ route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id]) }}"
                                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                        Edit
                                                    </a>
                                                    
                                                    @if($job->status === 'draft')
                                                        <form method="POST" action="{{ route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                                Publish
                                                            </button>
                                                        </form>
                                                    @elseif($job->status === 'published')
                                                        <form method="POST" action="{{ route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                                Close
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <form method="POST" action="{{ route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    {{ $jobs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-600 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-black">No jobs found</h3>
                    <p class="mt-1 text-sm text-black">Try adjusting your search criteria.</p>
                </div>
            @endif
        </x-card>
    </div>

    <script>
        function showJobLimitReached() {
            const currentCount = {{ $currentJobCount }};
            const maxJobs = {{ $maxJobs === -1 ? 'Infinity' : $maxJobs }};
            
            let message = `You've reached the job openings limit for your current plan. `;
            if (maxJobs !== Infinity) {
                message += `You currently have ${currentCount} job openings out of ${maxJobs} allowed. `;
            }
            message += `Please upgrade your subscription plan to create more job openings.`;
            
            alert(message);
            
            // Redirect to tenant-specific subscription page
            if (confirm('Would you like to view available plans?')) {
                window.location.href = '{{ route("subscription.show", $tenant->slug) }}';
            }
        }
    </script>
</x-app-layout>
