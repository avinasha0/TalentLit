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
        <?php
            $breadcrumbs = [
                ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
                ['label' => $job->title, 'url' => route('tenant.jobs.show', [$tenant->slug, $job->id])],
                ['label' => 'Pipeline', 'url' => null]
            ];
        ?>
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
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white"><?php echo e($job->title); ?> - Pipeline</h1>
                <p class="mt-1 text-sm text-white">
                    Drag and drop applications between stages to manage your hiring pipeline.
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('tenant.interviews.index', $tenant->slug)); ?>?job_id=<?php echo e($job->id); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    View Interviews
                </a>
                
                <button id="refresh-btn" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                
                <a href="<?php echo e(route('tenant.jobs.show', [$tenant->slug, $job->id])); ?>" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Job
                </a>
            </div>
        </div>

        <!-- Pipeline Board -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                
                <div id="pipeline-board" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 min-h-96">
                    <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $stageApplications = $applicationsByStage->get($stage->id, collect());
                            $stageCount = $stageApplications->count();
                        ?>
                        
                        
                        <div class="bg-gray-50 rounded-lg border-2 border-gray-200 min-h-96" 
                             data-stage-id="<?php echo e($stage->id); ?>"
                             ondrop="drop(event)" 
                             ondragover="allowDrop(event)">
                            
                            <!-- Stage Header -->
                            <div class="p-4 border-b border-gray-200 bg-white rounded-t-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900"><?php echo e($stage->name); ?></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo e($stageCount); ?>

                                    </span>
                                </div>
                            </div>
                            
                            <!-- Applications Container -->
                            <div class="p-4 space-y-3 min-h-80" id="stage-<?php echo e($stage->id); ?>-applications">
                                <?php $__empty_1 = true; $__currentLoopData = $stageApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php if($application->candidate): ?>
                                    <div class="bg-white rounded-lg border border-gray-200 p-4 cursor-move hover:shadow-md transition-shadow"
                                         draggable="true"
                                         data-application-id="<?php echo e($application->id); ?>"
                                         data-stage-id="<?php echo e($stage->id); ?>"
                                         ondragstart="drag(event)"
                                         ondragend="dragEnd(event)">
                                        
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0 candidate-info">
                                                <a href="<?php echo e(route('tenant.candidates.show', ['tenant' => $tenant->slug, 'candidate' => $application->candidate->id])); ?>" 
                                                   class="block hover:bg-gray-50 rounded-md p-1 -m-1 transition-colors">
                                                    <h4 class="text-sm font-medium text-gray-900 truncate hover:text-blue-600">
                                                        <?php echo e($application->candidate->full_name); ?>

                                                    </h4>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        <?php echo e($application->candidate->email); ?>

                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        Applied <?php echo e($application->applied_at->diffForHumans()); ?>

                                                    </p>
                                                    
                                                    <!-- Interview Information -->
                                                    <?php if($application->interviews->count() > 0): ?>
                                                        <?php
                                                            // Smart interview filtering based on stage:
                                                            $scheduledInterviews = $application->interviews->where('status', 'scheduled');
                                                            $completedInterviews = $application->interviews->where('status', 'completed');
                                                            
                                                            // Check if we're in Screen stage - hide all cancelled interviews
                                                            $isScreenStage = str_contains(strtolower($stage->name), 'screen');
                                                            
                                                            if ($isScreenStage) {
                                                                // In Screen stage: Only show scheduled or completed interviews, hide all cancelled
                                                                if ($scheduledInterviews->count() > 0) {
                                                                    $interviewsToShow = $scheduledInterviews;
                                                                } elseif ($completedInterviews->count() > 0) {
                                                                    $interviewsToShow = $completedInterviews;
                                                                } else {
                                                                    $interviewsToShow = collect(); // No interviews to show
                                                                }
                                                            } else {
                                                                // In other stages: Show scheduled, completed, or manually cancelled interviews
                                                                $cancelledInterviews = $application->interviews->where('status', 'canceled')
                                                                    ->filter(function($interview) {
                                                                        // Only show cancelled interviews that were cancelled manually (not due to stage changes)
                                                                        return $interview->cancellation_reason !== 'stage_change';
                                                                    });
                                                                
                                                                if ($scheduledInterviews->count() > 0) {
                                                                    $interviewsToShow = $scheduledInterviews;
                                                                } elseif ($completedInterviews->count() > 0) {
                                                                    $interviewsToShow = $completedInterviews;
                                                                } elseif ($cancelledInterviews->count() > 0) {
                                                                    $interviewsToShow = $cancelledInterviews;
                                                                } else {
                                                                    $interviewsToShow = collect(); // No interviews to show
                                                                }
                                                            }
                                                        ?>
                                                        <?php if($interviewsToShow->count() > 0): ?>
                                                            <div class="mt-2 space-y-1">
                                                                <?php if($scheduledInterviews->count() > 0 && !$isScreenStage && $application->interviews->where('status', 'canceled')->where('cancellation_reason', '!=', 'stage_change')->count() > 0): ?>
                                                                    <p class="text-xs text-gray-400 italic">Showing scheduled interviews only</p>
                                                                <?php endif; ?>
                                                                <?php $__currentLoopData = $interviewsToShow->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="flex items-center space-x-2 text-xs">
                                                                    <div class="flex items-center space-x-1">
                                                                        <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                                        </svg>
                                                                        <span class="text-blue-600 font-medium">
                                                                            <?php echo e($interview->scheduled_at->format('M j, g:i A')); ?>

                                                                        </span>
                                                                    </div>
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium
                                                            <?php if($interview->status === 'scheduled'): ?> bg-blue-100 text-blue-800
                                                            <?php elseif($interview->status === 'completed'): ?> bg-green-100 text-green-800
                                                            <?php else: ?> bg-red-100 text-red-800
                                                            <?php endif; ?>">
                                                            <?php echo e(ucfirst($interview->status)); ?>

                                                            <?php if($interview->status === 'canceled' && $interview->updated_at > $interview->created_at): ?>
                                                                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" title="Moved to screen stage">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                            </svg>
                                                                        <?php endif; ?>
                                                                    </span>
                                                                    <span class="text-gray-500"><?php echo e(ucfirst($interview->mode)); ?></span>
                                                                </div>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                <?php if($interviewsToShow->count() > 2): ?>
                                                                    <p class="text-xs text-gray-500">+<?php echo e($interviewsToShow->count() - 2); ?> more interviews</p>
                                                                <?php endif; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </a>
                                            </div>
                                            
                                            <div class="flex flex-col items-end space-y-2">
                                                <?php
                                                    $statusColors = [
                                                        'active' => 'bg-green-600 text-white',
                                                        'applied' => 'bg-green-600 text-white',
                                                        'hired' => 'bg-blue-100 text-blue-800',
                                                        'rejected' => 'bg-red-100 text-red-800',
                                                        'withdrawn' => 'bg-gray-100 text-gray-800',
                                                    ];
                                                    $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                                ?>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($statusColor); ?>">
                                                    <?php echo e(ucfirst($application->status)); ?>

                                                </span>
                                                
                                                <!-- Schedule Interview Button for Interview Stages -->
                                                <?php if(str_contains(strtolower($stage->name), 'interview') && $application->interviews->count() == 0): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create', App\Models\Interview::class)): ?>
                                                        <a href="<?php echo e(route('tenant.interviews.create', ['tenant' => $tenant->slug, 'candidate' => $application->candidate->id])); ?>?job_id=<?php echo e($job->id); ?>"
                                                           class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-md transition-colors duration-200"
                                                           title="Schedule Interview">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Schedule
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Loading indicator (hidden by default) -->
                                        <div class="hidden loading-indicator mt-2">
                                            <div class="flex items-center justify-center">
                                                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="ml-2 text-xs text-gray-500">Moving...</span>
                                            </div>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                    <!-- Application without candidate -->
                                    <div class="bg-red-50 rounded-lg border border-red-200 p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-red-800">Application #<?php echo e($application->id); ?></p>
                                                <p class="text-xs text-red-600">No candidate data available</p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="mt-2 text-sm">No applications in this stage</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Recent Events Sidebar -->
        <?php if($recentEvents->count() > 0): ?>
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium"><?php echo e($event->application->candidate->full_name); ?></span>
                                        <?php if($event->fromStage && $event->toStage): ?>
                                            moved from <span class="font-medium"><?php echo e($event->fromStage->name); ?></span> to <span class="font-medium"><?php echo e($event->toStage->name); ?></span>
                                        <?php elseif($event->toStage): ?>
                                            moved to <span class="font-medium"><?php echo e($event->toStage->name); ?></span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        by <?php echo e($event->user->name); ?> • <?php echo e($event->created_at->diffForHumans()); ?>

                                    </p>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Interview Scheduling Modal -->
    <div id="interviewModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-white rounded-xl shadow-2xl w-full max-w-2xl transform transition-all duration-300 scale-95 opacity-0" id="interviewModalContent">
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
                                <p class="text-blue-100 text-sm">Schedule interview for <span id="modalCandidateName"></span></p>
                            </div>
                        </div>
                        <button onclick="closeInterviewModal()" class="text-white hover:text-blue-200 transition-colors duration-200 p-1 rounded-lg hover:bg-white hover:bg-opacity-20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Modal Form -->
                <div class="px-6 py-6">
                    <form id="pipelineInterviewForm" class="space-y-6">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" id="modalApplicationId" name="application_id">
                        <input type="hidden" id="modalCandidateId" name="candidate_id">
                        <input type="hidden" name="job_id" value="<?php echo e($job->id); ?>">
                        
                        <!-- Schedule Details Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Schedule Details</h4>
                                <p class="text-sm text-gray-500">Set the date, time, and duration for the interview</p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Date and Time -->
                                <div>
                                    <label for="pipeline_scheduled_at" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date & Time <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="datetime-local" name="scheduled_at" id="pipeline_scheduled_at" required 
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
                                    <label for="pipeline_duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Duration <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select name="duration_minutes" id="pipeline_duration_minutes" required 
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
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 00-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
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
                            <div id="pipelineLocationField" class="hidden">
                                <label for="pipeline_location" class="block text-sm font-medium text-gray-700 mb-2">
                                    Location <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="location" id="pipeline_location" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="Office address or room number">
                            </div>

                            <!-- Meeting Link (shown when remote) -->
                            <div id="pipelineMeetingLinkField" class="hidden">
                                <label for="pipeline_meeting_link" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meeting Link <span class="text-red-500">*</span>
                                </label>
                                <input type="url" name="meeting_link" id="pipeline_meeting_link" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                       placeholder="https://zoom.us/j/...">
                            </div>
                        </div>

                        <!-- Notes Section -->
                        <div class="space-y-6">
                            <div class="border-l-4 border-gray-400 pl-4">
                                <h4 class="text-lg font-semibold text-gray-900">Additional Information</h4>
                                <p class="text-sm text-gray-500">Add any notes or special instructions</p>
                            </div>
                            
                            <div>
                                <label for="pipeline_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                <textarea name="notes" id="pipeline_notes" rows="3" 
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 resize-none"
                                          placeholder="Any additional notes about this interview..."></textarea>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeInterviewModal()" 
                                class="px-6 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="button" onclick="scheduleInterviewFromPipeline()" id="pipelineSubmitButton"
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

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <script>
        window.Laravel = {
            csrfToken: '<?php echo e(csrf_token()); ?>'
        };
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

<script>
// Drag and Drop functionality
let draggedElement = null;

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    draggedElement = ev.target;
    ev.target.style.opacity = '0.5';
    ev.dataTransfer.effectAllowed = 'move';
    ev.dataTransfer.setData('text/html', ev.target.outerHTML);
}

function dragEnd(ev) {
    // Reset opacity immediately on drag end
    ev.target.style.opacity = '1';
    // Don't clear draggedElement here - let the AJAX response handle it
}

function drop(ev) {
    ev.preventDefault();
    ev.stopPropagation();
    
    if (!draggedElement) {
        return;
    }
    
    const targetStage = ev.currentTarget;
    const targetStageId = targetStage.dataset.stageId;
    const applicationId = draggedElement.dataset.applicationId;
    const fromStageId = draggedElement.dataset.stageId;
    
    // Don't do anything if dropped in the same stage
    if (fromStageId === targetStageId) {
        draggedElement.style.opacity = '1';
        draggedElement = null;
        return;
    }
    
    // Show loading indicator
    const loadingIndicator = draggedElement.querySelector('.loading-indicator');
    if (loadingIndicator) {
        loadingIndicator.classList.remove('hidden');
    }
    
    // Move the element visually
    const targetContainer = targetStage.querySelector(`[id^="stage-${targetStageId}-applications"]`);
    if (!targetContainer) {
        return;
    }
    
    targetContainer.appendChild(draggedElement);
    
    // Update the stage ID
    draggedElement.dataset.stageId = targetStageId;
    
    // Send AJAX request
    const requestData = {
        application_id: applicationId,
        from_stage_id: fromStageId,
        to_stage_id: targetStageId,
        to_position: 0
    };
    
    fetch(`/<?php echo e($tenant->slug); ?>/jobs/<?php echo e($job->id); ?>/pipeline/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.ok) {
            // Update stage counts
            updateStageCounts(data.counts);
            
            // Hide loading indicator
            if (loadingIndicator) {
                loadingIndicator.classList.add('hidden');
            }
            
            // Show success message
            showNotification('Application moved successfully', 'success');
            
            // Check if moved to interview stage and open modal
            checkForInterviewStage(data.toStageId, applicationId, data.candidateName);
        } else {
            // Revert the move on error
            revertMove(draggedElement, fromStageId);
            showNotification('Failed to move application: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        // Revert the move on error
        revertMove(draggedElement, fromStageId);
        showNotification('Failed to move application: ' + error.message, 'error');
    })
    .finally(() => {
        draggedElement = null;
    });
}

function revertMove(element, originalStageId) {
    const originalStage = document.querySelector(`[data-stage-id="${originalStageId}"]`);
    const originalContainer = originalStage.querySelector(`[id^="stage-${originalStageId}-applications"]`);
    originalContainer.appendChild(element);
    element.dataset.stageId = originalStageId;
}

function updateStageCounts(counts) {
    Object.keys(counts).forEach(stageId => {
        const countElement = document.querySelector(`[data-stage-id="${stageId}"] .bg-blue-100`);
        if (countElement) {
            countElement.textContent = counts[stageId];
        }
    });
}

function showNotification(message, type) {
    // Simple notification - you can enhance this with a proper notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Refresh functionality
document.getElementById('refresh-btn').addEventListener('click', function() {
    fetch(`/<?php echo e($tenant->slug); ?>/jobs/<?php echo e($job->id); ?>/pipeline.json`)
        .then(response => response.json())
        .then(data => {
            // Reload the page with fresh data
            window.location.reload();
        })
        .catch(error => {
            showNotification('Failed to refresh pipeline', 'error');
        });
});

// Prevent default drag behavior on images and other elements
document.addEventListener('dragstart', function(e) {
    if (e.target.tagName === 'IMG' || e.target.tagName === 'A') {
        e.preventDefault();
    }
});

// Interview Modal Functions
function openInterviewModal(applicationId, candidateId, candidateName) {
    const modal = document.getElementById('interviewModal');
    const modalContent = document.getElementById('interviewModalContent');
    
    // Set form data
    document.getElementById('modalApplicationId').value = applicationId;
    document.getElementById('modalCandidateId').value = candidateId;
    document.getElementById('modalCandidateName').textContent = candidateName;
    
    // Set minimum date to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    document.getElementById('pipeline_scheduled_at').min = now.toISOString().slice(0, 16);
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeInterviewModal() {
    const modal = document.getElementById('interviewModal');
    const modalContent = document.getElementById('interviewModalContent');
    
    // Trigger close animation
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
    
    // Reset form
    document.getElementById('pipelineInterviewForm').reset();
    
    // Hide conditional fields
    document.getElementById('pipelineLocationField').classList.add('hidden');
    document.getElementById('pipelineMeetingLinkField').classList.add('hidden');
    
    // Reset button state
    const submitButton = document.getElementById('pipelineSubmitButton');
    submitButton.disabled = false;
    submitButton.querySelector('.submit-text').classList.remove('hidden');
    submitButton.querySelector('.loading-text').classList.add('hidden');
}

function checkForInterviewStage(stageId, applicationId, candidateName) {
    // Get stage name from the DOM
    const stageElement = document.querySelector(`[data-stage-id="${stageId}"]`);
    if (stageElement) {
        const stageName = stageElement.querySelector('h3').textContent.toLowerCase();
        if (stageName.includes('interview')) {
            // Get candidate ID from the application element
            const applicationElement = document.querySelector(`[data-application-id="${applicationId}"]`);
            if (applicationElement) {
                const candidateLink = applicationElement.querySelector('a[href*="/candidates/"]');
                if (candidateLink) {
                    const candidateId = candidateLink.href.split('/candidates/')[1];
                    openInterviewModal(applicationId, candidateId, candidateName);
                }
            }
        }
    }
}

function scheduleInterviewFromPipeline() {
    const form = document.getElementById('pipelineInterviewForm');
    const formData = new FormData(form);
    const submitButton = document.getElementById('pipelineSubmitButton');
    
    // Show loading state
    submitButton.disabled = true;
    submitButton.querySelector('.submit-text').classList.add('hidden');
    submitButton.querySelector('.loading-text').classList.remove('hidden');
    
    fetch('<?php echo e(route("tenant.interviews.store-direct", $tenant->slug)); ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': window.Laravel.csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('Interview scheduled successfully!', 'success');
            
            // Close modal
            closeInterviewModal();
            
            // Reload page to show updated interview information
            window.location.reload();
        } else {
            // Show error message
            showNotification(data.message || 'Failed to schedule interview. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('An error occurred. Please try again.', 'error');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.querySelector('.submit-text').classList.remove('hidden');
        submitButton.querySelector('.loading-text').classList.add('hidden');
    });
}

// Show/hide location and meeting link fields based on mode
document.addEventListener('change', function(e) {
    if (e.target.name === 'mode') {
        const mode = e.target.value;
        const locationField = document.getElementById('pipelineLocationField');
        const meetingLinkField = document.getElementById('pipelineMeetingLinkField');
        
        // Hide both fields first
        locationField.classList.add('hidden');
        meetingLinkField.classList.add('hidden');
        
        // Show appropriate field based on mode
        if (mode === 'onsite') {
            locationField.classList.remove('hidden');
            document.getElementById('pipeline_location').required = true;
            document.getElementById('pipeline_meeting_link').required = false;
        } else if (mode === 'remote') {
            meetingLinkField.classList.remove('hidden');
            document.getElementById('pipeline_meeting_link').required = true;
            document.getElementById('pipeline_location').required = false;
        } else {
            document.getElementById('pipeline_location').required = false;
            document.getElementById('pipeline_meeting_link').required = false;
        }
    }
});

// Listen for interview scheduled events from other tabs (like interviews page)
window.addEventListener('storage', function(e) {
    if (e.key === 'interviewScheduled') {
        const data = JSON.parse(e.newValue);
        if (data && data.interview) {
            updatePipelineWithNewInterview(data.interview);
        }
    }
});

// Listen for interview scheduled events from same tab
window.addEventListener('interviewScheduled', function(e) {
    if (e.detail && e.detail.interview) {
        updatePipelineWithNewInterview(e.detail.interview);
    }
});

// Function to update pipeline with new interview
function updatePipelineWithNewInterview(interview) {
    // Find the application card that matches this interview
    const applicationCards = document.querySelectorAll('[data-application-id]');
    
    applicationCards.forEach(card => {
        const applicationId = card.getAttribute('data-application-id');
        
        // Check if this interview belongs to this application
        if (interview.application_id && interview.application_id === applicationId) {
            // Update the interview information in this application card
            updateApplicationCardInterviews(card, interview);
        }
    });
}

// Function to update interview information in an application card
function updateApplicationCardInterviews(card, newInterview) {
    // Find the interview information section
    const interviewSection = card.querySelector('.interview-info');
    
    if (interviewSection) {
        // Remove existing interview information
        interviewSection.remove();
    }
    
    // Create new interview information section
    const newInterviewSection = createInterviewInfoSection([newInterview]);
    
    // Insert after the candidate info
    const candidateInfo = card.querySelector('.candidate-info');
    if (candidateInfo) {
        candidateInfo.insertAdjacentElement('afterend', newInterviewSection);
    }
}

// Function to create interview information section
function createInterviewInfoSection(interviews) {
    const section = document.createElement('div');
    section.className = 'interview-info mt-2 space-y-1';
    
    interviews.forEach(interview => {
        const scheduledAt = new Date(interview.scheduled_at);
        const formattedDate = scheduledAt.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: 'numeric',
            minute: '2-digit',
            hour12: true
        });
        
        const statusClass = interview.status === 'scheduled' ? 'bg-blue-100 text-blue-800' :
                           interview.status === 'completed' ? 'bg-green-100 text-green-800' :
                           'bg-red-100 text-red-800';
        
        const statusText = interview.status.charAt(0).toUpperCase() + interview.status.slice(1);
        
        const interviewDiv = document.createElement('div');
        interviewDiv.className = 'flex items-center space-x-2 text-xs';
        interviewDiv.innerHTML = `
            <div class="flex items-center space-x-1">
                <svg class="w-3 h-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-blue-600 font-medium">${formattedDate}</span>
            </div>
            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                ${statusText}
            </span>
            <span class="text-gray-500">${interview.mode.charAt(0).toUpperCase() + interview.mode.slice(1)}</span>
        `;
        
        section.appendChild(interviewDiv);
    });
    
    return section;
}
</script>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/jobs/pipeline.blade.php ENDPATH**/ ?>