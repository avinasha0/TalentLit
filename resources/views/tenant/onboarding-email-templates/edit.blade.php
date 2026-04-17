<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Onboarding Email Templates', 'url' => route('tenant.onboarding-email-templates.index', $tenant->slug)],
                ['label' => $template->name, 'url' => route('tenant.onboarding-email-templates.show', ['tenant' => $tenant->slug, 'template' => $template->id])],
                ['label' => 'Edit', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Edit Onboarding Email Template</h1>
            <p class="mt-1 text-sm text-white">
                Update the email template for employee onboarding communications.
            </p>
        </div>

        @if(session('warning'))
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md">
                {{ session('warning') }}
            </div>
        @endif

        <form action="{{ route('tenant.onboarding-email-templates.update', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

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
                                <label for="template_key" class="block text-sm font-medium text-black">Template Key <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="template_key" 
                                       id="template_key" 
                                       value="{{ old('template_key', $template->template_key) }}"
                                       pattern="[a-z0-9._-]+"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                <p class="mt-1 text-xs text-gray-500">Unique identifier (lowercase letters, numbers, dots, underscores, hyphens only)</p>
                                @error('template_key')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-black">Template Name <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="{{ old('name', $template->name) }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="purpose" class="block text-sm font-medium text-black">Purpose</label>
                                <textarea name="purpose" 
                                          id="purpose" 
                                          rows="2"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">{{ old('purpose', $template->purpose) }}</textarea>
                                @error('purpose')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-gray-50 p-3 rounded-md">
                                <p class="text-sm font-medium text-gray-700">Template Scope</p>
                                <p class="text-xs text-gray-600 mt-1">
                                    @if($template->isGlobal())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Global Template</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Tenant-specific Template</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-1">Scope cannot be changed after creation.</p>
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
                                <label for="subject" class="block text-sm font-medium text-black">Subject Line <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       value="{{ old('subject', $template->subject) }}"
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
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm font-mono">{{ old('body', $template->body) }}</textarea>
                                <p class="mt-1 text-xs text-gray-500">You can use HTML formatting. Tokens will be replaced when the email is sent.</p>
                                @error('body')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                            <p class="text-xs text-gray-600 mt-1">Click to insert into body</p>
                        </x-slot>

                        <div class="space-y-2">
                            @foreach($availableTokens as $key => $description)
                                <div class="flex items-start justify-between p-2 bg-gray-50 rounded hover:bg-gray-100">
                                    <div class="flex-1">
                                        <code class="text-sm font-mono text-blue-600">{{ '{{' . $key . '}}' }}</code>
                                        <p class="text-xs text-gray-600 mt-1">{{ $description }}</p>
                                    </div>
                                    <button type="button" 
                                            onclick="insertToken('{{ '{{' . $key . '}}' }}')"
                                            class="ml-2 text-blue-600 hover:text-blue-700 text-xs font-medium">
                                        Insert
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('tenant.onboarding-email-templates.show', ['tenant' => $tenant->slug, 'template' => $template->id]) }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Update Template
                </button>
            </div>
        </form>
    </div>

    <script>
        function insertToken(token) {
            const bodyTextarea = document.getElementById('body');
            const cursorPos = bodyTextarea.selectionStart;
            const textBefore = bodyTextarea.value.substring(0, cursorPos);
            const textAfter = bodyTextarea.value.substring(bodyTextarea.selectionEnd);
            
            bodyTextarea.value = textBefore + token + textAfter;
            bodyTextarea.focus();
            bodyTextarea.setSelectionRange(cursorPos + token.length, cursorPos + token.length);
        }
    </script>
</x-app-layout>

