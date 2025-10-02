<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Email Templates', 'url' => route('tenant.email-templates.index', $tenant->slug)],
                ['label' => 'Create', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Create Email Template</h1>
            <p class="mt-1 text-sm text-white">
                Design a new email template for candidate communications.
            </p>
        </div>

        <form action="{{ route('tenant.email-templates.store', $tenant->slug) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-black">Basic Information</h3>
                        </x-slot>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-black">Template Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-black">Template Type</label>
                                <select name="type" 
                                        id="type" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                        onchange="updateVariables()"
                                        required>
                                    @foreach($types as $key => $name)
                                        <option value="{{ $key }}" {{ old('type', $selectedType) == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       {{ old('is_active', true) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-black">
                                    Active (can be used for sending emails)
                                </label>
                            </div>
                        </div>
                    </x-card>

                    <!-- Premade Templates -->
                    @if(!empty($premadeTemplates))
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-black">Choose a Premade Template</h3>
                            <p class="text-sm text-gray-600 mt-1">Select a template to get started quickly, or create your own from scratch.</p>
                        </x-slot>

                        <div class="space-y-3">
                            @foreach($premadeTemplates as $index => $template)
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer" 
                                     onclick="loadPremadeTemplate({{ $index }})">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $template['name'] }}</h4>
                                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($template['subject'], 60) }}</p>
                                            <p class="text-xs text-gray-500 mt-2">{{ Str::limit(strip_tags($template['body']), 100) }}</p>
                                        </div>
                                        <button type="button" 
                                                class="ml-4 px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors">
                                            Use Template
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                    @endif

                    <!-- Email Content -->
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-black">Email Content</h3>
                        </x-slot>

                        <div class="space-y-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-black">Subject Line</label>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       value="{{ old('subject') }}"
                                       placeholder="e.g., Thank you for your application - {!! '{{' !!} job_title {!! '}}' !!}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="body" class="block text-sm font-medium text-black">Email Body</label>
                                <textarea name="body" 
                                          id="body" 
                                          rows="15"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Write your email content here. Use variables like {!! '{{' !!} candidate_name {!! '}}' !!} to personalize the message."
                                          required>{{ old('body') }}</textarea>
                                @error('body')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Available Variables -->
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-black">Available Variables</h3>
                        </x-slot>

                        <div id="variables-list" class="space-y-2">
                            @foreach($variables as $key => $description)
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <code class="text-sm font-mono text-blue-600">@{{ $key }}</code>
                                        <p class="text-xs text-gray-600">{{ $description }}</p>
                                    </div>
                                    <button type="button" 
                                            onclick="insertVariable('@{{ $key }}')"
                                            class="text-blue-600 hover:text-blue-700 text-sm">
                                        Insert
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </x-card>

                    <!-- Preview -->
                    <x-card>
                        <x-slot name="header">
                            <h3 class="text-lg font-medium text-black">Preview</h3>
                        </x-slot>

                        <div class="space-y-4">
                            <button type="button" 
                                    onclick="previewTemplate()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview Template
                            </button>

                            <div id="preview-content" class="hidden">
                                <div class="border rounded p-4 bg-gray-50">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Subject:</h4>
                                    <p id="preview-subject" class="text-sm text-gray-700 mb-4"></p>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Body:</h4>
                                    <div id="preview-body" class="text-sm text-gray-700 whitespace-pre-wrap"></div>
                                </div>
                            </div>
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('tenant.email-templates.index', $tenant->slug) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Template
                </button>
            </div>
        </form>
    </div>

    <script>
        const variables = @json($variables);

        function updateVariables() {
            const type = document.getElementById('type').value;
            
            // Reload the page with the new type to get updated variables and premade templates
            const url = new URL(window.location);
            url.searchParams.set('type', type);
            window.location.href = url.toString();
        }

        function updateVariablesList(vars) {
            const container = document.getElementById('variables-list');
            container.innerHTML = '';
            
            Object.entries(vars).forEach(([key, description]) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                div.innerHTML = `
                    <div>
                        <code class="text-sm font-mono text-blue-600">{{` + key + `}}</code>
                        <p class="text-xs text-gray-600">${description}</p>
                    </div>
                    <button type="button" 
                            onclick="insertVariable('{{` + key + `}}')"
                            class="text-blue-600 hover:text-blue-700 text-sm">
                        Insert
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function insertVariable(variable) {
            const bodyTextarea = document.getElementById('body');
            const cursorPos = bodyTextarea.selectionStart;
            const textBefore = bodyTextarea.value.substring(0, cursorPos);
            const textAfter = bodyTextarea.value.substring(bodyTextarea.selectionEnd);
            
            bodyTextarea.value = textBefore + variable + textAfter;
            bodyTextarea.focus();
            bodyTextarea.setSelectionRange(cursorPos + variable.length, cursorPos + variable.length);
        }

        function previewTemplate() {
            try {
                const subject = document.getElementById('subject').value || '';
                const body = document.getElementById('body').value || '';
                
                // Sample data for preview
                const sampleData = {
                    'candidate_name': 'John Doe',
                    'candidate_email': 'john.doe@example.com',
                    'job_title': 'Senior Developer',
                    'company_name': '{{ $tenant->name ?? "Your Company" }}',
                    'application_date': '{{ now()->format("M j, Y") }}',
                    'stage_name': 'Interview',
                    'previous_stage': 'Screen',
                    'message': 'Thank you for your interest in this position.',
                    'interview_date': '{{ now()->addDays(7)->format("M j, Y") }}',
                    'interview_time': '2:00 PM',
                    'interview_location': 'Office or Video Call',
                    'interviewer_name': 'Jane Smith',
                    'interview_notes': 'Please bring your portfolio and be ready to discuss your experience.',
                    'cancellation_reason': 'Scheduling conflict',
                };

                let previewSubject = subject;
                let previewBody = body;

                // Replace variables using simple string replacement
                Object.entries(sampleData).forEach(([key, value]) => {
                    const variablePattern = '{{' + key + '}}';
                    previewSubject = previewSubject.replaceAll(variablePattern, value);
                    previewBody = previewBody.replaceAll(variablePattern, value);
                });

                // Update preview content
                const previewSubjectEl = document.getElementById('preview-subject');
                const previewBodyEl = document.getElementById('preview-body');
                const previewContentEl = document.getElementById('preview-content');
                
                if (previewSubjectEl) {
                    previewSubjectEl.textContent = previewSubject || '(No subject entered)';
                }
                
                if (previewBodyEl) {
                    previewBodyEl.textContent = previewBody || '(No body content entered)';
                }
                
                if (previewContentEl) {
                    previewContentEl.classList.remove('hidden');
                }
                
                console.log('Preview updated:', { previewSubject, previewBody });
            } catch (error) {
                console.error('Preview error:', error);
                alert('Error generating preview: ' + error.message);
            }
        }

        // Update variables when type changes
        document.getElementById('type').addEventListener('change', function() {
            // In a real implementation, you'd fetch variables for the selected type
            // For now, we'll keep the current variables
        });

        // Load premade template
        function loadPremadeTemplate(templateIndex) {
            const type = document.getElementById('type').value;
            
            fetch('{{ route("tenant.email-templates.load-premade", $tenant->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    template_index: templateIndex
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error loading template: ' + data.error);
                    return;
                }
                
                // Populate the form with the premade template
                document.getElementById('name').value = data.name;
                document.getElementById('subject').value = data.subject;
                document.getElementById('body').value = data.body;
                
                // Scroll to the email content section
                document.getElementById('body').scrollIntoView({ behavior: 'smooth' });
                
                // Show success message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'Template loaded successfully!';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.remove();
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading template. Please try again.');
            });
        }

        // Debug: Test if preview button exists and is clickable
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking preview button...');
            const previewBtn = document.querySelector('button[onclick="previewTemplate()"]');
            if (previewBtn) {
                console.log('Preview button found:', previewBtn);
                previewBtn.addEventListener('click', function(e) {
                    console.log('Preview button clicked!');
                });
            } else {
                console.log('Preview button not found!');
            }
        });
    </script>
</x-app-layout>
