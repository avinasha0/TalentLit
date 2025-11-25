@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => '/'],
        ['label' => 'Employee Onboarding', 'url' => '/freeplan/employee-onboarding/all'],
        ['label' => 'All Onboardings', 'url' => null]
    ];
@endphp

<x-app-layout>
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
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
                           name="search"
                           value="{{ request('search', '') }}"
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
                                    Candidate
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role / Department</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joining Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($onboardings as $item)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" 
                                               class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">
                                                    {{ strtoupper(substr($item->first_name, 0, 1) . substr($item->last_name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $item->first_name }} {{ $item->last_name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $item->role ?? 'Not Assigned' }} / {{ $item->department ?? 'Not Assigned' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">
                                            @if($item->joining_date)
                                                {{ \Carbon\Carbon::parse($item->joining_date)->format('M d, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
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
                                            <span class="text-sm text-gray-700">{{ $item->progress ?? '0%' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-blue-600 hover:text-blue-800">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No onboardings found</h3>
                                        <p class="mt-1 text-sm text-gray-500">
                                            There are no active onboarding flows. Click "Start Onboarding" to create the first one.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>
