<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenant->slug)],
                ['label' => 'Employee Onboarding', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Employee Onboarding</h1>
            <p class="mt-1 text-sm text-white">
                Streamline the onboarding process for new employees and ensure a smooth transition into your organization.
            </p>
        </div>

        <!-- Content placeholder - can be expanded later -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Employee Onboarding</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This section will contain employee onboarding features and tools.
                </p>
            </div>
        </x-card>
    </div>
</x-app-layout>

