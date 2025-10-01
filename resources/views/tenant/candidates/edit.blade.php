@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantSlug)],
        ['label' => 'Candidates', 'url' => route('tenant.candidates.index', $tenantSlug)],
        ['label' => $candidate->full_name, 'url' => route('tenant.candidates.show', ['tenant' => $tenantSlug, 'candidate' => $candidate])],
        ['label' => 'Edit', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Edit Form -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold text-gray-900">Edit Candidate</h1>
                <p class="text-gray-600">Update candidate information</p>
            </div>

            <form method="POST" action="{{ route('tenant.candidates.update', ['tenant' => $tenantSlug, 'candidate' => $candidate]) }}" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input type="text" 
                               name="first_name" 
                               id="first_name" 
                               value="{{ old('first_name', $candidate->first_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('first_name') border-red-300 @enderror">
                        @error('first_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input type="text" 
                               name="last_name" 
                               id="last_name" 
                               value="{{ old('last_name', $candidate->last_name) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('last_name') border-red-300 @enderror">
                        @error('last_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="primary_email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" 
                               name="primary_email" 
                               id="primary_email" 
                               value="{{ old('primary_email', $candidate->primary_email) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('primary_email') border-red-300 @enderror">
                        @error('primary_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="primary_phone" class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="tel" 
                               name="primary_phone" 
                               id="primary_phone" 
                               value="{{ old('primary_phone', $candidate->primary_phone) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('primary_phone') border-red-300 @enderror">
                        @error('primary_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Source -->
                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                        <select name="source" 
                                id="source" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('source') border-red-300 @enderror">
                            <option value="">Select Source</option>
                            <option value="Career Site" {{ old('source', $candidate->source) == 'Career Site' ? 'selected' : '' }}>Career Site</option>
                            <option value="LinkedIn" {{ old('source', $candidate->source) == 'LinkedIn' ? 'selected' : '' }}>LinkedIn</option>
                            <option value="Indeed" {{ old('source', $candidate->source) == 'Indeed' ? 'selected' : '' }}>Indeed</option>
                            <option value="Referral" {{ old('source', $candidate->source) == 'Referral' ? 'selected' : '' }}>Referral</option>
                            <option value="Recruiter" {{ old('source', $candidate->source) == 'Recruiter' ? 'selected' : '' }}>Recruiter</option>
                            <option value="Other" {{ old('source', $candidate->source) == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('source')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('tenant.candidates.show', ['tenant' => $tenantSlug, 'candidate' => $candidate]) }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Candidate
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
