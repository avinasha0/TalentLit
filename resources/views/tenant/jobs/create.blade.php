@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
        ['label' => 'Create Job', 'url' => null]
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
                <h1 class="text-2xl font-bold text-black">Create New Job</h1>
                <p class="mt-1 text-sm text-black">Fill in the details to create a new job opening</p>
            </div>
        </div>

        <!-- Form -->
        <x-card>
            <form method="POST" action="{{ route('tenant.jobs.store', $tenant->slug) }}" class="space-y-6">
                @csrf

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-black mb-1">Job Title *</label>
                    <input type="text"
                           name="title"
                           id="title"
                           value="{{ old('title') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., Senior Software Engineer"
                           required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug -->
                <div>
                    <label for="slug" class="block text-sm font-medium text-black mb-1">URL Slug</label>
                    <input type="text"
                           name="slug"
                           id="slug"
                           value="{{ old('slug') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="e.g., senior-software-engineer">
                    <p class="mt-1 text-xs text-gray-600">Leave empty to auto-generate from title</p>
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-black mb-1">Department *</label>
                    <select name="department_id"
                            id="department_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="mt-2">
                        <a href="{{ route('tenant.departments.create', $tenant->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">+ Add Department</a>
                    </div>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Location -->
                <div>
                    <label for="location_id" class="block text-sm font-medium text-black mb-1">Location *</label>
                    <select name="location_id"
                            id="location_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="">Select Location</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                {{ $location->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-600">Choose from your organization locations</p>
                    <div class="mt-2">
                        <a href="{{ route('tenant.locations.create', $tenant->slug) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">+ Add Location</a>
                    </div>
                    @error('location_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>



                <!-- Employment Type and Openings Count -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employment_type" class="block text-sm font-medium text-black mb-1">Employment Type *</label>
                        <select name="employment_type"
                                id="employment_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="">Select Type</option>
                            @foreach($employmentTypes as $type)
                                <option value="{{ $type }}" {{ old('employment_type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('employment_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="openings_count" class="block text-sm font-medium text-black mb-1">Number of Openings *</label>
                        <input type="number"
                               name="openings_count"
                               id="openings_count"
                               value="{{ old('openings_count', 1) }}"
                               min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               required>
                        @error('openings_count')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-black mb-1">Status *</label>
                    <select name="status"
                            id="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="description" class="block text-sm font-medium text-black">Job Description *</label>
                        <button type="button" 
                                id="ai-generate-btn"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg hover:from-purple-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-all duration-200 shadow-sm hover:shadow-md">
                            <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Write With AI
                        </button>
                    </div>
                    
                    <!-- AI Generation Status -->
                    <div id="ai-status" class="hidden mb-2">
                        <div class="flex items-center text-sm text-blue-600">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            AI is generating your job description...
                        </div>
                    </div>
                    
                    <textarea name="description"
                              id="description"
                              rows="10"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Describe the role, responsibilities, requirements, and benefits... Or click 'Write With AI' to generate automatically."
                              required>{{ old('description') }}</textarea>
                    
                    <!-- Word count indicator -->
                    <div class="mt-1 flex justify-between items-center text-xs text-gray-500">
                        <span id="word-count">0 words</span>
                        <span id="ai-indicator" class="hidden text-purple-600 font-medium">✨ AI Generated</span>
                    </div>
                    
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('tenant.jobs.index', $tenant->slug) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Create Job
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aiGenerateBtn = document.getElementById('ai-generate-btn');
            const descriptionTextarea = document.getElementById('description');
            const aiStatus = document.getElementById('ai-status');
            const wordCount = document.getElementById('word-count');
            const aiIndicator = document.getElementById('ai-indicator');
            const titleInput = document.getElementById('title');

            // Word count functionality
            function updateWordCount() {
                const text = descriptionTextarea.value;
                const words = text.trim().split(/\s+/).filter(word => word.length > 0);
                wordCount.textContent = `${words.length} words`;
                
                // Show minimum word warning if less than 150 words
                if (words.length > 0 && words.length < 150) {
                    wordCount.className = 'text-orange-600 font-medium';
                } else if (words.length >= 150) {
                    wordCount.className = 'text-green-600 font-medium';
                } else {
                    wordCount.className = 'text-gray-500';
                }
            }

            // Update word count on input
            descriptionTextarea.addEventListener('input', updateWordCount);
            updateWordCount(); // Initial count

            // AI Generation functionality
            aiGenerateBtn.addEventListener('click', async function() {
                const jobTitle = titleInput.value.trim();
                
                if (!jobTitle) {
                    alert('Please enter a job title first to generate a description.');
                    titleInput.focus();
                    return;
                }

                // Show loading state
                aiStatus.classList.remove('hidden');
                aiGenerateBtn.disabled = true;
                aiGenerateBtn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-1.5 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Generating...
                `;

                try {
                    const response = await fetch('{{ route("tenant.jobs.ai-generate-description", $tenant->slug) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            job_title: jobTitle
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        // Set the generated description
                        descriptionTextarea.value = data.description;
                        updateWordCount();
                        
                        // Show AI indicator
                        aiIndicator.classList.remove('hidden');
                        
                        // Focus on the textarea so user can edit
                        descriptionTextarea.focus();
                        
                        // Show success message
                        showNotification('Job description generated successfully! You can edit it as needed.', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to generate description');
                    }
                } catch (error) {
                    console.error('AI Generation Error:', error);
                    showNotification('Failed to generate job description. Please try again or write manually.', 'error');
                } finally {
                    // Reset button state
                    aiStatus.classList.add('hidden');
                    aiGenerateBtn.disabled = false;
                    aiGenerateBtn.innerHTML = `
                        <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Write With AI
                    `;
                }
            });

            // Hide AI indicator when user starts editing
            descriptionTextarea.addEventListener('input', function() {
                if (aiIndicator.classList.contains('hidden') === false) {
                    // Check if user is actually editing (not just AI setting the value)
                    setTimeout(() => {
                        if (descriptionTextarea.value.length > 0) {
                            aiIndicator.classList.add('hidden');
                        }
                    }, 100);
                }
            });

            // Notification system
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
                
                const colors = {
                    success: 'bg-green-500 text-white',
                    error: 'bg-red-500 text-white',
                    info: 'bg-blue-500 text-white'
                };
                
                notification.className += ` ${colors[type]}`;
                notification.innerHTML = `
                    <div class="flex items-center">
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => notification.remove(), 300);
                }, 5000);
            }
        });
    </script>
</x-app-layout>
