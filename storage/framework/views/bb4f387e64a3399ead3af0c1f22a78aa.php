<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Interviews', 'url' => null]
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
                        <h1 class="text-2xl font-bold text-gray-900">Interviews</h1>
                        <p class="mt-1 text-sm text-gray-500">Schedule and manage candidate interviews</p>
                        <?php if($maxInterviews !== -1): ?>
                            <div class="mt-2 flex items-center space-x-2">
                                <span class="text-xs text-gray-600">
                                    <?php echo e($currentInterviewCount); ?> / <?php echo e($maxInterviews); ?> interviews this month
                                </span>
                                <div class="w-20 bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-purple-600 h-1.5 rounded-full transition-all duration-300" 
                                         style="width: <?php echo e($maxInterviews > 0 ? min(100, ($currentInterviewCount / $maxInterviews) * 100) : 0); ?>%"></div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Interview::class)): ?>
                        <?php if($canAddInterviews): ?>
                            <button onclick="openScheduleModal()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Schedule Interview
                            </button>
                        <?php else: ?>
                            <button onclick="showInterviewLimitReached()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-400 cursor-not-allowed opacity-75">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Schedule Interview
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
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
                        <label for="candidate_id" class="block text-sm font-medium text-gray-700 mb-1">Candidate</label>
                        <select name="candidate_id" id="candidate_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Candidates</option>
                                    <?php $__currentLoopData = $candidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($candidate->id); ?>" <?php echo e(request('candidate_id') == $candidate->id ? 'selected' : ''); ?>>
                                            <?php echo e($candidate->first_name); ?> <?php echo e($candidate->last_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                        <label for="job_id" class="block text-sm font-medium text-gray-700 mb-1">Job</label>
                        <select name="job_id" id="job_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Jobs</option>
                                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($job->id); ?>" <?php echo e(request('job_id') == $job->id ? 'selected' : ''); ?>>
                                            <?php echo e($job->title); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="scheduled" <?php echo e(request('status') == 'scheduled' ? 'selected' : ''); ?>>Scheduled</option>
                                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="canceled" <?php echo e(request('status') == 'canceled' ? 'selected' : ''); ?>>Canceled</option>
                                </select>
                            </div>

                            <div>
                        <label for="mode" class="block text-sm font-medium text-gray-700 mb-1">Mode</label>
                        <select name="mode" id="mode" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Modes</option>
                                    <option value="onsite" <?php echo e(request('mode') == 'onsite' ? 'selected' : ''); ?>>Onsite</option>
                                    <option value="remote" <?php echo e(request('mode') == 'remote' ? 'selected' : ''); ?>>Remote</option>
                                    <option value="phone" <?php echo e(request('mode') == 'phone' ? 'selected' : ''); ?>>Phone</option>
                                </select>
                            </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit" 
                                class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors duration-200">
                                    Filter
                                </button>
                        <a href="<?php echo e(route('tenant.interviews.index', ['tenant' => $tenantModel->slug])); ?>" 
                           class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors duration-200 text-center">
                                    Clear
                                </a>
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

                    <!-- Interviews List -->
                    <?php if($interviews->count() > 0): ?>
                        <div class="space-y-4 interviews-list">
                            <?php $__currentLoopData = $interviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => ['class' => 'hover:shadow-md transition-shadow duration-200']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'hover:shadow-md transition-shadow duration-200']); ?>
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900 truncate">
                                            <a href="<?php echo e(route('tenant.candidates.show', ['tenant' => $tenantModel->slug, 'candidate' => $interview->candidate])); ?>" 
                                               class="hover:text-blue-600 transition-colors duration-200">
                                                        <?php echo e($interview->candidate->first_name); ?> <?php echo e($interview->candidate->last_name); ?>

                                                    </a>
                                                </h3>
                                                <?php if($interview->job): ?>
                                            <p class="text-sm text-gray-500 truncate">
                                                        for <?php echo e($interview->job->title); ?>

                                            </p>
                                                <?php endif; ?>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-3 mb-3">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($interview->status === 'scheduled'): ?> bg-blue-100 text-blue-800
                                                    <?php elseif($interview->status === 'completed'): ?> bg-green-100 text-green-800
                                                    <?php else: ?> bg-red-100 text-red-800
                                                    <?php endif; ?>">
                                                    <?php echo e(ucfirst($interview->status)); ?>

                                                    <?php if($interview->status === 'canceled' && $interview->updated_at > $interview->created_at): ?>
                                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Status updated due to stage change">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    <?php endif; ?>
                                                </span>

                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <?php echo e(ucfirst($interview->mode)); ?>

                                                </span>

                                    <span class="text-sm text-gray-500">
                                        <?php echo e($interview->duration_minutes); ?> minutes
                                                </span>
                                            </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <?php echo e($interview->scheduled_at->format('M j, Y g:i A')); ?>

                                    </div>

                                                    <?php if($interview->location): ?>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            </svg>
                                                            <?php echo e($interview->location); ?>

                                        </div>
                                                    <?php endif; ?>

                                                    <?php if($interview->meeting_link): ?>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                            <a href="<?php echo e($interview->meeting_link); ?>" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                                            Join Meeting
                                                        </a>
                                        </div>
                                                    <?php endif; ?>
                                                </div>

                                                <?php if($interview->panelists->count() > 0): ?>
                                    <div class="mt-3">
                                                        <span class="text-sm font-medium text-gray-700">Panelists:</span>
                                                        <div class="flex flex-wrap gap-1 mt-1">
                                                            <?php $__currentLoopData = $interview->panelists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $panelist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                                                    <?php echo e($panelist->name); ?>

                                                                </span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if($interview->notes): ?>
                                    <div class="mt-3">
                                                        <span class="text-sm font-medium text-gray-700">Notes:</span>
                                                        <p class="text-sm text-gray-600 mt-1"><?php echo e(Str::limit($interview->notes, 100)); ?></p>
                                                    </div>
                                                <?php endif; ?>
                                        </div>

                            <div class="flex items-center space-x-2 ml-4">
                                            <a href="<?php echo e(route('tenant.interviews.show', ['tenant' => $tenantModel->slug, 'interview' => $interview])); ?>" 
                                   class="text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                                View
                                            </a>
                                            
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $interview)): ?>
                                                <a href="<?php echo e(route('tenant.interviews.edit', ['tenant' => $tenantModel->slug, 'interview' => $interview])); ?>" 
                                       class="text-gray-600 hover:text-gray-800 text-sm font-medium transition-colors duration-200">
                                                    Edit
                                                </a>
                                            <?php endif; ?>

                                            <?php if($interview->status === 'scheduled'): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $interview)): ?>
                                                    <form method="POST" action="<?php echo e(route('tenant.interviews.cancel', ['tenant' => $tenantModel->slug, 'interview' => $interview])); ?>" class="inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors duration-200" 
                                                                onclick="return confirm('Are you sure you want to cancel this interview?')">
                                                            Cancel
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
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
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <!-- Pagination -->
                        <div class="mt-6">
                            <?php echo e($interviews->links()); ?>

                </div>
                        </div>
                    <?php else: ?>
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
                <?php if (isset($component)) { $__componentOriginal4f22a152e0729cd34293e65bd200d933 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4f22a152e0729cd34293e65bd200d933 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.empty','data' => ['icon' => 'users','title' => 'No interviews found','description' => 'Get started by scheduling your first interview.','action' => null,'actionText' => '']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('empty'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['icon' => 'users','title' => 'No interviews found','description' => 'Get started by scheduling your first interview.','action' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null),'actionText' => '']); ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Interview::class)): ?>
                        <div class="mt-6">
                            <button onclick="openScheduleModal()" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Schedule Interview
                            </button>
                        </div>
                    <?php endif; ?>
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4f22a152e0729cd34293e65bd200d933)): ?>
<?php $attributes = $__attributesOriginal4f22a152e0729cd34293e65bd200d933; ?>
<?php unset($__attributesOriginal4f22a152e0729cd34293e65bd200d933); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4f22a152e0729cd34293e65bd200d933)): ?>
<?php $component = $__componentOriginal4f22a152e0729cd34293e65bd200d933; ?>
<?php unset($__componentOriginal4f22a152e0729cd34293e65bd200d933); ?>
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
        <?php endif; ?>
    </div>

    <!-- Schedule Interview Modal -->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Interview::class)): ?>
    <div id="scheduleModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 transition-opacity duration-300">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-white">Schedule Interview</h3>
                                <p class="text-blue-100 text-sm">Create a new interview for a candidate</p>
                            </div>
                        </div>
                        <button onclick="closeScheduleModal()" class="text-white hover:text-blue-200 transition-colors duration-200 p-1 rounded-lg hover:bg-white hover:bg-opacity-20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Form -->
                <div class="px-6 py-6">
                    <form id="scheduleInterviewForm" class="space-y-8">
                        <?php echo csrf_field(); ?>
                        
                        <!-- Basic Information Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Basic Information</h4>
                                <p class="text-sm text-gray-500">Select the candidate and job for this interview</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Candidate Selection -->
                                <div class="md:col-span-2">
                                    <label for="modal_candidate_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Candidate <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="candidate_id" id="modal_candidate_id" required 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white">
                                            <option value="">Select a candidate</option>
                                            <?php $__currentLoopData = $candidates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $candidate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($candidate->id); ?>">
                                                    <?php echo e($candidate->first_name); ?> <?php echo e($candidate->last_name); ?> (<?php echo e($candidate->primary_email); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Job Selection -->
                                <div class="md:col-span-2">
                                    <label for="modal_job_id" class="block text-sm font-medium text-gray-700 mb-2">Job (Optional)</label>
                                    <div class="relative">
                                        <select name="job_id" id="modal_job_id" 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white">
                                            <option value="">Select a job</option>
                                            <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($job->id); ?>"><?php echo e($job->title); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Details Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Schedule Details</h4>
                                <p class="text-sm text-gray-500">Set the date, time, and duration for the interview</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Date and Time -->
                                <div>
                                    <label for="scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date & Time <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="datetime-local" name="scheduled_at" id="scheduled_at" required 
                                               min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Duration -->
                                <div>
                                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Duration <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="duration_minutes" id="duration_minutes" required 
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 appearance-none bg-white">
                                            <option value="30">30 minutes</option>
                                            <option value="45">45 minutes</option>
                                            <option value="60" selected>60 minutes</option>
                                            <option value="90">90 minutes</option>
                                            <option value="120">120 minutes</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Interview Mode Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-purple-500 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Interview Mode</h4>
                                <p class="text-sm text-gray-500">Choose how the interview will be conducted</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="mode" value="onsite" class="sr-only peer" required>
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200 hover:border-gray-300">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center peer-checked:bg-blue-100">
                                                <svg class="w-4 h-4 text-gray-600 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Onsite</div>
                                                <div class="text-sm text-gray-500">In-person interview</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="mode" value="remote" class="sr-only peer" required>
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200 hover:border-gray-300">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center peer-checked:bg-blue-100">
                                                <svg class="w-4 h-4 text-gray-600 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Remote</div>
                                                <div class="text-sm text-gray-500">Video call interview</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="mode" value="phone" class="sr-only peer" required>
                                    <div class="p-4 border-2 border-gray-200 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all duration-200 hover:border-gray-300">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center peer-checked:bg-blue-100">
                                                <svg class="w-4 h-4 text-gray-600 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900">Phone</div>
                                                <div class="text-sm text-gray-500">Phone call interview</div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Location (shown when onsite) -->
                            <div id="locationField" class="hidden">
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="location" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Office address or room number">
                            </div>

                            <!-- Meeting Link (shown when remote) -->
                            <div id="meetingLinkField" class="hidden">
                                <label for="meeting_link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meeting Link <span class="text-red-500">*</span>
                                </label>
                                <input type="url" name="meeting_link" id="meeting_link" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="https://zoom.us/j/...">
                            </div>
                        </div>

                        <!-- Panelists Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Interview Panel</h4>
                                <p class="text-sm text-gray-500">Select team members to join the interview</p>
                            </div>
                            
                            <div class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center p-3 hover:bg-white rounded-lg cursor-pointer transition-all duration-200 border border-transparent hover:border-gray-200 hover:shadow-sm">
                                            <input type="checkbox" name="panelists[]" value="<?php echo e($user->id); ?>" 
                                                   class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <div class="ml-3 flex items-center space-x-2">
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                </div>
                                                <span class="text-sm font-medium text-gray-700"><?php echo e($user->name); ?></span>
                                            </div>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-gray-400 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Additional Information</h4>
                                <p class="text-sm text-gray-500">Add any notes or special instructions</p>
                            </div>
                            
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="4" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                          placeholder="Any additional notes about this interview..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeScheduleModal()" 
                                class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" form="scheduleInterviewForm" id="submitButton"
                                class="px-6 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 flex items-center space-x-2">
                            <span class="submit-text">Schedule Interview</span>
                            <span class="loading-text hidden flex items-center space-x-2">
                                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>Scheduling...</span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
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

<script>
function openScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
    
    // Set minimum date to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('scheduled_at').min = now.toISOString().slice(0, 16);
}

function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    // Trigger close animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    
    // Reset form
    document.getElementById('scheduleInterviewForm').reset();
    
    // Hide conditional fields
    document.getElementById('locationField').classList.add('hidden');
    document.getElementById('meetingLinkField').classList.add('hidden');
    
    // Reset button state
    const submitButton = document.getElementById('submitButton');
    submitButton.disabled = false;
    submitButton.querySelector('.submit-text').classList.remove('hidden');
    submitButton.querySelector('.loading-text').classList.add('hidden');
}

// Show/hide location and meeting link fields based on mode
document.addEventListener('change', function(e) {
    if (e.target.name === 'mode') {
        const mode = e.target.value;
        const locationField = document.getElementById('locationField');
        const meetingLinkField = document.getElementById('meetingLinkField');
        
        // Hide both fields first
        locationField.classList.add('hidden');
        meetingLinkField.classList.add('hidden');
        
        // Show appropriate field based on mode
        if (mode === 'onsite') {
            locationField.classList.remove('hidden');
            document.getElementById('location').required = true;
            document.getElementById('meeting_link').required = false;
        } else if (mode === 'remote') {
            meetingLinkField.classList.remove('hidden');
            document.getElementById('meeting_link').required = true;
            document.getElementById('location').required = false;
        } else {
            document.getElementById('location').required = false;
            document.getElementById('meeting_link').required = false;
        }
    }
});

// Handle form submission
document.getElementById('scheduleInterviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = document.getElementById('submitButton');
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.querySelector('.submit-text').classList.add('hidden');
    submitButton.querySelector('.loading-text').classList.remove('hidden');
    
    fetch('<?php echo e(route("tenant.interviews.store-direct", $tenantModel->slug)); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message with better UX
            const successMessage = document.createElement('div');
            successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successMessage.textContent = 'Interview scheduled successfully!';
            document.body.appendChild(successMessage);
            
            // Remove success message after 3 seconds
            setTimeout(() => {
                successMessage.remove();
            }, 3000);
            
            // Close modal
            closeScheduleModal();
            
            // Add new interview to the list dynamically
            addInterviewToList(data.interview);
            
            // Notify other tabs (like pipeline) about the new interview
            localStorage.setItem('interviewScheduled', JSON.stringify({
                interview: data.interview,
                timestamp: Date.now()
            }));
            
            // Trigger custom event for same-tab listeners
            window.dispatchEvent(new CustomEvent('interviewScheduled', {
                detail: { interview: data.interview }
            }));
        } else {
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            errorMessage.textContent = data.message || 'Failed to schedule interview. Please try again.';
            document.body.appendChild(errorMessage);
            
            // Remove error message after 5 seconds
            setTimeout(() => {
                errorMessage.remove();
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorMessage.textContent = 'An error occurred. Please try again.';
        document.body.appendChild(errorMessage);
        
        // Remove error message after 5 seconds
        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.querySelector('.submit-text').classList.remove('hidden');
        submitButton.querySelector('.loading-text').classList.add('hidden');
    });
});

// Function to add new interview to the list dynamically
function addInterviewToList(interview) {
    const interviewsList = document.querySelector('.interviews-list');
    if (!interviewsList) return;
    
    // Create new interview card
    const interviewCard = createInterviewCard(interview);
    
    // Add to the beginning of the list
    interviewsList.insertBefore(interviewCard, interviewsList.firstChild);
    
    // Update empty state if it exists
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        emptyState.style.display = 'none';
    }
}

// Function to create interview card HTML
function createInterviewCard(interview) {
    const scheduledAt = new Date(interview.scheduled_at);
    const formattedDate = scheduledAt.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });
    
    const statusClass = interview.status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                       interview.status === 'completed' ? 'bg-green-100 text-green-800' :
                       'bg-red-100 text-red-800';
    
    const statusText = interview.status.charAt(0).toUpperCase() + interview.status.slice(1);
    
    const locationHtml = interview.location ? `
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            ${interview.location}
        </div>
    ` : '';
    
    const meetingLinkHtml = interview.meeting_link ? `
        <div class="flex items-center">
            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            <a href="${interview.meeting_link}" target="_blank" class="text-blue-600 hover:text-blue-800 transition-colors duration-200">
                Join Meeting
            </a>
        </div>
    ` : '';
    
    const panelistsHtml = interview.panelists && interview.panelists.length > 0 ? `
        <div class="mt-3">
            <span class="text-sm font-medium text-gray-700">Panelists:</span>
            <div class="flex flex-wrap gap-1 mt-1">
                ${interview.panelists.map(panelist => `
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                        ${panelist.name}
                    </span>
                `).join('')}
            </div>
        </div>
    ` : '';
    
    const notesHtml = interview.notes ? `
        <div class="mt-3">
            <span class="text-sm font-medium text-gray-700">Notes:</span>
            <p class="text-sm text-gray-600 mt-1">${interview.notes}</p>
        </div>
    ` : '';
    
    const card = document.createElement('div');
    card.className = 'bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200';
    card.innerHTML = `
        <div class="p-6">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center space-x-4 mb-3">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-medium text-gray-900 truncate">
                                <a href="/${window.location.pathname.split('/')[1]}/candidates/${interview.candidate.id}" class="hover:text-blue-600 transition-colors duration-200">
                                    ${interview.candidate.first_name} ${interview.candidate.last_name}
                                </a>
                            </h3>
                            ${interview.job ? `<p class="text-sm text-gray-500 truncate">for ${interview.job.title}</p>` : ''}
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                            ${statusText}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            ${interview.mode.charAt(0).toUpperCase() + interview.mode.slice(1)}
                        </span>
                        <span class="text-sm text-gray-500">
                            ${interview.duration_minutes} minutes
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            ${formattedDate}
                        </div>
                        ${locationHtml}
                        ${meetingLinkHtml}
                    </div>

                    ${panelistsHtml}
                    ${notesHtml}
                </div>

                <div class="flex items-center space-x-2 ml-4">
                    <a href="/${window.location.pathname.split('/')[1]}/interviews/${interview.id}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        View
                    </a>
                    <a href="/${window.location.pathname.split('/')[1]}/interviews/${interview.id}/edit" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Edit
                    </a>
                </div>
            </div>
        </div>
    `;
    
    return card;
}

// Listen for interview scheduled events from other tabs
window.addEventListener('storage', function(e) {
    if (e.key === 'interviewScheduled') {
        const data = JSON.parse(e.newValue);
        if (data && data.interview) {
            addInterviewToList(data.interview);
        }
    }
});

// Listen for interview scheduled events from same tab
window.addEventListener('interviewScheduled', function(e) {
    if (e.detail && e.detail.interview) {
        addInterviewToList(e.detail.interview);
    }
});

function showInterviewLimitReached() {
    const currentCount = <?php echo e($currentInterviewCount); ?>;
    const maxInterviews = <?php echo e($maxInterviews === -1 ? 'Infinity' : $maxInterviews); ?>;
    
    let message = `You've reached the interviews limit for your current plan this month. `;
    if (maxInterviews !== Infinity) {
        message += `You currently have ${currentCount} interviews scheduled this month out of ${maxInterviews} allowed. `;
    }
    message += `Please upgrade your subscription plan to schedule more interviews.`;
    
    alert(message);
    
    // Redirect to tenant-specific subscription page
    if (confirm('Would you like to view available plans?')) {
        window.location.href = '<?php echo e(route("subscription.show", $tenant->slug)); ?>';
    }
}
</script><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/interviews/index.blade.php ENDPATH**/ ?>