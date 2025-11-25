<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenant->slug)],
                ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenant->slug)],
                ['label' => 'New Onboarding', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">New Onboarding</h1>
            <p class="mt-1 text-sm text-white">
                Start a new employee onboarding process.
            </p>
        </div>

        <!-- Content placeholder -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">New Onboarding</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This section will allow you to create a new employee onboarding process.
                </p>
            </div>
        </x-card>
    </div>
</x-app-layout>

