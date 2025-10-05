@php
    $tenant = tenant();
@endphp

<div class="lg:hidden bg-white border-b border-gray-200 px-4 py-3 flex items-center justify-between">
    <!-- Hamburger Menu Button -->
    <button type="button" data-mobile-toggle 
            class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out" 
            aria-label="Open menu">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Logo/Brand -->
    <div class="flex items-center space-x-2">
        @if($tenant && $tenant->branding && $tenant->branding->logo_path)
            <img src="{{ Storage::url($tenant->branding->logo_path) }}" alt="{{ $tenant->name }}" class="h-8 w-8 rounded">
        @else
            <div class="h-8 w-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">{{ substr($tenant->name ?? 'T', 0, 1) }}</span>
            </div>
        @endif
        <span class="text-lg font-semibold text-gray-900">{{ $tenant->name ?? 'TalentLit' }}</span>
    </div>

    <!-- User Menu -->
    <div class="flex items-center space-x-2">
        <span class="text-sm text-gray-600">{{ Auth::user()->name }}</span>
        @php
            $userRole = \DB::table('custom_user_roles')
                ->where('user_id', Auth::id())
                ->where('tenant_id', $tenant->id ?? '')
                ->value('role_name');
        @endphp
        @if($userRole)
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                @if($userRole === 'Owner') bg-purple-100 text-purple-800
                @elseif($userRole === 'Admin') bg-blue-100 text-blue-800
                @elseif($userRole === 'Recruiter') bg-green-100 text-green-800
                @elseif($userRole === 'Hiring Manager') bg-yellow-100 text-yellow-800
                @else bg-gray-100 text-gray-800 @endif">
                {{ $userRole }}
            </span>
        @endif
    </div>
</div>
