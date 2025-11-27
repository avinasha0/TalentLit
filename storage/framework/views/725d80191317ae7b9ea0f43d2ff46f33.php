<?php
    $tenant = tenant() ?? $tenant ?? null;
    
    // Ensure tenant is an object, not a string
    if (is_string($tenant)) {
        $tenant = \App\Models\Tenant::where('slug', $tenant)->first();
    }
    
    $branding = $tenant ? $tenant->branding : null;
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    // Handle both tenant.* and subdomain.* routes
    $isDashboard = in_array($currentRoute, ['tenant.dashboard', 'subdomain.dashboard']);
    $isRecruiting = str_starts_with($currentRoute, 'tenant.recruiting') || str_starts_with($currentRoute, 'subdomain.recruiting');
    $isJobs = str_starts_with($currentRoute, 'tenant.jobs') || str_starts_with($currentRoute, 'subdomain.jobs');
    $isCandidates = str_starts_with($currentRoute, 'tenant.candidates') || str_starts_with($currentRoute, 'subdomain.candidates');
    $isInterviews = str_starts_with($currentRoute, 'tenant.interviews') || str_starts_with($currentRoute, 'subdomain.interviews');
    $isOffers = str_starts_with($currentRoute, 'tenant.offers') || str_starts_with($currentRoute, 'subdomain.offers');
    $isAnalytics = str_starts_with($currentRoute, 'tenant.analytics') || str_starts_with($currentRoute, 'subdomain.analytics');
    $isReporting = $isAnalytics; // Reporting includes Analytics
    $isSettings = str_starts_with($currentRoute, 'tenant.settings') || str_starts_with($currentRoute, 'subdomain.settings');
    $isEmployeeOnboarding = str_starts_with($currentRoute, 'tenant.employee-onboarding') || str_starts_with($currentRoute, 'subdomain.employee-onboarding');

    $currentPlan = $tenant && is_object($tenant) ? $tenant->activeSubscription?->plan : null;
    $analyticsLocked = !$currentPlan || !$currentPlan->analytics_enabled || $currentPlan->slug === 'free';
    $analyticsUpgradeUrl = $tenant && is_object($tenant) ? tenantRoute('subscription.show', $tenant->slug) : '#';
?>

<div x-data='{ 
    sidebarOpen: $store.sidebar?.open ?? (window.innerWidth >= 1024),
    recruitingOpen: <?php echo e(($isRecruiting || $isJobs || $isCandidates || $isInterviews || $isOffers) ? 'true' : 'false'); ?>,
    jobsOpen: false,
    candidatesOpen: <?php echo e(($isCandidates || $isInterviews || $isOffers) ? 'true' : 'false'); ?>,
    reportingOpen: <?php echo e($isReporting ? 'true' : 'false'); ?>,
    settingsOpen: <?php echo e($isSettings ? 'true' : 'false'); ?>,
    employeeOnboardingOpen: <?php echo e($isEmployeeOnboarding ? 'true' : 'false'); ?>,
    analyticsLocked: <?php echo e($analyticsLocked ? 'true' : 'false'); ?>

}'
x-show="sidebarOpen"
x-init="
    sidebarOpen = $store.sidebar?.open ?? (window.innerWidth >= 1024);
    $watch('$store.sidebar.open', value => sidebarOpen = value);
    console.log('Sidebar initialized - recruitingOpen:', recruitingOpen, 'settingsOpen:', settingsOpen);
" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="-translate-x-full"
x-transition:enter-end="translate-x-0"
x-transition:leave="transition ease-in duration-300"
x-transition:leave-start="translate-x-0"
x-transition:leave-end="-translate-x-full"
class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white lg:translate-x-0"
:class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
@click.away="if (window.innerWidth < 1024) $store.sidebar.toggle()">
    
    <!-- Mobile overlay -->
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 lg:hidden" 
         x-show="sidebarOpen" 
         x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
         @click="$store.sidebar.toggle()"></div>

    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-700">
            <div class="flex items-center space-x-3">
                <?php if($branding && $branding->logo_path): ?>
                    <img src="<?php echo e(Storage::url($branding->logo_path)); ?>" alt="<?php echo e($tenant->name); ?>" class="h-8 w-8 rounded">
                <?php else: ?>
                    <div class="h-8 w-8 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm"><?php echo e(substr($tenant->name ?? 'T', 0, 1)); ?></span>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-lg font-semibold text-white"><?php echo e($tenant->name ?? 'TalentLit'); ?></h1>
                    <p class="text-xs text-gray-400">ATS Platform</p>
                </div>
            </div>
            <!-- Close button for mobile -->
            <button @click="if (window.innerWidth < 1024) $store.sidebar.toggle()" class="lg:hidden text-gray-400 hover:text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
  </div>

        <!-- Navigation -->
        <nav class="flex-1 min-h-0 px-4 py-4 space-y-2 overflow-y-auto">
            <?php if($tenant && is_object($tenant)): ?>
            <!-- Dashboard -->
    <a href="<?php echo e(tenantRoute('tenant.dashboard', $tenant->slug)); ?>"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isDashboard ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
      </svg>
                Dashboard
            </a>

            <!-- Recruiting -->
            <div>
                <button type="button" @click="recruitingOpen = !recruitingOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e(($isRecruiting || $isJobs || $isCandidates || $isInterviews || $isOffers || $isAnalytics) ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Recruiting
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': recruitingOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="recruitingOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                    <!-- Jobs -->
                    <div>
                        <button type="button" @click="jobsOpen = !jobsOpen" 
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Jobs
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': jobsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="jobsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                            <a href="<?php echo e(tenantRoute('tenant.jobs.index', $tenant->slug)); ?>"
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.jobs.index') || str_starts_with($currentRoute, 'subdomain.jobs.index')) ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                All Jobs
                            </a>
                            <?php if (app('App\Support\CustomPermissionChecker')->check('create_jobs', $tenant)): ?>
                            <a href="<?php echo e(tenantRoute('tenant.jobs.create', $tenant->slug)); ?>" 
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(in_array($currentRoute, ['tenant.jobs.create', 'subdomain.jobs.create']) ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create Job
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Candidates -->
                    <div>
                        <button type="button" @click="candidatesOpen = !candidatesOpen" 
                                class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                </svg>
                                Candidates
                            </div>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': candidatesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="candidatesOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                            <a href="<?php echo e(tenantRoute('tenant.candidates.index', $tenant->slug)); ?>"
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.candidates.index') || str_starts_with($currentRoute, 'subdomain.candidates.index')) ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                All Candidates
                            </a>
                            
                            <!-- Interviews -->
                            <a href="<?php echo e(tenantRoute('tenant.interviews.index', $tenant->slug)); ?>" 
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($isInterviews ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Interviews
                            </a>

                            <!-- Offer -->
                            <a href="<?php echo e(tenantRoute('tenant.offers.index', $tenant->slug)); ?>" 
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($isOffers ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Offer
                            </a>
                        </div>
                    </div>

                    <!-- Pipeline (contextual - only show if inside a job) -->
                    <?php if(request()->route('job')): ?>
                    <?php
                        $jobParam = request()->route('job');
                        $jobId = is_object($jobParam) ? $jobParam->id : $jobParam;
                    ?>
                    <a href="<?php echo e(tenantRoute('tenant.jobs.pipeline', [$tenant->slug, $jobId])); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.jobs.pipeline') || str_starts_with($currentRoute, 'subdomain.jobs.pipeline')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Pipeline
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Onboarding -->
            <div>
                <button type="button" @click="employeeOnboardingOpen = !employeeOnboardingOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isEmployeeOnboarding ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <span>Onboarding</span>
                        <span class="ml-2 px-2 py-0.5 text-xs font-semibold rounded-full bg-orange-600/30 text-orange-300 border border-orange-500/50">Under Development</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': employeeOnboardingOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="employeeOnboardingOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                    <?php
                        $permissionService = app(\App\Services\PermissionService::class);
                        $user = auth()->user();
                        $userRole = $user ? $permissionService->getUserRole($user->id, $tenant->id) : null;
                        $allowedRoles = ['Owner', 'Admin', 'Recruiter', 'Hiring Manager'];
                        $canViewDashboard = $userRole && in_array($userRole, $allowedRoles);
                    ?>
                    <?php if($canViewDashboard): ?>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.dashboard', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.dashboard') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.dashboard')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.all', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.all') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.all')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        All Onboardings
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.new', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.new') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.new')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        New Onboarding
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.tasks', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.tasks') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.tasks')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                        Tasks
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.documents', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.documents') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.documents')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Documents
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.it-assets', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.it-assets') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.it-assets')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                        </svg>
                        IT & Assets
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.employee-onboarding.approvals', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e((str_starts_with($currentRoute, 'tenant.employee-onboarding.approvals') || str_starts_with($currentRoute, 'subdomain.employee-onboarding.approvals')) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Approvals
                    </a>
                </div>
            </div>

            <!-- Careers Site (Public Page) -->
            <a href="<?php echo e(tenantRoute('careers.index', $tenant->slug)); ?>" 
               target="_blank"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 text-gray-300 hover:bg-gray-700 hover:text-white">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
                Careers Site
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                </svg>
            </a>

            <!-- Reporting -->
            <div>
                <button type="button" @click="reportingOpen = !reportingOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isReporting ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Reporting
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': reportingOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="reportingOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                    <!-- Analytics -->
                    <?php if (app('App\Support\CustomPermissionChecker')->check('view_analytics', $tenant)): ?>
                        <?php if($analyticsLocked): ?>
                            <a href="<?php echo e($analyticsUpgradeUrl); ?>"
                               class="flex items-center px-3 py-2 text-sm text-gray-400 rounded-lg border border-dashed border-purple-500/40 hover:bg-gray-800 hover:text-white transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-gray-900">
                                <svg class="w-4 h-4 mr-3 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="flex-1 text-left">Analytics</span>
                                <span class="ml-3 px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-600/20 text-purple-200">Pro+</span>
                            </a>
                        <?php else: ?>
                            <a href="<?php echo e(tenantRoute('tenant.analytics.index', $tenant->slug)); ?>"
                               class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($isAnalytics ? 'bg-gray-700 text-white' : ''); ?>">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Analytics
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Settings (Owner/Admin only) -->
            <?php if (app('App\Support\CustomPermissionChecker')->check('manage_settings', $tenant)): ?>
            <div>
                <button type="button" @click="settingsOpen = !settingsOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Settings
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': settingsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="settingsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
                    <a href="<?php echo e(tenantRoute('tenant.settings.general', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(in_array($currentRoute, ['tenant.settings.general', 'subdomain.settings.general']) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
                        General Settings
    </a>
                    <?php if (app('App\Support\CustomPermissionChecker')->check('manage_users', $tenant)): ?>
                    <a href="<?php echo e(tenantRoute('subscription.show', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(str_starts_with($currentRoute, 'subscription') ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Subscription
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(tenantRoute('tenant.settings.careers', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(in_array($currentRoute, ['tenant.settings.careers', 'subdomain.settings.careers']) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                        Careers Branding
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.settings.team', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(in_array($currentRoute, ['tenant.settings.team', 'subdomain.settings.team']) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Team Management
                    </a>
                    <a href="<?php echo e(tenantRoute('tenant.settings.roles', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(in_array($currentRoute, ['tenant.settings.roles', 'subdomain.settings.roles']) ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Roles & Permissions
                    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php else: ?>
            <!-- Fallback when tenant is not available -->
            <div class="text-center py-8">
                <div class="text-gray-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <p>Unable to load navigation</p>
                </div>
            </div>
            <?php endif; ?>
  </nav>

        <!-- Footer -->
        <div class="border-t border-gray-700 p-3 sm:p-4 mt-auto flex-shrink-0">
            <p class="text-xs sm:text-sm font-medium text-white text-center break-words">TalentLit - HR Recruit Tool</p>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/sidebar.blade.php ENDPATH**/ ?>