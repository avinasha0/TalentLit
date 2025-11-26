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
        <!-- Export function - defined early so onclick can access it -->
        <script>
        window.exportOnboardingsCSV = function(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            console.log('Export CSV button clicked!');
            
            try {
                const tenantSlug = @json($tenantSlug);
                console.log('Tenant slug:', tenantSlug);
                
                if (!tenantSlug) {
                    console.error('Tenant slug is missing');
                    alert('Error: Unable to determine tenant. Please refresh the page.');
                    return false;
                }
                
                // Get current filter values
                const searchInput = document.getElementById('search-input');
                const departmentSelect = document.getElementById('filter-department');
                const managerSelect = document.getElementById('filter-manager');
                const statusSelect = document.getElementById('filter-status');
                const joiningMonthInput = document.getElementById('filter-joining-month');
                
                const searchValue = searchInput?.value?.trim() || '';
                const departmentValue = departmentSelect?.value || '';
                const managerValue = managerSelect?.value || '';
                const statusValue = statusSelect?.value || '';
                const joiningMonthValue = joiningMonthInput?.value || '';
                
                console.log('Filter values:', { searchValue, departmentValue, managerValue, statusValue, joiningMonthValue });
                
                // Build query string with current filters
                const params = new URLSearchParams();
                if (searchValue) params.append('search', searchValue);
                if (departmentValue) params.append('department', departmentValue);
                if (managerValue) params.append('manager', managerValue);
                if (statusValue) params.append('status', statusValue);
                if (joiningMonthValue) params.append('joiningMonth', joiningMonthValue);
                
                // Build export URL with filters
                const queryString = params.toString();
                const exportUrl = `/${tenantSlug}/api/onboardings/export/csv${queryString ? '?' + queryString : ''}`;
                
                console.log('Exporting CSV to:', exportUrl);
                
                // Trigger download
                window.location.href = exportUrl;
                return false;
            } catch (error) {
                console.error('Export CSV error:', error);
                alert('An error occurred while exporting. Please try again.');
                return false;
            }
        };
        </script>
        
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
                        onclick="exportOnboardingsCSV(event)"
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
                                        <a href="javascript:void(0)" 
                                           class="view-candidate-btn text-blue-600 hover:text-blue-800" 
                                           data-candidate-id="{{ $item->id }}">View</a>
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
                                    <a href="javascript:void(0)" 
                                       class="view-candidate-btn inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-800" 
                                       data-candidate-id="{{ $item->id }}">
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

    <!-- Inline test script to verify JavaScript is working -->
    <script>
    console.log('=== INLINE TEST SCRIPT RUNNING ===');
    console.log('Timestamp:', new Date().toISOString());
    console.log('Page URL:', window.location.href);
    console.log('Document ready state:', document.readyState);
    
    // Test if buttons exist
    setTimeout(function() {
        const testButtons = document.querySelectorAll('.view-candidate-btn');
        console.log('=== INLINE TEST: Found', testButtons.length, 'View buttons ===');
        if (testButtons.length > 0) {
            console.log('First button:', testButtons[0]);
            console.log('First button ID:', testButtons[0].getAttribute('data-candidate-id'));
        }
    }, 500);
    </script>

    <!-- Slide-over for View Details -->
    <div id="slide-over" class="hidden fixed inset-0 z-50 overflow-hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
        <div class="absolute inset-0 bg-gray-500 bg-opacity-75 transition-opacity" id="slide-over-backdrop"></div>
        <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex sm:pl-0">
            <div class="relative w-screen max-w-md">
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
                        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Candidate Details</h2>
                    </div>
                    <div class="flex-1 px-4 py-6 sm:px-6">
                        <!-- Loading State -->
                        <div id="slide-over-loading" class="hidden">
                            <div class="flex items-center justify-center py-12">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                                <span class="ml-3 text-gray-600">Loading...</span>
                            </div>
                        </div>
                        
                        <!-- Error State -->
                        <div id="slide-over-error" class="hidden">
                            <div class="text-center py-12">
                                <p class="text-sm text-gray-600">Unable to load candidate details. Try again.</p>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div id="slide-over-content" class="hidden space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Name</label>
                                <p class="text-sm text-gray-900" id="candidate-name">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Email</label>
                                <p class="text-sm text-gray-900" id="candidate-email">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Phone</label>
                                <p class="text-sm text-gray-900" id="candidate-phone">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Designation</label>
                                <p class="text-sm text-gray-900" id="candidate-designation">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Department</label>
                                <p class="text-sm text-gray-900" id="candidate-department">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Manager</label>
                                <p class="text-sm text-gray-900" id="candidate-manager">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Joining Date</label>
                                <p class="text-sm text-gray-900" id="candidate-joining-date">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                                <p class="text-sm text-gray-900" id="candidate-status">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Progress</label>
                                <p class="text-sm text-gray-900" id="candidate-progress">-</p>
                            </div>
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

    <!-- Slide-over JavaScript - Inline to ensure it runs -->
    <script>
    console.log('=== SLIDE-OVER SCRIPT LOADING (INLINE) ===');
    console.log('Timestamp:', new Date().toISOString());
    console.log('Document ready state:', document.readyState);
    
    // Move the entire slide-over script here to ensure it runs
    (function() {
        'use strict';
        
        const tenantSlug = @json($tenantSlug);
        console.log('[Slide-over INLINE] IIFE executing, tenantSlug:', tenantSlug);
        let slideOver, slideOverBackdrop, closeSlideOverBtn, slideOverLoading, slideOverError, slideOverContent;
        
        function initSlideOver() {
            console.log('[Slide-over INLINE] Initializing slide-over elements...');
            slideOver = document.getElementById('slide-over');
            slideOverBackdrop = document.getElementById('slide-over-backdrop');
            closeSlideOverBtn = document.getElementById('close-slide-over');
            slideOverLoading = document.getElementById('slide-over-loading');
            slideOverError = document.getElementById('slide-over-error');
            slideOverContent = document.getElementById('slide-over-content');
            
            console.log('[Slide-over INLINE] Elements found:', {
                slideOver: !!slideOver,
                slideOverBackdrop: !!slideOverBackdrop,
                closeSlideOverBtn: !!closeSlideOverBtn,
                slideOverLoading: !!slideOverLoading,
                slideOverError: !!slideOverError,
                slideOverContent: !!slideOverContent
            });
        }
        
        function openSlideOver() {
            console.log('[Slide-over INLINE] Opening slide-over, element exists:', !!slideOver);
            if (slideOver) {
                slideOver.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                console.log('[Slide-over INLINE] Slide-over opened successfully');
            } else {
                console.error('[Slide-over INLINE] Cannot open: slide-over element not found');
            }
        }
        
        function closeSlideOver() {
            if (slideOver) {
                slideOver.classList.add('hidden');
                document.body.style.overflow = '';
                if (slideOverLoading) slideOverLoading.classList.add('hidden');
                if (slideOverError) slideOverError.classList.add('hidden');
                if (slideOverContent) slideOverContent.classList.add('hidden');
            }
        }
        
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            try {
                const date = new Date(dateString);
                return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            } catch (e) {
                return dateString;
            }
        }
        
        function formatStatus(status) {
            const statusConfig = {
                'Pre-boarding': { bg: 'bg-yellow-400', text: 'text-yellow-800' },
                'Pending Docs': { bg: 'bg-orange-400', text: 'text-orange-800' },
                'IT Pending': { bg: 'bg-blue-400', text: 'text-blue-800' },
                'Joining Soon': { bg: 'bg-indigo-500', text: 'text-indigo-800' },
                'Completed': { bg: 'bg-green-400', text: 'text-green-800' },
                'Overdue': { bg: 'bg-red-400', text: 'text-red-800' }
            };
            const config = statusConfig[status] || { bg: 'bg-gray-400', text: 'text-gray-800' };
            return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${config.bg} ${config.text}">${status}</span>`;
        }
        
        function loadCandidateDetails(candidateId) {
            console.log('[Slide-over INLINE] loadCandidateDetails called with ID:', candidateId);
            if (!candidateId) {
                console.error('[Slide-over INLINE] Candidate ID is missing');
                return;
            }
            
            if (slideOverLoading) slideOverLoading.classList.remove('hidden');
            if (slideOverError) slideOverError.classList.add('hidden');
            if (slideOverContent) slideOverContent.classList.add('hidden');
            
            const apiUrl = `/${tenantSlug}/api/onboardings/${candidateId}`;
            console.log('[Slide-over INLINE] Fetching from URL:', apiUrl);
            
            fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
            .then(response => {
                console.log('[Slide-over INLINE] API response status:', response.status);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('[Slide-over INLINE] API response data:', data);
                if (slideOverLoading) slideOverLoading.classList.add('hidden');
                if (slideOverError) slideOverError.classList.add('hidden');
                if (slideOverContent) slideOverContent.classList.remove('hidden');
                
                const fullName = `${data.firstName || ''} ${data.lastName || ''}`.trim() || 'N/A';
                document.getElementById('candidate-name').textContent = fullName;
                document.getElementById('candidate-email').textContent = data.email || 'N/A';
                
                const phoneElement = document.getElementById('candidate-phone');
                if (phoneElement) phoneElement.textContent = data.phone || 'N/A';
                
                document.getElementById('candidate-designation').textContent = data.designation || 'N/A';
                document.getElementById('candidate-department').textContent = data.department || 'N/A';
                document.getElementById('candidate-manager').textContent = data.manager || 'N/A';
                document.getElementById('candidate-joining-date').textContent = formatDate(data.joiningDate);
                document.getElementById('candidate-status').innerHTML = formatStatus(data.status || 'Pre-boarding');
                
                const progressElement = document.getElementById('candidate-progress');
                if (progressElement) {
                    progressElement.textContent = (data.progressPercent !== undefined && data.progressPercent !== null) 
                        ? `${data.progressPercent}%` : 'N/A';
                }
            })
            .catch(error => {
                console.error('[Slide-over INLINE] Error loading candidate details:', error);
                if (slideOverLoading) slideOverLoading.classList.add('hidden');
                if (slideOverError) slideOverError.classList.remove('hidden');
                if (slideOverContent) slideOverContent.classList.add('hidden');
            });
        }
        
        function handleViewClick(e) {
            console.log('=== [Slide-over INLINE] Click event detected! ===');
            console.log('[Slide-over INLINE] Event target:', e.target);
            console.log('[Slide-over INLINE] Target tagName:', e.target.tagName);
            console.log('[Slide-over INLINE] Target className:', e.target.className);
            
            const btn = e.target.closest('.view-candidate-btn');
            console.log('[Slide-over INLINE] Button found via closest:', !!btn);
            
            if (!btn) {
                console.log('[Slide-over INLINE] No .view-candidate-btn found, ignoring click');
                return;
            }
            
            e.preventDefault();
            e.stopPropagation();
            
            const candidateId = btn.getAttribute('data-candidate-id');
            console.log('[Slide-over INLINE] Candidate ID:', candidateId);
            
            if (candidateId) {
                console.log('[Slide-over INLINE] Opening slide-over...');
                openSlideOver();
                loadCandidateDetails(candidateId);
            } else {
                console.error('[Slide-over INLINE] No candidate ID found on button');
            }
        }
        
        function init() {
            console.log('[Slide-over INLINE] Init function called');
            initSlideOver();
            
            const viewButtons = document.querySelectorAll('.view-candidate-btn');
            console.log('[Slide-over INLINE] Found View buttons:', viewButtons.length);
            
            if (viewButtons.length > 0) {
                console.log('[Slide-over INLINE] First button:', viewButtons[0]);
                console.log('[Slide-over INLINE] First button ID:', viewButtons[0].getAttribute('data-candidate-id'));
            } else {
                console.warn('[Slide-over INLINE] WARNING: No View buttons found!');
            }
            
            const onboardingPage = document.getElementById('onboardings-page');
            console.log('[Slide-over INLINE] onboarding-page element found:', !!onboardingPage);
            
            if (onboardingPage) {
                console.log('[Slide-over INLINE] Attaching click handler to onboarding-page');
                onboardingPage.addEventListener('click', handleViewClick);
            } else {
                console.log('[Slide-over INLINE] Attaching click handler to document');
                document.addEventListener('click', handleViewClick);
            }
            
            // Direct attachment as fallback
            viewButtons.forEach((btn, index) => {
                console.log(`[Slide-over INLINE] Attaching direct handler to button ${index}`);
                btn.addEventListener('click', function(e) {
                    console.log('[Slide-over INLINE] Direct button click handler fired');
                    e.preventDefault();
                    e.stopPropagation();
                    const candidateId = this.getAttribute('data-candidate-id');
                    if (candidateId) {
                        openSlideOver();
                        loadCandidateDetails(candidateId);
                    }
                });
            });
            
            if (closeSlideOverBtn) {
                closeSlideOverBtn.addEventListener('click', closeSlideOver);
            }
            if (slideOverBackdrop) {
                slideOverBackdrop.addEventListener('click', closeSlideOver);
            }
            
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && slideOver && !slideOver.classList.contains('hidden')) {
                    closeSlideOver();
                }
            });
            
            console.log('[Slide-over INLINE] Initialization complete');
        }
        
        // Run when DOM is ready
        console.log('[Slide-over INLINE] Document ready state:', document.readyState);
        if (document.readyState === 'loading') {
            console.log('[Slide-over INLINE] Waiting for DOMContentLoaded');
            document.addEventListener('DOMContentLoaded', function() {
                console.log('[Slide-over INLINE] DOMContentLoaded fired');
                init();
            });
        } else {
            console.log('[Slide-over INLINE] DOM already ready, calling init');
            init();
        }
        
        // Test after delay
        setTimeout(function() {
            console.log('[Slide-over INLINE] TEST: Checking buttons after 1 second');
            const testButtons = document.querySelectorAll('.view-candidate-btn');
            console.log('[Slide-over INLINE] TEST: Found', testButtons.length, 'buttons');
        }, 1000);
    })();
    </script>
</x-app-layout>

@push('scripts')
<script>
console.log('Employee Onboarding page script loading...');
(function() {
    'use strict';
    
    console.log('Employee Onboarding script initialized');
    
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
})();
</script>
{{-- JavaScript for slide-over panel --}}
<script>
console.log('========================================');
console.log('[Slide-over] Script starting...');
console.log('[Slide-over] Timestamp:', new Date().toISOString());
console.log('========================================');

(function() {
    'use strict';
    
    const tenantSlug = @json($tenantSlug);
    console.log('[Slide-over] IIFE executing, tenantSlug:', tenantSlug);
    let slideOver, slideOverBackdrop, closeSlideOverBtn, slideOverLoading, slideOverError, slideOverContent;
    
    function initSlideOver() {
        console.log('[Slide-over] Initializing slide-over elements...');
        slideOver = document.getElementById('slide-over');
        slideOverBackdrop = document.getElementById('slide-over-backdrop');
        closeSlideOverBtn = document.getElementById('close-slide-over');
        slideOverLoading = document.getElementById('slide-over-loading');
        slideOverError = document.getElementById('slide-over-error');
        slideOverContent = document.getElementById('slide-over-content');
        
        console.log('[Slide-over] Elements found:', {
            slideOver: !!slideOver,
            slideOverBackdrop: !!slideOverBackdrop,
            closeSlideOverBtn: !!closeSlideOverBtn,
            slideOverLoading: !!slideOverLoading,
            slideOverError: !!slideOverError,
            slideOverContent: !!slideOverContent
        });
    }
    
    function openSlideOver() {
        console.log('[Slide-over] Opening slide-over, element exists:', !!slideOver);
        if (slideOver) {
            slideOver.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            console.log('[Slide-over] Slide-over opened successfully');
        } else {
            console.error('[Slide-over] Cannot open: slide-over element not found');
        }
    }
    
    function closeSlideOver() {
        if (slideOver) {
            slideOver.classList.add('hidden');
            document.body.style.overflow = '';
            // Reset states
            if (slideOverLoading) slideOverLoading.classList.add('hidden');
            if (slideOverError) slideOverError.classList.add('hidden');
            if (slideOverContent) slideOverContent.classList.add('hidden');
        }
    }
    
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        try {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        } catch (e) {
            return dateString;
        }
    }
    
    function formatStatus(status) {
        const statusConfig = {
            'Pre-boarding': { bg: 'bg-yellow-400', text: 'text-yellow-800' },
            'Pending Docs': { bg: 'bg-orange-400', text: 'text-orange-800' },
            'IT Pending': { bg: 'bg-blue-400', text: 'text-blue-800' },
            'Joining Soon': { bg: 'bg-indigo-500', text: 'text-indigo-800' },
            'Completed': { bg: 'bg-green-400', text: 'text-green-800' },
            'Overdue': { bg: 'bg-red-400', text: 'text-red-800' }
        };
        const config = statusConfig[status] || { bg: 'bg-gray-400', text: 'text-gray-800' };
        return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${config.bg} ${config.text}">${status}</span>`;
    }
    
    function loadCandidateDetails(candidateId) {
        console.log('[Slide-over] loadCandidateDetails called with ID:', candidateId);
        if (!candidateId) {
            console.error('[Slide-over] Candidate ID is missing');
            return;
        }
        
        // Show loading state
        console.log('[Slide-over] Showing loading state');
        if (slideOverLoading) slideOverLoading.classList.remove('hidden');
        if (slideOverError) slideOverError.classList.add('hidden');
        if (slideOverContent) slideOverContent.classList.add('hidden');
        
        // Build API URL
        const apiUrl = `/${tenantSlug}/api/onboardings/${candidateId}`;
        console.log('[Slide-over] Fetching from URL:', apiUrl);
        
        // Fetch candidate details
        fetch(apiUrl, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
            }
        })
        .then(response => {
            console.log('[Slide-over] API response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('[Slide-over] API response data:', data);
            // Hide loading, show content
            if (slideOverLoading) slideOverLoading.classList.add('hidden');
            if (slideOverError) slideOverError.classList.add('hidden');
            if (slideOverContent) slideOverContent.classList.remove('hidden');
            
            // Populate fields
            const fullName = `${data.firstName || ''} ${data.lastName || ''}`.trim() || 'N/A';
            document.getElementById('candidate-name').textContent = fullName;
            document.getElementById('candidate-email').textContent = data.email || 'N/A';
            
            // Phone
            const phoneElement = document.getElementById('candidate-phone');
            if (phoneElement) {
                phoneElement.textContent = data.phone || 'N/A';
            }
            
            document.getElementById('candidate-designation').textContent = data.designation || 'N/A';
            document.getElementById('candidate-department').textContent = data.department || 'N/A';
            document.getElementById('candidate-manager').textContent = data.manager || 'N/A';
            document.getElementById('candidate-joining-date').textContent = formatDate(data.joiningDate);
            document.getElementById('candidate-status').innerHTML = formatStatus(data.status || 'Pre-boarding');
            
            // Progress
            const progressElement = document.getElementById('candidate-progress');
            if (progressElement) {
                if (data.progressPercent !== undefined && data.progressPercent !== null) {
                    progressElement.textContent = `${data.progressPercent}%`;
                } else {
                    progressElement.textContent = 'N/A';
                }
            }
        })
        .catch(error => {
            console.error('[Slide-over] Error loading candidate details:', error);
            // Hide loading, show error
            if (slideOverLoading) slideOverLoading.classList.add('hidden');
            if (slideOverError) slideOverError.classList.remove('hidden');
            if (slideOverContent) slideOverContent.classList.add('hidden');
        });
    }
    
    // Use event delegation to handle clicks on View buttons
    function handleViewClick(e) {
        console.log('========================================');
        console.log('[Slide-over] Click event detected!');
        console.log('[Slide-over] Event target:', e.target);
        console.log('[Slide-over] Event currentTarget:', e.currentTarget);
        console.log('[Slide-over] Event type:', e.type);
        console.log('[Slide-over] Target tagName:', e.target.tagName);
        console.log('[Slide-over] Target className:', e.target.className);
        
        const btn = e.target.closest('.view-candidate-btn');
        console.log('[Slide-over] Button found via closest:', !!btn);
        if (btn) {
            console.log('[Slide-over] Button element:', btn);
        }
        
        if (!btn) {
            console.log('[Slide-over] No .view-candidate-btn found, ignoring click');
            return;
        }
        
        e.preventDefault();
        e.stopPropagation();
        console.log('[Slide-over] Default prevented, event stopped');
        
        const candidateId = btn.getAttribute('data-candidate-id');
        console.log('[Slide-over] View clicked, candidate ID:', candidateId);
        console.log('[Slide-over] Button element:', btn);
        console.log('[Slide-over] Button classes:', btn.className);
        console.log('[Slide-over] Button attributes:', Array.from(btn.attributes).map(attr => `${attr.name}="${attr.value}"`));
        
        if (candidateId) {
            console.log('[Slide-over] Candidate ID found, opening slide-over...');
            openSlideOver();
            loadCandidateDetails(candidateId);
        } else {
            console.error('[Slide-over] No candidate ID found on button');
        }
    }
    
    // Initialize when DOM is ready
    function init() {
        console.log('[Slide-over] Init function called');
        initSlideOver();
        
        // Check for View buttons
        const viewButtons = document.querySelectorAll('.view-candidate-btn');
        console.log('========================================');
        console.log('[Slide-over] Searching for View buttons...');
        console.log('[Slide-over] Found View buttons:', viewButtons.length);
        
        if (viewButtons.length > 0) {
            console.log('[Slide-over] First button element:', viewButtons[0]);
            console.log('[Slide-over] First button HTML:', viewButtons[0].outerHTML);
            console.log('[Slide-over] First button ID:', viewButtons[0].getAttribute('data-candidate-id'));
            console.log('[Slide-over] First button classes:', viewButtons[0].className);
            console.log('[Slide-over] First button href:', viewButtons[0].getAttribute('href'));
        } else {
            console.warn('[Slide-over] WARNING: No View buttons found!');
            console.log('[Slide-over] Searching for any links with view-candidate-btn class...');
            const allLinks = document.querySelectorAll('a');
            console.log('[Slide-over] Total links on page:', allLinks.length);
            allLinks.forEach((link, idx) => {
                if (link.className && link.className.includes('view')) {
                    console.log(`[Slide-over] Link ${idx} with "view" in class:`, link.className, link);
                }
            });
        }
        console.log('========================================');
        
        // Use event delegation on the document or a parent container
        const onboardingPage = document.getElementById('onboardings-page');
        console.log('[Slide-over] onboarding-page element found:', !!onboardingPage);
        
        if (onboardingPage) {
            console.log('[Slide-over] Attaching click handler to onboarding-page container');
            onboardingPage.addEventListener('click', handleViewClick);
        } else {
            console.log('[Slide-over] onboarding-page not found, attaching to document');
            // Fallback to document if container not found
            document.addEventListener('click', handleViewClick);
        }
        
        // Also try direct attachment to buttons as fallback
        viewButtons.forEach((btn, index) => {
            console.log(`[Slide-over] Attaching direct handler to button ${index}`);
            btn.addEventListener('click', function(e) {
                console.log('[Slide-over] Direct button click handler fired');
                e.preventDefault();
                e.stopPropagation();
                const candidateId = this.getAttribute('data-candidate-id');
                console.log('[Slide-over] Direct handler - candidate ID:', candidateId);
                if (candidateId) {
                    openSlideOver();
                    loadCandidateDetails(candidateId);
                }
            });
        });
        
        // Close handlers
        if (closeSlideOverBtn) {
            console.log('[Slide-over] Attaching close button handler');
            closeSlideOverBtn.addEventListener('click', closeSlideOver);
        }
        
        if (slideOverBackdrop) {
            console.log('[Slide-over] Attaching backdrop click handler');
            slideOverBackdrop.addEventListener('click', closeSlideOver);
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && slideOver && !slideOver.classList.contains('hidden')) {
                closeSlideOver();
            }
        });
        
        console.log('[Slide-over] Initialization complete');
    }
    
    // Run when DOM is ready
    console.log('[Slide-over] Document ready state:', document.readyState);
    if (document.readyState === 'loading') {
        console.log('[Slide-over] DOM still loading, waiting for DOMContentLoaded');
        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Slide-over] DOMContentLoaded fired, calling init');
            init();
        });
    } else {
        console.log('[Slide-over] DOM already ready, calling init immediately');
        // DOM is already ready
        init();
    }
    
    console.log('[Slide-over] Script setup complete');
    console.log('========================================');
    
    // Test: Log all clickable elements after a short delay
    setTimeout(function() {
        console.log('[Slide-over] TEST: Checking page after 1 second...');
        const testButtons = document.querySelectorAll('.view-candidate-btn');
        console.log('[Slide-over] TEST: Found buttons:', testButtons.length);
        if (testButtons.length === 0) {
            console.error('[Slide-over] TEST FAILED: No buttons found!');
            console.log('[Slide-over] TEST: Checking if script is in correct location...');
            console.log('[Slide-over] TEST: Current script location:', window.location.href);
        } else {
            console.log('[Slide-over] TEST PASSED: Buttons found!');
            testButtons.forEach((btn, idx) => {
                console.log(`[Slide-over] TEST: Button ${idx}:`, {
                    id: btn.getAttribute('data-candidate-id'),
                    href: btn.getAttribute('href'),
                    classes: btn.className,
                    text: btn.textContent.trim()
                });
            });
        }
    }, 1000);
})();

// Global test function - can be called from console
window.testSlideOver = function() {
    console.log('[Slide-over] TEST FUNCTION CALLED');
    const buttons = document.querySelectorAll('.view-candidate-btn');
    console.log('Found buttons:', buttons.length);
    buttons.forEach((btn, idx) => {
        console.log(`Button ${idx}:`, btn);
    });
    return buttons.length;
};
</script>
@endpush
