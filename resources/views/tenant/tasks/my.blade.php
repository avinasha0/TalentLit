@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $isSubdomain = str_starts_with($currentRoute, 'subdomain.');
    $apiBasePath = $isSubdomain ? '/api/tasks' : "/{$tenantSlug}/api/tasks";
    $requisitionBasePath = $isSubdomain ? '/requisitions' : "/{$tenantSlug}/requisitions";
    $jobsBasePath = $isSubdomain ? '/jobs' : "/{$tenantSlug}/jobs";
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Tasks', 'url' => null],
        ['label' => 'My Tasks', 'url' => null]
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
                        <h1 class="text-2xl font-bold text-black">My Tasks</h1>
                        <p class="mt-1 text-sm text-black">Manage your assigned tasks and approvals</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <x-card>
            <div class="py-6">
                <form id="filterForm" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-black mb-1">Status</label>
                        <select name="status" 
                                id="status"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="">All Statuses</option>
                            <option value="Pending">Pending</option>
                            <option value="InProgress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="task_type" class="block text-sm font-medium text-black mb-1">Task Type</label>
                        <select name="task_type" 
                                id="task_type"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="">All Types</option>
                            <option value="Requisition Approval">Requisition Approval</option>
                            <option value="Requisition Edit Needed">Requisition Edit Needed</option>
                            <option value="Job Publish">Job Publish</option>
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-sm font-medium text-black mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               placeholder="Title, requisition ID, or job…"
                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                    </div>

                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-black mb-1">Sort By</label>
                        <select name="sort_by" 
                                id="sort_by"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="created_at">Created Date</option>
                            <option value="due_at">Due Date</option>
                        </select>
                    </div>
                </form>
            </div>
        </x-card>

        <!-- Tasks Table -->
        <x-card>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="tasksTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Task Title</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Related</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created By</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="tasksTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                <div class="flex justify-center items-center">
                                    <svg class="animate-spin h-5 w-5 text-gray-400 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Loading tasks...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="px-6 py-4 border-t border-gray-200"></div>
        </x-card>
    </div>

    <!-- Task Detail Modal -->
    <div id="taskModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-black" id="modalTitle">Task Details</h3>
                    <button onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="modalContent" class="space-y-4">
                    <!-- Content will be loaded dynamically -->
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button onclick="closeTaskModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Close
                    </button>
                    <button id="openApprovalBtn" onclick="openApproval()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 hidden">
                        Open
                    </button>
                    <button id="completeTaskBtn" onclick="completeTask()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 hidden">
                        Complete Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const tenantSlug = '{{ $tenantSlug }}';
        const apiBasePath = '{{ $apiBasePath }}';
        const requisitionBasePath = '{{ $requisitionBasePath }}';
        const jobsBasePath = '{{ $jobsBasePath }}';
        let currentPage = 1;
        let currentTaskId = null;

        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTasks();
            
            // Filter change handlers
            document.getElementById('status').addEventListener('change', loadTasks);
            document.getElementById('task_type').addEventListener('change', loadTasks);
            document.getElementById('search').addEventListener('input', debounce(loadTasks, 500));
            document.getElementById('sort_by').addEventListener('change', loadTasks);
            
            // Event delegation for task row clicks
            // Buttons use inline onclick handlers for reliability with dynamic content
            const tasksTable = document.getElementById('tasksTable');
            if (tasksTable) {
                tasksTable.addEventListener('click', function(e) {
                    // Only handle row clicks if not clicking on buttons, inputs, or links
                    if (!e.target.closest('button') && !e.target.closest('input[type="checkbox"]') && !e.target.closest('a')) {
                        const row = e.target.closest('.task-row');
                        if (row) {
                            const taskId = row.getAttribute('data-task-id');
                            if (taskId && taskId !== 'undefined' && taskId !== 'null' && !isNaN(parseInt(taskId, 10))) {
                                openTaskModal(taskId);
                            }
                        }
                    }
                });
            }
        });

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function loadTasks(page = 1) {
            currentPage = page;
            const status = document.getElementById('status').value;
            const taskType = document.getElementById('task_type').value;
            const search = document.getElementById('search').value;
            const sortBy = document.getElementById('sort_by').value || 'created_at';

            const params = new URLSearchParams({
                page: page,
                per_page: 20,
            });

            if (status) params.append('status', status);
            if (taskType) params.append('task_type', taskType);
            if (search) params.append('search', search);
            if (sortBy) params.append('sort_by', sortBy);
            params.append('sort_order', 'asc');

            fetch(`${apiBasePath}/my?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw new Error(err.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Tasks API response:', {
                    success: data.success,
                    dataCount: data.data ? data.data.length : 0,
                    sampleTask: data.data && data.data.length > 0 ? data.data[0] : null,
                    pagination: data.pagination
                });
                
                if (data.success) {
                    // Log each task's ID to verify they're present
                    if (data.data && data.data.length > 0) {
                        const taskIds = data.data.map(t => t.id).filter(id => id != null);
                        console.log('Task IDs received:', taskIds);
                        
                        if (taskIds.length !== data.data.length) {
                            console.warn('Some tasks are missing IDs:', {
                                total: data.data.length,
                                withIds: taskIds.length,
                                tasks: data.data
                            });
                        }
                    }
                    
                    renderTasks(data.data);
                    renderPagination(data.pagination);
                } else {
                    showError(data.message || 'Failed to load tasks');
                }
            })
            .catch(error => {
                console.error('Error loading tasks:', error);
                showError('Failed to load tasks: ' + error.message);
            });
        }

        function renderTasks(tasks) {
            const tbody = document.getElementById('tasksTableBody');
            
            if (tasks.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No tasks found.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = tasks.map((task, index) => {
                // Ensure task has an ID
                if (task.id === undefined || task.id === null) {
                    console.error('Task missing ID at index', index, ':', {
                        task: task,
                        keys: Object.keys(task),
                        id: task.id,
                        idType: typeof task.id
                    });
                    return '';
                }
                
                const taskId = parseInt(task.id, 10);
                if (isNaN(taskId) || taskId <= 0) {
                    console.error('Invalid task ID at index', index, ':', {
                        originalId: task.id,
                        idType: typeof task.id,
                        parsedId: taskId,
                        task: task
                    });
                    return '';
                }
                
                const dueDate = task.due_at ? new Date(task.due_at).toLocaleDateString() : 'N/A';
                const createdBy = task.creator ? task.creator.name : 'N/A';
                const statusBadge = getStatusBadge(task.status);
                const typeBadge = getTypeBadge(task.task_type);
                let relatedCell = 'N/A';
                if (task.requisition_id) {
                    relatedCell = `<a href="${requisitionBasePath}/${task.requisition_id}" class="text-blue-600 hover:underline" onclick="event.stopPropagation()">Req #${task.requisition_id}</a>`;
                } else if (task.job_opening_id && task.job_opening) {
                    relatedCell = `<a href="${jobsBasePath}/${task.job_opening_id}" class="text-blue-600 hover:underline" onclick="event.stopPropagation()">${escapeHtml(task.job_opening.title || 'Job')}</a>`;
                }

                return `
                    <tr class="hover:bg-gray-50 cursor-pointer task-row" data-task-id="${taskId}">
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            <input type="checkbox" class="task-checkbox rounded border-gray-300" value="${taskId}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(task.title || 'Untitled Task')}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                ${relatedCell}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${typeBadge}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${dueDate}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${escapeHtml(createdBy)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${statusBadge}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                            <button type="button" class="open-task-btn text-blue-600 hover:text-blue-900 mr-2" data-task-id="${taskId}" onclick="console.log('Open button clicked, taskId:', ${taskId}); event.stopPropagation(); if(typeof openTaskModal === 'function') { openTaskModal(${taskId}); } else { console.error('openTaskModal function not found'); alert('openTaskModal function not found'); } return false;">Open</button>
                            ${task.status !== 'Completed' ? `<button type="button" class="complete-task-btn text-green-600 hover:text-green-900" data-task-id="${taskId}" onclick="console.log('Complete button clicked, taskId:', ${taskId}); event.stopPropagation(); if(typeof completeTask === 'function') { completeTask(${taskId}); } else { console.error('completeTask function not found'); alert('completeTask function not found'); } return false;">Complete</button>` : ''}
                        </td>
                    </tr>
                `;
            }).filter(row => row !== '').join('');
        }

        function getStatusBadge(status) {
            const badges = {
                'Pending': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                'InProgress': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">In Progress</span>',
                'Completed': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>',
                'Cancelled': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>'
            };
            return badges[status] || status;
        }

        function getTypeBadge(type) {
            const badges = {
                'Requisition Approval': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Approval</span>',
                'Requisition Edit Needed': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Edit Needed</span>',
                'Job Publish': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-teal-100 text-teal-800">Job</span>'
            };
            return badges[type] || type;
        }

        function renderPagination(pagination) {
            const paginationDiv = document.getElementById('pagination');
            if (!pagination || pagination.last_page <= 1) {
                paginationDiv.innerHTML = '';
                return;
            }

            let html = '<div class="flex items-center justify-between">';
            html += `<div class="text-sm text-gray-700">Showing ${((pagination.current_page - 1) * pagination.per_page) + 1} to ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} of ${pagination.total} tasks</div>`;
            html += '<div class="flex space-x-2">';

            if (pagination.current_page > 1) {
                html += `<button onclick="loadTasks(${pagination.current_page - 1})" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Previous</button>`;
            }

            for (let i = 1; i <= pagination.last_page; i++) {
                if (i === 1 || i === pagination.last_page || (i >= pagination.current_page - 2 && i <= pagination.current_page + 2)) {
                    html += `<button onclick="loadTasks(${i})" class="px-3 py-1 border border-gray-300 rounded-md text-sm ${i === pagination.current_page ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-gray-50'}">${i}</button>`;
                } else if (i === pagination.current_page - 3 || i === pagination.current_page + 3) {
                    html += '<span class="px-3 py-1 text-gray-700">...</span>';
                }
            }

            if (pagination.current_page < pagination.last_page) {
                html += `<button onclick="loadTasks(${pagination.current_page + 1})" class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-700 hover:bg-gray-50">Next</button>`;
            }

            html += '</div></div>';
            paginationDiv.innerHTML = html;
        }

        function openTaskModal(taskId) {
            console.log('openTaskModal called with:', taskId, typeof taskId);
            
            // Validate task ID
            const id = parseInt(taskId, 10);
            if (isNaN(id) || id <= 0) {
                console.error('Invalid task ID provided:', taskId, 'parsed as:', id);
                showError('Invalid task ID. Please refresh the page and try again.');
                return;
            }
            
            currentTaskId = id;
            const url = `${apiBasePath}/${id}`;
            
            console.log('Fetching task details:', {
                taskId: id,
                originalTaskId: taskId,
                apiBasePath: apiBasePath,
                url: url
            });
            
            fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                console.log('Task fetch response:', {
                    status: response.status,
                    statusText: response.statusText,
                    ok: response.ok
                });
                
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Task fetch error response:', err);
                        throw new Error(err.message || `HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Task fetch data:', data);
                
                if (data.success) {
                    renderTaskModal(data.data);
                    document.getElementById('taskModal').classList.remove('hidden');
                } else {
                    showError(data.message || 'Failed to load task details');
                }
            })
            .catch(error => {
                console.error('Error loading task details:', {
                    error: error,
                    message: error.message,
                    taskId: taskId,
                    url: url
                });
                showError('Failed to load task details: ' + error.message);
            });
        }

        function renderTaskModal(task) {
            console.log('Rendering task modal with data:', task);
            
            // Set modal title with fallback
            const taskTitle = task.title || task.task_type || 'Untitled Task';
            document.getElementById('modalTitle').textContent = taskTitle;
            
            const modalContent = document.getElementById('modalContent');
            const dueDate = task.due_at ? new Date(task.due_at).toLocaleString() : 'N/A';
            const createdDate = task.created_at ? new Date(task.created_at).toLocaleString() : 'N/A';
            
            // Get creator name with multiple fallbacks
            let createdBy = 'N/A';
            if (task.creator) {
                createdBy = task.creator.name || task.creator.email || 'Unknown User';
            } else if (task.created_by) {
                createdBy = `User ID: ${task.created_by}`;
            }
            
            // Get assignee name with multiple fallbacks
            let assignee = 'N/A';
            if (task.assignee) {
                assignee = task.assignee.name || task.assignee.email || 'Unknown User';
            } else if (task.user_id) {
                assignee = `User ID: ${task.user_id}`;
            }

            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-black mb-2">Task Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Title:</span> ${escapeHtml(taskTitle)}</div>
                            <div><span class="font-medium">Type:</span> ${escapeHtml(task.task_type || 'N/A')}</div>
                            <div><span class="font-medium">Status:</span> ${getStatusBadge(task.status)}</div>
                            <div><span class="font-medium">Due Date:</span> ${dueDate}</div>
                            <div><span class="font-medium">Assigned To:</span> ${escapeHtml(assignee)}</div>
                            <div><span class="font-medium">Created By:</span> ${escapeHtml(createdBy)}</div>
                            <div><span class="font-medium">Created At:</span> ${createdDate}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-black mb-2">Related</h4>
                        ${task.requisition ? `
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Job Title:</span> ${escapeHtml(task.requisition.job_title)}</div>
                                <div><span class="font-medium">Department:</span> ${escapeHtml(task.requisition.department || 'N/A')}</div>
                                <div><span class="font-medium">Status:</span> ${escapeHtml(task.requisition.status || 'N/A')}</div>
                                <a href="${requisitionBasePath}/${task.requisition_id}" class="text-blue-600 hover:underline">View Requisition →</a>
                            </div>
                        ` : task.job_opening ? `
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Job:</span> ${escapeHtml(task.job_opening.title || 'Job opening')}</div>
                                <a href="${jobsBasePath}/${task.job_opening_id}" class="text-blue-600 hover:underline">Open job →</a>
                            </div>
                        ` : '<p class="text-sm text-gray-500">No related record</p>'}
                    </div>
                </div>
            `;

            // Show/hide action buttons
            const openApprovalBtn = document.getElementById('openApprovalBtn');
            const completeTaskBtn = document.getElementById('completeTaskBtn');

            if (task.link && task.status !== 'Completed') {
                openApprovalBtn.classList.remove('hidden');
                openApprovalBtn.textContent = task.job_opening_id ? 'Open job' : 'Open approval';
                openApprovalBtn.onclick = () => window.location.href = task.link;
            } else {
                openApprovalBtn.classList.add('hidden');
            }

            if (task.status !== 'Completed') {
                completeTaskBtn.classList.remove('hidden');
            } else {
                completeTaskBtn.classList.add('hidden');
            }
        }

        function closeTaskModal() {
            document.getElementById('taskModal').classList.add('hidden');
            currentTaskId = null;
        }

        function openApproval() {
            if (currentTaskId) {
                fetch(`${apiBasePath}/${currentTaskId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data.link) {
                        window.location.href = data.data.link;
                    }
                });
            }
        }

        function completeTask(taskId = null) {
            const id = taskId || currentTaskId;
            if (!id) return;

            if (!confirm('Are you sure you want to mark this task as completed?')) {
                return;
            }

            fetch(`${apiBasePath}/${id}/complete`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('Task completed successfully');
                    closeTaskModal();
                    loadTasks(currentPage);
                } else {
                    showError(data.message || 'Failed to complete task');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to complete task');
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showSuccess(message) {
            // You can implement a toast notification here
            alert(message);
        }

        function showError(message) {
            // You can implement a toast notification here
            alert('Error: ' + message);
        }
    </script>
    @endpush
</x-app-layout>

