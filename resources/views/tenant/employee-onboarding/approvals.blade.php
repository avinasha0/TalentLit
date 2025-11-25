<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenant->slug)],
                ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenant->slug)],
                ['label' => 'Approvals', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Approvals</h1>
            <p class="mt-1 text-sm text-white">
                Manage approval workflows for employee onboarding.
            </p>
        </div>

        <!-- Content placeholder -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Approvals</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This section will display all pending and completed approvals.
                </p>
            </div>
        </x-card>
    </div>
</x-app-layout>

