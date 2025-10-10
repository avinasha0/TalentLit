<?php
    $tenant = tenant() ?? $tenant ?? null;
    $branding = $tenant ? $tenant->branding : null;
    $currentRoute = request()->route() ? request()->route()->getName() : '';
    $isDashboard = $currentRoute === 'tenant.dashboard';
    $isJobs = str_starts_with($currentRoute, 'tenant.jobs');
    $isCandidates = str_starts_with($currentRoute, 'tenant.candidates');
    $isInterviews = str_starts_with($currentRoute, 'tenant.interviews');
    $isAnalytics = str_starts_with($currentRoute, 'tenant.analytics');
    $isSettings = str_starts_with($currentRoute, 'tenant.settings');
?>

<div x-data="{ 
    sidebarOpen: $store.sidebar?.open ?? (window.innerWidth >= 1024),
    jobsOpen: false,
    candidatesOpen: false,
    settingsOpen: false
}" 
x-show="sidebarOpen"
x-init="sidebarOpen = $store.sidebar?.open ?? (window.innerWidth >= 1024); $watch('$store.sidebar.open', value => sidebarOpen = value)" 
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="-translate-x-full"
x-transition:enter-end="translate-x-0"
x-transition:leave="transition ease-in duration-300"
x-transition:leave-start="translate-x-0"
x-transition:leave-end="-translate-x-full"
class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-900 text-white transform lg:translate-x-0 lg:static lg:inset-0"
:class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
@click.away="if (window.innerWidth < 1024) $store.sidebar.toggle()"
@click.stop>
    
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
        <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">
            <?php if($tenant): ?>
            <!-- Dashboard -->
    <a href="<?php echo e(route('tenant.dashboard', $tenant->slug)); ?>"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isDashboard ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"></path>
      </svg>
                Dashboard
            </a>

            <!-- Jobs -->
            <div>
                <button @click="jobsOpen = !jobsOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                        Jobs
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': jobsOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="jobsOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
    <a href="<?php echo e(route('tenant.jobs.index', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(str_starts_with($currentRoute, 'tenant.jobs.index') ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        All Jobs
                    </a>
                    <?php if (app('App\Support\CustomPermissionChecker')->check('create_jobs', $tenant)): ?>
                    <a href="<?php echo e(route('tenant.jobs.create', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($currentRoute === 'tenant.jobs.create' ? 'bg-gray-700 text-white' : ''); ?>">
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
                <button @click="candidatesOpen = !candidatesOpen" 
                        class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Candidates
                    </div>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': candidatesOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div x-show="candidatesOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="ml-8 mt-2 space-y-1">
    <a href="<?php echo e(route('tenant.candidates.index', $tenant->slug)); ?>"
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(str_starts_with($currentRoute, 'tenant.candidates.index') ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        All Candidates
                    </a>
                </div>
            </div>


            <!-- Pipeline (contextual - only show if inside a job) -->
            <?php if(request()->route('job')): ?>
            <a href="<?php echo e(route('tenant.jobs.pipeline', [$tenant->slug, request()->route('job')->id])); ?>" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e(str_starts_with($currentRoute, 'tenant.jobs.pipeline') ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Pipeline
            </a>
            <?php endif; ?>

            <!-- Interviews -->
            <a href="<?php echo e(route('tenant.interviews.index', $tenant->slug)); ?>" 
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isInterviews ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
      </svg>
                Interviews
    </a>


            <!-- Analytics -->
            <?php if (app('App\Support\CustomPermissionChecker')->check('view_analytics', $tenant)): ?>
    <a href="<?php echo e(route('tenant.analytics.index', $tenant->slug)); ?>"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-200 <?php echo e($isAnalytics ? 'bg-purple-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white'); ?>">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                Analytics
            </a>
            <?php endif; ?>

            <!-- Careers Site (Public Page) -->
            <a href="<?php echo e(route('careers.index', $tenant->slug)); ?>" 
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

            <!-- Settings (Owner/Admin only) -->
            <?php if (app('App\Support\CustomPermissionChecker')->check('manage_settings', $tenant)): ?>
            <div>
                <button @click="settingsOpen = !settingsOpen" 
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
                    <a href="<?php echo e(route('tenant.settings.careers', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($currentRoute === 'tenant.settings.careers' ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                        Careers Branding
                    </a>
                    <a href="<?php echo e(route('tenant.settings.team', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($currentRoute === 'tenant.settings.team' ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        Team Management
                    </a>
                    <a href="<?php echo e(route('tenant.settings.roles', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($currentRoute === 'tenant.settings.roles' ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Roles & Permissions
                    </a>
                    <?php if (app('App\Support\CustomPermissionChecker')->check('manage_users', $tenant)): ?>
                    <a href="<?php echo e(route('subscription.show', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e(str_starts_with($currentRoute, 'subscription') ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Subscription
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('tenant.settings.general', $tenant->slug)); ?>" 
                       class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200 <?php echo e($currentRoute === 'tenant.settings.general' ? 'bg-gray-700 text-white' : ''); ?>">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
      </svg>
                        General Settings
    </a>
                </div>
            </div>
            <?php endif; ?>
            <?php endif; ?>
  </nav>

        <!-- Footer -->
        <?php if(Auth::check()): ?>
        <div class="border-t border-gray-700 p-4">
            <div class="flex items-center space-x-3 mb-4">
                <div class="h-8 w-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate"><?php echo e(Auth::user()->name); ?></p>
                    <p class="text-xs text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></p>
                </div>
            </div>
            
            <div class="space-y-1">
                <a href="<?php echo e(route('account.profile', $tenant->slug)); ?>" 
                   class="flex items-center px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile
                </a>
                
                <form method="POST" action="<?php echo e(route('logout')); ?>" class="w-full">
                    <?php echo csrf_field(); ?>
                    <button type="submit" 
                            class="flex items-center w-full px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-gray-700 hover:text-white transition-colors duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/sidebar.blade.php ENDPATH**/ ?>