@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Settings', 'url' => null],
        ['label' => 'Careers', 'url' => null]
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
                <h1 class="text-2xl font-bold text-black">Careers Settings</h1>
                <p class="mt-1 text-sm text-black">Customize your careers page branding and appearance</p>
            </div>
        </div>

        <!-- Form -->
        <x-card>
            <form method="POST" action="{{ route('tenant.settings.careers.update', $tenant->slug) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Form Fields -->
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-black mb-1">Company Logo</label>
                            <input type="file"
                                   name="logo"
                                   id="logo"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($branding->logo_path)
                                <p class="mt-1 text-sm text-gray-600">Current: {{ basename($branding->logo_path) }}</p>
                            @endif
                        </div>

                        <!-- Hero Image Upload -->
                        <div>
                            <label for="hero_image" class="block text-sm font-medium text-black mb-1">Hero Background Image</label>
                            <input type="file"
                                   name="hero_image"
                                   id="hero_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('hero_image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @if($branding->hero_image_path)
                                <p class="mt-1 text-sm text-gray-600">Current: {{ basename($branding->hero_image_path) }}</p>
                            @endif
                        </div>

                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-black mb-1">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       name="primary_color"
                                       id="primary_color"
                                       value="{{ $branding->primary_color ?? '#4f46e5' }}"
                                       class="w-12 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text"
                                       value="{{ $branding->primary_color ?? '#4f46e5' }}"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="#4f46e5"
                                       readonly>
                            </div>
                            @error('primary_color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Intro Headline -->
                        <div>
                            <label for="intro_headline" class="block text-sm font-medium text-black mb-1">Hero Headline</label>
                            <input type="text"
                                   name="intro_headline"
                                   id="intro_headline"
                                   value="{{ old('intro_headline', $branding->intro_headline) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Join Our Amazing Team">
                            @error('intro_headline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Intro Subtitle -->
                        <div>
                            <label for="intro_subtitle" class="block text-sm font-medium text-black mb-1">Hero Subtitle</label>
                            <textarea name="intro_subtitle"
                                      id="intro_subtitle"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="We're looking for talented individuals to join our growing team...">{{ old('intro_subtitle', $branding->intro_subtitle) }}</textarea>
                            @error('intro_subtitle')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column - Preview -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-black">Preview</h3>
                        
                        <!-- Hero Section Preview -->
                        <div class="relative bg-gray-200 rounded-lg overflow-hidden" style="height: 300px;">
                            @if($branding->hero_image_path)
                                <img src="{{ asset('storage/' . $branding->hero_image_path) }}" 
                                     alt="Hero Preview" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600"></div>
                            @endif
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                            
                            <!-- Content -->
                            <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-6 text-center">
                                @if($branding->logo_path)
                                    <img src="{{ asset('storage/' . $branding->logo_path) }}" 
                                         alt="Logo" 
                                         class="h-12 mb-4">
                                @endif
                                
                                <h2 class="text-2xl font-bold mb-2">
                                    {{ $branding->intro_headline ?? 'Join Our Amazing Team' }}
                                </h2>
                                
                                <p class="text-lg opacity-90">
                                    {{ $branding->intro_subtitle ?? 'We\'re looking for talented individuals to join our growing team...' }}
                                </p>
                                
                                <button class="mt-4 px-6 py-2 rounded-md text-white font-medium"
                                        style="background-color: {{ $branding->primary_color ?? '#4f46e5' }}">
                                    View Open Positions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Settings
                    </button>
                </div>
            </form>
        </x-card>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('primary_color').addEventListener('input', function() {
            this.nextElementSibling.value = this.value;
        });
    </script>
</x-app-layout>
