@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $isJobFiltered = request()->route('job') !== null;
    $jobTitle = $isJobFiltered ? \App\Models\JobOpening::find(request()->route('job'))?->title : null;
    
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantSlug)],
        ['label' => 'Candidates', 'url' => null]
    ];
    
    if ($isJobFiltered && $jobTitle) {
        $breadcrumbs = [
            ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantSlug)],
            ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenantSlug)],
            ['label' => $jobTitle, 'url' => route('tenant.jobs.show', ['tenant' => $tenantSlug, 'job' => request()->route('job')])],
            ['label' => 'Applications', 'url' => null]
        ];
    }
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    @if($isJobFiltered && $jobTitle)
                        Applications for {{ $jobTitle }}
                    @else
                        Candidates
                    @endif
                </h1>
                <p class="mt-1 text-sm text-gray-500">
                    @if($isJobFiltered && $jobTitle)
                        View and manage applications for this job posting
                    @else
                        Manage candidate profiles and applications
                    @endif
                </p>
                @if(!$isJobFiltered && $maxCandidates !== -1)
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="text-xs text-gray-600">
                            {{ $currentCandidateCount }} / {{ $maxCandidates }} candidates
                        </span>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5">
                            <div class="bg-green-600 h-1.5 rounded-full transition-all duration-300" 
                                 style="width: {{ $maxCandidates > 0 ? min(100, ($currentCandidateCount / $maxCandidates) * 100) : 0 }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
            <div>
                @if(!$isJobFiltered)
                    @if($canAddCandidates)
                        <x-button variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Candidate
                        </x-button>
                    @else
                        <button onclick="showCandidateLimitReached()"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed opacity-75">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add Candidate
                        </button>
                    @endif
                @endif
            </div>
        </div>

        <!-- Filters -->
        <x-card>
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" 
                           name="search" 
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Name or email..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>

                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                    <select name="source" 
                            id="source"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Sources</option>
                        @foreach($sources as $source)
                            <option value="{{ $source }}" {{ request('source') == $source ? 'selected' : '' }}>
                                {{ $source }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="tag" class="block text-sm font-medium text-gray-700 mb-1">Tag</label>
                    <select name="tag" 
                            id="tag"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                        <option value="">All Tags</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag }}" {{ request('tag') == $tag ? 'selected' : '' }}>
                                {{ $tag }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end">
                    <x-button type="submit" variant="primary" class="w-full">
                        Filter Candidates
                    </x-button>
                </div>
            </form>
        </x-card>

        <!-- Results header -->
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-medium text-gray-900">
                Showing {{ $candidates->total() }} candidates
            </h2>
        </div>

        @if($candidates->count() > 0)
            <!-- Candidates Table -->
            <x-card>
                <x-table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($candidates as $candidate)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ substr($candidate->first_name, 0, 1) }}{{ substr($candidate->last_name, 0, 1) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $candidate->full_name }}
                                            </div>
                                            @if($candidate->primary_phone)
                                                <div class="text-sm text-gray-500">
                                                    {{ $candidate->primary_phone }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $candidate->primary_email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($candidate->source)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $candidate->source }}
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $candidate->updated_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('tenant.candidates.show', ['tenant' => $tenant->slug, 'candidate' => $candidate->id]) }}" 
                                       class="text-blue-600 hover:text-blue-800">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $candidates->appends(request()->query())->links() }}
                </div>
            </x-card>
        @else
            <x-empty 
                icon="users"
                title="No candidates found"
                description="Try adjusting your search criteria or add your first candidate."
                :action="'#'"
                actionText="Add Candidate"
            />
        @endif
    </div>

    <script>
        function showCandidateLimitReached() {
            const currentCount = {{ $currentCandidateCount }};
            const maxCandidates = {{ $maxCandidates === -1 ? 'Infinity' : $maxCandidates }};
            
            let message = `You've reached the candidates limit for your current plan. `;
            if (maxCandidates !== Infinity) {
                message += `You currently have ${currentCount} candidates out of ${maxCandidates} allowed. `;
            }
            message += `Please upgrade your subscription plan to add more candidates.`;
            
            alert(message);
            
            // Redirect to tenant-specific subscription page
            if (confirm('Would you like to view available plans?')) {
                window.location.href = '{{ route("subscription.show", $tenant->slug) }}';
            }
        }
    </script>
</x-app-layout>
