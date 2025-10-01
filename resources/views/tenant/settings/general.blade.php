@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Settings', 'url' => null],
        ['label' => 'General', 'url' => null]
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
                <h1 class="text-2xl font-bold text-gray-900">General Settings</h1>
                <p class="mt-1 text-sm text-gray-600">Manage your organization's basic information and preferences</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Form -->
        <form method="POST" action="{{ route('tenant.settings.general.update', $tenant->slug) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Organization Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Organization Information</h3>
                    <p class="mt-1 text-sm text-gray-600">Basic details about your organization</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Organization Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Organization Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name', $tenant->name) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('name') border-red-300 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Organization Slug -->
                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700">Organization Slug *</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <span class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    {{ url('/') }}/
                                </span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       value="{{ old('slug', $tenant->slug) }}"
                                       class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border-gray-300 focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('slug') border-red-300 @enderror"
                                       required>
                            </div>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Used in URLs. Only lowercase letters, numbers, and hyphens allowed.</p>
                        </div>

                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                            <input type="url" 
                                   name="website" 
                                   id="website" 
                                   value="{{ old('website', $tenant->website) }}"
                                   placeholder="https://example.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('website') border-red-300 @enderror">
                            @error('website')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                            <input type="text" 
                                   name="location" 
                                   id="location" 
                                   value="{{ old('location', $tenant->location) }}"
                                   placeholder="City, Country"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('location') border-red-300 @enderror">
                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Company Size -->
                        <div>
                            <label for="company_size" class="block text-sm font-medium text-gray-700">Company Size</label>
                            <select name="company_size" 
                                    id="company_size" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('company_size') border-red-300 @enderror">
                                <option value="">Select company size</option>
                                <option value="1-10" {{ old('company_size', $tenant->company_size) == '1-10' ? 'selected' : '' }}>1-10 employees</option>
                                <option value="11-50" {{ old('company_size', $tenant->company_size) == '11-50' ? 'selected' : '' }}>11-50 employees</option>
                                <option value="51-200" {{ old('company_size', $tenant->company_size) == '51-200' ? 'selected' : '' }}>51-200 employees</option>
                                <option value="201-500" {{ old('company_size', $tenant->company_size) == '201-500' ? 'selected' : '' }}>201-500 employees</option>
                                <option value="501-1000" {{ old('company_size', $tenant->company_size) == '501-1000' ? 'selected' : '' }}>501-1000 employees</option>
                                <option value="1000+" {{ old('company_size', $tenant->company_size) == '1000+' ? 'selected' : '' }}>1000+ employees</option>
                            </select>
                            @error('company_size')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Branding -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Branding</h3>
                    <p class="mt-1 text-sm text-gray-600">Customize your organization's visual identity</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Logo -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                            <div class="mt-1 flex items-center space-x-4">
                                @if($tenant->logo)
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('storage/' . $tenant->logo) }}" alt="Current logo" class="h-16 w-16 object-contain rounded-lg border border-gray-200">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" 
                                           name="logo" 
                                           id="logo" 
                                           accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('logo') border-red-300 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF, SVG up to 2MB</p>
                                </div>
                            </div>
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                            <div class="mt-1 flex items-center space-x-3">
                                <input type="color" 
                                       name="primary_color" 
                                       id="primary_color" 
                                       value="{{ old('primary_color', $tenant->primary_color ?? '#3B82F6') }}"
                                       class="h-10 w-20 rounded-md border border-gray-300 focus:border-blue-500 focus:ring-blue-500 @error('primary_color') border-red-300 @enderror">
                                <input type="text" 
                                       id="primary_color_text" 
                                       value="{{ old('primary_color', $tenant->primary_color ?? '#3B82F6') }}"
                                       class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                       placeholder="#3B82F6">
                            </div>
                            @error('primary_color')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regional Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Regional Settings</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure timezone and language preferences</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Timezone -->
                        <div>
                            <label for="timezone" class="block text-sm font-medium text-gray-700">Timezone *</label>
                            <select name="timezone" 
                                    id="timezone" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('timezone') border-red-300 @enderror"
                                    required>
                                <option value="">Select timezone</option>
                                <option value="UTC" {{ old('timezone', $tenant->timezone) == 'UTC' ? 'selected' : '' }}>UTC</option>
                                <option value="America/New_York" {{ old('timezone', $tenant->timezone) == 'America/New_York' ? 'selected' : '' }}>Eastern Time (ET)</option>
                                <option value="America/Chicago" {{ old('timezone', $tenant->timezone) == 'America/Chicago' ? 'selected' : '' }}>Central Time (CT)</option>
                                <option value="America/Denver" {{ old('timezone', $tenant->timezone) == 'America/Denver' ? 'selected' : '' }}>Mountain Time (MT)</option>
                                <option value="America/Los_Angeles" {{ old('timezone', $tenant->timezone) == 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time (PT)</option>
                                <option value="Europe/London" {{ old('timezone', $tenant->timezone) == 'Europe/London' ? 'selected' : '' }}>London (GMT)</option>
                                <option value="Europe/Paris" {{ old('timezone', $tenant->timezone) == 'Europe/Paris' ? 'selected' : '' }}>Paris (CET)</option>
                                <option value="Asia/Tokyo" {{ old('timezone', $tenant->timezone) == 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo (JST)</option>
                                <option value="Asia/Kolkata" {{ old('timezone', $tenant->timezone) == 'Asia/Kolkata' ? 'selected' : '' }}>Mumbai (IST)</option>
                                <option value="Asia/Shanghai" {{ old('timezone', $tenant->timezone) == 'Asia/Shanghai' ? 'selected' : '' }}>Shanghai (CST)</option>
                                <option value="Australia/Sydney" {{ old('timezone', $tenant->timezone) == 'Australia/Sydney' ? 'selected' : '' }}>Sydney (AEST)</option>
                            </select>
                            @error('timezone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Locale -->
                        <div>
                            <label for="locale" class="block text-sm font-medium text-gray-700">Language *</label>
                            <select name="locale" 
                                    id="locale" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('locale') border-red-300 @enderror"
                                    required>
                                <option value="">Select language</option>
                                <option value="en" {{ old('locale', $tenant->locale) == 'en' ? 'selected' : '' }}>English</option>
                                <option value="es" {{ old('locale', $tenant->locale) == 'es' ? 'selected' : '' }}>Español</option>
                                <option value="fr" {{ old('locale', $tenant->locale) == 'fr' ? 'selected' : '' }}>Français</option>
                                <option value="de" {{ old('locale', $tenant->locale) == 'de' ? 'selected' : '' }}>Deutsch</option>
                                <option value="it" {{ old('locale', $tenant->locale) == 'it' ? 'selected' : '' }}>Italiano</option>
                                <option value="pt" {{ old('locale', $tenant->locale) == 'pt' ? 'selected' : '' }}>Português</option>
                                <option value="ru" {{ old('locale', $tenant->locale) == 'ru' ? 'selected' : '' }}>Русский</option>
                                <option value="ja" {{ old('locale', $tenant->locale) == 'ja' ? 'selected' : '' }}>日本語</option>
                                <option value="ko" {{ old('locale', $tenant->locale) == 'ko' ? 'selected' : '' }}>한국어</option>
                                <option value="zh" {{ old('locale', $tenant->locale) == 'zh' ? 'selected' : '' }}>中文</option>
                                <option value="hi" {{ old('locale', $tenant->locale) == 'hi' ? 'selected' : '' }}>हिन्दी</option>
                            </select>
                            @error('locale')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Careers Settings -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Careers Page Settings</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure your public careers page</p>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Careers Enabled -->
                    <div class="flex items-center">
                        <input type="checkbox" 
                               name="careers_enabled" 
                               id="careers_enabled" 
                               value="1" 
                               {{ old('careers_enabled', $tenant->careers_enabled) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="careers_enabled" class="ml-2 block text-sm text-gray-900">
                            Enable public careers page
                        </label>
                    </div>

                    <!-- Careers Introduction -->
                    <div>
                        <label for="careers_intro" class="block text-sm font-medium text-gray-700">Careers Page Introduction</label>
                        <textarea name="careers_intro" 
                                  id="careers_intro" 
                                  rows="4" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('careers_intro') border-red-300 @enderror"
                                  placeholder="Welcome to our careers page. We're looking for talented individuals to join our team...">{{ old('careers_intro', $tenant->careers_intro) }}</textarea>
                        @error('careers_intro')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">This text will appear at the top of your careers page</p>
                    </div>

                    <!-- Consent Text -->
                    <div>
                        <label for="consent_text" class="block text-sm font-medium text-gray-700">Data Consent Text</label>
                        <textarea name="consent_text" 
                                  id="consent_text" 
                                  rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('consent_text') border-red-300 @enderror"
                                  placeholder="By submitting your application, you consent to...">{{ old('consent_text', $tenant->consent_text) }}</textarea>
                        @error('consent_text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">This text will be shown to candidates before they submit applications</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('tenant.dashboard', $tenant->slug) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Save Settings
                </button>
        </div>
        </form>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('primary_color').addEventListener('input', function() {
            document.getElementById('primary_color_text').value = this.value;
        });

        document.getElementById('primary_color_text').addEventListener('input', function() {
            if (/^#[0-9A-Fa-f]{6}$/.test(this.value)) {
                document.getElementById('primary_color').value = this.value;
            }
        });
    </script>
</x-app-layout>
