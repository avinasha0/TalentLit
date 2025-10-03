
<div data-mobile-overlay class="fixed inset-0 bg-black bg-opacity-50 hidden lg:hidden"></div>


<div data-mobile-drawer class="fixed top-0 left-0 w-64 h-full bg-white border-r border-gray-300 transform -translate-x-full transition-transform duration-300 lg:hidden flex flex-col">
    
    
    <div class="p-4 border-b border-gray-300 flex-shrink-0">
        <div class="flex justify-between items-center">
            <span class="font-bold text-lg"><?php echo e($tenant->name ?? 'TalentLit'); ?></span>
            <button data-mobile-close class="text-gray-600 hover:text-gray-800">✕</button>
        </div>
    </div>

    
    <nav class="flex-1 overflow-y-auto p-4">
        <?php if($tenant): ?>
        <div class="space-y-2">
            <a href="<?php echo e(route('tenant.dashboard', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-2 text-gray-700 hover:text-blue-600">Dashboard</a>
            
            
            <div class="mt-4">
                <button data-mobile-jobs-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Jobs</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-jobs-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-jobs-content class="ml-4 space-y-1 hidden">
                    <a href="<?php echo e(route('tenant.jobs.index', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">All Jobs</a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create jobs')): ?>
                    <a href="<?php echo e(route('tenant.jobs.create', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Create Job</a>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="mt-4">
                <button data-mobile-candidates-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Candidates</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-candidates-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-candidates-content class="ml-4 space-y-1 hidden">
                    <a href="<?php echo e(route('tenant.candidates.index', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">All Candidates</a>
                </div>
            </div>

            <?php if(request()->route('job')): ?>
            <a href="<?php echo e(route('tenant.jobs.pipeline', [$tenant->slug ?? tenant()->slug, request()->route('job')->id])); ?>" class="block py-2 text-gray-700 hover:text-blue-600">Pipeline</a>
            <?php endif; ?>

            <a href="<?php echo e(route('tenant.interviews.index', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-2 text-gray-700 hover:text-blue-600">Interviews</a>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view analytics')): ?>
            <a href="<?php echo e(route('tenant.analytics.index', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-2 text-gray-700 hover:text-blue-600">Analytics</a>
            <?php endif; ?>

            <a href="<?php echo e(route('careers.index', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" target="_blank" class="block py-2 text-gray-700 hover:text-blue-600">Careers Site</a>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', ['Owner', 'Admin'])): ?>
            
            <div class="mt-4">
                <button data-mobile-settings-toggle class="flex items-center justify-between w-full text-left text-sm font-semibold text-gray-500 mb-2 hover:text-gray-700">
                    <span>Settings</span>
                    <svg class="w-4 h-4 transition-transform duration-200" data-mobile-settings-arrow fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div data-mobile-settings-content class="ml-4 space-y-1 hidden">
                    <a href="<?php echo e(route('tenant.settings.careers', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Careers Branding</a>
                    <a href="<?php echo e(route('tenant.settings.team', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Team Management</a>
                    <a href="<?php echo e(route('tenant.settings.roles', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Roles & Permissions</a>
                    <a href="<?php echo e(route('tenant.settings.general', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">General Settings</a>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                    <a href="<?php echo e(route('subscription.show', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Subscription</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </nav>

    
    <div class="flex-shrink-0 p-4 border-t border-gray-300 bg-white">
        <div class="mb-2">
            <div class="text-sm font-medium"><?php echo e(Auth::user()->name); ?></div>
            <div class="text-xs text-gray-500"><?php echo e(Auth::user()->email); ?></div>
        </div>
        <a href="<?php echo e(route('account.profile', ['tenant' => $tenant->slug ?? tenant()->slug])); ?>" class="block py-1 text-gray-700 hover:text-blue-600">Profile</a>
        <form id="logout-form-mobile" action="<?php echo e(route('logout')); ?>" method="POST"><?php echo csrf_field(); ?></form>
        <button data-mobile-logout class="text-red-600 hover:text-red-800">Sign out</button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\layouts\partials\mobile-menu.blade.php ENDPATH**/ ?>