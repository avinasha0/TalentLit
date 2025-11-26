@php
    $tenant = $tenantModel ?? $tenant ?? tenant();
    $tenantSlug = $tenant->slug;
    
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenantSlug)],
        ['label' => 'All Onboardings', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6" id="onboardings-page" data-tenant-slug="{{ $tenantSlug }}">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Employee Onboarding — All Onboardings</h1>
                <p class="mt-1 text-sm text-white">
                    View and manage every active onboarding. Search, filter, and take quick actions.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                <button type="button" 
                        id="import-candidates-btn"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white"
                        onclick="(function(){ const modal = document.getElementById('import-modal'); if(modal) { modal.classList.remove('hidden'); } })();">
                    Import Candidates
                </button>
                <span class="hidden sm:inline text-gray-500">·</span>
                <button type="button" 
                        id="export-csv-btn"
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white">
                    Export CSV
                </button>
                <button type="button" 
                        id="start-onboarding-btn"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Start Onboarding
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <x-card>
            <div class="p-4 space-y-4">
                <!-- Search -->
                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <label for="search-input" class="sr-only">Search</label>
                        <input type="text" 
                               id="search-input"
                               name="search"
                               value="{{ request('search', '') }}"
                               placeholder="Search by name, email, department or role"
                               class="w-full px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <button type="button" 
                            id="clear-search-btn"
                            class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 whitespace-nowrap">
                        Clear
                    </button>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="filter-department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="filter-department" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Departments</option>
                            <option value="Product">Product</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Sales">Sales</option>
                            <option value="People Ops">People Ops</option>
                            <option value="Infrastructure">Infrastructure</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-manager" class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                        <select id="filter-manager" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Managers</option>
                            <option value="Priya Patel">Priya Patel</option>
                            <option value="Rajesh Kumar">Rajesh Kumar</option>
                            <option value="Amit Verma">Amit Verma</option>
                            <option value="Suresh Menon">Suresh Menon</option>
                            <option value="CEO">CEO</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="filter-status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All</option>
                            <option value="Pre-boarding">Pre-boarding</option>
                            <option value="Pending Docs">Pending Docs</option>
                            <option value="IT Pending">IT Pending</option>
                            <option value="Joining Soon">Joining Soon</option>
                            <option value="Completed">Completed</option>
                            <option value="Overdue">Overdue</option>
                        </select>
                    </div>

                    <div>
                        <label for="filter-joining-month" class="block text-sm font-medium text-gray-700 mb-1">Joining Month</label>
                        <input type="month" 
                               id="filter-joining-month"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Bulk Actions Toolbar (hidden by default) -->
        <div id="bulk-actions-toolbar" class="hidden">
            <x-card>
                <div class="p-4 flex items-center justify-between">
                    <span id="bulk-selection-count" class="text-sm font-medium text-gray-700"></span>
                    <div class="flex gap-2">
                        <button type="button" 
                                id="bulk-send-reminder"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Send Reminder
                        </button>
                        <button type="button" 
                                id="bulk-mark-complete"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Mark Complete
                        </button>
                        <button type="button" 
                                id="bulk-export-selected"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Export Selected
                        </button>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Table Container -->
        @if($onboardings->isNotEmpty())
        <x-card>
            <!-- Desktop Table -->
            <div class="hidden lg:block overflow-hidden">
                <table role="table" aria-label="All onboardings table" class="w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                                    <input type="checkbox" 
                                           id="select-all-checkbox"
                                           aria-label="Select all on this page"
                                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[180px]">
                                    <button type="button" class="sort-btn" data-sort="fullName">
                                        Candidate
                                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[200px]">Email</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[150px]">Role / Dept</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">
                                    <button type="button" class="sort-btn" data-sort="joiningDate">
                                        Joining Date
                                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[120px]">Progress</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[130px]">Status</th>
                                <th scope="col" class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-[80px]">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="onboardings-tbody" class="bg-white divide-y divide-gray-200">
                            @forelse($onboardings as $item)
                                <tr class="onboarding-item hover:bg-gray-50 transition-colors"
                                    data-name="{{ strtolower($item->first_name . ' ' . $item->last_name) }}"
                                    data-email="{{ strtolower($item->email ?? '') }}"
                                    data-department="{{ strtolower($item->department ?? 'not assigned') }}"
                                    data-role="{{ strtolower($item->role ?? 'not assigned') }}">
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <input type="checkbox" 
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center min-w-0">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-xs">
                                                    {{ strtoupper(substr($item->first_name, 0, 1) . substr($item->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-2 min-w-0 flex-1">
                                                <div class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $item->first_name }} {{ $item->last_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="text-sm text-gray-900 truncate" title="{{ $item->email }}">{{ $item->email }}</div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="text-sm text-gray-900 truncate" title="{{ $item->role ?? 'N/A' }}">{{ $item->role ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-500 truncate" title="{{ $item->department ?? 'N/A' }}">{{ $item->department ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @if($item->joining_date)
                                                {{ \Carbon\Carbon::parse($item->joining_date)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="flex items-center space-x-1.5">
                                            <div class="flex-1 bg-gray-200 rounded-full h-2 min-w-[60px] max-w-[70px]">
                                                @php
                                                    $progress = str_replace('%', '', $item->progress ?? '0');
                                                    $progress = is_numeric($progress) ? (int)$progress : 0;
                                                @endphp
                                                <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" 
                                                     style="width: {{ $progress }}%"
                                                     role="progressbar" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100" 
                                                     aria-valuenow="{{ $progress }}"></div>
                                            </div>
                                            <span class="text-xs text-gray-600 whitespace-nowrap">{{ $item->progress ?? '0%' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap">
                                        @php
                                            $status = $item->status ?? 'Pre-boarding';
                                            $statusConfig = [
                                                'Pre-boarding' => ['bg' => 'bg-yellow-400', 'text' => 'text-yellow-800'],
                                                'Pending Docs' => ['bg' => 'bg-orange-400', 'text' => 'text-orange-800'],
                                                'IT Pending' => ['bg' => 'bg-blue-400', 'text' => 'text-blue-800'],
                                                'Joining Soon' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-800'],
                                                'Completed' => ['bg' => 'bg-green-400', 'text' => 'text-green-800'],
                                                'Overdue' => ['bg' => 'bg-red-400', 'text' => 'text-red-800']
                                            ];
                                            $config = $statusConfig[$status] ?? ['bg' => 'bg-gray-400', 'text' => 'text-gray-800'];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }}">
                                            {{ $status }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="text-sm text-gray-500">No onboardings found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div id="mobile-cards" class="lg:hidden space-y-3 px-2 py-2">
                    @forelse($onboardings as $item)
                        <x-card class="onboarding-item" 
                                data-name="{{ strtolower($item->first_name . ' ' . $item->last_name) }}"
                                data-email="{{ strtolower($item->email ?? '') }}"
                                data-department="{{ strtolower($item->department ?? 'not assigned') }}"
                                data-role="{{ strtolower($item->role ?? 'not assigned') }}">
                            <div class="p-3 space-y-2.5">
                                <!-- Candidate Header -->
                                <div class="flex items-start justify-between gap-2">
                                    <div class="flex items-center space-x-2.5 flex-1 min-w-0">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">
                                                {{ strtoupper(substr($item->first_name, 0, 1) . substr($item->last_name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $item->first_name }} {{ $item->last_name }}
                                            </div>
                                            <div class="text-xs text-gray-500 truncate mt-0.5">{{ $item->email }}</div>
                                        </div>
                                    </div>
                                    @php
                                        $status = $item->status ?? 'Pre-boarding';
                                        $statusConfig = [
                                            'Pre-boarding' => ['bg' => 'bg-yellow-400', 'text' => 'text-yellow-800'],
                                            'Pending Docs' => ['bg' => 'bg-orange-400', 'text' => 'text-orange-800'],
                                            'IT Pending' => ['bg' => 'bg-blue-400', 'text' => 'text-blue-800'],
                                            'Joining Soon' => ['bg' => 'bg-indigo-500', 'text' => 'text-indigo-800'],
                                            'Completed' => ['bg' => 'bg-green-400', 'text' => 'text-green-800'],
                                            'Overdue' => ['bg' => 'bg-red-400', 'text' => 'text-red-800']
                                        ];
                                        $config = $statusConfig[$status] ?? ['bg' => 'bg-gray-400', 'text' => 'text-gray-800'];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }} {{ $config['text'] }} flex-shrink-0">
                                        {{ $status }}
                                    </span>
                                </div>
                                
                                <!-- Details - Compact Layout -->
                                <div class="grid grid-cols-2 gap-2.5 pt-2 border-t border-gray-100">
                                    <div class="min-w-0">
                                        <div class="text-xs font-medium text-gray-500 mb-0.5">Role</div>
                                        <div class="text-sm font-medium text-gray-900 truncate" title="{{ $item->role ?? 'Not Assigned' }}">
                                            {{ $item->role ?? 'Not Assigned' }}
                                        </div>
                                        <div class="text-xs text-gray-500 truncate mt-0.5" title="{{ $item->department ?? 'Not Assigned' }}">
                                            {{ $item->department ?? 'Not Assigned' }}
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-medium text-gray-500 mb-0.5">Joining</div>
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($item->joining_date)
                                                {{ \Carbon\Carbon::parse($item->joining_date)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Progress -->
                                <div class="pt-2 border-t border-gray-100">
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-xs font-medium text-gray-600">Progress</span>
                                        <span class="text-xs font-semibold text-gray-900">{{ $item->progress ?? '0%' }}</span>
                                    </div>
                                    @php
                                        $progress = str_replace('%', '', $item->progress ?? '0');
                                        $progress = is_numeric($progress) ? (int)$progress : 0;
                                    @endphp
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-purple-600 h-2.5 rounded-full transition-all duration-300" 
                                             style="width: {{ $progress }}%"
                                             role="progressbar" 
                                             aria-valuemin="0" 
                                             aria-valuemax="100" 
                                             aria-valuenow="{{ $progress }}"></div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="pt-2 border-t border-gray-100">
                                    <a href="#" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-800">
                                        View Details
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </x-card>
                    @empty
                        <x-card>
                            <div class="px-4 py-8 text-center">
                                <div class="text-sm text-gray-500">No onboardings found</div>
                            </div>
                        </x-card>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if(method_exists($onboardings, 'links'))
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-200">
                {{ $onboardings->appends(request()->except('page'))->links() }}
            </div>
            @endif
        </x-card>
        @endif

        <!-- No Search Results State -->
        <div id="no-results-message" class="hidden">
            <x-card>
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        No onboardings match your search criteria. Try adjusting your search or filters.
                    </p>
                </div>
            </x-card>
        </div>

        <!-- Empty State -->
        @if($onboardings->isEmpty())
        <div id="empty-state">
            <x-card>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No onboardings found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        There are no active onboarding flows. Click "Start Onboarding" to create the first one.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('tenant.employee-onboarding.new', $tenantSlug) }}" 
                           class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                            Start Onboarding
                        </a>
                    </div>
                </div>
            </x-card>
        </div>
        @endif

        <!-- Loading Skeleton -->
        <div id="loading-skeleton" class="hidden">
            <x-card>
                <div class="animate-pulse space-y-4 p-6">
                    @for($i = 0; $i < 5; $i++)
                        <div class="flex items-center space-x-4">
                            <div class="rounded-full bg-gray-200 h-10 w-10"></div>
                            <div class="flex-1 space-y-2">
                                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                                <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </x-card>
        </div>
    </div>

    <!-- Slide-over for View Details -->
    <div id="slide-over" class="hidden fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="slide-over-backdrop"></div>
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
            <div class="relative w-screen max-w-2xl">
                <div class="absolute top-0 left-0 -ml-8 pt-4 pr-2 flex sm:-ml-10 sm:pr-4">
                    <button type="button" 
                            id="close-slide-over"
                            class="rounded-md text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white">
                        <span class="sr-only">Close panel</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
                    <div class="px-4 py-6 sm:px-6 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Onboarding Details</h2>
                    </div>
                    <div class="flex-1 px-4 py-6 sm:px-6">
                        <!-- Tabs -->
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button type="button" class="tab-btn active border-purple-500 text-purple-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="overview">Overview</button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="tasks">Tasks</button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="documents">Documents</button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="it-assets">IT & Assets</button>
                                <button type="button" class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="approvals">Approvals</button>
                            </nav>
                        </div>
                        <!-- Tab Content -->
                        <div id="tab-content" class="mt-6">
                            <!-- Content will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed bottom-4 right-4 z-50 space-y-2"></div>
    
    <!-- Import Modal (hidden by default) -->
    <div id="import-modal" class="hidden fixed inset-0 z-50 overflow-hidden">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75" id="import-modal-backdrop"></div>
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full p-6">
                    <h3 class="text-lg font-semibold mb-4">Import Candidates</h3>
                    <form id="import-form" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Select CSV/Excel File</label>
                            <input type="file" id="import-file" name="file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700">
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="{{ route('api.onboardings.import.template', $tenantSlug) }}" class="text-sm text-purple-600 hover:text-purple-800">Download Template</a>
                            <div class="flex gap-2">
                                <button type="button" id="cancel-import" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">Cancel</button>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700">Import</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
<script>
(function() {
    'use strict';
    
    function openImportModal() {
        const modal = document.getElementById('import-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }
    
    function closeImportModal() {
        const modal = document.getElementById('import-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
    
    function initImportButton() {
        const btn = document.getElementById('import-candidates-btn');
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openImportModal();
            });
        }
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initImportButton);
    } else {
        initImportButton();
    }
    
    // Modal close handlers
    document.addEventListener('DOMContentLoaded', function() {
        const backdrop = document.getElementById('import-modal-backdrop');
        const cancelBtn = document.getElementById('cancel-import');
        const form = document.getElementById('import-form');
        
        if (backdrop) {
            backdrop.addEventListener('click', closeImportModal);
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeImportModal);
        }
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Importing...';
                
                const importUrl = @json(route('api.onboardings.import.candidates', $tenantSlug));
                fetch(importUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Import';
                    if (data.success) {
                        alert(data.message || 'Candidates imported successfully');
                        closeImportModal();
                        // Reload page to show updated data
                        location.reload();
                    } else {
                        alert(data.message || 'Import failed');
                    }
                })
                .catch(error => {
                    console.error('Import error:', error);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Import';
                    alert('Import failed. Please try again.');
                });
            });
        }
    });
    
    // Make globally available
    window.openImportModal = openImportModal;
    window.closeImportModal = closeImportModal;
})();
</script>
{{-- JavaScript for interactive features (search, filters) - works with already-rendered data --}}
<script>
(function() {
    'use strict';
    
    // Client-side search functionality (works with rendered data)
    const searchInput = document.getElementById('search-input');
    const onboardingItems = document.querySelectorAll('.onboarding-item');
    const mobileCardsContainer = document.getElementById('mobile-cards');
    const tableBody = document.getElementById('onboardings-tbody');
    const noResultsMessage = document.getElementById('no-results-message');
    
    function performSearch() {
        const searchTerm = (searchInput?.value || '').trim().toLowerCase();
        let visibleCount = 0;
        
        if (onboardingItems.length === 0) return;
        
        onboardingItems.forEach(item => {
            const name = item.getAttribute('data-name') || '';
            const email = item.getAttribute('data-email') || '';
            const department = item.getAttribute('data-department') || '';
            const role = item.getAttribute('data-role') || '';
            
            const matches = !searchTerm || 
                name.includes(searchTerm) || 
                email.includes(searchTerm) || 
                department.includes(searchTerm) || 
                role.includes(searchTerm);
            
            if (matches) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        if (visibleCount === 0 && noResultsMessage) {
            noResultsMessage.classList.remove('hidden');
            if (tableBody) tableBody.closest('table').style.display = 'none';
            if (mobileCardsContainer) mobileCardsContainer.style.display = 'none';
        } else {
            if (noResultsMessage) noResultsMessage.classList.add('hidden');
            if (tableBody) tableBody.closest('table').style.display = '';
            if (mobileCardsContainer) mobileCardsContainer.style.display = '';
        }
    }
    
    // Search on input (debounced)
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(performSearch, 300);
        });
        
        // Search on Enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
        });
    }
    
    // Clear search button
    const clearSearchBtn = document.getElementById('clear-search-btn');
    if (clearSearchBtn) {
        clearSearchBtn.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                performSearch();
            }
        });
    }
    
    // Import modal handlers (keep existing functionality)
    const importBtn = document.getElementById('import-candidates-btn');
    const importModal = document.getElementById('import-modal');
    const closeModalBtn = document.getElementById('close-import-modal');
    const cancelBtn = document.getElementById('cancel-import-btn');
    
    function openImportModal() {
        if (importModal) importModal.classList.remove('hidden');
    }
    
    function closeImportModal() {
        if (importModal) importModal.classList.add('hidden');
    }
    
    if (importBtn) {
        importBtn.addEventListener('click', openImportModal);
    }
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeImportModal);
    }
    if (cancelBtn) {
        cancelBtn.addEventListener('click', closeImportModal);
    }
    
    // Export CSV button
    const exportBtn = document.getElementById('export-csv-btn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const tenantSlug = @json($tenantSlug);
            window.location.href = `/${tenantSlug}/api/onboardings/export/csv`;
        });
    }
})();
</script>
@endpush
