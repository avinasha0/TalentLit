@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Locations', 'url' => route('tenant.locations.index', $tenant->slug)],
        ['label' => 'Add Location', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <x-card>
            <form method="POST" action="{{ route('tenant.locations.store', $tenant->slug) }}" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-black mb-1">Location Name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="code" class="block text-sm font-medium text-black mb-1">Code</label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., NYC, LDN">
                    @error('code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded" {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 block text-sm text-black">Active</label>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('tenant.locations.index', $tenant->slug) }}" class="px-4 py-2 bg-gray-100 text-gray-800 rounded-md hover:bg-gray-200">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create Location</button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
