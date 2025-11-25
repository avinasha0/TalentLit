/**
 * Employee Onboarding - All Onboardings Page
 * Handles table display, search, filters, bulk actions, pagination, and slide-over
 */

(function() {
    'use strict';

    // Configuration
    // Get tenant slug from current URL or data attribute
    const tenantSlug = document.querySelector('[data-tenant-slug]')?.dataset.tenantSlug || 
                       window.location.pathname.split('/')[1] || '';
    const API_BASE = `/${tenantSlug}/api/onboardings`;
    const USE_MOCK = true; // Set to false when backend is ready

    // State
    let state = {
        onboardings: [],
        filteredOnboardings: [],
        selectedIds: new Set(),
        currentPage: 1,
        pageSize: 10,
        total: 0,
        sortBy: 'joiningDate',
        sortDir: 'asc',
        filters: {
            search: '',
            status: '',
            department: '',
            manager: '',
            joiningMonth: ''
        },
        isLoading: false
    };

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadOnboardings();
    });

    /**
     * Initialize all event listeners
     */
    function initializeEventListeners() {
        // Search
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    state.filters.search = e.target.value;
                    state.currentPage = 1;
                    loadOnboardings();
                }, 300);
            });
        }

        // Filters
        ['department', 'manager', 'status', 'joining-month'].forEach(filterId => {
            const element = document.getElementById(`filter-${filterId}`);
            if (element) {
                element.addEventListener('change', function(e) {
                    const key = filterId.replace('-', '');
                    state.filters[key] = e.target.value;
                    state.currentPage = 1;
                    loadOnboardings();
                });
            }
        });

        // Sort buttons
        document.querySelectorAll('.sort-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const sortBy = this.dataset.sort;
                if (state.sortBy === sortBy) {
                    state.sortDir = state.sortDir === 'asc' ? 'desc' : 'asc';
                } else {
                    state.sortBy = sortBy;
                    state.sortDir = 'asc';
                }
                loadOnboardings();
            });
        });

        // Select all checkbox
        const selectAll = document.getElementById('select-all-checkbox');
        if (selectAll) {
            selectAll.addEventListener('change', function(e) {
                const checked = e.target.checked;
                state.selectedIds.clear();
                if (checked) {
                    state.filteredOnboardings.forEach(item => {
                        state.selectedIds.add(item.id);
                    });
                }
                updateBulkActions();
                renderTable();
            });
        }

        // Bulk actions
        document.getElementById('bulk-send-reminder')?.addEventListener('click', handleBulkRemind);
        document.getElementById('bulk-mark-complete')?.addEventListener('click', handleBulkMarkComplete);
        document.getElementById('bulk-export-selected')?.addEventListener('click', handleBulkExport);

        // Start onboarding buttons
        document.getElementById('start-onboarding-btn')?.addEventListener('click', () => {
            // TODO: Open modal for new onboarding
            showToast('Start Onboarding modal will open here', 'info');
        });
        document.getElementById('start-onboarding-empty')?.addEventListener('click', () => {
            // TODO: Open modal for new onboarding
            showToast('Start Onboarding modal will open here', 'info');
        });

        // Export CSV
        document.getElementById('export-csv-btn')?.addEventListener('click', handleExportCSV);

        // Slide-over
        document.getElementById('close-slide-over')?.addEventListener('click', closeSlideOver);
        document.getElementById('slide-over-backdrop')?.addEventListener('click', closeSlideOver);
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                switchTab(this.dataset.tab);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSlideOver();
            }
        });
    }

    /**
     * Load onboardings from API
     */
    async function loadOnboardings() {
        state.isLoading = true;
        showLoading();

        try {
            const params = new URLSearchParams({
                page: state.currentPage,
                pageSize: state.pageSize,
                sortBy: state.sortBy,
                sortDir: state.sortDir,
                ...Object.fromEntries(
                    Object.entries(state.filters).filter(([_, v]) => v !== '')
                )
            });

            const response = await fetch(`${API_BASE}?${params}`);
            if (!response.ok) {
                throw new Error('Failed to load onboardings');
            }

            const data = await response.json();
            state.onboardings = data.data || [];
            state.filteredOnboardings = data.data || [];
            state.total = data.meta?.total || 0;

            hideLoading();
            renderTable();
            renderPagination();
        } catch (error) {
            console.error('Error loading onboardings:', error);
            hideLoading();
            showError('Failed to load onboardings. Please try again.');
        } finally {
            state.isLoading = false;
        }
    }

    /**
     * Render table with onboardings data
     */
    function renderTable() {
        const tbody = document.getElementById('onboardings-tbody');
        const mobileCards = document.getElementById('mobile-cards');
        const emptyState = document.getElementById('empty-state');

        if (!tbody || !mobileCards) return;

        if (state.filteredOnboardings.length === 0) {
            tbody.innerHTML = '';
            mobileCards.innerHTML = '';
            emptyState?.classList.remove('hidden');
            return;
        }

        emptyState?.classList.add('hidden');

        // Desktop table
        tbody.innerHTML = state.filteredOnboardings.map(item => createTableRow(item)).join('');

        // Mobile cards
        mobileCards.innerHTML = state.filteredOnboardings.map(item => createMobileCard(item)).join('');

        // Attach event listeners to new elements
        attachRowEventListeners();
    }

    /**
     * Create table row HTML
     */
    function createTableRow(item) {
        const initials = getInitials(item.fullName);
        const joiningDate = formatDate(item.joiningDate);
        const isSelected = state.selectedIds.has(item.id);
        const canConvert = item.progressPercent >= 100;

        return `
            <tr class="hover:bg-gray-50" tabindex="0" data-id="${item.id}">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" 
                           class="row-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                           data-id="${item.id}"
                           ${isSelected ? 'checked' : ''}>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            ${item.avatarUrl 
                                ? `<img class="h-10 w-10 rounded-full" src="${item.avatarUrl}" alt="${item.fullName}">`
                                : `<div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">${initials}</div>`
                            }
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(item.fullName)}</div>
                            <div class="text-sm text-gray-500">${escapeHtml(item.designation)}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="mailto:${item.email}" class="text-sm text-blue-600 hover:text-blue-800">${escapeHtml(item.email)}</a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${escapeHtml(item.designation)} · ${escapeHtml(item.department)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${joiningDate}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-purple-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: ${item.progressPercent}%"
                                 role="progressbar" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100" 
                                 aria-valuenow="${item.progressPercent}"></div>
                        </div>
                        <span class="text-sm text-gray-700">${item.progressPercent}%</span>
                    </div>
                    ${item.pendingItems > 0 ? `<div class="text-xs text-gray-500 mt-1">${item.pendingItems} pending</div>` : ''}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    ${createStatusBadge(item.status)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <button type="button" 
                                class="action-btn text-blue-600 hover:text-blue-800"
                                data-action="view"
                                data-id="${item.id}"
                                aria-label="View onboarding for ${escapeHtml(item.fullName)}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                class="action-btn text-gray-600 hover:text-gray-800"
                                data-action="edit"
                                data-id="${item.id}"
                                aria-label="Edit onboarding for ${escapeHtml(item.fullName)}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button type="button" 
                                class="action-btn ${canConvert ? 'bg-purple-600 text-white hover:bg-purple-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed'} px-3 py-1 rounded text-sm font-medium"
                                data-action="convert"
                                data-id="${item.id}"
                                ${!canConvert ? 'disabled title="Convert to employee (complete pending items first)"' : ''}
                                aria-label="Convert to employee for ${escapeHtml(item.fullName)}">
                            Convert
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }

    /**
     * Create mobile card HTML
     */
    function createMobileCard(item) {
        const initials = getInitials(item.fullName);
        const joiningDate = formatDate(item.joiningDate);
        const isSelected = state.selectedIds.has(item.id);
        const canConvert = item.progressPercent >= 100;

        return `
            <div class="bg-white border border-gray-200 rounded-lg p-4" data-id="${item.id}">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" 
                               class="row-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500"
                               data-id="${item.id}"
                               ${isSelected ? 'checked' : ''}>
                        ${item.avatarUrl 
                            ? `<img class="h-10 w-10 rounded-full" src="${item.avatarUrl}" alt="${item.fullName}">`
                            : `<div class="h-10 w-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-medium text-sm">${initials}</div>`
                        }
                        <div>
                            <div class="font-medium text-gray-900">${escapeHtml(item.fullName)}</div>
                            <div class="text-sm text-gray-500">${escapeHtml(item.designation)} · ${escapeHtml(item.department)}</div>
                        </div>
                    </div>
                    ${createStatusBadge(item.status)}
                </div>
                <div class="space-y-2 text-sm text-gray-600 mb-3">
                    <div>${joiningDate} · ${item.progressPercent}% completed</div>
                    <div class="flex items-center">
                        <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: ${item.progressPercent}%"></div>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end space-x-2">
                    <button type="button" class="action-btn text-blue-600" data-action="view" data-id="${item.id}">View</button>
                    <button type="button" class="action-btn text-gray-600" data-action="edit" data-id="${item.id}">Edit</button>
                    <button type="button" 
                            class="action-btn ${canConvert ? 'bg-purple-600 text-white' : 'bg-gray-300 text-gray-500'} px-3 py-1 rounded text-sm"
                            data-action="convert"
                            data-id="${item.id}"
                            ${!canConvert ? 'disabled' : ''}>
                        Convert
                    </button>
                </div>
            </div>
        `;
    }

    /**
     * Create status badge HTML
     */
    function createStatusBadge(status) {
        const statusConfig = {
            'Pre-boarding': { bg: 'bg-yellow-400', text: 'text-yellow-800' },
            'Pending Docs': { bg: 'bg-orange-400', text: 'text-orange-800' },
            'IT Pending': { bg: 'bg-blue-400', text: 'text-blue-800' },
            'Joining Soon': { bg: 'bg-indigo-500', text: 'text-indigo-800' },
            'Completed': { bg: 'bg-green-400', text: 'text-green-800' },
            'Overdue': { bg: 'bg-red-400', text: 'text-red-800' }
        };

        const config = statusConfig[status] || { bg: 'bg-gray-400', text: 'text-gray-800' };

        return `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${config.bg} ${config.text}">${escapeHtml(status)}</span>`;
    }

    /**
     * Attach event listeners to table rows
     */
    function attachRowEventListeners() {
        // Row checkboxes
        document.querySelectorAll('.row-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function(e) {
                const id = parseInt(this.dataset.id);
                if (e.target.checked) {
                    state.selectedIds.add(id);
                } else {
                    state.selectedIds.delete(id);
                }
                updateSelectAllCheckbox();
                updateBulkActions();
            });
        });

        // Action buttons
        document.querySelectorAll('.action-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.dataset.action;
                const id = parseInt(this.dataset.id);
                handleRowAction(action, id);
            });
        });

        // Row click (for view)
        document.querySelectorAll('tr[data-id]').forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('button') && !e.target.closest('input')) {
                    const id = parseInt(this.dataset.id);
                    handleRowAction('view', id);
                }
            });
        });
    }

    /**
     * Handle row action
     */
    async function handleRowAction(action, id) {
        switch(action) {
            case 'view':
                await openSlideOver(id);
                break;
            case 'edit':
                showToast('Edit onboarding modal will open here', 'info');
                break;
            case 'convert':
                await handleConvert(id);
                break;
        }
    }

    /**
     * Open slide-over with onboarding details
     */
    async function openSlideOver(id) {
        try {
            const response = await fetch(`${API_BASE}/${id}`);
            if (!response.ok) {
                throw new Error('Failed to load onboarding details');
            }
            const data = await response.json();
            
            const slideOver = document.getElementById('slide-over');
            const title = document.getElementById('slide-over-title');
            
            if (title) {
                title.textContent = `Onboarding: ${data.fullName}`;
            }
            
            slideOver?.classList.remove('hidden');
            switchTab('overview', data);
        } catch (error) {
            console.error('Error loading onboarding details:', error);
            showError('Failed to load onboarding details');
        }
    }

    /**
     * Close slide-over
     */
    function closeSlideOver() {
        document.getElementById('slide-over')?.classList.add('hidden');
    }

    /**
     * Switch tab in slide-over
     */
    function switchTab(tabName, data = null) {
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'border-purple-500', 'text-purple-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });

        const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active', 'border-purple-500', 'text-purple-600');
            activeBtn.classList.remove('border-transparent', 'text-gray-500');
        }

        const content = document.getElementById('tab-content');
        if (content) {
            content.innerHTML = `<p class="text-gray-500">${tabName.charAt(0).toUpperCase() + tabName.slice(1)} tab content will be displayed here.</p>`;
        }
    }

    /**
     * Handle convert action
     */
    async function handleConvert(id) {
        try {
            const response = await fetch(`${API_BASE}/${id}/convert`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                const error = await response.json();
                throw new Error(error.message || 'Failed to convert onboarding');
            }

            const data = await response.json();
            showToast('Onboarding converted to employee. Employee account created.', 'success');
            loadOnboardings();
        } catch (error) {
            console.error('Error converting onboarding:', error);
            showError(error.message || 'Failed to convert onboarding');
        }
    }

    /**
     * Handle bulk remind
     */
    async function handleBulkRemind() {
        if (state.selectedIds.size === 0) return;

        try {
            const response = await fetch(`${API_BASE}/bulk/remind`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ ids: Array.from(state.selectedIds) })
            });

            if (!response.ok) {
                throw new Error('Failed to send reminders');
            }

            showToast('Reminder sent to selected candidates.', 'success');
            state.selectedIds.clear();
            updateBulkActions();
            renderTable();
        } catch (error) {
            console.error('Error sending reminders:', error);
            showError('Failed to send reminders');
        }
    }

    /**
     * Handle bulk mark complete
     */
    async function handleBulkMarkComplete() {
        if (state.selectedIds.size === 0) return;
        // TODO: Implement bulk mark complete API call
        showToast('Bulk mark complete will be implemented', 'info');
    }

    /**
     * Handle bulk export
     */
    function handleBulkExport() {
        if (state.selectedIds.size === 0) return;
        const selected = state.filteredOnboardings.filter(item => state.selectedIds.has(item.id));
        exportToCSV(selected);
    }

    /**
     * Handle export CSV (all filtered results)
     */
    function handleExportCSV() {
        exportToCSV(state.filteredOnboardings);
    }

    /**
     * Export data to CSV
     */
    function exportToCSV(data) {
        const headers = ['candidate_name', 'email', 'role', 'department', 'manager', 'joining_date', 'progress_percent', 'status', 'last_updated'];
        const rows = data.map(item => [
            item.fullName,
            item.email,
            item.designation,
            item.department,
            item.manager,
            item.joiningDate,
            item.progressPercent,
            item.status,
            item.lastUpdated
        ]);

        const csv = [
            headers.join(','),
            ...rows.map(row => row.map(cell => `"${cell}"`).join(','))
        ].join('\n');

        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `onboardings-${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    /**
     * Render pagination
     */
    function renderPagination() {
        const container = document.getElementById('pagination-container');
        if (!container) return;

        const totalPages = Math.ceil(state.total / state.pageSize);
        if (totalPages <= 1) {
            container.innerHTML = '';
            return;
        }

        let html = '<div class="flex items-center justify-between"><div class="flex items-center space-x-2">';
        
        // Previous button
        html += `<button type="button" 
                         class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${state.currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}"
                         data-page="${state.currentPage - 1}"
                         ${state.currentPage === 1 ? 'disabled' : ''}>
                    Previous
                 </button>`;

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= state.currentPage - 1 && i <= state.currentPage + 1)) {
                html += `<button type="button" 
                                 class="pagination-btn px-3 py-2 text-sm font-medium ${i === state.currentPage ? 'bg-purple-600 text-white' : 'text-gray-700 bg-white border border-gray-300'} rounded-md hover:bg-gray-50"
                                 data-page="${i}">
                            ${i}
                         </button>`;
            } else if (i === state.currentPage - 2 || i === state.currentPage + 2) {
                html += '<span class="px-3 py-2 text-sm text-gray-700">...</span>';
            }
        }

        // Next button
        html += `<button type="button" 
                         class="pagination-btn px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 ${state.currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : ''}"
                         data-page="${state.currentPage + 1}"
                         ${state.currentPage === totalPages ? 'disabled' : ''}>
                    Next
                 </button>`;

        html += '</div>';

        // Page size selector
        html += `<div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-700">Show:</label>
                    <select id="page-size-select" class="px-2 py-1 border border-gray-300 rounded-md text-sm">
                        <option value="10" ${state.pageSize === 10 ? 'selected' : ''}>10</option>
                        <option value="25" ${state.pageSize === 25 ? 'selected' : ''}>25</option>
                        <option value="50" ${state.pageSize === 50 ? 'selected' : ''}>50</option>
                    </select>
                 </div></div>`;

        container.innerHTML = html;

        // Attach pagination event listeners
        document.querySelectorAll('.pagination-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const page = parseInt(this.dataset.page);
                if (page && page !== state.currentPage) {
                    state.currentPage = page;
                    loadOnboardings();
                }
            });
        });

        document.getElementById('page-size-select')?.addEventListener('change', function(e) {
            state.pageSize = parseInt(e.target.value);
            state.currentPage = 1;
            loadOnboardings();
        });
    }

    /**
     * Update select all checkbox
     */
    function updateSelectAllCheckbox() {
        const selectAll = document.getElementById('select-all-checkbox');
        if (selectAll) {
            const allSelected = state.filteredOnboardings.length > 0 && 
                               state.filteredOnboardings.every(item => state.selectedIds.has(item.id));
            selectAll.checked = allSelected;
            selectAll.indeterminate = !allSelected && state.selectedIds.size > 0;
        }
    }

    /**
     * Update bulk actions toolbar
     */
    function updateBulkActions() {
        const toolbar = document.getElementById('bulk-actions-toolbar');
        const count = document.getElementById('bulk-selection-count');
        
        if (state.selectedIds.size > 0) {
            toolbar?.classList.remove('hidden');
            if (count) {
                count.textContent = `${state.selectedIds.size} selected`;
            }
        } else {
            toolbar?.classList.add('hidden');
        }
    }

    /**
     * Show loading skeleton
     */
    function showLoading() {
        document.getElementById('loading-skeleton')?.classList.remove('hidden');
        document.getElementById('onboardings-tbody').innerHTML = '';
        document.getElementById('mobile-cards').innerHTML = '';
    }

    /**
     * Hide loading skeleton
     */
    function hideLoading() {
        document.getElementById('loading-skeleton')?.classList.add('hidden');
    }

    /**
     * Show toast notification
     */
    function showToast(message, type = 'info') {
        const container = document.getElementById('toast-container');
        if (!container) return;

        const bgColors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500'
        };

        const toast = document.createElement('div');
        toast.className = `${bgColors[type] || bgColors.info} text-white px-4 py-3 rounded-lg shadow-lg flex items-center justify-between min-w-[300px]`;
        toast.innerHTML = `
            <span>${escapeHtml(message)}</span>
            <button type="button" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

        container.appendChild(toast);

        toast.querySelector('button').addEventListener('click', () => {
            toast.remove();
        });

        setTimeout(() => {
            toast.remove();
        }, 5000);
    }

    /**
     * Show error toast
     */
    function showError(message) {
        showToast(message, 'error');
    }

    /**
     * Utility: Get initials from name
     */
    function getInitials(name) {
        return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2);
    }

    /**
     * Utility: Format date to MMM DD, YYYY
     */
    function formatDate(dateString) {
        const date = new Date(dateString);
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        return `${months[date.getMonth()]} ${String(date.getDate()).padStart(2, '0')}, ${date.getFullYear()}`;
    }

    /**
     * Utility: Escape HTML
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
})();

