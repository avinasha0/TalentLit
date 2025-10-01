@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantSlug)],
        ['label' => 'Candidates', 'url' => route('tenant.candidates.index', $tenantSlug)],
        ['label' => $candidate->full_name, 'url' => null]
    ];
    
    // Status configuration for applications - moved outside loop for performance
    $statusConfig = [
        'applied' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dark_bg' => 'dark:bg-blue-900/20', 'dark_text' => 'dark:text-blue-300'],
        'active' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dark_bg' => 'dark:bg-green-900/20', 'dark_text' => 'dark:text-green-300'],
        'interview' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dark_bg' => 'dark:bg-yellow-900/20', 'dark_text' => 'dark:text-yellow-300'],
        'hired' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'dark_bg' => 'dark:bg-emerald-900/20', 'dark_text' => 'dark:text-emerald-300'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dark_bg' => 'dark:bg-red-900/20', 'dark_text' => 'dark:text-red-300'],
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Candidate Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-primary-100 flex items-center justify-center">
                                <span class="text-2xl font-medium text-blue-600">
                                    {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-6">
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $candidate->full_name }}
                            </h1>
                            <p class="text-gray-600">{{ $candidate->primary_email }}</p>
                            @if($candidate->primary_phone)
                                <p class="text-gray-600">{{ $candidate->primary_phone }}</p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        @if($candidate->tags->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($candidate->tags as $tag)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
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
        <div class="bg-white shadow rounded-lg">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                        Overview
                    </button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Resumes
                    </button>
                    <button class="whitespace-nowrap py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                        Applications
                    </button>
                </nav>
            </div>

            <div class="p-6">
                <!-- Overview Tab Content -->
                <div class="space-y-6">
                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $candidate->primary_email }}</p>
                            </div>
                            @if($candidate->primary_phone)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $candidate->primary_phone }}</p>
                                </div>
                            @endif
                            @if($candidate->source)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Source</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $candidate->source }}</p>
                                </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $candidate->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Applications Summary -->
                    @if($candidate->applications->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Applications ({{ $candidate->applications->count() }})</h3>
                            <div class="space-y-3">
                                @foreach($candidate->applications as $application)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900">
                                                {{ $application->jobOpening->title ?? 'Unknown Job' }}
                                            </h4>
                                            <p class="text-sm text-gray-500">
                                                {{ $application->jobOpening->department->name ?? 'No Department' }} • 
                                                Applied {{ $application->applied_at ? $application->applied_at->format('M j, Y') : 'Unknown' }}
                                            </p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @php
                                                $status = strtolower($application->status);
                                                $config = $statusConfig[$status] ?? $statusConfig['applied'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} {{ $config['dark_bg'] }} {{ $config['dark_text'] }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Tags Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Tags</h3>
                        <div class="mb-4">
                            <div class="flex flex-wrap gap-2 mb-3">
                                @foreach($candidate->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700">
                                        {{ $tag->name }}
                                        <button type="button" 
                                                class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none"
                                                onclick="removeTag('{{ $tag->id }}', '{{ $tag->name }}')">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </span>
                                @endforeach
                            </div>
                            <div class="flex">
                                <input type="text" 
                                       id="tagInput" 
                                       placeholder="Add a tag..." 
                                       class="flex-1 rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       onkeypress="handleTagKeypress(event)">
                                <button type="button" 
                                        onclick="addTag()"
                                        class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Interviews Section -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Interviews ({{ $candidate->interviews->count() }})</h3>
                            @can('create', App\Models\Interview::class)
                                <a href="{{ route('tenant.interviews.create', ['tenant' => $tenantSlug, 'candidate' => $candidate]) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Schedule Interview
                                </a>
                            @endcan
                        </div>

                        @if($candidate->interviews->count() > 0)
                            <div class="space-y-3">
                                @foreach($candidate->interviews->sortByDesc('scheduled_at') as $interview)
                                    <div class="border rounded-lg p-4 hover:bg-gray-50">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3 mb-2">
                                                    <h4 class="text-sm font-medium text-gray-900">
                                                        @if($interview->job)
                                                            {{ $interview->job->title }}
                                                        @else
                                                            General Interview
                                                        @endif
                                                    </h4>
                                                    
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                        @if($interview->status === 'scheduled') bg-blue-100 text-blue-800
                                                        @elseif($interview->status === 'completed') bg-green-100 text-green-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($interview->status) }}
                                                    </span>

                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        {{ ucfirst($interview->mode) }}
                                                    </span>
                                                </div>

                                                <div class="text-sm text-gray-600 space-y-1">
                                                    <div class="flex items-center">
                                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        {{ $interview->scheduled_at->format('M j, Y g:i A') }}
                                                        ({{ $interview->duration_minutes }} min)
                                                    </div>

                                                    @if($interview->location)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            {{ $interview->location }}
                                                        </div>
                                                    @endif

                                                    @if($interview->meeting_link)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                            <a href="{{ $interview->meeting_link }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                                                Join Meeting
                                                            </a>
                                                        </div>
                                                    @endif

                                                    @if($interview->panelists->count() > 0)
                                                        <div class="flex items-center">
                                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                                            </svg>
                                                            <span class="text-xs">
                                                                Panelists: {{ $interview->panelists->pluck('name')->join(', ') }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if($interview->notes)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ Str::limit($interview->notes, 100) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-2 ml-4">
                                                <a href="{{ route('tenant.interviews.show', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" 
                                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    View
                                                </a>
                                                
                                                @can('update', $interview)
                                                    <a href="{{ route('tenant.interviews.edit', ['tenant' => $tenantSlug, 'interview' => $interview]) }}" 
                                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                        Edit
                                                    </a>
                                                @endcan
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 bg-gray-50 rounded-lg">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No interviews scheduled</h3>
                                <p class="mt-1 text-sm text-gray-500">Schedule an interview to get started.</p>
                                @can('create', App\Models\Interview::class)
                                    <div class="mt-4">
                                        <a href="{{ route('tenant.interviews.create', ['tenant' => $tenantSlug, 'candidate' => $candidate]) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Schedule Interview
                                        </a>
                                    </div>
                                @endcan
                            </div>
                        @endif
                    </div>

                    <!-- Notes Section -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Notes ({{ $candidate->notes->count() }})</h3>
                        <div class="mb-4">
                            <form id="noteForm" onsubmit="addNote(event)">
                                <div>
                                    <textarea id="noteBody" 
                                              name="body" 
                                              rows="3" 
                                              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                              placeholder="Add a note about this candidate..."></textarea>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" 
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        Add Note
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div id="notesList" class="space-y-3">
                            @foreach($candidate->notes as $note)
                                <div class="bg-gray-50 rounded-lg p-4" data-note-id="{{ $note->id }}">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">{{ $note->body }}</p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                By {{ $note->user->name }} on {{ $note->created_at->format('M j, Y g:i A') }}
                                            </p>
                                        </div>
                                        @if($note->user_id === auth()->id() || auth()->user()->hasAnyRole(['Owner', 'Admin']))
                                            <button type="button" 
                                                    class="ml-2 text-red-400 hover:text-red-600 focus:outline-none"
                                                    onclick="removeNote('{{ $note->id }}')">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Resumes -->
                    @if($candidate->resumes->count() > 0)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Resumes ({{ $candidate->resumes->count() }})</h3>
                            <div class="space-y-3">
                                @foreach($candidate->resumes as $resume)
                                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                                        <div class="flex items-center">
                                            <svg class="w-8 h-8 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $resume->filename }}</p>
                                                <p class="text-sm text-gray-500">
                                                    Uploaded {{ $resume->created_at->format('M j, Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <a href="{{ asset('storage/' . $resume->path) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
                                           download="{{ $resume->filename }}">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No resumes uploaded</h3>
                            <p class="mt-1 text-sm text-gray-500">This candidate hasn't uploaded any resumes yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        const candidateId = '{{ $candidate->id }}';
        const tenantSlug = '{{ $tenantSlug }}';
        
        // Function to get fresh CSRF token
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
        
        // Function to refresh CSRF token
        function refreshCsrfToken() {
            return fetch(`/${tenantSlug}/candidates/${candidateId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'text/html'
                }
            })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newToken = doc.querySelector('meta[name="csrf-token"]').getAttribute('content');
                document.querySelector('meta[name="csrf-token"]').setAttribute('content', newToken);
                return newToken;
            });
        }

        // Notes functionality
        function addNote(event) {
            event.preventDefault();
            const body = document.getElementById('noteBody').value.trim();
            
            if (!body) return;

            // Get CSRF token
            const csrfToken = getCsrfToken();
            
            fetch(`/${tenantSlug}/candidates/${candidateId}/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ body: body })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        if (response.status === 419) {
                            // CSRF token mismatch - refresh token and retry
                            return refreshCsrfToken().then(newToken => {
                                return fetch(`/${tenantSlug}/candidates/${candidateId}/notes`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ body: body })
                                });
                            }).then(retryResponse => {
                                if (!retryResponse.ok) {
                                    throw new Error(`HTTP error! status: ${retryResponse.status}`);
                                }
                                return retryResponse.json();
                            });
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add note to the list
                    const notesList = document.getElementById('notesList');
                    const noteElement = document.createElement('div');
                    noteElement.className = 'bg-gray-50 rounded-lg p-4';
                    noteElement.setAttribute('data-note-id', data.note.id);
                    noteElement.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">${data.note.body}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    By {{ auth()->user()->name }} on ${data.note.created_at}
                                </p>
                            </div>
                            <button type="button" 
                                    class="ml-2 text-red-400 hover:text-red-600 focus:outline-none"
                                    onclick="removeNote('${data.note.id}')">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                    notesList.insertBefore(noteElement, notesList.firstChild);
                    
                    // Clear the form
                    document.getElementById('noteBody').value = '';
                    
                    // Update notes count
                    const notesCount = document.querySelector('h3');
                    if (notesCount && notesCount.textContent.includes('Notes')) {
                        const currentCount = parseInt(notesCount.textContent.match(/\((\d+)\)/)[1]);
                        notesCount.textContent = notesCount.textContent.replace(/\(\d+\)/, `(${currentCount + 1})`);
                    }
                }
            })
            .catch(error => {
                console.error('Error adding note:', error);
                console.error('Error stack:', error.stack);
                alert('Error adding note: ' + error.message);
            });
        }

        function removeNote(noteId) {
            if (!confirm('Are you sure you want to delete this note?')) return;

            console.log('Removing note:', noteId);
            console.log('Tenant slug:', tenantSlug);
            console.log('Candidate ID:', candidateId);
            
            const csrfToken = getCsrfToken();
            console.log('CSRF Token:', csrfToken);

            fetch(`/${tenantSlug}/candidates/${candidateId}/notes/${noteId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Remove note response status:', response.status);
                console.log('Remove note response headers:', response.headers);
                
                if (!response.ok) {
                    return response.text().then(text => {
                        console.log('Remove note error response:', text);
                        if (response.status === 419) {
                            // CSRF token mismatch - refresh token and retry
                            return refreshCsrfToken().then(newToken => {
                                return fetch(`/${tenantSlug}/candidates/${candidateId}/notes/${noteId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json'
                                    }
                                });
                            }).then(retryResponse => {
                                if (!retryResponse.ok) {
                                    return retryResponse.text().then(text => {
                                        console.log('Remove note retry error response:', text);
                                        throw new Error(`HTTP error! status: ${retryResponse.status}`);
                                    });
                                }
                                return retryResponse.json();
                            });
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (jsonError) {
                        console.error('JSON parsing error:', jsonError);
                        console.error('Response text:', text);
                        throw new Error('Invalid JSON response from server: ' + text.substring(0, 200));
                    }
                });
            })
            .then(data => {
                console.log('Remove note success response:', data);
                if (data.success) {
                    const noteElement = document.querySelector(`[data-note-id="${noteId}"]`);
                    if (noteElement) {
                        noteElement.remove();
                        
                        // Update notes count
                        const notesCount = document.querySelector('h3');
                        if (notesCount && notesCount.textContent.includes('Notes')) {
                            const currentCount = parseInt(notesCount.textContent.match(/\((\d+)\)/)[1]);
                            notesCount.textContent = notesCount.textContent.replace(/\(\d+\)/, `(${currentCount - 1})`);
                        }
                    }
                } else {
                    console.error('Remove note failed:', data);
                    alert('Error removing note: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error removing note:', error);
                console.error('Error stack:', error.stack);
                alert('Error removing note: ' + error.message);
            });
        }

        // Tags functionality
        function handleTagKeypress(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                addTag();
            }
        }

        function addTag() {
            const tagInput = document.getElementById('tagInput');
            const tagName = tagInput.value.trim();
            
            if (!tagName) return;

            // Get CSRF token
            const csrfToken = getCsrfToken();
            
            fetch(`/${tenantSlug}/candidates/${candidateId}/tags`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: tagName })
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        if (response.status === 419) {
                            // CSRF token mismatch - refresh token and retry
                            return refreshCsrfToken().then(newToken => {
                                return fetch(`/${tenantSlug}/candidates/${candidateId}/tags`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': newToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({ name: tagName })
                                });
                            }).then(retryResponse => {
                                if (!retryResponse.ok) {
                                    throw new Error(`HTTP error! status: ${retryResponse.status}`);
                                }
                                return retryResponse.json();
                            });
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add tag to the list
                    const tagsContainer = document.querySelector('.flex.flex-wrap.gap-2.mb-3');
                    const tagElement = document.createElement('span');
                    tagElement.className = 'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-700';
                    tagElement.innerHTML = `
                        ${data.tag.name}
                        <button type="button" 
                                class="ml-1 inline-flex items-center justify-center w-4 h-4 rounded-full text-indigo-400 hover:bg-indigo-200 hover:text-indigo-500 focus:outline-none"
                                onclick="removeTag('${data.tag.id}', '${data.tag.name}')">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    `;
                    tagsContainer.appendChild(tagElement);
                    
                    // Clear the input
                    tagInput.value = '';
                }
            })
            .catch(error => {
                console.error('Error adding tag:', error);
                console.error('Error stack:', error.stack);
                alert('Error adding tag: ' + error.message);
            });
        }

        function removeTag(tagId, tagName) {
            if (!confirm(`Are you sure you want to remove the tag "${tagName}"?`)) return;

            fetch(`/${tenantSlug}/candidates/${candidateId}/tags/${tagId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove tag from the list
                    const tagElements = document.querySelectorAll('.flex.flex-wrap.gap-2.mb-3 span');
                    tagElements.forEach(element => {
                        if (element.textContent.includes(tagName)) {
                            element.remove();
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error removing tag:', error);
                console.error('Error stack:', error.stack);
                alert('Error removing tag: ' + error.message);
            });
        }
    </script>
</x-app-layout>