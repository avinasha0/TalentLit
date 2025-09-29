<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'HireHub - Modern ATS for Growing Teams' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Navigation -->
        <x-public-navbar />

        <!-- Page content -->
        <main>
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1 md:col-span-2">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                </svg>
                            </div>
                            <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">HireHub</span>
                        </div>
                        <p class="mt-4 text-gray-600 dark:text-gray-400">
                            Modern ATS for growing teams. Streamline your hiring process with powerful tools and intuitive design.
                        </p>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Product</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Features</a></li>
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Pricing</a></li>
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Integrations</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white tracking-wider uppercase">Support</h3>
                        <ul class="mt-4 space-y-4">
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Help Center</a></li>
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Contact</a></li>
                            <li><a href="#" class="text-base text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400">Status</a></li>
                        </ul>
                    </div>
                </div>
                <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-8">
                    <p class="text-base text-gray-400 dark:text-gray-500 text-center">
                        &copy; {{ date('Y') }} HireHub. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
