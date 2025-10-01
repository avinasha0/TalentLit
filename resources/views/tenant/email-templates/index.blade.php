<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Email Templates', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Email Templates</h1>
                <p class="mt-1 text-sm text-white">
                    Manage your email communication templates for candidates.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tenant.email-templates.create', $tenant->slug) }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Template
                </a>
            </div>
        </div>

        <!-- Filters -->
        <x-card>
            <div class="flex items-center space-x-4">
                <div class="flex-1">
                    <label for="type-filter" class="block text-sm font-medium text-black mb-1">Filter by Type</label>
                    <select id="type-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Types</option>
                        @foreach($types as $key => $name)
                            <option value="{{ $key }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="status-filter" class="block text-sm font-medium text-black mb-1">Filter by Status</label>
                    <select id="status-filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </x-card>

        <!-- Templates List -->
        @if($templates->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($templates as $template)
                    <x-card class="hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-lg font-medium text-black truncate">{{ $template->name }}</h3>
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
                                <p class="mt-1 text-sm text-gray-600">{{ $template->type_name }}</p>
                                <p class="mt-2 text-sm text-gray-500 line-clamp-2">{{ $template->subject }}</p>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between">
                            <div class="flex space-x-2">
                                <a href="{{ route('tenant.email-templates.show', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                    View
                                </a>
                                <a href="{{ route('tenant.email-templates.edit', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                                   class="text-gray-600 hover:text-gray-700 text-sm font-medium">
                                    Edit
                                </a>
                                <button onclick="duplicateTemplate('{{ $template->id }}')" 
                                        class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    Duplicate
                                </button>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="previewTemplate('{{ $template->id }}')" 
                                        class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                    Preview
                                </button>
                                <form action="{{ route('tenant.email-templates.destroy', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </x-card>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $templates->links() }}
            </div>
        @else
            <x-empty 
                icon="mail"
                title="No email templates"
                description="Create your first email template to get started with automated communications."
                :action="route('tenant.email-templates.create', $tenant->slug)"
                actionText="Create Template"
            />
        @endif
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
        function previewTemplate(templateId) {
            fetch(`/{{ $tenant->slug }}/email-templates/${templateId}/preview`, {
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

        function duplicateTemplate(templateId) {
            if (confirm('Are you sure you want to duplicate this template?')) {
                window.location.href = `/{{ $tenant->slug }}/email-templates/${templateId}/duplicate`;
            }
        }

        // Filter functionality
        document.getElementById('type-filter').addEventListener('change', function() {
            const type = this.value;
            const status = document.getElementById('status-filter').value;
            filterTemplates(type, status);
        });

        document.getElementById('status-filter').addEventListener('change', function() {
            const status = this.value;
            const type = document.getElementById('type-filter').value;
            filterTemplates(type, status);
        });

        function filterTemplates(type, status) {
            const cards = document.querySelectorAll('[data-template-type]');
            cards.forEach(card => {
                const cardType = card.getAttribute('data-template-type');
                const cardStatus = card.getAttribute('data-template-status');
                
                const typeMatch = !type || cardType === type;
                const statusMatch = !status || 
                    (status === 'active' && cardStatus === 'true') ||
                    (status === 'inactive' && cardStatus === 'false');
                
                if (typeMatch && statusMatch) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
