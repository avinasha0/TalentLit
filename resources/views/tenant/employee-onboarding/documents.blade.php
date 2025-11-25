<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenant->slug)],
                ['label' => 'Employee Onboarding', 'url' => tenantRoute('tenant.employee-onboarding.index', $tenant->slug)],
                ['label' => 'Documents', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Onboarding Documents</h1>
            <p class="mt-1 text-sm text-white">
                Manage documents required for employee onboarding.
            </p>
        </div>

        <!-- Content placeholder -->
        <x-card>
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Documents</h3>
                <p class="mt-1 text-sm text-gray-500">
                    This section will allow you to manage onboarding documents.
                </p>
            </div>
        </x-card>
    </div>
</x-app-layout>

