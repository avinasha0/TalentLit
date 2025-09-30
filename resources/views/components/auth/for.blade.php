@props(['role' => null, 'permission' => null])

@if($role)
    @if(auth()->user()->hasRole($role))
        {{ $slot }}
    @endif
@elseif($permission)
    @if(auth()->user()->can($permission))
        {{ $slot }}
    @endif
@endif
