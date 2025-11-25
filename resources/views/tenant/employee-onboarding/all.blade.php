@php
    $tenant = $tenant ?? tenant();
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
                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 hover:text-white">
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
                <div>
                    <label for="search-input" class="sr-only">Search</label>
                    <input type="text" 
                           id="search-input"
                           placeholder="Search by name, email, department or role"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
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
        <x-card>
            <div class="overflow-x-auto">
                <!-- Desktop Table -->
                <div class="hidden lg:block">
                    <table role="table" aria-label="All onboardings table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" 
                                           id="select-all-checkbox"
                                           aria-label="Select all on this page"
                                           class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button type="button" class="sort-btn" data-sort="fullName">
                                        Candidate
                                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role / Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <button type="button" class="sort-btn" data-sort="joiningDate">
                                        Joining Date
                                        <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </button>
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="onboardings-tbody" class="bg-white divide-y divide-gray-200">
                            <!-- Table rows will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div id="mobile-cards" class="lg:hidden space-y-4 p-4">
                    <!-- Cards will be populated by JavaScript -->
                </div>
            </div>

            <!-- Pagination -->
            <div id="pagination-container" class="px-6 py-4 border-t border-gray-200">
                <!-- Pagination will be populated by JavaScript -->
            </div>
        </x-card>

        <!-- Empty State -->
        <div id="empty-state" class="hidden">
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
                        <button type="button" 
                                id="start-onboarding-empty"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700">
                            Start Onboarding
                        </button>
                    </div>
                </div>
            </x-card>
        </div>

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
</x-app-layout>

@push('scripts')
<script src="{{ asset('js/employee-onboarding-all.js') }}"></script>
@endpush
