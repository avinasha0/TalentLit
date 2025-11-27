@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Pending Approvals', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-black">Pending Approvals</h1>
                <p class="mt-1 text-sm text-black">Requisitions awaiting approval</p>
            </div>
        </div>

        <!-- Requisitions Table -->
        <x-card>
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-lg leading-6 font-medium text-black">
                    {{ $requisitions->total() }} pending requisition{{ $requisitions->total() !== 1 ? 's' : '' }}
                </h3>
            </div>

            @if($requisitions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Headcount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($requisitions as $requisition)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}" 
                                           class="text-sm font-medium text-blue-600 hover:text-blue-900 hover:underline focus:outline-none focus:underline">
                                            {{ $requisition->job_title }}
                                        </a>
                                        @if($requisition->justification)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($requisition->justification, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requisition->department ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requisition->location ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $requisition->headcount }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $requisition->created_at->format('M j, Y') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200">
                    {{ $requisitions->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No pending requisitions</h3>
                    <p class="mt-1 text-sm text-gray-500">All requisitions have been processed.</p>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>

