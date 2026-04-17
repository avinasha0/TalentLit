<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Onboarding Email Templates', 'url' => route('tenant.onboarding-email-templates.index', $tenant->slug)],
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
                    View onboarding email template details and preview.
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('tenant.onboarding-email-templates.edit', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Template Details -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Template Details</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Template Key</label>
                            <p class="mt-1 text-sm text-gray-900 font-mono">{{ $template->template_key }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Template Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $template->name }}</p>
                        </div>

                        @if($template->purpose)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purpose</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $template->purpose }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scope</label>
                            <p class="mt-1">
                                @if($template->isGlobal())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Global Template
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Tenant-specific Template
                                    </span>
                                    @if($template->tenant)
                                        <span class="ml-2 text-sm text-gray-600">({{ $template->tenant->name }})</span>
                                    @endif
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Modified</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $template->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                    </div>
                </x-card>

                <!-- Email Preview -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Email Preview</h3>
                        <p class="text-xs text-gray-600 mt-1">Subject and body preview (tokens not replaced)</p>
                    </x-slot>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <div class="p-3 bg-gray-50 rounded-md border border-gray-200">
                                <p class="text-sm text-gray-900">{{ $template->subject ?: '(No subject)' }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Body</label>
                            <div class="p-4 bg-gray-50 rounded-md border border-gray-200">
                                @if($template->body)
                                    <div class="text-sm text-gray-900 whitespace-pre-wrap font-mono">{{ $template->body }}</div>
                                @else
                                    <p class="text-sm text-gray-500 italic">(No body content)</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Available Tokens -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Available Tokens</h3>
                        <p class="text-xs text-gray-600 mt-1">Tokens that can be used in templates</p>
                    </x-slot>

                    <div class="space-y-2">
                        @foreach($availableTokens as $key => $description)
                            <div class="p-2 bg-gray-50 rounded">
                                <code class="text-sm font-mono text-blue-600">{{ '{{' . $key . '}}' }}</code>
                                <p class="text-xs text-gray-600 mt-1">{{ $description }}</p>
                            </div>
                        @endforeach
                    </div>
                </x-card>

                <!-- Actions -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Actions</h3>
                    </x-slot>

                    <div class="space-y-2">
                        <a href="{{ route('tenant.onboarding-email-templates.edit', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Edit Template
                        </a>
                        <form action="{{ route('tenant.onboarding-email-templates.destroy', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this template? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                                Delete Template
                            </button>
                        </form>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</x-app-layout>

