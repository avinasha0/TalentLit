<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    @php
        $seoTitle = $pageTitle ?? (isset($title) ? $title . ' – TalentLit' : 'TalentLit – Smarter Recruitment Platform');
        $seoDescription = $pageDescription ?? 'TalentLit is a modern Applicant Tracking System (ATS) that helps organizations streamline recruitment, manage candidates, and hire top talent efficiently.';
        $tenant = $tenant ?? null;
    @endphp
    @include('layouts.partials.head')
</head>
<body class="h-full bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-talentlit-small.png') }}" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Get Started
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <main>
        @isset($slot)
            {{ $slot }}
        @else
            @yield('content')
        @endisset
    </main>

    <!-- Footer -->
    <footer class="bg-gray-50">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <p class="text-gray-500 text-sm">
                    © {{ date('Y') }} TalentLit. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
