@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantSlug)],
        ['label' => 'Candidates', 'url' => route('tenant.candidates.index', $tenantSlug)],
        ['label' => $candidate->full_name, 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Candidate Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-primary-100 dark:bg-primary-900/20 flex items-center justify-center">
                                <span class="text-2xl font-medium text-primary-600 dark:text-primary-400">
                                    {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $candidate->full_name }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">{{ $candidate->primary_email }}</p>
                            @if($candidate->primary_phone)
                                <p class="text-gray-600 dark:text-gray-400">{{ $candidate->primary_phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($candidate->tags->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($candidate->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        <x-button variant="secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </x-button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600 dark:text-primary-400">
                        Overview
                    </button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        Resumes
                    </button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        Applications
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Overview Tab Content -->
                <div class="space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $candidate->primary_email }}</p>
                            </div>
                            @if($candidate->primary_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $candidate->primary_phone }}</p>
                                </div>
                            @endif
                            @if($candidate->source)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Source</label>
                                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $candidate->source }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Created</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $candidate->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Summary -->
                    @if($candidate->applications->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Applications ({{ $candidate->applications->count() }})</h3>
                            <div class="space-y-3">
                                @foreach($candidate->applications as $application)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                                {{ $application->jobOpening->title }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $application->jobOpening->department->name ?? 'No Department' }} • 
                                                Applied {{ $application->applied_at->format('M j, Y') }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if($application->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300
                                                @elseif($application->status === 'hired') bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300
                                                @elseif($application->status === 'rejected') bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Resumes -->
                    @if($candidate->resumes->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Resumes ({{ $candidate->resumes->count() }})</h3>
                            <div class="space-y-3">
                                @foreach($candidate->resumes as $resume)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $resume->filename }}</p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Uploaded {{ $resume->created_at->format('M j, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <x-button variant="ghost" size="sm">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download
                                        </x-button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No resumes uploaded</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This candidate hasn't uploaded any resumes yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>