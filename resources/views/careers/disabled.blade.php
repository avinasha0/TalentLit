<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $tenantModel = $tenant ?? $tenantModel ?? null;
        $seoTitle = 'Careers at ' . $tenantModel->name . ' | Currently Unavailable';
        $seoDescription = 'The careers page for ' . $tenantModel->name . ' is currently unavailable. Please check back later or contact us for more information.';
        $seoKeywords = 'careers, jobs, ' . $tenantModel->name . ', employment, recruitment, hiring, TalentLit, job opportunities';
        $seoAuthor = $tenantModel->name;
        $seoImage = ($branding && $branding->logo_path) 
            ? asset('storage/' . $branding->logo_path) 
            : asset('logo-talentlit-small.png');
    @endphp
    @include('layouts.partials.head')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --brand: {{ $branding && $branding->primary_color ? $branding->primary_color : '#4f46e5' }};
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-6">
                    <div class="flex items-center">
                        @if($branding && $branding->logo_path)
                            <img src="{{ asset('storage/' . $branding->logo_path) }}" 
                                 alt="{{ $tenantModel->name }} Company Logo" 
                                 class="h-8 mr-4">
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">{{ $tenantModel->name }}</h1>
                            <p class="text-gray-600">Careers</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center">
            <div class="max-w-md mx-auto text-center px-4 sm:px-6 lg:px-8">
                <!-- Icon -->
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-gray-100 mb-8">
                    <svg class="h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Careers Page Temporarily Unavailable
                </h1>

                <!-- Description -->
                <p class="text-lg text-gray-600 mb-8">
                    We're currently updating our careers page. Please check back later or contact us directly for career opportunities.
                </p>

                <!-- Contact Information -->
                @if($tenantModel->website)
                    <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Get in Touch</h3>
                        <div class="space-y-2">
                            @if($tenantModel->website)
                                <p class="text-gray-600">
                                    <span class="font-medium">Website:</span> 
                                    <a href="{{ $tenantModel->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        {{ $tenantModel->website }}
                                    </a>
                                </p>
                            @endif
                            @if($tenantModel->location)
                                <p class="text-gray-600">
                                    <span class="font-medium">Location:</span> {{ $tenantModel->location }}
                                </p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <button onclick="window.location.reload()" 
                            class="w-full sm:w-auto inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white transition-colors"
                            style="background-color: var(--brand);">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Page
                    </button>
                    
                    @if($tenantModel->website)
                        <a href="{{ $tenantModel->website }}" 
                           target="_blank"
                           class="w-full sm:w-auto inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Visit Our Website
                        </a>
                    @endif
                </div>

                <!-- Footer Note -->
                <p class="text-sm text-gray-500 mt-8">
                    This page will be available again soon. Thank you for your interest in joining our team.
                </p>
            </div>
        </main>

        <!-- Centralized Footer Component -->
    <x-footer />

    </div>
</body>
</html>
