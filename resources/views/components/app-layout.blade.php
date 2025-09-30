<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'HireHub') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="h-full bg-gray-50 dark:bg-gray-900">
        <div class="h-full">
            @if(Auth::check())
                <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
                    <!-- Sidebar -->
                    <div class="hidden md:block md:fixed md:inset-y-0 md:w-64">
                        <x-sidebar :tenant="$tenant" />
                    </div>

                    <!-- Main content -->
                    <div class="md:ml-64">
                        <!-- Top navigation -->
                        <x-navbar :tenant="$tenant" />

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
    </body>
</html>
