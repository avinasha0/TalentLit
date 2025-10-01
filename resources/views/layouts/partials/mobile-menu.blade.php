{{-- Mobile-only overlay --}}
<div data-mobile-overlay
     class="fixed inset-0 z-40 bg-black/40 hidden opacity-0 transition-opacity duration-200 lg:hidden"></div>

{{-- Mobile-only drawer --}}
<aside data-mobile-drawer
       class="fixed inset-y-0 left-0 z-50 w-72 max-w-[90vw] -translate-x-full transform bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 shadow-xl transition-transform duration-200 ease-out lg:hidden"
       aria-hidden="true" inert="true" role="dialog" aria-modal="true">

    <div class="h-16 flex items-center px-4 border-b border-gray-200 dark:border-gray-800">
        {{-- Brand --}}
        <div class="flex items-center gap-2">
            @if($tenant && $tenant->branding && $tenant->branding->logo_path)
                <img src="{{ Storage::url($tenant->branding->logo_path) }}" alt="{{ $tenant->name }}" class="h-6 w-auto">
            @else
                <img src="{{ asset('images/logo-talentlit.svg') }}" alt="TalentLit" class="h-6 w-auto">
            @endif
            <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $tenant->name ?? 'TalentLit' }}</span>
        </div>
        <button type="button" data-mobile-close
                class="ml-auto inline-flex items-center justify-center rounded-md p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200"
                aria-label="Close menu">
            ✕
        </button>
    </div>

    <nav class="px-3 py-4 space-y-1 overflow-y-auto">
        @if($tenant)
        {{-- Dashboard --}}
        <a href="{{ route('tenant.dashboard', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
           class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
            Dashboard
        </a>

        {{-- Jobs --}}
        <div>
            <div class="px-3 text-xs uppercase tracking-wide text-gray-400">Jobs</div>
            <a href="{{ route('tenant.jobs.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="mt-1 block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               All Jobs
            </a>
            @can('create jobs')
            <a href="{{ route('tenant.jobs.create', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               Create Job
            </a>
            @endcan
        </div>

        {{-- Candidates --}}
        <div>
            <div class="px-3 text-xs uppercase tracking-wide text-gray-400">Candidates</div>
            <a href="{{ route('tenant.candidates.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="mt-1 block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               All Candidates
            </a>
            <a href="{{ route('tenant.tags.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               Tags & Notes
            </a>
        </div>

        {{-- Pipeline (contextual - only show if inside a job) --}}
        @if(request()->route('job'))
        <a href="{{ route('tenant.jobs.pipeline', [$tenant->slug ?? tenant()->slug, request()->route('job')->id]) }}"
           class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
           Pipeline
        </a>
        @endif

        {{-- Interviews --}}
        <a href="{{ route('tenant.interviews.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
           class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
           Interviews
        </a>

        {{-- Analytics --}}
        @can('view analytics')
        <a href="{{ route('tenant.analytics.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
           class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
           Analytics
        </a>
        @endcan

        {{-- Careers Site --}}
        <a href="{{ route('careers.index', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" target="_blank"
           class="block rounded-md px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
           Careers Site
        </a>

        {{-- Settings (Owner/Admin only) --}}
        @role(['Owner', 'Admin'])
        <div class="pt-2">
            <div class="px-3 text-xs uppercase tracking-wide text-gray-400">Settings</div>
            <a href="{{ route('tenant.settings.careers', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="mt-1 block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               Careers Branding
            </a>
            <a href="{{ route('tenant.settings.team', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               Team Management
            </a>
            <a href="{{ route('tenant.settings.roles', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               Roles & Permissions
            </a>
            <a href="{{ route('tenant.settings.general', ['tenant' => $tenant->slug ?? tenant()->slug]) }}"
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
               General Settings
            </a>
        </div>
        @endrole
        @endif
    </nav>

    {{-- Footer --}}
    <div class="mt-auto p-3 border-t border-gray-200 dark:border-gray-800">
        <div class="flex items-center space-x-3 mb-4">
            <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-sm">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
            </div>
        </div>
        
        <div class="space-y-1">
            <a href="{{ route('account.profile', ['tenant' => $tenant->slug ?? tenant()->slug]) }}" 
               class="block rounded-md px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800">
                Profile
            </a>
            
            <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST">@csrf</form>
            <button type="button" data-mobile-logout
                    class="w-full rounded-md px-3 py-2 text-sm text-left text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                Sign out
            </button>
        </div>
    </div>
</aside>
