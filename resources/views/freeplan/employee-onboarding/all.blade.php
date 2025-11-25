@php
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => '/'],
        ['label' => 'Employee Onboarding', 'url' => '/freeplan/employee-onboarding/all'],
        ['label' => 'All Onboardings', 'url' => null]
    ];
    // Get tenant from controller or user's first tenant for API routes
    $tenant = $tenantModel ?? (auth()->check() ? (auth()->user()->tenants->first() ?? null) : null);
    $tenantSlug = $tenant ? $tenant->slug : 'freeplan';
@endphp

<x-app-layout>
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Success Notification Toast -->
        <div id="success-notification" class="hidden fixed top-4 right-4 z-50 max-w-md w-full" style="transform: translateX(400px); opacity: 0; transition: all 0.3s ease-in-out;">
            <div class="bg-green-500 text-white rounded-lg shadow-xl p-4 flex items-center justify-between border-l-4 border-green-600">
                <div class="flex items-center flex-1">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="font-semibold text-base">Upload Successful!</p>
                        <p id="success-notification-message" class="text-sm text-green-100 mt-1"></p>
                    </div>
                </div>
                <button onclick="hideNotification()" class="ml-4 text-white hover:text-green-200 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

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

    <!-- Import Candidates Modal -->
    <div id="import-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-3 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Import Candidates for Onboarding</h3>
                    <button type="button" id="close-import-modal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="mt-4 space-y-6">
                    <!-- Success Message -->
                    <div id="import-success" class="hidden rounded-md bg-green-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800" id="import-success-message"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div id="import-error" class="hidden rounded-md bg-red-50 p-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800" id="import-error-message"></p>
                            </div>
                        </div>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label for="import-file" class="block text-sm font-medium text-gray-700 mb-2">
                            Select File
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex flex-col items-center text-sm text-gray-600">
                                    <input id="import-file" name="file" type="file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-gray-700">
                                    <p class="mt-2">or drag and drop</p>
                                    <p class="mt-2 text-xs text-gray-500">CSV, XLSX, XLS up to 10MB</p>
                                    <p id="import-file-selected" class="mt-2 text-xs text-gray-600">No file chosen</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="text-sm font-medium text-gray-900">Need a template?</p>
                            <p class="text-xs text-gray-500 mt-1">Download our CSV template to see the correct format</p>
                        </div>
                        <a href="{{ $tenant ? route('api.onboardings.import.template', $tenant->slug) : route('freeplan.onboardings.import.template') }}" 
                           id="download-template-btn"
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template
                        </a>
                    </div>

                    <!-- Format Requirements -->
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <h4 class="text-sm font-medium text-gray-900 mb-2">Format Requirements</h4>
                        <div class="text-xs text-gray-600 space-y-1">
                            <p><strong>Required:</strong> primary_email</p>
                            <p><strong>Optional:</strong> first_name, last_name, primary_phone, source, tags</p>
                            <p class="mt-2">• Use the first row for column headers</p>
                            <p>• Duplicate emails will update existing candidates</p>
                            <p>• Maximum file size: 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t">
                    <button type="button" id="cancel-import-btn" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Cancel
                    </button>
                    <button type="button" id="submit-import-btn" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Import Candidates
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to hide notification
        function hideNotification() {
            const notification = document.getElementById('success-notification');
            if (notification) {
                notification.style.transform = 'translateX(400px)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 300);
            }
        }

        (function() {
            const importBtn = document.getElementById('import-candidates-btn');
            const importModal = document.getElementById('import-modal');
            const closeModalBtn = document.getElementById('close-import-modal');
            const cancelBtn = document.getElementById('cancel-import-btn');
            const submitBtn = document.getElementById('submit-import-btn');
            const fileInput = document.getElementById('import-file');
            const fileSelected = document.getElementById('import-file-selected');
            const successDiv = document.getElementById('import-success');
            const errorDiv = document.getElementById('import-error');
            const successMessage = document.getElementById('import-success-message');
            const errorMessage = document.getElementById('import-error-message');

            const tenantSlug = @json($tenantSlug);
            const importUrl = tenantSlug && tenantSlug !== 'freeplan' 
                ? `/{{ $tenantSlug }}/api/onboardings/import/candidates`
                : '/freeplan/api/onboardings/import/candidates';

            // Open modal
            if (importBtn) {
                importBtn.addEventListener('click', function() {
                    if (!importUrl) {
                        alert('Please ensure you are logged in and have access to a tenant.');
                        return;
                    }
                    importModal.classList.remove('hidden');
                    // Reset form
                    fileInput.value = '';
                    fileSelected.textContent = 'No file chosen';
                    successDiv.classList.add('hidden');
                    errorDiv.classList.add('hidden');
                });
            }

            // Close modal
            function closeModal() {
                importModal.classList.add('hidden');
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeModal);
            }

            // File input change
            if (fileInput) {
                fileInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const fileName = file.name;
                        const fileSize = (file.size / 1024 / 1024).toFixed(2);
                        fileSelected.innerHTML = `<span class="text-green-700">${fileName}</span> <span class="text-gray-500">(${fileSize} MB)</span>`;
                    } else {
                        fileSelected.textContent = 'No file chosen';
                    }
                });
            }

            // Submit import
            if (submitBtn && importUrl) {
                submitBtn.addEventListener('click', function() {
                    if (!fileInput.files || fileInput.files.length === 0) {
                        errorDiv.classList.remove('hidden');
                        errorMessage.textContent = 'Please select a file to import.';
                        return;
                    }

                    const file = fileInput.files[0];
                    const maxSize = 10 * 1024 * 1024; // 10MB

                    if (file.size > maxSize) {
                        errorDiv.classList.remove('hidden');
                        errorMessage.textContent = 'File size exceeds 10MB limit.';
                        return;
                    }

                    // Hide previous messages
                    successDiv.classList.add('hidden');
                    errorDiv.classList.add('hidden');

                    // Disable submit button
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Importing...';

                    // Create FormData
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');

                    // Send request
                    fetch(importUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Only show success if data was actually saved to database
                        if (data.success && data.data_saved === true && data.verified === true) {
                            // Close modal first
                            closeModal();
                            
                            // Show success notification
                            const notification = document.getElementById('success-notification');
                            const notificationMessage = document.getElementById('success-notification-message');
                            
                            // Use the message from server which includes created/updated counts
                            notificationMessage.textContent = data.message || `Successfully imported ${data.count || 0} candidate${(data.count || 0) !== 1 ? 's' : ''} for onboarding.`;
                            notification.classList.remove('hidden');
                            
                            // Animate notification in
                            requestAnimationFrame(() => {
                                notification.style.transform = 'translateX(0)';
                                notification.style.opacity = '1';
                            });
                            
                            // Reload page after 3 seconds to show updated data
                            setTimeout(() => {
                                window.location.reload();
                            }, 3000);
                        } else {
                            errorDiv.classList.remove('hidden');
                            errorMessage.textContent = data.message || 'Import failed. Please try again.';
                            if (data.errors && Array.isArray(data.errors)) {
                                errorMessage.innerHTML = data.message + '<ul class="mt-2 list-disc list-inside text-xs">' + 
                                    data.errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                            }
                        }
                    })
                    .catch(error => {
                        errorDiv.classList.remove('hidden');
                        errorMessage.textContent = 'An error occurred during import: ' + (error.message || 'Unknown error');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Import Candidates';
                    });
                });
            }

            // Drag and drop
            const dropzone = fileInput?.closest('.border-dashed');
            if (dropzone && fileInput) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                    });
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => dropzone.classList.add('border-purple-500'));
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, () => dropzone.classList.remove('border-purple-500'));
                });

                dropzone.addEventListener('drop', (e) => {
                    const dt = e.dataTransfer;
                    if (dt && dt.files && dt.files.length > 0) {
                        fileInput.files = dt.files;
                        const changeEvent = new Event('change');
                        fileInput.dispatchEvent(changeEvent);
                    }
                });
            }
        })();
    </script>
</x-app-layout>
