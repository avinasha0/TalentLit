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
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-black">
                        <span id="total-requisitions-counter">Total Requisitions: {{ $requisitions->total() }}</span>
                    </h3>
                </div>
                <div class="flex items-center gap-2">
                    <!-- Bulk Actions -->
                    <div id="bulk-actions-container" class="hidden flex items-center gap-2">
                        <button type="button" 
                                id="bulk-delete-btn"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-white border border-red-300 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Bulk Delete
                        </button>
                        <button type="button" 
                                id="bulk-status-update-btn"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-yellow-700 bg-white border border-yellow-300 rounded-md hover:bg-yellow-50 focus:outline-none focus:ring-2 focus:ring-yellow-500">
                            Set to Pending
                        </button>
                    </div>
                    <button type="button" 
                            id="print-table-btn"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Print
                    </button>
                    <div class="relative">
                        <button type="button" 
                                id="column-visibility-btn"
                                class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Columns
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="column-visibility-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-50">
                            <div class="p-2 space-y-1">
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="job-title" checked>
                                    <span class="ml-2 text-sm text-gray-700">Job Title</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="department" checked>
                                    <span class="ml-2 text-sm text-gray-700">Department</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="headcount" checked>
                                    <span class="ml-2 text-sm text-gray-700">Headcount</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="contract-type" checked>
                                    <span class="ml-2 text-sm text-gray-700">Contract Type</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="status" checked>
                                    <span class="ml-2 text-sm text-gray-700">Status</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="created-date" checked>
                                    <span class="ml-2 text-sm text-gray-700">Created Date</span>
                                </label>
                                <label class="flex items-center px-2 py-1 hover:bg-gray-100 cursor-pointer">
                                    <input type="checkbox" class="column-toggle" data-column="actions" checked>
                                    <span class="ml-2 text-sm text-gray-700">Actions</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <a href="{{ tenantRoute('tenant.requisitions.export.csv', $tenantSlug) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Export CSV
                    </a>
                    <a href="{{ tenantRoute('tenant.requisitions.export.excel', $tenantSlug) }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}"
                       class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Export Excel
                    </a>
                    <button type="button" 
                            id="refresh-requisitions-btn"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            title="Refresh">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                    </button>
                </div>
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
                        <label for="requisition-department-filter" class="text-sm font-medium text-gray-700">Filter by Department</label>
                        <select id="requisition-department-filter"
                                class="mt-1 w-full sm:w-52 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="all" selected>All Departments</option>
                            @foreach($allDepartments as $dept)
                                <option value="{{ strtolower($dept) }}">{{ $dept }}</option>
                            @endforeach
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
                <!-- No Internet Banner -->
                <div id="offline-banner" class="hidden bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 px-4 py-3 mb-4">
                    <p class="font-medium">You are offline. Reconnecting…</p>
                </div>

                <!-- Loading Spinner -->
                <div id="table-loading-spinner" class="hidden flex justify-center items-center py-12">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                </div>

                <div class="overflow-x-auto" id="table-container">
                    <table class="min-w-full divide-y divide-gray-200" id="requisitions-table">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="job-title">Job Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="department">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="headcount">
                                    @php
                                        $currentHeadcountSort = strtolower(request('headcount_sort', ''));
                                        $nextHeadcountSort = '';
                                        $headcountSortIcon = '';
                                        if ($currentHeadcountSort === 'asc') {
                                            $nextHeadcountSort = 'desc';
                                            $headcountSortIcon = '↑';
                                        } elseif ($currentHeadcountSort === 'desc') {
                                            $nextHeadcountSort = 'asc';
                                            $headcountSortIcon = '↓';
                                        } else {
                                            $nextHeadcountSort = 'asc';
                                            $headcountSortIcon = '';
                                        }
                                        $headcountSortUrl = request()->fullUrlWithQuery([
                                            'headcount_sort' => $nextHeadcountSort,
                                            'page' => 1,
                                        ]);
                                    @endphp
                                    <button type="button"
                                            class="flex items-center gap-1 text-gray-600 hover:text-gray-900 focus:outline-none"
                                            title="Sort by Headcount"
                                            onclick="window.location='{{ $headcountSortUrl }}'">
                                        <span>Headcount</span>
                                        @if($headcountSortIcon)
                                            <span aria-hidden="true" class="text-xs">{{ $headcountSortIcon }}</span>
                                        @endif
                                        <span class="sr-only">Current sort: {{ $currentHeadcountSort === 'asc' ? 'Low to High' : ($currentHeadcountSort === 'desc' ? 'High to Low' : 'Not sorted') }}</span>
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="contract-type">Contract Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="status">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="created-date">
                                    <button type="button"
                                            class="flex items-center gap-1 text-gray-600 hover:text-gray-900 focus:outline-none"
                                            title="Sort by Created Date"
                                            onclick="window.location='{{ $createdSortUrl }}'">
                                        <span>Created Date</span>
                                        <span aria-hidden="true" class="text-xs">{{ $createdSortIcon }}</span>
                                        <span class="sr-only">Current sort: {{ $currentCreatedSort === 'desc' ? 'Newest to oldest' : 'Oldest to newest' }}</span>
                                    </button>
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider column-header" data-column="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="requisition-table-body" class="bg-white divide-y divide-gray-200">
                            @foreach($requisitions as $requisition)
                                @php
                                    $rowJobTitle = strtolower($requisition->job_title ?? '');
                                    $rowDepartment = strtolower($requisition->department ?? '');
                                    $rowStatus = strtolower($requisition->status ?? '');
                                @endphp
                                <tr class="requisition-row hover:bg-gray-50 transition-colors duration-150"
                                    data-job-title="{{ $rowJobTitle }}"
                                    data-department="{{ $rowDepartment }}"
                                    data-status="{{ $rowStatus }}"
                                    data-headcount="{{ $requisition->headcount ?? 0 }}"
                                    data-requisition-id="{{ $requisition->id }}">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" class="requisition-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $requisition->id }}">
                                    </td>
                                    <td class="px-6 py-4 column-cell" data-column="job-title">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}" 
                                               class="text-blue-600 hover:text-blue-900 hover:underline focus:outline-none focus:underline">
                                                {{ $requisition->job_title }}
                                            </a>
                                        </div>
                                        @if($requisition->justification)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($requisition->justification, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 column-cell" data-column="department">
                                        {{ $requisition->department ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 column-cell" data-column="headcount">
                                        {{ $requisition->headcount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 column-cell" data-column="contract-type">
                                        {{ $requisition->contract_type ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap column-cell" data-column="status">
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
                                            @endif"
                                            title="{{ $requisition->status }}">
                                            {{ $requisition->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 column-cell" data-column="created-date">
                                        {{ optional($requisition->created_at)->format('d M Y') ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium column-cell" data-column="actions">
                                        <div class="relative">
                                            <button type="button" 
                                                    class="row-actions-btn inline-flex items-center text-gray-600 hover:text-gray-900 focus:outline-none"
                                                    data-requisition-id="{{ $requisition->id }}">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                                </svg>
                                            </button>
                                            <div id="actions-dropdown-{{ $requisition->id }}" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded-md shadow-lg z-50">
                                                <div class="py-1">
                                                    <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</a>
                                                    <a href="{{ tenantRoute('tenant.requisitions.edit', [$tenantSlug, $requisition->id]) }}" 
                                                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                                                    <button type="button" 
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 duplicate-requisition-btn"
                                                            data-requisition-id="{{ $requisition->id }}">
                                                        Duplicate
                                                    </button>
                                                    <button type="button" 
                                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 view-audit-log-btn"
                                                            data-requisition-id="{{ $requisition->id }}">
                                                        Audit Log
                                                    </button>
                                                    @if($requisition->status === 'Pending')
                                                        <form method="POST" action="{{ tenantRoute('tenant.requisitions.approve', [$tenantSlug, $requisition->id]) }}" class="inline-block w-full">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="w-full text-left px-4 py-2 text-sm text-green-600 hover:bg-gray-100"
                                                                    onclick="return confirm('Are you sure you want to approve this requisition?')">
                                                                Approve
                                                            </button>
                                                        </form>
                                                        <form method="POST" action="{{ tenantRoute('tenant.requisitions.reject', [$tenantSlug, $requisition->id]) }}" class="inline-block w-full">
                                                            @csrf
                                                            <button type="submit" 
                                                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100"
                                                                    onclick="return confirm('Are you sure you want to reject this requisition?')">
                                                                Reject
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="requisition-no-results-row" class="hidden">
                                <td colspan="8" class="px-6 py-6 text-center text-sm text-gray-500">
                                    No requisitions found. Try adjusting your filters.
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
                <div class="text-center py-12" id="empty-state-message">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No requisitions found</h3>
                    <p class="mt-1 text-sm text-gray-500">No requisitions found. Try adjusting your filters.</p>
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

<!-- Toast Notification Container -->
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

<!-- Bulk Delete Confirmation Modal -->
<div id="bulk-delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Confirm Bulk Delete</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Are you sure you want to delete the selected requisitions? This action cannot be undone.</p>
            </div>
            <div class="flex justify-center gap-3 mt-4">
                <button id="confirm-bulk-delete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete
                </button>
                <button id="cancel-bulk-delete" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Audit Log Modal -->
<div id="audit-log-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Audit Log</h3>
                <button id="close-audit-log-modal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="audit-log-content" class="max-h-96 overflow-y-auto">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #table-container, #table-container * {
            visibility: visible;
        }
        #table-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('requisition-search');
        const statusFilter = document.getElementById('requisition-status-filter');
        const departmentFilter = document.getElementById('requisition-department-filter');
        const refreshBtn = document.getElementById('refresh-requisitions-btn');
        const totalCounter = document.getElementById('total-requisitions-counter');
        const rows = Array.from(document.querySelectorAll('.requisition-row'));
        const noResultsRow = document.getElementById('requisition-no-results-row');
        const paginationContainer = document.getElementById('requisition-pagination');
        const tableContainer = document.querySelector('.overflow-x-auto');
        const emptyStateMessage = document.getElementById('empty-state-message');
        const rowsPerPage = 10;
        let currentPage = 1;

        if (!searchInput || !statusFilter || !paginationContainer) {
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
            const departmentValue = departmentFilter ? normalize(departmentFilter.value) : 'all';

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
                const matchesDepartment = departmentValue === 'all' || department === departmentValue;

                return matchesSearch && matchesStatus && matchesDepartment;
            });
        };

        const updateTotalCounter = (count) => {
            if (totalCounter) {
                totalCounter.textContent = `Total Requisitions: ${count}`;
            }
        };

        const toggleEmptyState = (hasResults) => {
            if (tableContainer) {
                tableContainer.style.display = hasResults ? '' : 'none';
            }
            if (emptyStateMessage) {
                emptyStateMessage.style.display = hasResults ? 'none' : '';
            }
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
                toggleEmptyState(false);
                return;
            }

            toggleEmptyState(true);

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
            updateTotalCounter(filteredRows.length);
            renderTable(filteredRows);
            renderPagination(filteredRows.length);
        };

        const resetAndRefresh = () => {
            currentPage = 1;
            refreshView();
        };

        // Refresh button handler
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                window.location.reload();
            });
        }

        // Event listeners
        searchInput.addEventListener('input', resetAndRefresh);
        statusFilter.addEventListener('change', resetAndRefresh);
        if (departmentFilter) {
            departmentFilter.addEventListener('change', resetAndRefresh);
        }

        // ========== BULK SELECT FUNCTIONALITY ==========
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const requisitionCheckboxes = document.querySelectorAll('.requisition-checkbox');
        const bulkActionsContainer = document.getElementById('bulk-actions-container');

        function updateBulkActionsVisibility() {
            const selectedCount = document.querySelectorAll('.requisition-checkbox:checked').length;
            if (selectedCount > 0) {
                bulkActionsContainer.classList.remove('hidden');
            } else {
                bulkActionsContainer.classList.add('hidden');
            }
        }

        function updateSelectAllCheckbox() {
            const visibleCheckboxes = Array.from(requisitionCheckboxes).filter(cb => {
                const row = cb.closest('.requisition-row');
                return row && !row.classList.contains('hidden');
            });
            const checkedCount = visibleCheckboxes.filter(cb => cb.checked).length;
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = checkedCount > 0 && checkedCount === visibleCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < visibleCheckboxes.length;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const visibleCheckboxes = Array.from(requisitionCheckboxes).filter(cb => {
                    const row = cb.closest('.requisition-row');
                    return row && !row.classList.contains('hidden');
                });
                visibleCheckboxes.forEach(cb => {
                    cb.checked = this.checked;
                });
                updateBulkActionsVisibility();
            });
        }

        requisitionCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateBulkActionsVisibility();
                updateSelectAllCheckbox();
            });
        });

        // ========== BULK DELETE ==========
        const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
        const bulkDeleteModal = document.getElementById('bulk-delete-modal');
        const confirmBulkDelete = document.getElementById('confirm-bulk-delete');
        const cancelBulkDelete = document.getElementById('cancel-bulk-delete');

        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selectedIds = Array.from(document.querySelectorAll('.requisition-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length === 0) {
                    showToast('Please select at least one requisition.', 'error');
                    return;
                }
                bulkDeleteModal.classList.remove('hidden');
            });
        }

        if (cancelBulkDelete) {
            cancelBulkDelete.addEventListener('click', function() {
                bulkDeleteModal.classList.add('hidden');
            });
        }

        if (confirmBulkDelete) {
            confirmBulkDelete.addEventListener('click', async function() {
                const selectedIds = Array.from(document.querySelectorAll('.requisition-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length === 0) {
                    bulkDeleteModal.classList.add('hidden');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Deleting...';

                try {
                    const response = await fetch('{{ tenantRoute("tenant.requisitions.bulk-delete", $tenantSlug) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ ids: selectedIds }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast(data.message, 'success');
                        bulkDeleteModal.classList.add('hidden');
                        // Remove deleted rows
                        selectedIds.forEach(id => {
                            const row = document.querySelector(`tr[data-requisition-id="${id}"]`);
                            if (row) {
                                row.style.transition = 'opacity 0.3s';
                                row.style.opacity = '0';
                                setTimeout(() => row.remove(), 300);
                            }
                        });
                        updateBulkActionsVisibility();
                        updateSelectAllCheckbox();
                        refreshView();
                    } else {
                        showToast(data.message || 'Failed to delete requisitions.', 'error');
                    }
                } catch (error) {
                    console.error('Bulk delete error:', error);
                    showToast('Failed to delete requisitions. Please try again.', 'error');
                } finally {
                    this.disabled = false;
                    this.textContent = 'Delete';
                }
            });
        }

        // ========== BULK STATUS UPDATE ==========
        const bulkStatusUpdateBtn = document.getElementById('bulk-status-update-btn');

        if (bulkStatusUpdateBtn) {
            bulkStatusUpdateBtn.addEventListener('click', async function() {
                const selectedIds = Array.from(document.querySelectorAll('.requisition-checkbox:checked')).map(cb => cb.value);
                if (selectedIds.length === 0) {
                    showToast('Please select at least one requisition.', 'error');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Updating...';

                try {
                    const response = await fetch('{{ tenantRoute("tenant.requisitions.bulk-status-update", $tenantSlug) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({ ids: selectedIds, status: 'Pending' }),
                    });

                    const data = await response.json();

                    if (data.success) {
                        showToast('Status updated successfully', 'success');
                        // Uncheck all checkboxes
                        requisitionCheckboxes.forEach(cb => cb.checked = false);
                        updateBulkActionsVisibility();
                        updateSelectAllCheckbox();
                        // Reload page to show updated statuses
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        showToast(data.message || 'Failed to update status.', 'error');
                    }
                } catch (error) {
                    console.error('Bulk status update error:', error);
                    showToast('Failed to update status. Please try again.', 'error');
                } finally {
                    this.disabled = false;
                    this.textContent = 'Set to Pending';
                }
            });
        }

        // ========== PRINT TABLE ==========
        const printTableBtn = document.getElementById('print-table-btn');
        if (printTableBtn) {
            printTableBtn.addEventListener('click', function() {
                window.print();
            });
        }

        // ========== COLUMN VISIBILITY TOGGLE ==========
        const columnVisibilityBtn = document.getElementById('column-visibility-btn');
        const columnVisibilityDropdown = document.getElementById('column-visibility-dropdown');
        const columnToggles = document.querySelectorAll('.column-toggle');

        if (columnVisibilityBtn && columnVisibilityDropdown) {
            columnVisibilityBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                columnVisibilityDropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function(e) {
                if (!columnVisibilityBtn.contains(e.target) && !columnVisibilityDropdown.contains(e.target)) {
                    columnVisibilityDropdown.classList.add('hidden');
                }
            });

            columnToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const column = this.dataset.column;
                    const headers = document.querySelectorAll(`.column-header[data-column="${column}"]`);
                    const cells = document.querySelectorAll(`.column-cell[data-column="${column}"]`);
                    
                    headers.forEach(h => h.style.display = this.checked ? '' : 'none');
                    cells.forEach(c => c.style.display = this.checked ? '' : 'none');
                });
            });
        }

        // ========== ROW ACTIONS DROPDOWN ==========
        document.querySelectorAll('.row-actions-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const requisitionId = this.dataset.requisitionId;
                const dropdown = document.getElementById(`actions-dropdown-${requisitionId}`);
                
                // Close all other dropdowns
                document.querySelectorAll('[id^="actions-dropdown-"]').forEach(d => {
                    if (d !== dropdown) {
                        d.classList.add('hidden');
                    }
                });
                
                dropdown.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.row-actions-btn') && !e.target.closest('[id^="actions-dropdown-"]')) {
                document.querySelectorAll('[id^="actions-dropdown-"]').forEach(d => {
                    d.classList.add('hidden');
                });
            }
        });

        // ========== DUPLICATE REQUISITION ==========
        document.querySelectorAll('.duplicate-requisition-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const requisitionId = this.dataset.requisitionId;
                this.disabled = true;
                this.textContent = 'Duplicating...';

                try {
                    // Build the URL properly with the requisition ID
                    // Generate base URL pattern and replace the ID
                    @php
                        // Use first requisition ID or 1 as placeholder to generate route pattern
                        $sampleId = $requisitions->first()->id ?? 1;
                        $duplicateRoutePattern = tenantRoute('tenant.requisitions.duplicate', [$tenantSlug, $sampleId]);
                    @endphp
                    const duplicateUrl = '{{ $duplicateRoutePattern }}'.replace(/{{ $sampleId }}(?=\/|$)/g, requisitionId);
                    const response = await fetch(duplicateUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else if (response.redirected) {
                            window.location.href = response.url;
                        } else {
                            showToast('Requisition duplicated successfully.', 'success');
                            setTimeout(() => window.location.reload(), 1000);
                        }
                    } else {
                        const data = await response.json().catch(() => ({}));
                        showToast(data.message || 'Failed to duplicate requisition.', 'error');
                        this.disabled = false;
                        this.textContent = 'Duplicate';
                    }
                } catch (error) {
                    console.error('Duplicate error:', error);
                    showToast('Failed to duplicate requisition. Please try again.', 'error');
                    this.disabled = false;
                    this.textContent = 'Duplicate';
                }
            });
        });

        // ========== VIEW AUDIT LOG ==========
        const auditLogModal = document.getElementById('audit-log-modal');
        const closeAuditLogModal = document.getElementById('close-audit-log-modal');
        const auditLogContent = document.getElementById('audit-log-content');

        document.querySelectorAll('.view-audit-log-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const requisitionId = this.dataset.requisitionId;
                auditLogContent.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div></div>';

                try {
                    // Build the URL properly with the requisition ID
                    // Generate base URL pattern and replace the ID
                    @php
                        // Use first requisition ID or 1 as placeholder to generate route pattern
                        $sampleId = $requisitions->first()->id ?? 1;
                        $auditLogRoutePattern = tenantRoute('tenant.requisitions.audit-log', [$tenantSlug, $sampleId]);
                    @endphp
                    const auditLogUrl = '{{ $auditLogRoutePattern }}'.replace(/{{ $sampleId }}(?=\/|$)/g, requisitionId);
                    const response = await fetch(auditLogUrl);
                    const data = await response.json();

                    if (data.success) {
                        let html = `
                            <div class="mb-4 p-4 bg-gray-50 rounded">
                                <h4 class="font-medium text-gray-900">${data.requisition.job_title}</h4>
                                <p class="text-sm text-gray-600">Created: ${data.requisition.created_at} by ${data.requisition.created_by}</p>
                                <p class="text-sm text-gray-600">Status: ${data.requisition.status}</p>
                            </div>
                            <div class="space-y-3">
                        `;

                        if (data.audit_logs.length === 0) {
                            html += '<p class="text-center text-gray-500 py-8">No audit logs found.</p>';
                        } else {
                            data.audit_logs.forEach(log => {
                                html += `
                                    <div class="border-b border-gray-200 pb-3">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="font-medium text-gray-900">${log.action.replace('_', ' ').toUpperCase()}</p>
                                                ${log.field_name ? `<p class="text-sm text-gray-600">Field: ${log.field_name}</p>` : ''}
                                                ${log.old_value ? `<p class="text-sm text-gray-500">From: ${log.old_value}</p>` : ''}
                                                ${log.new_value ? `<p class="text-sm text-gray-500">To: ${log.new_value}</p>` : ''}
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-600">${log.user ? log.user.name : 'System'}</p>
                                                <p class="text-xs text-gray-500">${log.created_at}</p>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        html += '</div>';
                        auditLogContent.innerHTML = html;
                        auditLogModal.classList.remove('hidden');
                    } else {
                        auditLogContent.innerHTML = '<p class="text-center text-red-500 py-8">Failed to load audit log.</p>';
                    }
                } catch (error) {
                    console.error('Audit log error:', error);
                    auditLogContent.innerHTML = '<p class="text-center text-red-500 py-8">Failed to load audit log.</p>';
                }
            });
        });

        if (closeAuditLogModal) {
            closeAuditLogModal.addEventListener('click', function() {
                auditLogModal.classList.add('hidden');
            });
        }

        // ========== HIGHLIGHT RECENTLY UPDATED ROWS ==========
        function highlightRow(requisitionId) {
            const row = document.querySelector(`tr[data-requisition-id="${requisitionId}"]`);
            if (row) {
                row.classList.add('bg-yellow-100');
                setTimeout(() => {
                    row.classList.remove('bg-yellow-100');
                }, 5000);
            }
        }

        // Check for updated requisitions from URL parameter
        const urlParams = new URLSearchParams(window.location.search);
        const updatedId = urlParams.get('updated');
        if (updatedId) {
            highlightRow(updatedId);
        }

        // ========== LOADING SPINNER ==========
        const tableLoadingSpinner = document.getElementById('table-loading-spinner');
        const tableContainer = document.getElementById('table-container');

        function showLoading() {
            if (tableLoadingSpinner) tableLoadingSpinner.classList.remove('hidden');
            if (tableContainer) tableContainer.style.opacity = '0.5';
        }

        function hideLoading() {
            if (tableLoadingSpinner) tableLoadingSpinner.classList.add('hidden');
            if (tableContainer) tableContainer.style.opacity = '1';
        }

        // ========== OFFLINE DETECTION ==========
        const offlineBanner = document.getElementById('offline-banner');

        function updateOnlineStatus() {
            if (navigator.onLine) {
                if (offlineBanner) offlineBanner.classList.add('hidden');
            } else {
                if (offlineBanner) offlineBanner.classList.remove('hidden');
            }
        }

        window.addEventListener('online', updateOnlineStatus);
        window.addEventListener('offline', updateOnlineStatus);
        updateOnlineStatus();

        // ========== ERROR TOAST ==========
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `px-4 py-3 rounded-md shadow-lg flex items-center justify-between ${
                type === 'success' ? 'bg-green-100 text-green-800 border border-green-300' :
                type === 'error' ? 'bg-red-100 text-red-800 border border-red-300' :
                'bg-blue-100 text-blue-800 border border-blue-300'
            }`;
            toast.innerHTML = `
                <span>${message}</span>
                <button class="ml-4 text-gray-500 hover:text-gray-700" onclick="this.parentElement.remove()">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            document.getElementById('toast-container').appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        // ========== AUTO RELOAD EVERY 60 SECONDS ==========
        let autoReloadInterval;
        let isUserScrolling = false;
        let scrollTimeout;

        function startAutoReload() {
            autoReloadInterval = setInterval(async () => {
                // Don't reload if user is scrolling or has selections
                if (isUserScrolling || document.querySelectorAll('.requisition-checkbox:checked').length > 0) {
                    return;
                }

                try {
                    showLoading();
                    const response = await fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (response.ok) {
                        // Silently refresh - could parse and update table if needed
                        // For now, just hide loading
                        hideLoading();
                    } else {
                        hideLoading();
                        showToast('Failed to load requisitions. Please try again.', 'error');
                    }
                } catch (error) {
                    hideLoading();
                    if (navigator.onLine) {
                        showToast('Failed to load requisitions. Please try again.', 'error');
                    }
                }
            }, 60000); // 60 seconds
        }

        // Track scrolling
        window.addEventListener('scroll', function() {
            isUserScrolling = true;
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                isUserScrolling = false;
            }, 2000);
        });

        startAutoReload();

        // ========== ENHANCED REFRESH BUTTON ==========
        if (refreshBtn) {
            refreshBtn.addEventListener('click', async function() {
                showLoading();
                try {
                    await fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });
                    window.location.reload();
                } catch (error) {
                    hideLoading();
                    showToast('Failed to refresh. Please try again.', 'error');
                }
            });
        }

        // Initial render
        refreshView();
    });
</script>

