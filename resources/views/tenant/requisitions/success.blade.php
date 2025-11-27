@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Success', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <!-- Success Icon -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <!-- Success Message (Tasks 82-84) -->
                <h1 class="text-3xl font-bold text-gray-900 mb-4">
                    Requisition Created Successfully!
                </h1>
                <p class="text-lg text-gray-600 mb-2">
                    Your requisition has been submitted for approval.
                </p>
                <p class="text-sm text-gray-500 mb-8">
                    Requisition ID: <span class="font-semibold text-gray-900">{{ $requisition->id }}</span>
                </p>

                <!-- Requisition Summary -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8 text-left">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Requisition Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Job Title</p>
                            <p class="text-sm font-medium text-gray-900">{{ $requisition->job_title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Department</p>
                            <p class="text-sm font-medium text-gray-900">{{ $requisition->department }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Headcount</p>
                            <p class="text-sm font-medium text-gray-900">{{ $requisition->headcount }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Priority</p>
                            <p class="text-sm font-medium text-gray-900">{{ $requisition->priority }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $requisition->status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons (Task 85) -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ tenantRoute('tenant.requisitions.index', $tenantSlug) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Go to All Requisitions
                    </a>
                    <a href="{{ tenantRoute('tenant.requisitions.create', $tenantSlug) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Create Another Requisition
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

