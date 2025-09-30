@props(['role' => null, 'size' => 'sm'])

@php
    $role = $role ?? auth()->user()->roles->first();
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-sm',
        'md' => 'px-3 py-1.5 text-base',
        'lg' => 'px-4 py-2 text-lg'
    ];
@endphp

@if($role)
    <span class="inline-flex items-center {{ $sizeClasses[$size] }} rounded-full font-medium
        @if($role->name === 'Owner') bg-purple-100 text-purple-800 border border-purple-200
        @elseif($role->name === 'Admin') bg-blue-100 text-blue-800 border border-blue-200
        @elseif($role->name === 'Recruiter') bg-green-100 text-green-800 border border-green-200
        @elseif($role->name === 'Hiring Manager') bg-yellow-100 text-yellow-800 border border-yellow-200
        @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
        
        @if($size !== 'xs')
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        @endif
        
        {{ $role->name }}
    </span>
@else
    <span class="inline-flex items-center {{ $sizeClasses[$size] }} rounded-full font-medium bg-gray-100 text-gray-800 border border-gray-200">
        No Role
    </span>
@endif
