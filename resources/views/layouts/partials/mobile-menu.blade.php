@php
    // Ensure tenant is an object, not a string
    $tenant = $tenant ?? tenant();
    if (is_string($tenant)) {
        $tenant = \App\Models\Tenant::where('slug', $tenant)->first();
    }

    $currentPlan = $tenant && is_object($tenant) ? $tenant->activeSubscription?->plan : null;
    $mobileAnalyticsLocked = !$currentPlan || !$currentPlan->analytics_enabled || $currentPlan->slug === 'free';
    $analyticsUpgradeUrl = $tenant && is_object($tenant) ? tenantRoute('subscription.show', $tenant->slug) : '#';
@endphp

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
        @if($tenant && is_object($tenant))
        <div class="space-y-2">
            <a href="{{ tenantRoute('tenant.dashboard', $tenant->slug ?? tenant()->slug) }}" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
            
            {{-- Recruiting Section --}}
            <div class="mt-4">
                <button type="button" data-mobile-recruiting-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Recruiting</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-recruiting-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-recruiting-content class="ml-4 space-y-1 hidden">
                    {{-- Jobs Section --}}
                    <div class="mt-2">
                        <button type="button" data-mobile-jobs-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                            <span>Jobs</span>
                            <svg class="w-4 h-4 transition-transform duration-200" data-mobile-jobs-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div data-mobile-jobs-content class="ml-4 space-y-1 hidden">
                            <a href="{{ tenantRoute('tenant.jobs.index', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">All Jobs</a>
                            @customCan('create_jobs', $tenant ?? tenant())
                            <a href="{{ tenantRoute('tenant.jobs.create', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Create Job</a>
                            @endcustomCan
                        </div>
                    </div>

                    {{-- Candidates Section --}}
                    <div class="mt-2">
                        <button type="button" data-mobile-candidates-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                            <span>Candidates</span>
                            <svg class="w-4 h-4 transition-transform duration-200" data-mobile-candidates-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div data-mobile-candidates-content class="ml-4 space-y-1 hidden">
                            <a href="{{ tenantRoute('tenant.candidates.index', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">All Candidates</a>
                        </div>
                    </div>

                    @if(request()->route('job'))
                    @php
                        $jobParam = request()->route('job');
                        $jobId = is_object($jobParam) ? $jobParam->id : $jobParam;
                    @endphp
                    <a href="{{ tenantRoute('tenant.jobs.pipeline', [$tenant->slug ?? tenant()->slug, $jobId]) }}" class="block py-1 text-gray-700 hover:text-blue-600">Pipeline</a>
                    @endif

                    <a href="{{ tenantRoute('tenant.interviews.index', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Interviews</a>

                    @customCan('view_analytics', $tenant ?? tenant())
                        @if($mobileAnalyticsLocked)
                            <button type="button"
                                    data-analytics-upgrade-trigger
                                    class="w-full flex items-center justify-between py-1 text-sm font-medium text-purple-600">
                                <span>Analytics (Pro+)</span>
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5-5 5M6 12h12"></path>
                                </svg>
                            </button>
                        @else
                            <a href="{{ tenantRoute('tenant.analytics.index', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Analytics</a>
                        @endif
                    @endcustomCan
                </div>
            </div>

            <a href="{{ tenantRoute('tenant.employee-onboarding.index', $tenant->slug ?? tenant()->slug) }}" class="flex items-center justify-between py-2 text-gray-700 hover:text-blue-600">
                <span>Employee Onboarding</span>
                <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-600/20 text-yellow-600">Under Development</span>
            </a>

            <a href="{{ tenantRoute('careers.index', $tenant->slug ?? tenant()->slug) }}" target="_blank" class="block py-2 text-gray-700 hover:text-blue-600">Careers Site</a>

            @customCan('manage_settings', $tenant ?? tenant())
            {{-- Settings Section --}}
            <div class="mt-4">
                <button type="button" data-mobile-settings-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Settings</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-settings-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-settings-content class="ml-4 space-y-1 hidden">
                    <a href="{{ tenantRoute('tenant.settings.general', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">General Settings</a>
                    @customCan('manage_users', $tenant ?? tenant())
                    <a href="{{ tenantRoute('subscription.show', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Subscription</a>
                    @endcustomCan
                    <a href="{{ tenantRoute('tenant.settings.careers', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Careers Branding</a>
                    <a href="{{ tenantRoute('tenant.settings.team', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Team Management</a>
                    <a href="{{ tenantRoute('tenant.settings.roles', $tenant->slug ?? tenant()->slug) }}" class="block py-1 text-gray-700 hover:text-blue-600">Roles & Permissions</a>
                </div>
            </div>
            @endcustomCan
        </div>
        @else
        <!-- Fallback when tenant is not available -->
        <div class="text-center py-8">
            <div class="text-gray-400 text-sm">
                <p>Unable to load navigation</p>
            </div>
        </div>
        @endif
    </nav>

    {{-- Footer - Fixed at bottom --}}
    <div class="flex-shrink-0 p-3 sm:p-4 border-t border-gray-300 bg-white">
        <p class="text-xs sm:text-sm font-medium text-gray-700 text-center break-words">TalentLit - HR Recruit Tool</p>
    </div>
</div>
