@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
        ['label' => $job->title, 'url' => null]
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
                        <h1 class="text-2xl font-bold text-black">{{ $job->title }}</h1>
                    </div>
                    <div>
                        <a href="{{ route('tenant.jobs.index', ['tenant' => $tenant->slug]) }}" 
                           class="text-blue-600 font-medium">
                            Back to Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Job Details -->
            <div class="lg:col-span-2">
                <x-card>
                        <!-- Job Header -->
                        <div class="px-6 py-8 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-black">
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
                                        {{ $job->location->name }}
                                    </span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                    </span>
                                </div>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    @if($job->status === 'published') bg-green-100 text-green-800
                                    @elseif($job->status === 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            <div class="text-lg text-gray-600">
                                {{ $job->openings_count }} opening{{ $job->openings_count !== 1 ? 's' : '' }} available
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="px-6 py-8">
                            @if($job->description)
                                <div class="prose max-w-none">
                                    {!! nl2br(e($job->description)) !!}
                                </div>
                            @else
                                <p class="text-gray-600">No job description available.</p>
                            @endif
                        </div>

                        <!-- Applications -->
                        @if($job->applications->count() > 0)
                            <div class="px-6 py-8 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Applications ({{ $job->applications->count() }})
                                </h3>
                                <div class="space-y-3">
                                    @foreach($job->applications->take(5) as $application)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    {{ $application->candidate->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    {{ $application->candidate->primary_email }}
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">
                                                    Applied {{ $application->applied_at->format('M j, Y') }}
                                                </div>
                                                @if($application->currentStage)
                                                    <div class="text-sm font-medium text-blue-600">
                                                        {{ $application->currentStage->name }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if($job->applications->count() > 5)
                                        <div class="text-center">
                                            <a href="{{ route('tenant.candidates.index', ['tenant' => tenant()->slug, 'job' => $job->id]) }}" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                View all {{ $job->applications->count() }} applications
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Job Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Status</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst($job->status) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Employment Type</dt>
                                <dd class="text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Openings</dt>
                                <dd class="text-sm text-gray-900">{{ $job->openings_count }}</dd>
                            </div>
                            @if($job->published_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Published</dt>
                                    <dd class="text-sm text-gray-900">{{ $job->published_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Created</dt>
                                <dd class="text-sm text-gray-900">{{ $job->created_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Pipeline Stages -->
                    @if($job->jobStages->count() > 0)
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pipeline Stages</h3>
                            <div class="space-y-3">
                                @foreach($job->jobStages->sortBy('sort_order') as $stage)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">{{ $stage->sort_order }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $stage->name }}</h4>
                                            @if($stage->is_terminal)
                                                <p class="text-xs text-gray-600">Final stage</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-card>

                <!-- Actions -->
                <x-card>
                    <h3 class="text-lg font-semibold text-black mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" 
                           class="w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors">
                            Edit Job
                        </a>
                        <a href="{{ route('careers.show', ['tenant' => $tenant->slug, 'job' => $job->slug]) }}" 
                           target="_blank"
                           class="w-full bg-gray-100 text-black text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            View Public Job Page
                        </a>
                        <a href="{{ route('tenant.candidates.index', ['tenant' => $tenant->slug, 'job' => $job->id]) }}" 
                           class="w-full bg-gray-100 text-black text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            View Applications
                        </a>
                        
                        @if($job->status === 'draft')
                            <form method="POST" action="{{ route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-green-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                    Publish Job
                                </button>
                            </form>
                        @elseif($job->status === 'published')
                            <form method="POST" action="{{ route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id]) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full bg-red-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                    Close Job
                                </button>
                            </form>
                        @endif
                        
                        <form method="POST" action="{{ route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id]) }}"
                              onsubmit="return confirm('Are you sure you want to delete this job?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors">
                                Delete Job
                            </button>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>
