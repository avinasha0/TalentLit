@props(['role' => null, 'permission' => null])

@if($role)
    @php
        $userRole = \DB::table('custom_user_roles')
            ->where('user_id', auth()->id())
            ->where('tenant_id', $tenant->id ?? '')
            ->value('role_name');
    @endphp
    @if($userRole === $role)
        {{ $slot }}
    @endif
@elseif($permission)
    @customCan($permission, $tenant ?? tenant())
        {{ $slot }}
    @endcustomCan
@endif
