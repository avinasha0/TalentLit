<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Email Templates', 'url' => route('tenant.email-templates.index', $tenant->slug)],
                ['label' => $template->name, 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $template->name }}</h1>
                <p class="mt-1 text-sm text-white">
                    {{ $template->type_name }} template
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tenant.email-templates.edit', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <button onclick="previewTemplate()" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Preview
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Template Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status and Type -->
                <x-card>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div>
                                <h3 class="text-lg font-medium text-black">Status</h3>
                                @if($template->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Inactive
                                    </span>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-black">Type</h3>
                                <p class="text-sm text-gray-600">{{ $template->type_name }}</p>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Created {{ $template->created_at->diffForHumans() }}
                        </div>
                    </div>
                </x-card>

                <!-- Email Content -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Email Content</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-black">Subject Line</h4>
                            <div class="mt-1 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-900">{{ $template->subject }}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-black">Email Body</h4>
                            <div class="mt-1 p-4 bg-gray-50 rounded-md">
                                <div class="text-sm text-gray-900 whitespace-pre-wrap">{{ $template->body }}</div>
                            </div>
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

                    <div class="space-y-2">
                        @foreach($variables as $key => $description)
                            <div class="p-2 bg-gray-50 rounded">
                                <code class="text-sm font-mono text-blue-600">{{ $key }}</code>
                                <p class="text-xs text-gray-600">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Actions -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Actions</h3>
                    </x-slot>

                    <div class="space-y-3">
                        <a href="{{ route('tenant.email-templates.edit', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Template
                        </a>

                        <button onclick="duplicateTemplate()" 
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            Duplicate Template
                        </button>

                        <form action="{{ route('tenant.email-templates.destroy', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this template?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Template
                            </button>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div id="preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-black">Template Preview</h3>
                    <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="preview-content" class="space-y-4">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewTemplate() {
            fetch(`/{{ $tenant->slug }}/email-templates/{{ $template->id }}/preview`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('preview-content').innerHTML = `
                    <div class="border-b pb-4">
                        <h4 class="text-sm font-medium text-gray-900">Subject:</h4>
                        <p class="mt-1 text-sm text-gray-700">${data.subject}</p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Body:</h4>
                        <div class="mt-1 text-sm text-gray-700 whitespace-pre-wrap border rounded p-4 bg-gray-50">${data.body}</div>
                    </div>
                `;
                document.getElementById('preview-modal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading preview');
            });
        }

        function closePreview() {
            document.getElementById('preview-modal').classList.add('hidden');
        }

        function duplicateTemplate() {
            if (confirm('Are you sure you want to duplicate this template?')) {
                window.location.href = `/{{ $tenant->slug }}/email-templates/{{ $template->id }}/duplicate`;
            }
        }
    </script>
</x-app-layout>
