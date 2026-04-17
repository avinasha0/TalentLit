@php
    $tenant = $tenantModel ?? $tenant ?? tenant();
    $tenantSlug = $tenant->slug;
    $isHiringManager = ($userRole ?? null) === 'Hiring Manager';
    $canExport = !$isHiringManager;
    $isSubdomainMode = $isSubdomain ?? false;
    
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenantSlug)],
        ['label' => 'Dashboard', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6" id="onboarding-dashboard" data-tenant-slug="{{ $tenantSlug }}" data-user-role="{{ $userRole ?? '' }}">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Employee Onboarding — Dashboard</h1>
                <p class="mt-1 text-sm text-white">
                    At-a-glance view of active onboardings, bottlenecks, and conversion metrics.
                </p>
            </div>
            @if($canExport)
            <div class="mt-4 sm:mt-0">
                <button type="button" 
                        id="export-dashboard-btn"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>
            @endif
        </div>

        <!-- Filters -->
        <x-card>
            <div class="p-4 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="filter-status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="filter-status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="All">All</option>
                            <option value="Pre-boarding">Pre-boarding</option>
                            <option value="Pending Docs">Pending Docs</option>
                            <option value="IT Pending">IT Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Converted">Converted</option>
                        </select>
                    </div>
                    <div>
                        <label for="filter-department" class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select id="filter-department" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All</option>
                        </select>
                    </div>
                    <div>
                        <label for="filter-manager" class="block text-sm font-medium text-gray-700 mb-1">Manager</label>
                        <select id="filter-manager" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All</option>
                        </select>
                    </div>
                    <div>
                        <label for="filter-date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select id="filter-date-range" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Time</option>
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 90 days</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                </div>
                <div id="custom-date-range" class="hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="filter-start-date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="filter-start-date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="filter-end-date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" id="filter-end-date" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="button" 
                            id="apply-filters-btn"
                            class="px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Apply Filters
                    </button>
                </div>
            </div>
        </x-card>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="kpi-cards">
            <x-card>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Active Onboardings</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900" id="kpi-active-onboardings">-</p>
                        </div>
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-sm" id="kpi-active-trend">
                        <span class="text-gray-500">Loading...</span>
                    </div>
                    <p class="mt-2 text-xs text-gray-500" id="kpi-active-updated">-</p>
                </div>
            </x-card>

            <x-card>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Pending Documents</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900" id="kpi-pending-documents">-</p>
                        </div>
                        <div class="p-3 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Missing required documents</p>
                </div>
            </x-card>

            <x-card>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Overdue Tasks</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900" id="kpi-overdue-tasks">-</p>
                        </div>
                        <div class="p-3 bg-red-100 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Tasks past due date</p>
                </div>
            </x-card>

            <x-card>
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600">Approvals Pending</p>
                            <p class="mt-2 text-3xl font-bold text-gray-900" id="kpi-approvals-pending">-</p>
                        </div>
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-4 text-xs text-gray-500">Awaiting approval</p>
                </div>
            </x-card>
        </div>

        <!-- Bottleneck Lists -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" id="bottleneck-lists">
            <!-- Missing Documents -->
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Missing Documents</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Missing</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joining</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="missing-docs-tbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>

            <!-- Overdue Tasks -->
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Overdue Tasks</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Overdue</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Oldest Due</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="overdue-tasks-tbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>

            <!-- Pending Approvals -->
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Approvals</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Candidate</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pending</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approver</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pending-approvals-tbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="charts-section">
            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Onboardings Created vs Converted (30 days)</h3>
                    <div class="h-64">
                        <canvas id="created-vs-converted-chart"></canvas>
                    </div>
                </div>
            </x-card>

            <x-card>
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Average Days to Convert</h3>
                    <div class="h-64 flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-4xl font-bold text-gray-900" id="avg-days-to-convert">-</p>
                            <p class="mt-2 text-sm text-gray-500">Rolling 30-day average</p>
                        </div>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Error Messages -->
        <div id="error-messages" class="hidden"></div>
    </div>

    @vite(['resources/js/app.js'])
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tenantSlug = @json($tenantSlug);
            const isSubdomainMode = @json($isSubdomainMode);
            
            // Determine basePath based on subdomain mode
            // In subdomain mode, API routes don't include tenant slug in path
            // In slug mode, API routes include tenant slug: /{tenant}/api/...
            const basePath = isSubdomainMode ? '' : `/${tenantSlug}`;
            
            let charts = {};
            let currentFilters = {};

            // Initialize
            console.log('Dashboard initialized', { 
                tenantSlug, 
                isSubdomainMode,
                basePath,
                currentPath: window.location.pathname,
                fullUrl: window.location.href
            });
            init();

            function init() {
                setupEventListeners();
                // Load data with a small delay to ensure DOM is ready
                setTimeout(() => {
                    loadKPIs();
                    loadBottlenecks();
                    loadCharts();
                }, 100);
            }

            function setupEventListeners() {
                // Apply filters
                document.getElementById('apply-filters-btn').addEventListener('click', function() {
                    applyFilters();
                });

                // Date range change
                document.getElementById('filter-date-range').addEventListener('change', function() {
                    const customRange = document.getElementById('custom-date-range');
                    if (this.value === 'custom') {
                        customRange.classList.remove('hidden');
                    } else {
                        customRange.classList.add('hidden');
                    }
                });

                // Export button
                const exportBtn = document.getElementById('export-dashboard-btn');
                if (exportBtn) {
                    exportBtn.addEventListener('click', function() {
                        exportDashboard();
                    });
                }
            }

            function getFilters() {
                return {
                    status: document.getElementById('filter-status').value,
                    department: document.getElementById('filter-department').value,
                    manager: document.getElementById('filter-manager').value,
                    dateRange: document.getElementById('filter-date-range').value,
                    startDate: document.getElementById('filter-start-date').value,
                    endDate: document.getElementById('filter-end-date').value,
                };
            }

            function applyFilters() {
                currentFilters = getFilters();
                loadKPIs();
                loadBottlenecks();
                loadCharts();
                
                // Update URL without reload
                const params = new URLSearchParams();
                Object.entries(currentFilters).forEach(([key, value]) => {
                    if (value) params.append(key, value);
                });
                const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
                window.history.pushState({}, '', newUrl);
            }

            async function loadKPIs() {
                try {
                    const params = new URLSearchParams(currentFilters);
                    const urlPath = basePath ? `${basePath}/api/onboardings/dashboard/kpis` : '/api/onboardings/dashboard/kpis';
                    const url = `${urlPath}?${params}`;
                    console.log('Loading KPIs from:', url);
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    
                    if (!response.ok) throw new Error('Failed to load KPIs');
                    
                    const data = await response.json();
                    
                    document.getElementById('kpi-active-onboardings').textContent = data.active_onboardings || 0;
                    document.getElementById('kpi-pending-documents').textContent = data.pending_documents || 0;
                    document.getElementById('kpi-overdue-tasks').textContent = data.overdue_tasks || 0;
                    document.getElementById('kpi-approvals-pending').textContent = data.approvals_pending || 0;
                    
                    // Trend indicator
                    const trendEl = document.getElementById('kpi-active-trend');
                    if (data.active_trend !== undefined) {
                        const trend = data.active_trend;
                        const isPositive = trend >= 0;
                        trendEl.innerHTML = `
                            <span class="${isPositive ? 'text-green-600' : 'text-red-600'}">
                                ${isPositive ? '↑' : '↓'} ${Math.abs(trend)}%
                            </span>
                            <span class="text-gray-500 ml-2">vs 7 days ago</span>
                        `;
                    }
                    
                    if (data.last_updated) {
                        document.getElementById('kpi-active-updated').textContent = 
                            'Updated: ' + new Date(data.last_updated).toLocaleString();
                    }
                } catch (error) {
                    console.error('Error loading KPIs:', error);
                    showError('Failed to load KPIs. Please try again.');
                }
            }

            async function loadBottlenecks() {
                try {
                    const params = new URLSearchParams(currentFilters);
                    const urlPath = basePath ? `${basePath}/api/onboardings/dashboard/bottlenecks` : '/api/onboardings/dashboard/bottlenecks';
                    const url = `${urlPath}?${params}`;
                    console.log('Loading bottlenecks from:', url);
                    
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Bottlenecks API error:', response.status, errorText);
                        throw new Error(`Failed to load bottlenecks: ${response.status} ${errorText.substring(0, 100)}`);
                    }
                    
                    const data = await response.json();
                    console.log('Bottlenecks data received:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    // Missing Documents
                    renderTable('missing-docs-tbody', data.missing_documents || [], ['name', 'missing_docs_count', 'joining_date'], 'Missing');
                    
                    // Overdue Tasks
                    renderTable('overdue-tasks-tbody', data.overdue_tasks || [], ['name', 'overdue_task_count', 'oldest_overdue_date'], 'Overdue');
                    
                    // Pending Approvals
                    renderTable('pending-approvals-tbody', data.pending_approvals || [], ['name', 'pending_approvals_count', 'first_pending_approver'], 'Pending');
                } catch (error) {
                    console.error('Error loading bottlenecks:', error);
                    showError('Failed to load bottleneck lists: ' + error.message);
                }
            }

            function renderTable(tbodyId, data, columns, type) {
                const tbody = document.getElementById(tbodyId);
                
                if (!data || data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">No data available</td></tr>';
                    return;
                }
                
                tbody.innerHTML = data.map(item => {
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(item.name)}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">${item[columns[1]] || 'N/A'}</td>
                            <td class="px-4 py-3 text-sm text-gray-500">${item[columns[2]] || 'N/A'}</td>
                            <td class="px-4 py-3 text-sm">
                                <button onclick="openOnboardingSlideOver('${item.id}')" 
                                        class="text-purple-600 hover:text-purple-900 font-medium">
                                    View
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            async function loadCharts() {
                try {
                    const params = new URLSearchParams(currentFilters);
                    // Ensure we have a leading slash and correct path
                    const urlPath = basePath ? `${basePath}/api/onboardings/dashboard/charts` : '/api/onboardings/dashboard/charts';
                    const url = `${urlPath}?${params}`;
                    console.log('Loading charts from:', url, 'basePath:', basePath);
                    
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin'
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Charts API error:', response.status, errorText);
                        throw new Error(`Failed to load charts: ${response.status} ${errorText}`);
                    }
                    
                    const data = await response.json();
                    console.log('Charts data received:', data);
                    
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    
                    // Created vs Converted chart
                    if (window.Chart) {
                        createCreatedVsConvertedChart(data.created_vs_converted);
                    } else {
                        console.warn('Chart.js not available, showing fallback');
                        showChartFallback(data);
                    }
                    
                    // Avg days to convert
                    document.getElementById('avg-days-to-convert').textContent = 
                        data.avg_days_to_convert !== undefined && data.avg_days_to_convert !== null
                            ? data.avg_days_to_convert + ' days' 
                            : 'N/A';
                } catch (error) {
                    console.error('Error loading charts:', error);
                    showError('Failed to load charts: ' + error.message);
                }
            }
            
            function showChartFallback(data) {
                // Fallback table if Chart.js is not available
                const chartContainer = document.getElementById('created-vs-converted-chart');
                if (chartContainer && chartContainer.parentElement) {
                    const created = data.created_vs_converted?.created || {};
                    const converted = data.created_vs_converted?.converted || {};
                    const allDates = [...new Set([...Object.keys(created), ...Object.keys(converted)])].sort();
                    
                    let html = '<table class="min-w-full divide-y divide-gray-200"><thead><tr>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Date</th>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Created</th>';
                    html += '<th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Converted</th>';
                    html += '</tr></thead><tbody>';
                    
                    allDates.forEach(date => {
                        html += `<tr><td class="px-4 py-2 text-sm">${date}</td>`;
                        html += `<td class="px-4 py-2 text-sm">${created[date] || 0}</td>`;
                        html += `<td class="px-4 py-2 text-sm">${converted[date] || 0}</td></tr>`;
                    });
                    
                    html += '</tbody></table>';
                    chartContainer.parentElement.innerHTML = html;
                }
            }

            function createCreatedVsConvertedChart(data) {
                const ctx = document.getElementById('created-vs-converted-chart');
                if (!ctx) {
                    console.error('Chart canvas element not found');
                    return;
                }
                
                // Destroy existing chart
                if (charts.createdVsConverted) {
                    charts.createdVsConverted.destroy();
                }
                
                if (!data) {
                    console.error('No chart data provided');
                    ctx.parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">No data available</p>';
                    return;
                }
                
                const created = data.created || {};
                const converted = data.converted || {};
                
                // Get all dates
                const allDates = [...new Set([...Object.keys(created), ...Object.keys(converted)])].sort();
                
                if (allDates.length === 0) {
                    ctx.parentElement.innerHTML = '<p class="text-gray-500 text-center py-8">No data available for the selected period</p>';
                    return;
                }
                
                try {
                    charts.createdVsConverted = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: allDates,
                            datasets: [
                                {
                                    label: 'Created',
                                    data: allDates.map(date => created[date] || 0),
                                    borderColor: 'rgb(139, 92, 246)',
                                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                    tension: 0.4
                                },
                                {
                                    label: 'Converted',
                                    data: allDates.map(date => converted[date] || 0),
                                    borderColor: 'rgb(16, 185, 129)',
                                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                    tension: 0.4
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Error creating chart:', error);
                    ctx.parentElement.innerHTML = '<p class="text-red-500 text-center py-8">Error rendering chart: ' + escapeHtml(error.message) + '</p>';
                }
            }

            function exportDashboard() {
                const params = new URLSearchParams(currentFilters);
                const exportUrl = `${basePath}/api/onboardings/dashboard/export?${params}`;
                window.location.href = exportUrl;
            }

            function showError(message) {
                const errorDiv = document.getElementById('error-messages');
                errorDiv.classList.remove('hidden');
                errorDiv.innerHTML = `
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">${escapeHtml(message)}</span>
                    </div>
                `;
            }

            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Open onboarding slide-over (reuse existing function if available)
            window.openOnboardingSlideOver = function(id) {
                // This should open the existing slide-over from all.blade.php
                // For now, redirect to the onboarding detail
                const tenantSlug = @json($tenantSlug);
                if (window.openOnboardingDetail) {
                    window.openOnboardingDetail(id);
                } else {
                    // Fallback: redirect to all onboardings page
                    const currentBasePath = basePath || '';
                    window.location.href = currentBasePath 
                        ? `${currentBasePath}/employee-onboarding/all`
                        : '/employee-onboarding/all';
                }
            };

            // Load initial filters from URL
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('status')) {
                document.getElementById('filter-status').value = urlParams.get('status');
            }
            if (urlParams.has('department')) {
                document.getElementById('filter-department').value = urlParams.get('department');
            }
            if (urlParams.has('manager')) {
                document.getElementById('filter-manager').value = urlParams.get('manager');
            }
            if (urlParams.has('dateRange')) {
                document.getElementById('filter-date-range').value = urlParams.get('dateRange');
            }
            if (urlParams.has('startDate')) {
                document.getElementById('filter-start-date').value = urlParams.get('startDate');
            }
            if (urlParams.has('endDate')) {
                document.getElementById('filter-end-date').value = urlParams.get('endDate');
            }
            
            currentFilters = getFilters();
        });
    </script>
</x-app-layout>

