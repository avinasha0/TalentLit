<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
        ['label' => $job->title, 'url' => route('tenant.jobs.show', [$tenant->slug, $job->id])],
        ['label' => 'Questions', 'url' => null]
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
                <h1 class="text-2xl font-bold text-black">Job Application Questions</h1>
                <p class="mt-1 text-sm text-black">Configure custom questions for "<?php echo e($job->title); ?>"</p>
            </div>
        </div>

        <!-- Questions Management -->
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
            <form method="POST" action="<?php echo e(route('tenant.jobs.questions.update', [$tenant->slug, $job->id])); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Available Questions -->
                    <div>
                        <h3 class="text-lg font-medium text-black mb-4">Available Questions</h3>
                        <div class="space-y-2 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4">
                            <?php $__empty_1 = true; $__currentLoopData = $availableQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-md">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm text-black"><?php echo e($question->label); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?></div>
                                    </div>
                                    <button type="button" 
                                            onclick="addQuestion('<?php echo e($question->id); ?>', '<?php echo e($question->label); ?>', '<?php echo e($question->type); ?>', <?php echo e($question->required ? 'true' : 'false'); ?>)"
                                            class="px-3 py-1 text-xs bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Add
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p class="text-gray-500 text-sm">No questions available. Create some questions first.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Selected Questions -->
                    <div>
                        <h3 class="text-lg font-medium text-black mb-4">Selected Questions</h3>
                        <div id="selected-questions" class="space-y-2 min-h-32 border border-gray-200 rounded-md p-4">
                            <?php $__currentLoopData = $job->applicationQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-md border border-blue-200" data-question-id="<?php echo e($question->id); ?>">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm text-black"><?php echo e($question->label); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo e(ucfirst(str_replace('_', ' ', $question->type))); ?></div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="questions[<?php echo e($index); ?>][required]" 
                                                   value="1"
                                                   <?php echo e($question->pivot->required_override ? 'checked' : ''); ?>

                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-1 text-xs text-gray-600">Required</span>
                                        </label>
                                        <button type="button" 
                                                onclick="removeQuestion(this)"
                                                class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                                            Remove
                                        </button>
                                    </div>
                                    <input type="hidden" name="questions[<?php echo e($index); ?>][question_id]" value="<?php echo e($question->id); ?>">
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Questions
                    </button>
                </div>
            </form>
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
        let questionIndex = <?php echo e($job->applicationQuestions->count()); ?>;

        function addQuestion(id, label, type, required) {
            const container = document.getElementById('selected-questions');
            
            // Check if question already exists
            if (document.querySelector(`[data-question-id="${id}"]`)) {
                return;
            }

            const questionDiv = document.createElement('div');
            questionDiv.className = 'flex items-center justify-between p-3 bg-blue-50 rounded-md border border-blue-200';
            questionDiv.setAttribute('data-question-id', id);
            
            questionDiv.innerHTML = `
                <div class="flex-1">
                    <div class="font-medium text-sm text-black">${label}</div>
                    <div class="text-xs text-gray-500">${type.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}</div>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="questions[${questionIndex}][required]" 
                               value="1"
                               ${required ? 'checked' : ''}
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-1 text-xs text-gray-600">Required</span>
                    </label>
                    <button type="button" 
                            onclick="removeQuestion(this)"
                            class="px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700">
                        Remove
                    </button>
                </div>
                <input type="hidden" name="questions[${questionIndex}][question_id]" value="${id}">
            `;
            
            container.appendChild(questionDiv);
            questionIndex++;
        }

        function removeQuestion(button) {
            button.closest('[data-question-id]').remove();
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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\tenant\jobs\questions.blade.php ENDPATH**/ ?>