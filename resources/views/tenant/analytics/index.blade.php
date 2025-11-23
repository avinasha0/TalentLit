<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
                ['label' => 'Analytics', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filter Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-4 sm:p-6">
                    <div class="flex flex-col lg:flex-row lg:flex-wrap lg:items-end gap-4">
                        <div class="flex-1 min-w-0 lg:min-w-[200px]">
                            <label for="date-from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" id="date-from" name="from" 
                                   value="{{ now()->subDays(90)->format('Y-m-d') }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex-1 min-w-0 lg:min-w-[200px]">
                            <label for="date-to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" id="date-to" name="to" 
                                   value="{{ now()->format('Y-m-d') }}"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <div class="lg:flex-shrink-0">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quick Filters</label>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="preset-btn px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" data-days="30">30D</button>
                                <button type="button" class="preset-btn px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" data-days="90">90D</button>
                                <button type="button" class="preset-btn px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" data-days="ytd">YTD</button>
                                <button type="button" class="preset-btn px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200" data-days="all">ALL</button>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 lg:flex-shrink-0">
                            <button type="button" id="apply-filters" 
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 whitespace-nowrap">
                                Apply Filters
                            </button>
                            <a href="#" id="export-data" 
                               class="px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 text-center whitespace-nowrap flex items-center justify-center">
                                Export CSV
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div id="loading-state" class="hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-1/4 mb-4"></div>
                        <div class="space-y-3">
                            <div class="h-4 bg-gray-200 rounded"></div>
                            <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-100 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Applications</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="kpi-total-applications">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Hires</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="kpi-hires">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Median Time-to-Hire</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="kpi-median-time">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-100 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Active Pipeline</dt>
                                    <dd class="text-lg font-medium text-gray-900" id="kpi-active-pipeline">-</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1: Applications and Hires Over Time -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Applications Over Time</h3>
                        <div class="h-64">
                            <canvas id="applications-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Hires Over Time</h3>
                        <div class="h-64">
                            <canvas id="hires-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2: Stage Funnel and Source Effectiveness -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Stage Funnel</h3>
                        <div class="h-64">
                            <canvas id="stage-funnel-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" id="source-effectiveness-container">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Source Effectiveness</h3>
                        <div class="h-64">
                            <canvas id="source-effectiveness-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 3: Open Jobs by Department and Pipeline Snapshot -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Open Jobs by Department</h3>
                        <div class="h-64">
                            <canvas id="open-jobs-chart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Pipeline Snapshot</h3>
                        <div class="h-64">
                            <canvas id="pipeline-snapshot-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js', 'resources/js/analytics.js'])
</x-app-layout>
