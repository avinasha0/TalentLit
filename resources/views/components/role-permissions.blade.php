@props(['user' => null, 'showTitle' => true])

@php
    $user = $user ?? auth()->user();
    $role = $user->roles->first();
    $permissions = $role ? $role->permissions : collect();
    
    $permissionGroups = [
        'Jobs' => ['view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs'],
        'Candidates' => ['view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates'],
        'Stages' => ['view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages', 'manage stages'],
        'System' => ['view dashboard', 'manage users', 'manage settings']
    ];
@endphp

@if($role)
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        @if($showTitle)
            <h3 class="text-lg font-medium text-gray-900 mb-3">Your Permissions</h3>
        @endif
        
        <div class="space-y-4">
            @foreach($permissionGroups as $groupName => $groupPermissions)
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2">{{ $groupName }}</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($groupPermissions as $permission)
                            @php
                                $hasPermission = $permissions->contains('name', $permission);
                            @endphp
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                @if($hasPermission) bg-green-100 text-green-800 border border-green-200
                                @else bg-gray-100 text-gray-500 border border-gray-200 @endif">
                                @if($hasPermission)
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                @else
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                @endif
                                {{ str_replace('_', ' ', $permission) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-center text-gray-500">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <p>No role assigned. Contact your administrator.</p>
        </div>
    </div>
@endif
