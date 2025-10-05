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
                ['label' => 'Email Templates', 'url' => route('tenant.email-templates.index', $tenant->slug)],
                ['label' => 'Create', 'url' => null]
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
        <div>
            <h1 class="text-2xl font-bold text-white">Create Email Template</h1>
            <p class="mt-1 text-sm text-white">
                Design a new email template for candidate communications.
            </p>
        </div>

        <form action="<?php echo e(route('tenant.email-templates.store', $tenant->slug)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Main Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Basic Information -->
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
                         <?php $__env->slot('header', null, []); ?> 
                            <h3 class="text-lg font-medium text-black">Basic Information</h3>
                         <?php $__env->endSlot(); ?>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-black">Template Name</label>
                                <input type="text" 
                                       name="name" 
                                       id="name" 
                                       value="<?php echo e(old('name')); ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-medium text-black">Template Type</label>
                                <select name="type" 
                                        id="type" 
                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md"
                                        onchange="updateVariables()"
                                        required>
                                    <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>" <?php echo e(old('type', $selectedType) == $key ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" 
                                       name="is_active" 
                                       id="is_active" 
                                       value="1"
                                       <?php echo e(old('is_active', true) ? 'checked' : ''); ?>

                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-black">
                                    Active (can be used for sending emails)
                                </label>
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

                    <!-- Premade Templates -->
                    <?php if(!empty($premadeTemplates)): ?>
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
                         <?php $__env->slot('header', null, []); ?> 
                            <h3 class="text-lg font-medium text-black">Choose a Premade Template</h3>
                            <p class="text-sm text-gray-600 mt-1">Select a template to get started quickly, or create your own from scratch.</p>
                         <?php $__env->endSlot(); ?>

                        <div class="space-y-3">
                            <?php $__currentLoopData = $premadeTemplates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 transition-colors cursor-pointer" 
                                     onclick="loadPremadeTemplate(<?php echo e($index); ?>)">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900"><?php echo e($template['name']); ?></h4>
                                            <p class="text-xs text-gray-600 mt-1"><?php echo e(Str::limit($template['subject'], 60)); ?></p>
                                            <p class="text-xs text-gray-500 mt-2"><?php echo e(Str::limit(strip_tags($template['body']), 100)); ?></p>
                                        </div>
                                        <button type="button" 
                                                class="ml-4 px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-md hover:bg-blue-200 transition-colors">
                                            Use Template
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php endif; ?>

                    <!-- Email Content -->
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
                         <?php $__env->slot('header', null, []); ?> 
                            <h3 class="text-lg font-medium text-black">Email Content</h3>
                         <?php $__env->endSlot(); ?>

                        <div class="space-y-4">
                            <div>
                                <label for="subject" class="block text-sm font-medium text-black">Subject Line</label>
                                <input type="text" 
                                       name="subject" 
                                       id="subject" 
                                       value="<?php echo e(old('subject')); ?>"
                                       placeholder="e.g., Thank you for your application - <?php echo '<?php echo e('; ?> job_title <?php echo '); ?>'; ?>"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                                <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="body" class="block text-sm font-medium text-black">Email Body</label>
                                <textarea name="body" 
                                          id="body" 
                                          rows="15"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                          placeholder="Write your email content here. Use variables like <?php echo '<?php echo e('; ?> candidate_name <?php echo '); ?>'; ?> to personalize the message."
                                          required><?php echo e(old('body')); ?></textarea>
                                <?php $__errorArgs = ['body'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Available Variables -->
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
                         <?php $__env->slot('header', null, []); ?> 
                            <h3 class="text-lg font-medium text-black">Available Variables</h3>
                         <?php $__env->endSlot(); ?>

                        <div id="variables-list" class="space-y-2">
                            <?php $__currentLoopData = $variables; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $description): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                                    <div>
                                        <code class="text-sm font-mono text-blue-600">{{ $key }}</code>
                                        <p class="text-xs text-gray-600"><?php echo e($description); ?></p>
                                    </div>
                                    <button type="button" 
                                            onclick="insertVariable('{{ $key }}')"
                                            class="text-blue-600 hover:text-blue-700 text-sm">
                                        Insert
                                    </button>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

                    <!-- Preview -->
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
                         <?php $__env->slot('header', null, []); ?> 
                            <h3 class="text-lg font-medium text-black">Preview</h3>
                         <?php $__env->endSlot(); ?>

                        <div class="space-y-4">
                            <button type="button" 
                                    onclick="previewTemplate()"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Preview Template
                            </button>

                            <div id="preview-content" class="hidden">
                                <div class="border rounded p-4 bg-gray-50">
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Subject:</h4>
                                    <p id="preview-subject" class="text-sm text-gray-700 mb-4"></p>
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Body:</h4>
                                    <div id="preview-body" class="text-sm text-gray-700 whitespace-pre-wrap"></div>
                                </div>
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
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3">
                <a href="<?php echo e(route('tenant.email-templates.index', $tenant->slug)); ?>" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Create Template
                </button>
            </div>
        </form>
    </div>

    <script>
        const variables = <?php echo json_encode($variables, 15, 512) ?>;

        function updateVariables() {
            const type = document.getElementById('type').value;
            
            // Reload the page with the new type to get updated variables and premade templates
            const url = new URL(window.location);
            url.searchParams.set('type', type);
            window.location.href = url.toString();
        }

        function updateVariablesList(vars) {
            const container = document.getElementById('variables-list');
            container.innerHTML = '';
            
            Object.entries(vars).forEach(([key, description]) => {
                const div = document.createElement('div');
                div.className = 'flex items-center justify-between p-2 bg-gray-50 rounded';
                div.innerHTML = `
                    <div>
                        <code class="text-sm font-mono text-blue-600"><?php echo e(` + key + `); ?></code>
                        <p class="text-xs text-gray-600">${description}</p>
                    </div>
                    <button type="button" 
                            onclick="insertVariable('<?php echo e(` + key + `); ?>')"
                            class="text-blue-600 hover:text-blue-700 text-sm">
                        Insert
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function insertVariable(variable) {
            const bodyTextarea = document.getElementById('body');
            const cursorPos = bodyTextarea.selectionStart;
            const textBefore = bodyTextarea.value.substring(0, cursorPos);
            const textAfter = bodyTextarea.value.substring(bodyTextarea.selectionEnd);
            
            bodyTextarea.value = textBefore + variable + textAfter;
            bodyTextarea.focus();
            bodyTextarea.setSelectionRange(cursorPos + variable.length, cursorPos + variable.length);
        }

        function previewTemplate() {
            try {
                const subject = document.getElementById('subject').value || '';
                const body = document.getElementById('body').value || '';
                
                // Sample data for preview
                const sampleData = {
                    'candidate_name': 'John Doe',
                    'candidate_email': 'john.doe@example.com',
                    'job_title': 'Senior Developer',
                    'company_name': '<?php echo e($tenant->name ?? "Your Company"); ?>',
                    'application_date': '<?php echo e(now()->format("M j, Y")); ?>',
                    'stage_name': 'Interview',
                    'previous_stage': 'Screen',
                    'message': 'Thank you for your interest in this position.',
                    'interview_date': '<?php echo e(now()->addDays(7)->format("M j, Y")); ?>',
                    'interview_time': '2:00 PM',
                    'interview_location': 'Office or Video Call',
                    'interviewer_name': 'Jane Smith',
                    'interview_notes': 'Please bring your portfolio and be ready to discuss your experience.',
                    'cancellation_reason': 'Scheduling conflict',
                };

                let previewSubject = subject;
                let previewBody = body;

                // Replace variables using simple string replacement
                Object.entries(sampleData).forEach(([key, value]) => {
                    const variablePattern = '<?php echo e(' + key + '); ?>';
                    previewSubject = previewSubject.replaceAll(variablePattern, value);
                    previewBody = previewBody.replaceAll(variablePattern, value);
                });

                // Update preview content
                const previewSubjectEl = document.getElementById('preview-subject');
                const previewBodyEl = document.getElementById('preview-body');
                const previewContentEl = document.getElementById('preview-content');
                
                if (previewSubjectEl) {
                    previewSubjectEl.textContent = previewSubject || '(No subject entered)';
                }
                
                if (previewBodyEl) {
                    previewBodyEl.textContent = previewBody || '(No body content entered)';
                }
                
                if (previewContentEl) {
                    previewContentEl.classList.remove('hidden');
                }
                
                console.log('Preview updated:', { previewSubject, previewBody });
            } catch (error) {
                console.error('Preview error:', error);
                alert('Error generating preview: ' + error.message);
            }
        }

        // Update variables when type changes
        document.getElementById('type').addEventListener('change', function() {
            // In a real implementation, you'd fetch variables for the selected type
            // For now, we'll keep the current variables
        });

        // Load premade template
        function loadPremadeTemplate(templateIndex) {
            const type = document.getElementById('type').value;
            
            fetch('<?php echo e(route("tenant.email-templates.load-premade", $tenant->slug)); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    template_index: templateIndex
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error loading template: ' + data.error);
                    return;
                }
                
                // Populate the form with the premade template
                document.getElementById('name').value = data.name;
                document.getElementById('subject').value = data.subject;
                document.getElementById('body').value = data.body;
                
                // Scroll to the email content section
                document.getElementById('body').scrollIntoView({ behavior: 'smooth' });
                
                // Show success message
                const message = document.createElement('div');
                message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-md shadow-lg z-50';
                message.textContent = 'Template loaded successfully!';
                document.body.appendChild(message);
                
                setTimeout(() => {
                    message.remove();
                }, 3000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading template. Please try again.');
            });
        }

        // Debug: Test if preview button exists and is clickable
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, checking preview button...');
            const previewBtn = document.querySelector('button[onclick="previewTemplate()"]');
            if (previewBtn) {
                console.log('Preview button found:', previewBtn);
                previewBtn.addEventListener('click', function(e) {
                    console.log('Preview button clicked!');
                });
            } else {
                console.log('Preview button not found!');
            }
        });
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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\tenant\email-templates\create.blade.php ENDPATH**/ ?>