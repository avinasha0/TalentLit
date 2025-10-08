@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Locations', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Locations</h1>
                        <p class="mt-1 text-sm text-black">Manage organization locations</p>
                    </div>
                    <div>
                        <a href="{{ route('tenant.locations.create', $tenant->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            + Add Location
                        </a>
                    </div>
                </div>

                <!-- Search -->
                <form method="GET" class="mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name or code" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        <div class="md:col-span-2 flex md:justify-end">
                            <button class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-900">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if($locations->count() > 0)
            <!-- Mobile cards -->
            <div class="block lg:hidden space-y-4">
                @foreach($locations as $location)
                    <div class="bg-white rounded-lg shadow p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-base font-semibold text-black">{{ $location->name }}</p>
                                <p class="text-sm text-gray-600">Code: {{ $location->code ?? '—' }}</p>
                            </div>
                            <div>
                                @if($location->is_active)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Desktop table -->
            <x-card class="hidden lg:block">
                <x-table>
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($locations as $location)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $location->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black">{{ $location->code ?? '—' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if($location->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-table>

                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $locations->links() }}
                </div>
            </x-card>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-white">No locations found</h3>
                <p class="mt-1 text-sm text-gray-300">Get started by creating your first location.</p>
                <div class="mt-6">
                    <a href="{{ route('tenant.locations.create', $tenant->slug) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        + Add Location
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
