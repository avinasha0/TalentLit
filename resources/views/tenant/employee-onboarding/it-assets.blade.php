<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenant->slug)],
                ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenant->slug)],
                ['label' => 'IT & Assets', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">IT & Assets</h1>
            <p class="mt-1 text-sm text-white">
                Manage IT equipment and assets for new employees.
            </p>
        </div>

        <!-- Content placeholder -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">IT & Assets</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This section will allow you to manage IT equipment and assets allocation.
                </p>
            </div>
        </x-card>
    </div>
</x-app-layout>

