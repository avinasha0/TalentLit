<x-app-layout :tenant="$tenantModel">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenantModel->slug)],
                ['label' => 'Organisation', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Organisation Structure</h1>
            <p class="mt-1 text-sm text-white">
                View your company's organizational structure, departments, locations, and team members.
            </p>
        </div>

        <!-- Organizational Tree Structure -->
        @if(!empty($orgTree))
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-black">Organizational Hierarchy</h3>
                <p class="mt-1 text-sm text-gray-600">Tree structure showing reporting hierarchy: CEO → Line Manager → HR Manager → HR Recruiter</p>
            </x-slot>

            <div class="py-4 overflow-x-auto max-h-[calc(100vh-300px)]">
                <style>
                    .org-tree-container {
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        min-width: max-content;
                    }
                    .org-tree-node {
                        position: relative;
                    }
                    .org-tree-connector {
                        position: absolute;
                        background-color: #6b7280;
                    }
                    .org-tree-connector-vertical {
                        width: 2px;
                        height: 16px;
                    }
                    .org-tree-connector-horizontal {
                        height: 2px;
                        width: 100%;
                    }
                    /* Level indicators for better visibility */
                    .org-level-1 { border-width: 3px; }
                    .org-level-2 { border-width: 2px; }
                    .org-level-3 { border-width: 2px; }
                    .org-level-4 { border-width: 2px; }
                </style>
                <div class="org-tree-container">
                    @foreach($orgTree as $rootNode)
                        <div class="w-full flex justify-center">
                            @include('tenant.organization.partials.tree-node', ['node' => $rootNode, 'isRoot' => true])
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Departments -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Departments</dt>
                            <dd class="text-lg font-medium text-black">{{ $stats['total_departments'] }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Total Locations -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Locations</dt>
                            <dd class="text-lg font-medium text-black">{{ $stats['total_locations'] }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Total Team Members -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Team Members</dt>
                            <dd class="text-lg font-medium text-black">{{ $stats['total_team_members'] }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Total Job Openings -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Job Openings</dt>
                            <dd class="text-lg font-medium text-black">{{ $stats['total_job_openings'] }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Departments Section -->
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-black">Departments</h3>
                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                            {{ $departments->count() }}
                        </span>
                    </div>
                </x-slot>

                @if($departments->count() > 0)
                    <div class="space-y-3">
                        @foreach($departments as $department)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $department->name }}</p>
                                        @if($department->code)
                                            <p class="text-xs text-gray-500">{{ $department->code }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No departments found</p>
                    </div>
                @endif
            </x-card>

            <!-- Locations Section -->
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-black">Locations</h3>
                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                            {{ $locations->count() }}
                        </span>
                    </div>
                </x-slot>

                @if($locations->count() > 0)
                    <div class="space-y-3">
                        @foreach($locations as $location)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $location->name }}</p>
                                        @if($location->city || $location->country)
                                            <p class="text-xs text-gray-500">
                                                {{ $location->city }}@if($location->city && $location->country), @endif{{ $location->country }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No locations found</p>
                    </div>
                @endif
            </x-card>

            <!-- Team Members by Role -->
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-black">Team by Role</h3>
                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full">
                            {{ $users->count() }}
                        </span>
                    </div>
                </x-slot>

                @if($usersByRole->count() > 0)
                    <div class="space-y-3">
                        @foreach($usersByRole as $role => $roleUsers)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $role }}</h4>
                                    <span class="px-2 py-0.5 text-xs font-medium text-gray-600 bg-white rounded">
                                        {{ $roleUsers->count() }}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    @foreach($roleUsers as $user)
                                        <div class="flex items-center text-sm">
                                            <div class="flex-shrink-0 w-6 h-6 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                            <span class="ml-2 text-gray-700 truncate">{{ $user->name }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-500">No team members found</p>
                    </div>
                @endif
            </x-card>
        </div>

        <!-- Company Information -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-black">Company Information</h3>
            </x-slot>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $tenantModel->name }}</dd>
                </div>
                @if($tenantModel->location)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenantModel->location }}</dd>
                    </div>
                @endif
                @if($tenantModel->company_size)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Company Size</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenantModel->company_size }}</dd>
                    </div>
                @endif
                @if($tenantModel->website)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Website</dt>
                        <dd class="mt-1 text-sm">
                            <a href="{{ $tenantModel->website }}" target="_blank" class="text-blue-600 hover:text-blue-700">
                                {{ $tenantModel->website }}
                            </a>
                        </dd>
                    </div>
                @endif
            </div>
        </x-card>

        <!-- All Team Members -->
        <x-card>
            <x-slot name="header">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-black">All Team Members</h3>
                    <a href="{{ route('tenant.settings.team', $tenantModel->slug) }}" class="text-sm text-blue-600 hover:text-blue-700">
                        Manage Team
                    </a>
                </div>
            </x-slot>

            @if($users->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Job Openings</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requisitions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($user->tenant_role === 'Owner') bg-purple-100 text-purple-800
                                            @elseif($user->tenant_role === 'Admin') bg-blue-100 text-blue-800
                                            @elseif($user->tenant_role === 'Recruiter') bg-green-100 text-green-800
                                            @elseif($user->tenant_role === 'Hiring Manager') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $user->tenant_role ?? 'No Role' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->job_openings_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $user->requisitions_count ?? 0 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-500">No team members found</p>
                </div>
            @endif
        </x-card>
    </div>
</x-app-layout>

