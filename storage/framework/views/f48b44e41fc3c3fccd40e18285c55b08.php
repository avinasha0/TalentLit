<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
        ['label' => $job->title, 'url' => null]
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
                        <h1 class="text-2xl font-bold text-black"><?php echo e($job->title); ?></h1>
                    </div>
                    <div>
                        <a href="<?php echo e(route('tenant.jobs.index', ['tenant' => $tenant->slug])); ?>" 
                           class="text-blue-600 font-medium">
                            Back to Jobs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Job Details -->
            <div class="lg:col-span-2">
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
                        <!-- Job Header -->
                        <div class="px-6 py-8 border-b border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-4 text-sm text-black">
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
                                        <?php echo e($job->globalLocation?->name ?? $job->city?->formatted_location ?? 'No location specified'); ?>

                                    </span>
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?>

                                    </span>
                                </div>
                                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full
                                    <?php if($job->status === 'published'): ?> bg-green-100 text-green-800
                                    <?php elseif($job->status === 'draft'): ?> bg-yellow-100 text-yellow-800
                                    <?php else: ?> bg-red-100 text-red-800
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst($job->status)); ?>

                                </span>
                            </div>
                            <div class="text-lg text-gray-600">
                                <?php echo e($job->openings_count); ?> opening<?php echo e($job->openings_count !== 1 ? 's' : ''); ?> available
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="px-6 py-8">
                            <?php if($job->description): ?>
                                <div class="prose max-w-none">
                                    <?php echo nl2br(e($job->description)); ?>

                                </div>
                            <?php else: ?>
                                <p class="text-gray-600">No job description available.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Applications -->
                        <?php if($job->applications->count() > 0): ?>
                            <div class="px-6 py-8 border-t border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Applications (<?php echo e($job->applications->count()); ?>)
                                </h3>
                                <div class="space-y-3">
                                    <?php $__currentLoopData = $job->applications->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <div class="font-medium text-gray-900">
                                                    <?php echo e($application->candidate->full_name); ?>

                                                </div>
                                                <div class="text-sm text-gray-600">
                                                    <?php echo e($application->candidate->primary_email); ?>

                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-sm text-gray-600">
                                                    Applied <?php echo e($application->applied_at->format('M j, Y')); ?>

                                                </div>
                                                <?php if($application->currentStage): ?>
                                                    <div class="text-sm font-medium text-blue-600">
                                                        <?php echo e($application->currentStage->name); ?>

                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($job->applications->count() > 5): ?>
                                        <div class="text-center">
                                            <a href="<?php echo e(route('tenant.candidates.index', ['tenant' => tenant()->slug, 'job' => $job->id])); ?>" 
                                               class="text-blue-600 hover:text-blue-800 text-sm">
                                                View all <?php echo e($job->applications->count()); ?> applications
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Job Info -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Information</h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Status</dt>
                                <dd class="text-sm text-gray-900"><?php echo e(ucfirst($job->status)); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Employment Type</dt>
                                <dd class="text-sm text-gray-900"><?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Openings</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($job->openings_count); ?></dd>
                            </div>
                            <?php if($job->published_at): ?>
                                <div>
                                    <dt class="text-sm font-medium text-gray-600">Published</dt>
                                    <dd class="text-sm text-gray-900"><?php echo e($job->published_at->format('M j, Y \a\t g:i A')); ?></dd>
                                </div>
                            <?php endif; ?>
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Created</dt>
                                <dd class="text-sm text-gray-900"><?php echo e($job->created_at->format('M j, Y \a\t g:i A')); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Pipeline Stages -->
                    <?php if($job->jobStages->count() > 0): ?>
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pipeline Stages</h3>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $job->jobStages->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-600"><?php echo e($stage->sort_order); ?></span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900"><?php echo e($stage->name); ?></h4>
                                            <?php if($stage->is_terminal): ?>
                                                <p class="text-xs text-gray-600">Final stage</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
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

                <!-- Actions -->
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
                    <h3 class="text-lg font-semibold text-black mb-4">Actions</h3>
                    <div class="space-y-3 w-full">
                        <a href="<?php echo e(route('tenant.jobs.edit', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" 
                           class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                            Edit Job
                        </a>
                        <a href="<?php echo e(route('careers.show', ['tenant' => $tenant->slug, 'job' => $job->slug])); ?>" 
                           target="_blank"
                           class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                            View Public Job Page
                        </a>
                        <a href="<?php echo e(route('tenant.jobs.pipeline', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" 
                           class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                            View Pipeline
                        </a>
                        <a href="<?php echo e(route('tenant.candidates.index.job', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>" 
                           class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                            View Applications
                        </a>
                        
                        <?php if($job->status === 'draft'): ?>
                            <form method="POST" action="<?php echo e(route('tenant.jobs.publish', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                                    Publish Job
                                </button>
                            </form>
                        <?php elseif($job->status === 'published'): ?>
                            <form method="POST" action="<?php echo e(route('tenant.jobs.close', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                                    Close Job
                                </button>
                            </form>
                        <?php endif; ?>
                        
                        <form method="POST" action="<?php echo e(route('tenant.jobs.destroy', ['tenant' => $tenant->slug, 'job' => $job->id])); ?>"
                              onsubmit="return confirm('Are you sure you want to delete this job?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                                Delete Job
                            </button>
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
            </div>
        </div>
    </div>
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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/jobs/show.blade.php ENDPATH**/ ?>