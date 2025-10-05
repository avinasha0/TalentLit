{{-- Simple Mobile Overlay --}}
<div data-mobile-overlay class="fixed inset-0 bg-black bg-opacity-50 hidden lg:hidden"></div>

{{-- Simple Mobile Drawer --}}
<div data-mobile-drawer class="fixed top-0 left-0 w-64 h-full bg-white border-r border-gray-300 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
    
    {{-- Header --}}
    <div class="p-4 border-b border-gray-300 flex-shrink-0">
        <div class="flex justify-between items-center">
            <span class="font-bold text-lg">{{ $tenant->name ?? 'TalentLit' }}</span>
            <button data-mobile-close class="text-gray-600 hover:text-gray-800">✕</button>
        </div>
    </div>

    {{-- Navigation - Scrollable --}}
    <nav class="flex-1 overflow-y-auto p-4">
        @if($tenant)
        <div class="space-y-2">
            <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
            
            {{-- Jobs Section --}}
            <div class="mt-4">
                <button data-mobile-jobs-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Jobs</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-jobs-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-jobs-content class="ml-4 space-y-1 hidden">
                    <a href="{{ route('tenant.jobs.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">All Jobs</a>
                    @customCan('create_jobs', $tenant ?? tenant())
                    <a href="{{ route('tenant.jobs.create', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Create Job</a>
                    @endcustomCan
                </div>
            </div>

            {{-- Candidates Section --}}
            <div class="mt-4">
                <button data-mobile-candidates-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Candidates</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-candidates-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-candidates-content class="ml-4 space-y-1 hidden">
                    <a href="{{ route('tenant.candidates.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">All Candidates</a>
                </div>
            </div>

            @if(request()->route('job'))
            <a href="{{ route('tenant.jobs.pipeline', [$tenant->slug ?? tenant()->slug, request()->route('job')->id]) }}" class="block py-2 text-gray-700 hover:text-blue-600">Pipeline</a>
            @endif

            <a href="{{ route('tenant.interviews.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-2 text-gray-700 hover:text-blue-600">Interviews</a>

            @customCan('view_analytics', $tenant ?? tenant())
            <a href="{{ route('tenant.analytics.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-2 text-gray-700 hover:text-blue-600">Analytics</a>
            @endcustomCan

            <a href="{{ route('careers.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" target="_blank" class="block py-2 text-gray-700 hover:text-blue-600">Careers Site</a>

            @customCan('manage_settings', $tenant ?? tenant())
            {{-- Settings Section --}}
            <div class="mt-4">
                <button data-mobile-settings-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Settings</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-settings-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-settings-content class="ml-4 space-y-1 hidden">
                    <a href="{{ route('tenant.settings.careers', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Careers Branding</a>
                    <a href="{{ route('tenant.settings.team', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Team Management</a>
                    <a href="{{ route('tenant.settings.roles', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Roles & Permissions</a>
                    <a href="{{ route('tenant.settings.general', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">General Settings</a>
                    @customCan('manage_users', $tenant ?? tenant())
                    <a href="{{ route('subscription.show', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Subscription</a>
                    @endcustomCan
                </div>
            </div>
            @endcustomCan
        </div>
        @endif
    </nav>

    {{-- Footer - Fixed at bottom --}}
    <div class="flex-shrink-0 p-4 border-t border-gray-300 bg-white">
        <div class="mb-2">
            <div class="text-sm font-medium">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
        </div>
        <a href="{{ route('account.profile', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Profile</a>
        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">@csrf</form>
        <button data-mobile-logout class="text-red-600 hover:text-red-800">Sign out</button>
    </div>
</div>
