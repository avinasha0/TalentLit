<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Candidates', 'url' => route('tenant.candidates.index', $tenant->slug)],
                ['label' => 'Import', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Import Candidates</h1>
            <p class="mt-1 text-sm text-white">
                Upload a CSV or Excel file to import multiple candidates at once.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Import Form -->
            <div class="lg:col-span-2">
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Upload File</h3>
                    </x-slot>

                    <form action="{{ route('tenant.candidates.import.store', $tenant->slug) }}" 
                          method="POST" 
                          enctype="multipart/form-data" 
                          class="space-y-6">
                        @csrf

                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block text-sm font-medium text-black mb-2">
                                Select File
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a file</span>
                                            <input id="file" name="file" type="file" class="sr-only" accept=".csv,.xlsx,.xls" required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">CSV, XLSX, XLS up to 10MB</p>
                                </div>
                            </div>
                            @error('file')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Source -->
                        <div>
                            <label for="source" class="block text-sm font-medium text-black mb-2">
                                Source (Optional)
                            </label>
                            <input type="text" 
                                   name="source" 
                                   id="source" 
                                   value="{{ old('source', 'Import') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <p class="mt-1 text-sm text-gray-500">This will be set as the source for all imported candidates</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('tenant.candidates.index', $tenant->slug) }}" 
                               class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Import Candidates
                            </button>
                        </div>
                    </form>
                </x-card>
            </div>

            <!-- Instructions -->
            <div class="space-y-6">
                <!-- Download Template -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Template</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <p class="text-sm text-gray-600">
                            Download our template to see the correct format for importing candidates.
                        </p>
                        <a href="{{ route('tenant.candidates.import.template', $tenant->slug) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Template
                        </a>
                    </div>
                </x-card>

                <!-- Format Instructions -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-lg font-medium text-black">Format Requirements</h3>
                    </x-slot>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-black">Required Fields:</h4>
                            <ul class="mt-1 text-sm text-gray-600 space-y-1">
                                <li>• <strong>primary_email</strong> - Candidate's email address</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-black">Optional Fields:</h4>
                            <ul class="mt-1 text-sm text-gray-600 space-y-1">
                                <li>• <strong>first_name</strong> - Candidate's first name</li>
                                <li>• <strong>last_name</strong> - Candidate's last name</li>
                                <li>• <strong>primary_phone</strong> - Phone number</li>
                                <li>• <strong>source</strong> - How they found you</li>
                                <li>• <strong>tags</strong> - Comma-separated tags</li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-sm font-medium text-black">Tips:</h4>
                            <ul class="mt-1 text-sm text-gray-600 space-y-1">
                                <li>• Use the first row for column headers</li>
                                <li>• Duplicate emails will update existing candidates</li>
                                <li>• Tags should be comma-separated (e.g., "PHP,Senior,Remote")</li>
                                <li>• Maximum file size: 10MB</li>
                            </ul>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        document.getElementById('file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                
                // Update the upload area to show selected file
                const uploadArea = document.querySelector('.border-dashed');
                uploadArea.innerHTML = `
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="text-sm text-gray-600">
                            <p class="font-medium text-green-600">${fileName}</p>
                            <p class="text-gray-500">${fileSize} MB</p>
                        </div>
                    </div>
                `;
            }
        });
    </script>
</x-app-layout>
