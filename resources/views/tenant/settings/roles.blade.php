<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Settings', 'url' => null],
                ['label' => 'Roles & Permissions', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Roles & Permissions</h1>
            <p class="mt-1 text-sm text-white">
                Manage roles and their permissions within your organization.
            </p>
        </div>

        <!-- Roles Overview -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 xl:grid-cols-4">
            @php
                $roles = \App\Models\TenantRole::forTenant(tenant_id())->get();
                $roleInfo = [
                    'Owner' => [
                        'description' => 'Full access to all features and settings',
                        'color' => 'bg-purple-100 text-purple-800',
                        'icon' => 'crown'
                    ],
                    'Admin' => [
                        'description' => 'Manage jobs, candidates, and most settings',
                        'color' => 'bg-blue-100 text-blue-800',
                        'icon' => 'shield-check'
                    ],
                    'Recruiter' => [
                        'description' => 'Manage jobs and candidates, limited settings',
                        'color' => 'bg-green-100 text-green-800',
                        'icon' => 'user-group'
                    ],
                    'Hiring Manager' => [
                        'description' => 'View jobs and candidates, limited access',
                        'color' => 'bg-yellow-100 text-yellow-800',
                        'icon' => 'eye'
                    ]
                ];
            @endphp

            @foreach($roles as $role)
                @php
                    $info = $roleInfo[$role->name] ?? ['description' => 'Custom role', 'color' => 'bg-gray-100 text-gray-800', 'icon' => 'user'];
                @endphp
                <x-card>
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $info['color'] }} rounded-lg flex items-center justify-center">
                                @if($info['icon'] === 'crown')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                @elseif($info['icon'] === 'shield-check')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($info['icon'] === 'user-group')
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-black truncate">{{ $role->name }}</dt>
                                <dd class="text-sm text-gray-600">{{ $info['description'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </x-card>
            @endforeach
        </div>

        <!-- Detailed Permissions -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-black">Role Permissions</h3>
            </x-slot>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permission</th>
                            @foreach($roles as $role)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $role->name }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php
                            $permissionCategories = [
                                'Jobs' => ['view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs'],
                                'Job Stages' => ['manage stages', 'view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages'],
                                'Candidates' => ['view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates'],
                                'Interviews' => ['view interviews', 'create interviews', 'edit interviews', 'delete interviews'],
                                'Analytics' => ['view analytics'],
                                'System' => ['view dashboard', 'manage users', 'manage settings', 'manage email templates'],
                            ];
                        @endphp

                        @foreach($permissionCategories as $category => $permissions)
                            <tr class="bg-gray-50">
                                <td colspan="{{ $roles->count() + 1 }}" class="px-6 py-3 text-sm font-medium text-gray-900 bg-gray-100">
                                    {{ $category }}
                                </td>
                            </tr>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucwords(str_replace('_', ' ', $permission)) }}
                                    </td>
                                    @foreach($roles as $role)
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($role->hasPermissionTo($permission))
                                                <svg class="w-5 h-5 text-green-500 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <!-- Role Management Info -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-medium text-black">Role Management</h3>
            </x-slot>

            <div class="space-y-4">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">Role Hierarchy</h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Roles are hierarchical with the following access levels:</p>
                                <ul class="mt-2 list-disc list-inside space-y-1">
                                    <li><strong>Owner:</strong> Full access to all features and settings</li>
                                    <li><strong>Admin:</strong> Can manage most features except user management</li>
                                    <li><strong>Recruiter:</strong> Can manage jobs and candidates</li>
                                    <li><strong>Hiring Manager:</strong> Can view jobs and candidates</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.726-1.36 3.491 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">Important Notes</h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Only Owners and Admins can manage team members</li>
                                    <li>At least one Owner must always exist in the organization</li>
                                    <li>Role permissions cannot be modified in this version</li>
                                    <li>Users can only have one role per tenant</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-app-layout>