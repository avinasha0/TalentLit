<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @php
            $seoTitle = (isset($header) ? strip_tags($header) . ' – ' : '') . 'TalentLit ATS';
            $seoDescription = 'Manage your recruitment process with TalentLit. Track candidates, schedule interviews, and make data-driven hiring decisions with our modern ATS platform.';
            $tenant = tenant();
        @endphp
        @include('layouts.partials.head')
    </head>
    <body class="font-sans antialiased" x-data x-init="$store.sidebar = { open: false }">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <x-sidebar />
            
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Mobile Header -->
                <x-mobile-header />
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:block">
                    @include('layouts.navigation')
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>
        
        @include('layouts.partials.mobile-menu')
    </body>
</html>
