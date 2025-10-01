<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interview Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Header -->
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">
                                Interview with {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                            </h3>
                            @if($interview->job)
                                <p class="text-lg text-gray-600 mt-1">for {{ $interview->job->title }}</p>
                            @endif
                        </div>
                        
                        <div class="flex items-center space-x-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($interview->status === 'scheduled') bg-blue-100 text-blue-800
                                @elseif($interview->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($interview->status) }}
                            </span>

                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                {{ ucfirst($interview->mode) }}
                            </span>
                        </div>
                    </div>

                    <!-- Interview Details -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Main Details -->
                        <div class="lg:col-span-2 space-y-6">
                            <!-- Date & Time -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Date & Time</h4>
                                <div class="flex items-center space-x-4">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $interview->scheduled_at->format('l, F j, Y') }}
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $interview->scheduled_at->format('g:i A') }}
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $interview->duration_minutes }} minutes
                                    </div>
                                </div>
                            </div>

                            <!-- Location/Meeting Details -->
                            @if($interview->location || $interview->meeting_link)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-3">
                                        @if($interview->mode === 'onsite')
                                            Location
                                        @elseif($interview->mode === 'remote')
                                            Meeting Details
                                        @else
                                            Contact Information
                                        @endif
                                    </h4>
                                    
                                    @if($interview->location)
                                        <div class="flex items-start text-gray-600">
                                            <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>{{ $interview->location }}</span>
                                        </div>
                                    @endif

                                    @if($interview->meeting_link)
                                        <div class="flex items-start text-gray-600 mt-2">
                                            <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                            </svg>
                                            <a href="{{ $interview->meeting_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                                {{ $interview->meeting_link }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Notes -->
                            @if($interview->notes)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-3">Notes</h4>
                                    <p class="text-gray-600 whitespace-pre-wrap">{{ $interview->notes }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Panelists -->
                            @if($interview->panelists->count() > 0)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-lg font-medium text-gray-900 mb-3">Panelists</h4>
                                    <div class="space-y-2">
                                        @foreach($interview->panelists as $panelist)
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                    <span class="text-sm font-medium text-indigo-700">
                                                        {{ substr($panelist->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <span class="ml-3 text-sm text-gray-700">{{ $panelist->name }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Candidate Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Candidate</h4>
                                <div class="space-y-2">
                                    <p class="text-sm text-gray-700">
                                        <strong>{{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}</strong>
                                    </p>
                                    <p class="text-sm text-gray-600">{{ $interview->candidate->primary_email }}</p>
                                    @if($interview->candidate->primary_phone)
                                        <p class="text-sm text-gray-600">{{ $interview->candidate->primary_phone }}</p>
                                    @endif
                                    <a href="{{ route('tenant.candidates.show', ['tenant' => $tenantSlug, 'candidate' => $interview->candidate]) }}" 
                                       class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                        View Full Profile →
                                    </a>
                                </div>
                            </div>

                            <!-- Interview Info -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-gray-900 mb-3">Interview Info</h4>
                                <div class="space-y-2 text-sm text-gray-600">
                                    <p><strong>Created by:</strong> {{ $interview->createdBy->name }}</p>
                                    <p><strong>Created:</strong> {{ $interview->created_at->format('M j, Y g:i A') }}</p>
                                    @if($interview->updated_at != $interview->created_at)
                                        <p><strong>Last updated:</strong> {{ $interview->updated_at->format('M j, Y g:i A') }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-3">
                        <a href="{{ route('tenant.interviews.index', ['tenant' => $tenantSlug]) }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Back to Interviews
                        </a>

                        @can('update', $interview)
                            <a href="{{ route('tenant.interviews.edit', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" 
                               class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Edit Interview
                            </a>
                        @endcan

                        @if($interview->status === 'scheduled')
                            @can('update', $interview)
                                <form method="POST" action="{{ route('tenant.interviews.complete', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            onclick="return confirm('Mark this interview as completed?')">
                                        Mark Complete
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('tenant.interviews.cancel', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            onclick="return confirm('Cancel this interview?')">
                                        Cancel
                                    </button>
                                </form>
                            @endcan
                        @endif

                        @can('delete', $interview)
                            <form method="POST" action="{{ route('tenant.interviews.destroy', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        onclick="return confirm('Are you sure you want to delete this interview? This action cannot be undone.')">
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
