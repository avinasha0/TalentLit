<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => null]
    ];
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tenant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant)]); ?>
     <?php $__env->slot('breadcrumbs', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal360d002b1b676b6f84d43220f22129e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal360d002b1b676b6f84d43220f22129e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumbs','data' => ['items' => $breadcrumbs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $attributes = $__attributesOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__attributesOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $component = $__componentOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__componentOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">Job Openings</h1>
                        <p class="mt-1 text-sm text-black">Manage job postings and applications</p>
                        <?php if($maxJobs !== -1): ?>
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-xs text-gray-600">
                                    <?php echo e($currentJobCount); ?> / <?php echo e($maxJobs); ?> jobs
                                </span>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" 
                                         style="width: <?php echo e($maxJobs > 0 ? min(100, ($currentJobCount / $maxJobs) * 100) : 0); ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <?php if($canAddJobs): ?>
                            <a href="<?php echo e(route('tenant.jobs.create', $tenant->slug)); ?>"
                               class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Create Job
                            </a>
                        <?php else: ?>
                            <button onclick="showJobLimitReached()"
                                    class="bg-gray-400 text-white px-4 py-2 rounded-md cursor-not-allowed opacity-75">
                                Create Job
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="py-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-black mb-1">Search</label>
                        <input type="text" 
                               name="keyword" 
                               id="keyword"
                               value="<?php echo e(request('keyword')); ?>"
                               placeholder="Job title or keywords..."
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-black mb-1">Status</label>
                        <select name="status" 
                                id="status"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Statuses</option>
                            <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($status); ?>" <?php echo e(request('status') == $status ? 'selected' : ''); ?>>
                                    <?php echo e(ucfirst($status)); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="department" class="block text-sm font-medium text-black mb-1">Department</label>
                        <select name="department" 
                                id="department"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Departments</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($dept); ?>" <?php echo e(request('department') == $dept ? 'selected' : ''); ?>>
                                    <?php echo e($dept); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-black mb-1">Location</label>
                        <select name="location" 
                                id="location"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            <option value="">All Locations</option>
                            <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($loc); ?>" <?php echo e(request('location') == $loc ? 'selected' : ''); ?>>
                                    <?php echo e($loc); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" 
                                class="w-full bg-blue-600 text-white px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Filter Jobs
                        </button>
                    </div>
                </form>
            </div>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>

        <!-- Jobs Table -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg leading-6 font-medium text-black">
                    <?php echo e($jobs->total()); ?> job<?php echo e($jobs->total() !== 1 ? 's' : ''); ?> found
                </h3>
            </div>

            <?php if($jobs->count() > 0): ?>
                <!-- Mobile: Horizontal scroll container -->
                <div class="block lg:hidden overflow-x-auto">
                    <div class="min-w-full">
                        <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-b border-gray-200 dark:border-gray-700 p-4 min-w-[320px]">
                                <!-- Mobile Job Card -->
                                <div class="space-y-3">
                                    <!-- Job Title -->
                                    <div>
                                        <h4 class="text-lg font-medium text-black">
                                            <a href="<?php echo e(route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="hover:text-blue-600">
                                                <?php echo e($job->title); ?>

                                            </a>
                                        </h4>
                                    </div>
                                    
                                    <!-- Job Details -->
                                    <div class="space-y-2">
                                        <div class="flex items-center text-sm text-black">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            <span class="truncate"><?php echo e($job->department->name); ?></span>
                                        </div>
                                        <div class="flex items-center text-sm text-black">
                                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span class="truncate"><?php echo e($job->location->name); ?></span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="px-2 py-1 bg-blue-600 text-white rounded-full text-xs">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?>

                                            </span>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                <?php if($job->status === 'published'): ?> bg-green-600 text-white
                                                <?php elseif($job->status === 'draft'): ?> bg-yellow-600 text-white
                                                <?php else: ?> bg-red-600 text-white
                                                <?php endif; ?>">
                                                <?php echo e(ucfirst($job->status)); ?>

                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Stats and Actions -->
                                    <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                                        <div class="text-sm text-black">
                                            <div><?php echo e($job->openings_count); ?> opening<?php echo e($job->openings_count !== 1 ? 's' : ''); ?></div>
                                            <div><?php echo e($job->applications->count()); ?> application<?php echo e($job->applications->count() !== 1 ? 's' : ''); ?></div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <a href="<?php echo e(route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>"
                                               class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                View
                                            </a>
                                            <a href="<?php echo e(route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>"
                                               class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Actions -->
                                    <div class="flex items-center justify-between">
                                        <?php if($job->published_at): ?>
                                            <div class="text-xs text-black">
                                                Published <?php echo e($job->published_at->format('M j, Y')); ?>

                                            </div>
                                        <?php else: ?>
                                            <div></div>
                                        <?php endif; ?>
                                        
                                        <div class="flex items-center space-x-2">
                                            <?php if($job->status === 'draft'): ?>
                                                <form method="POST" action="<?php echo e(route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                        Publish
                                                    </button>
                                                </form>
                                            <?php elseif($job->status === 'published'): ?>
                                                <form method="POST" action="<?php echo e(route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PATCH'); ?>
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Close
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <form method="POST" action="<?php echo e(route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Desktop: Original table layout -->
                <ul class="hidden lg:block divide-y divide-gray-200 dark:divide-gray-700">
                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="px-4 py-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-lg font-medium text-black truncate">
                                                <a href="<?php echo e(route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>">
                                                    <?php echo e($job->title); ?>

                                                </a>
                                            </h4>
                                            <div class="mt-1 flex items-center space-x-4 text-sm text-black">
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                    </svg>
                                                    <?php echo e($job->department->name); ?>

                                                </span>
                                                <span class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    <?php echo e($job->location->name); ?>

                                                </span>
                                                <span class="px-2 py-1 bg-blue-600 text-white rounded-full text-xs">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?>

                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <div class="text-right">
                                                <div class="text-sm font-medium text-black">
                                                    <?php echo e($job->openings_count); ?> opening<?php echo e($job->openings_count !== 1 ? 's' : ''); ?>

                                                </div>
                                                <div class="text-sm text-black">
                                                    <?php echo e($job->applications->count()); ?> application<?php echo e($job->applications->count() !== 1 ? 's' : ''); ?>

                                                </div>
                                            </div>
                                            <div class="flex flex-col items-end space-y-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                                    <?php if($job->status === 'published'): ?> bg-green-600 text-white
                                                    <?php elseif($job->status === 'draft'): ?> bg-yellow-600 text-white
                                                    <?php else: ?> bg-red-600 text-white
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst($job->status)); ?>

                                                </span>
                                                <?php if($job->published_at): ?>
                                                    <div class="text-xs text-black">
                                                        Published <?php echo e($job->published_at->format('M j, Y')); ?>

                                                    </div>
                                                <?php endif; ?>
                                                
                                                <!-- Action Buttons -->
                                                <div class="flex items-center space-x-2">
                                                    <a href="<?php echo e(route('tenant.jobs.show', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>"
                                                       class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                        View
                                                    </a>
                                                    <a href="<?php echo e(route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>"
                                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium">
                                                        Edit
                                                    </a>
                                                    
                                                    <?php if($job->status === 'draft'): ?>
                                                        <form method="POST" action="<?php echo e(route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>
                                                            <button type="submit" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                                                Publish
                                                            </button>
                                                        </form>
                                                    <?php elseif($job->status === 'published'): ?>
                                                        <form method="POST" action="<?php echo e(route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PATCH'); ?>
                                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                                Close
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
                                                    
                                                    <form method="POST" action="<?php echo e(route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" class="inline"
                                                          onsubmit="return confirm('Are you sure you want to delete this job?')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <?php echo e($jobs->appends(request()->query())->links()); ?>

                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-black">No jobs found</h3>
                    <p class="mt-1 text-sm text-black">Try adjusting your search criteria.</p>
                </div>
            <?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
    </div>

    <script>
        function showJobLimitReached() {
            const currentCount = <?php echo e($currentJobCount); ?>;
            const maxJobs = <?php echo e($maxJobs === -1 ? 'Infinity' : $maxJobs); ?>;
            
            let message = `You've reached the job openings limit for your current plan. `;
            if (maxJobs !== Infinity) {
                message += `You currently have ${currentCount} job openings out of ${maxJobs} allowed. `;
            }
            message += `Please upgrade your subscription plan to create more job openings.`;
            
            alert(message);
            
            // Redirect to tenant-specific subscription page
            if (confirm('Would you like to view available plans?')) {
                window.location.href = '<?php echo e(route("subscription.show", $tenant->slug)); ?>';
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/jobs/index.blade.php ENDPATH**/ ?>