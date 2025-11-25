<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @php
            $seoTitle = ($pageTitle ?? 'Dashboard') . ' – TalentLit ATS';
            $seoDescription = 'TalentLit ATS Dashboard - Manage your recruitment pipeline, track candidates, and make smarter hiring decisions.';
            $tenant = tenant();
        @endphp
        @include('layouts.partials.head')
    </head>
    <body class="h-full bg-gray-50 dark:bg-gray-900">
        <div class="h-full">
            @if(Auth::check())
                <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
                    <!-- Sidebar -->
                    <div class="lg:block lg:fixed lg:inset-y-0 lg:w-64">
                        @isset($tenant)
                            <x-sidebar :tenant="$tenant" />
                        @else
                            <x-sidebar :tenant="tenant()" />
                        @endisset
                    </div>

                    <!-- Main content -->
                    <div class="lg:ml-64">
                        <!-- Top navigation -->
                        @isset($tenant)
                            <x-navbar :tenant="$tenant" />
                        @else
                            <x-navbar :tenant="tenant()" />
                        @endisset

                        <!-- Page content -->
                        <main class="py-6">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                                {{ $slot }}
                            </div>
                        </main>
                    </div>
                </div>
            @else
                <!-- Guest layout for non-authenticated users -->
                <div class="min-h-full">
                    {{ $slot }}
                </div>
            @endif
        </div>
        
        @include('layouts.partials.mobile-menu')
        
        @stack('scripts')
    </body>
</html>
