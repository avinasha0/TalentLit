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

                        <!-- Currency (Fixed to INR) -->
                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                            <div class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50 px-3 py-2 text-gray-600">
                                🇮🇳 Indian Rupee (₹)
                            </div>
                            <input type="hidden" name="currency" value="INR">
                            <p class="mt-1 text-xs text-gray-500">Currency is set to Indian Rupee for all tenants.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subdomain Configuration (Enterprise Only) -->
            @php
                $currentPlan = $tenant->activeSubscription?->plan;
                $isEnterprise = $currentPlan && $currentPlan->slug === 'enterprise';
            @endphp
            @if($isEnterprise)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Custom Subdomain</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure a custom subdomain for your organization (Enterprise feature)</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium">Subdomain Access</p>
                                <p class="mt-1">Once configured, you can access your dashboard at: <strong>{{ $tenant->subdomain ? $tenant->subdomain . '.' . parse_url(config('app.url'), PHP_URL_HOST) : 'your-subdomain.example.com' }}</strong></p>
                                <p class="mt-2">You'll still be able to access via the standard path-based URL as well.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Subdomain -->
                        <div>
                            <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" 
                                       name="subdomain" 
                                       id="subdomain" 
                                       value="{{ old('subdomain', $tenant->subdomain) }}"
                                       placeholder="your-company"
                                       pattern="[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?"
                                       class="block w-full rounded-l-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('subdomain') border-red-300 @enderror">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    .{{ parse_url(config('app.url'), PHP_URL_HOST) ?? 'example.com' }}
                                </span>
                            </div>
                            @error('subdomain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Only lowercase letters, numbers, and hyphens. Must be unique.</p>
                        </div>

                        <!-- Subdomain Enabled -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Enable Subdomain</label>
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="subdomain_enabled" 
                                       id="subdomain_enabled" 
                                       value="1"
                                       {{ old('subdomain_enabled', $tenant->subdomain_enabled) ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="subdomain_enabled" class="ml-2 block text-sm text-gray-700">
                                    Enable subdomain access
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Subdomain must be set before enabling.</p>
                        </div>
                    </div>

                    @if($tenant->subdomain && $tenant->subdomain_enabled)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="text-sm text-green-800">
                                <p class="font-medium">Subdomain Active</p>
                                <p class="mt-1">Your subdomain is active. Access your dashboard at: <a href="https://{{ $tenant->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}/dashboard" target="_blank" class="underline font-medium">https://{{ $tenant->subdomain }}.{{ parse_url(config('app.url'), PHP_URL_HOST) }}/dashboard</a></p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- SMTP Configuration -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">SMTP Configuration</h3>
                    <p class="mt-1 text-sm text-gray-600">Configure your email server settings for sending notifications</p>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- SMTP Host -->
                        <div>
                            <label for="smtp_host" class="block text-sm font-medium text-gray-700">SMTP Host</label>
                            <input type="text" 
                                   name="smtp_host" 
                                   id="smtp_host" 
                                   value="{{ old('smtp_host', $tenant->smtp_host) }}"
                                   placeholder="smtp.gmail.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_host') border-red-300 @enderror">
                            @error('smtp_host')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Port -->
                        <div>
                            <label for="smtp_port" class="block text-sm font-medium text-gray-700">SMTP Port</label>
                            <input type="number" 
                                   name="smtp_port" 
                                   id="smtp_port" 
                                   value="{{ old('smtp_port', $tenant->smtp_port) }}"
                                   placeholder="587"
                                   min="1"
                                   max="65535"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_port') border-red-300 @enderror">
                            @error('smtp_port')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Username -->
                        <div>
                            <label for="smtp_username" class="block text-sm font-medium text-gray-700">SMTP Username</label>
                            <input type="text" 
                                   name="smtp_username" 
                                   id="smtp_username" 
                                   value="{{ old('smtp_username', $tenant->smtp_username) }}"
                                   placeholder="your-email@gmail.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_username') border-red-300 @enderror">
                            @error('smtp_username')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- SMTP Password -->
                        <div>
                            <label for="smtp_password" class="block text-sm font-medium text-gray-700">SMTP Password</label>
                            <div class="mt-1 relative">
                                <input type="password" 
                                       name="smtp_password" 
                                       id="smtp_password" 
                                       value="{{ $tenant->smtp_password ? '••••••••••••••••' : '' }}"
                                       placeholder="{{ $tenant->smtp_password ? '••••••••••••••••' : 'Enter SMTP password' }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm pr-10 @error('smtp_password') border-red-300 @enderror">
                                <button type="button" 
                                        onclick="togglePasswordVisibility('smtp_password')"
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg id="smtp_password_eye_open" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="smtp_password_eye_closed" class="h-5 w-5 text-gray-400 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                    </svg>
                                </button>
                            </div>
                            @error('smtp_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">
                                @if($tenant->smtp_password)
                                    Password is set. Click the eye icon to view or enter a new password to change it.
                                @else
                                    Enter SMTP password
                                @endif
                            </p>
                        </div>

                        <!-- SMTP Encryption -->
                        <div>
                            <label for="smtp_encryption" class="block text-sm font-medium text-gray-700">Encryption</label>
                            <select name="smtp_encryption" 
                                    id="smtp_encryption" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_encryption') border-red-300 @enderror">
                                <option value="">None</option>
                                <option value="tls" {{ old('smtp_encryption', $tenant->smtp_encryption) == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ old('smtp_encryption', $tenant->smtp_encryption) == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('smtp_encryption')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Address -->
                        <div>
                            <label for="smtp_from_address" class="block text-sm font-medium text-gray-700">From Email Address</label>
                            <input type="email" 
                                   name="smtp_from_address" 
                                   id="smtp_from_address" 
                                   value="{{ old('smtp_from_address', $tenant->smtp_from_address) }}"
                                   placeholder="noreply@yourcompany.com"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_from_address') border-red-300 @enderror">
                            @error('smtp_from_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Name -->
                        <div>
                            <label for="smtp_from_name" class="block text-sm font-medium text-gray-700">From Name</label>
                            <input type="text" 
                                   name="smtp_from_name" 
                                   id="smtp_from_name" 
                                   value="{{ old('smtp_from_name', $tenant->smtp_from_name) }}"
                                   placeholder="{{ $tenant->name }} Careers"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm @error('smtp_from_name') border-red-300 @enderror">
                            @error('smtp_from_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Test Email Section -->
                    <div class="border-t border-gray-200 pt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-4">Test Email Configuration</h4>
                        <div class="flex items-end space-x-4">
                            <div class="flex-1">
                                <label for="test_email" class="block text-sm font-medium text-gray-700">Test Email Address</label>
                                <input type="email" 
                                       id="test_email" 
                                       placeholder="test@example.com"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                            </div>
                            <button type="button" 
                                    onclick="sendTestEmail()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Test Email
                            </button>
                        </div>
                    </div>

                    <!-- SMTP Save Button -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex justify-end">
                            <button type="button" 
                                    onclick="saveSmtpSettings()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save SMTP Configuration
                            </button>
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

        // Password visibility toggle
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeOpen = document.getElementById(fieldId + '_eye_open');
            const eyeClosed = document.getElementById(fieldId + '_eye_closed');
            
            if (field.type === 'password') {
                // If showing password and it's the dots placeholder, fetch real password
                if (field.value === '••••••••••••••••') {
                    fetchRealPassword(fieldId);
                } else {
                    field.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                }
            } else {
                field.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }

        // Fetch real password from server
        function fetchRealPassword(fieldId) {
            const field = document.getElementById(fieldId);
            const eyeOpen = document.getElementById(fieldId + '_eye_open');
            const eyeClosed = document.getElementById(fieldId + '_eye_closed');
            
            // Show loading state
            const originalValue = field.value;
            field.value = 'Loading...';
            field.disabled = true;
            
            fetch('{{ route("tenant.settings.general.get-password", $tenant->slug) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    field.value = data.password;
                    field.type = 'text';
                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');
                } else {
                    field.value = originalValue;
                    alert('Failed to retrieve password: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                field.value = originalValue;
                alert('Failed to retrieve password: ' + error.message);
            })
            .finally(() => {
                field.disabled = false;
            });
        }

        // Send test email
        function sendTestEmail() {
            const testEmail = document.getElementById('test_email').value;
            
            if (!testEmail) {
                alert('Please enter a test email address');
                return;
            }

            if (!isValidEmail(testEmail)) {
                alert('Please enter a valid email address');
                return;
            }

            // Show loading state
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';
            button.disabled = true;

            // Create form data
            const formData = new FormData();
            formData.append('test_email', testEmail);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            // Send request
            fetch('{{ route("tenant.settings.general.test-email", $tenant->slug) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Test email sent successfully!');
                } else {
                    alert('Failed to send test email: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Failed to send test email: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Email validation
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        // Save SMTP settings
        function saveSmtpSettings() {
            const button = event.target.closest('button');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
            button.disabled = true;

            // Collect SMTP form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            
            // Add SMTP fields
            const smtpFields = [
                'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 
                'smtp_encryption', 'smtp_from_address', 'smtp_from_name'
            ];
            
            smtpFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && element.value && element.value !== '••••••••••••••••') {
                    formData.append(field, element.value);
                }
            });

            // Send request
            fetch('{{ route("tenant.settings.general.smtp", $tenant->slug) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Failed to save SMTP configuration: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                alert('Failed to save SMTP configuration: ' + error.message);
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        // Currency is now fixed to INR, no auto-detection needed
    </script>
</x-app-layout>
