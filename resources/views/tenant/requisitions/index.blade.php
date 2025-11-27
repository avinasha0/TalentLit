@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => null]
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
                        <h1 class="text-2xl font-bold text-black">All Requisitions</h1>
                        <p class="mt-1 text-sm text-black">Manage job requisitions and approvals</p>
                    </div>
                    <div>
                        <a href="{{ tenantRoute('tenant.requisitions.create', $tenantSlug) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Create Requisition
                        </a>
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
                               placeholder="Title or description..."
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
                                    {{ $status }}
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
                            @foreach($allDepartments as $dept)
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
                            @foreach($allLocations as $loc)
                                <option value="{{ $loc }}" {{ request('location') == $loc ? 'selected' : '' }}>
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
        </x-card>

        <!-- Requisitions Table -->
        <x-card>
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-black">
                    {{ $requisitions->total() }} requisition{{ $requisitions->total() !== 1 ? 's' : '' }} found
                </h3>
            </div>

            @if($requisitions->count() > 0)
                @php
                    $currentCreatedSort = strtolower(request('created_sort', 'desc')) === 'asc' ? 'asc' : 'desc';
                    $nextCreatedSort = $currentCreatedSort === 'desc' ? 'asc' : 'desc';
                    $createdSortIcon = $currentCreatedSort === 'desc' ? '↓' : '↑';
                    $createdSortUrl = request()->fullUrlWithQuery([
                        'created_sort' => $nextCreatedSort,
                        'page' => 1,
                    ]);
                @endphp
                <div class="px-4 py-4 bg-white border-b border-gray-200 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div class="w-full sm:w-auto">
                        <label for="requisition-status-filter" class="text-sm font-medium text-gray-700">Filter by Status</label>
                        <select id="requisition-status-filter"
                                class="mt-1 w-full sm:w-52 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all" selected>All</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="w-full sm:w-auto">
                        <label for="requisition-search" class="text-sm font-medium text-gray-700">Search</label>
                        <input id="requisition-search"
                               type="search"
                               placeholder="Search job, dept, or status"
                               class="mt-1 w-full sm:w-64 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Headcount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button type="button"
                                            class="flex items-center gap-1 text-gray-600 hover:text-gray-900 focus:outline-none"
                                            title="Sort by Created Date"
                                            onclick="window.location='{{ $createdSortUrl }}'">
                                        <span>Created Date</span>
                                        <span aria-hidden="true" class="text-xs">{{ $createdSortIcon }}</span>
                                        <span class="sr-only">Current sort: {{ $currentCreatedSort === 'desc' ? 'Newest to oldest' : 'Oldest to newest' }}</span>
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requisition-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach($requisitions as $requisition)
                                @php
                                    $rowJobTitle = strtolower($requisition->job_title ?? '');
                                    $rowDepartment = strtolower($requisition->department ?? '');
                                    $rowStatus = strtolower($requisition->status ?? '');
                                @endphp
                                <tr class="requisition-row"
                                    data-job-title="{{ $rowJobTitle }}"
                                    data-department="{{ $rowDepartment }}"
                                    data-status="{{ $rowStatus }}">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $requisition->job_title }}</div>
                                        @if($requisition->justification)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($requisition->justification, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requisition->department ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requisition->headcount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                            @if($requisition->status === 'Approved') 
                                                bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($requisition->status === 'Pending') 
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($requisition->status === 'Rejected') 
                                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @elseif($requisition->status === 'Draft') 
                                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @else 
                                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endif">
                                            {{ $requisition->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ optional($requisition->created_at)->format('d M Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 focus:outline-none">
                                                View
                                            </a>
                                            @if($requisition->status === 'Pending')
                                                <span class="text-gray-300">|</span>
                                                <form method="POST" action="{{ tenantRoute('tenant.requisitions.approve', [$tenantSlug, $requisition->id]) }}" class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-green-600 hover:text-green-900 focus:outline-none"
                                                            onclick="return confirm('Are you sure you want to approve this requisition?')">
                                                        Approve
                                                    </button>
                                                </form>
                                                <span class="text-gray-300">|</span>
                                                <form method="POST" action="{{ tenantRoute('tenant.requisitions.reject', [$tenantSlug, $requisition->id]) }}" class="inline-block">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 focus:outline-none"
                                                            onclick="return confirm('Are you sure you want to reject this requisition?')">
                                                        Reject
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="requisition-no-results-row" class="hidden">
                                <td colspan="6" class="px-6 py-6 text-center text-sm text-gray-500">
                                    No requisitions match your filters.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200">
                    <div id="requisition-pagination" class="flex flex-wrap items-center gap-2 text-sm"></div>
                    <noscript>
                        <div class="mt-3">
                            {{ $requisitions->appends(request()->query())->links() }}
                        </div>
                    </noscript>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No requisitions found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new requisition.</p>
                    <div class="mt-6">
                        <a href="{{ tenantRoute('tenant.requisitions.create', $tenantSlug) }}"
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Create Requisition
                        </a>
                    </div>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('requisition-search');
        const statusFilter = document.getElementById('requisition-status-filter');
        const rows = Array.from(document.querySelectorAll('.requisition-row'));
        const noResultsRow = document.getElementById('requisition-no-results-row');
        const paginationContainer = document.getElementById('requisition-pagination');
        const rowsPerPage = 10;
        let currentPage = 1;

        if (!searchInput || !statusFilter || rows.length === 0 || !paginationContainer) {
            console.warn('Requisition filters unavailable: required elements missing.', {
                hasSearchInput: Boolean(searchInput),
                hasStatusFilter: Boolean(statusFilter),
                hasPaginationContainer: Boolean(paginationContainer),
                rowCount: rows.length,
            });
            return;
        }

        const normalize = (value) => (value || '').toString().toLowerCase();

        const getFilteredRows = () => {
            const searchTerm = normalize(searchInput.value).trim();
            const statusValue = normalize(statusFilter.value);

            return rows.filter((row) => {
                const jobTitle = normalize(row.dataset.jobTitle);
                const department = normalize(row.dataset.department);
                const status = normalize(row.dataset.status);

                const matchesSearch =
                    !searchTerm ||
                    jobTitle.includes(searchTerm) ||
                    department.includes(searchTerm) ||
                    status.includes(searchTerm);

                const matchesStatus = statusValue === 'all' || status === statusValue;

                return matchesSearch && matchesStatus;
            });
        };

        const renderPagination = (filteredCount) => {
            if (filteredCount === 0) {
                paginationContainer.innerHTML = '';
                paginationContainer.classList.add('hidden');
                return;
            }

            paginationContainer.classList.remove('hidden');
            const totalPages = Math.max(1, Math.ceil(filteredCount / rowsPerPage));
            paginationContainer.innerHTML = '';

            const createButton = (label, disabled, onClick, isActive = false) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.textContent = label;
                button.className = [
                    'px-3 py-1 rounded-md border transition-colors duration-150',
                    disabled ? 'text-gray-400 border-gray-200 cursor-not-allowed bg-gray-50' : 'text-gray-700 border-gray-300 hover:bg-gray-100',
                    isActive ? 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700' : '',
                ].join(' ').trim();
                button.disabled = disabled;

                if (!disabled) {
                    button.addEventListener('click', onClick);
                }

                return button;
            };

            const totalPagesArray = Array.from({ length: totalPages }, (_, i) => i + 1);

            paginationContainer.appendChild(
                createButton('Previous', currentPage === 1, () => {
                    currentPage = Math.max(1, currentPage - 1);
                    refreshView();
                })
            );

            totalPagesArray.forEach((pageNumber) => {
                paginationContainer.appendChild(
                    createButton(
                        pageNumber,
                        false,
                        () => {
                            currentPage = pageNumber;
                            refreshView();
                        },
                        pageNumber === currentPage
                    )
                );
            });

            paginationContainer.appendChild(
                createButton('Next', currentPage === totalPages, () => {
                    currentPage = Math.min(totalPages, currentPage + 1);
                    refreshView();
                })
            );
        };

        const renderTable = (filteredRows) => {
            rows.forEach((row) => row.classList.add('hidden'));

            if (filteredRows.length === 0) {
                if (noResultsRow) {
                    noResultsRow.classList.remove('hidden');
                }
                return;
            }

            const totalPages = Math.max(1, Math.ceil(filteredRows.length / rowsPerPage));
            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            const start = (currentPage - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            filteredRows.slice(start, end).forEach((row) => row.classList.remove('hidden'));

            if (noResultsRow) {
                noResultsRow.classList.add('hidden');
            }
        };

        const refreshView = () => {
            const filteredRows = getFilteredRows();
            renderTable(filteredRows);
            renderPagination(filteredRows.length);
        };

        const resetAndRefresh = () => {
            currentPage = 1;
            refreshView();
        };

        searchInput.addEventListener('input', resetAndRefresh);
        statusFilter.addEventListener('change', resetAndRefresh);
        refreshView();
    });
</script>

