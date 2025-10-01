<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Interviews') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Filters -->
                    <div class="mb-6">
                        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="candidate_id" class="block text-sm font-medium text-gray-700">Candidate</label>
                                <select name="candidate_id" id="candidate_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Candidates</option>
                                    @foreach($candidates as $candidate)
                                        <option value="{{ $candidate->id }}" {{ request('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                            {{ $candidate->first_name }} {{ $candidate->last_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="job_id" class="block text-sm font-medium text-gray-700">Job</label>
                                <select name="job_id" id="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Jobs</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                            {{ $job->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                </select>
                            </div>

                            <div>
                                <label for="mode" class="block text-sm font-medium text-gray-700">Mode</label>
                                <select name="mode" id="mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">All Modes</option>
                                    <option value="onsite" {{ request('mode') == 'onsite' ? 'selected' : '' }}>Onsite</option>
                                    <option value="remote" {{ request('mode') == 'remote' ? 'selected' : '' }}>Remote</option>
                                    <option value="phone" {{ request('mode') == 'phone' ? 'selected' : '' }}>Phone</option>
                                </select>
                            </div>

                            <div class="md:col-span-4">
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Filter
                                </button>
                                <a href="{{ route('tenant.interviews.index', ['tenant' => $tenantModel->slug]) }}" class="ml-2 bg-blue-100 text-blue-700 px-4 py-2 rounded-md hover:bg-blue-200">
                                    Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Interviews List -->
                    @if($interviews->count() > 0)
                        <div class="space-y-4">
                            @foreach($interviews as $interview)
                                <div class="border rounded-lg p-4 hover:bg-gray-50">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <h3 class="text-lg font-medium text-gray-900">
                                                    <a href="{{ route('tenant.candidates.show', ['tenant' => $tenantModel->slug, 'candidate' => $interview->candidate]) }}" class="hover:text-indigo-600">
                                                        {{ $interview->candidate->first_name }} {{ $interview->candidate->last_name }}
                                                    </a>
                                                </h3>
                                                
                                                @if($interview->job)
                                                    <span class="text-sm text-gray-500">
                                                        for {{ $interview->job->title }}
                                                    </span>
                                                @endif

                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($interview->status === 'scheduled') bg-blue-100 text-blue-800
                                                    @elseif($interview->status === 'completed') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($interview->status) }}
                                                </span>

                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    {{ ucfirst($interview->mode) }}
                                                </span>
                                            </div>

                                            <div class="mt-2 text-sm text-gray-600">
                                                <div class="flex items-center space-x-4">
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $interview->scheduled_at->format('M j, Y g:i A') }}
                                                    </span>
                                                    
                                                    <span class="flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        {{ $interview->duration_minutes }} minutes
                                                    </span>

                                                    @if($interview->location)
                                                        <span class="flex items-center">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            {{ $interview->location }}
                                                        </span>
                                                    @endif

                                                    @if($interview->meeting_link)
                                                        <a href="{{ $interview->meeting_link }}" target="_blank" class="flex items-center text-indigo-600 hover:text-indigo-800">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                            Join Meeting
                                                        </a>
                                                    @endif
                                                </div>

                                                @if($interview->panelists->count() > 0)
                                                    <div class="mt-2">
                                                        <span class="text-sm font-medium text-gray-700">Panelists:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            @foreach($interview->panelists as $panelist)
                                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                                                    {{ $panelist->name }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif

                                                @if($interview->notes)
                                                    <div class="mt-2">
                                                        <span class="text-sm font-medium text-gray-700">Notes:</span>
                                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($interview->notes, 100) }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('tenant.interviews.show', ['tenant' => $tenantModel->slug, 'interview' => $interview]) }}" 
                                               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                View
                                            </a>
                                            
                                            @can('update', $interview)
                                                <a href="{{ route('tenant.interviews.edit', ['tenant' => $tenantModel->slug, 'interview' => $interview]) }}" 
                                                   class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                    Edit
                                                </a>
                                            @endcan

                                            @if($interview->status === 'scheduled')
                                                @can('update', $interview)
                                                    <form method="POST" action="{{ route('tenant.interviews.cancel', ['tenant' => $tenantModel->slug, 'interview' => $interview]) }}" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium" 
                                                                onclick="return confirm('Are you sure you want to cancel this interview?')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                @endcan
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $interviews->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No interviews found</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by scheduling your first interview.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
