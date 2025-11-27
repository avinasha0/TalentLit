@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
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
                        </select>
                    </div>

                    <div>
                        <label for="search" class="block text-sm font-medium text-black mb-1">Search</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               placeholder="Title or requisition ID..."
                               class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                    </div>

                    <div>
                        <label for="sort_by" class="block text-sm font-medium text-black mb-1">Sort By</label>
                        <select name="sort_by" 
                                id="sort_by"
                                class="w-full px-3 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-black">
                            <option value="due_at">Due Date</option>
                            <option value="created_at">Created Date</option>
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
                        Open Approval
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
            const sortBy = document.getElementById('sort_by').value;

            const params = new URLSearchParams({
                page: page,
                per_page: 20,
            });

            if (status) params.append('status', status);
            if (taskType) params.append('task_type', taskType);
            if (search) params.append('search', search);
            if (sortBy) params.append('sort_by', sortBy);
            params.append('sort_order', 'asc');

            fetch(`/${tenantSlug}/api/tasks/my?${params.toString()}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTasks(data.data);
                    renderPagination(data.pagination);
                } else {
                    showError('Failed to load tasks');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to load tasks');
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

            tbody.innerHTML = tasks.map(task => {
                const dueDate = task.due_at ? new Date(task.due_at).toLocaleDateString() : 'N/A';
                const createdBy = task.creator ? task.creator.name : 'N/A';
                const reqId = task.requisition_id || 'N/A';
                const statusBadge = getStatusBadge(task.status);
                const typeBadge = getTypeBadge(task.task_type);

                return `
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="openTaskModal(${task.id})">
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            <input type="checkbox" class="task-checkbox rounded border-gray-300" value="${task.id}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${escapeHtml(task.title)}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-500">
                                ${task.requisition_id ? `<a href="/${tenantSlug}/requisitions/${task.requisition_id}" class="text-blue-600 hover:underline" onclick="event.stopPropagation()">Req #${task.requisition_id}</a>` : 'N/A'}
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
                            <button onclick="openTaskModal(${task.id})" class="text-blue-600 hover:text-blue-900 mr-2">Open</button>
                            ${task.status !== 'Completed' ? `<button onclick="completeTask(${task.id})" class="text-green-600 hover:text-green-900">Complete</button>` : ''}
                        </td>
                    </tr>
                `;
            }).join('');
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
                'Requisition Edit Needed': '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">Edit Needed</span>'
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
            currentTaskId = taskId;
            fetch(`/${tenantSlug}/api/tasks/${taskId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderTaskModal(data.data);
                    document.getElementById('taskModal').classList.remove('hidden');
                } else {
                    showError('Failed to load task details');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Failed to load task details');
            });
        }

        function renderTaskModal(task) {
            document.getElementById('modalTitle').textContent = task.title;
            
            const modalContent = document.getElementById('modalContent');
            const dueDate = task.due_at ? new Date(task.due_at).toLocaleString() : 'N/A';
            const createdDate = task.created_at ? new Date(task.created_at).toLocaleString() : 'N/A';
            const createdBy = task.creator ? task.creator.name : 'N/A';
            const assignee = task.assignee ? task.assignee.name : 'N/A';

            modalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="font-semibold text-black mb-2">Task Information</h4>
                        <div class="space-y-2 text-sm">
                            <div><span class="font-medium">Type:</span> ${escapeHtml(task.task_type)}</div>
                            <div><span class="font-medium">Status:</span> ${getStatusBadge(task.status)}</div>
                            <div><span class="font-medium">Due Date:</span> ${dueDate}</div>
                            <div><span class="font-medium">Assigned To:</span> ${escapeHtml(assignee)}</div>
                            <div><span class="font-medium">Created By:</span> ${escapeHtml(createdBy)}</div>
                            <div><span class="font-medium">Created At:</span> ${createdDate}</div>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-semibold text-black mb-2">Related Requisition</h4>
                        ${task.requisition ? `
                            <div class="space-y-2 text-sm">
                                <div><span class="font-medium">Job Title:</span> ${escapeHtml(task.requisition.job_title)}</div>
                                <div><span class="font-medium">Department:</span> ${escapeHtml(task.requisition.department || 'N/A')}</div>
                                <div><span class="font-medium">Status:</span> ${escapeHtml(task.requisition.status || 'N/A')}</div>
                                <a href="/${tenantSlug}/requisitions/${task.requisition_id}" class="text-blue-600 hover:underline">View Requisition →</a>
                            </div>
                        ` : '<p class="text-sm text-gray-500">No related requisition</p>'}
                    </div>
                </div>
            `;

            // Show/hide action buttons
            const openApprovalBtn = document.getElementById('openApprovalBtn');
            const completeTaskBtn = document.getElementById('completeTaskBtn');

            if (task.link && task.status !== 'Completed') {
                openApprovalBtn.classList.remove('hidden');
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
                fetch(`/${tenantSlug}/api/tasks/${currentTaskId}`, {
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

            fetch(`/${tenantSlug}/api/tasks/${id}/complete`, {
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

