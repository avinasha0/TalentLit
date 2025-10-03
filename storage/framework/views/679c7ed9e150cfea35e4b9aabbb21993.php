<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Schedule Interview')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Candidate Info -->
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900">Candidate</h3>
                        <p class="text-sm text-gray-600"><?php echo e($candidate->first_name); ?> <?php echo e($candidate->last_name); ?></p>
                        <p class="text-sm text-gray-500"><?php echo e($candidate->primary_email); ?></p>
                    </div>

                    <form method="POST" action="<?php echo e(route('tenant.interviews.store', ['tenant' => $tenant, 'candidate' => $candidate])); ?>">
                        <?php echo csrf_field(); ?>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Job Selection -->
                            <div class="md:col-span-2">
                                <label for="job_id" class="block text-sm font-medium text-gray-700">Job Position</label>
                                <select name="job_id" id="job_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a job position</option>
                                    <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($job->id); ?>" <?php echo e(old('job_id') == $job->id ? 'selected' : ''); ?>>
                                            <?php echo e($job->title); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['job_id'];
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

                            <!-- Date and Time -->
                            <div>
                                <label for="scheduled_at" class="block text-sm font-medium text-gray-700">Date & Time</label>
                                <input type="datetime-local" 
                                       name="scheduled_at" 
                                       id="scheduled_at" 
                                       value="<?php echo e(old('scheduled_at')); ?>"
                                       min="<?php echo e(now()->format('Y-m-d\TH:i')); ?>"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       required>
                                <?php $__errorArgs = ['scheduled_at'];
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

                            <!-- Duration -->
                            <div>
                                <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes)</label>
                                <select name="duration_minutes" id="duration_minutes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select duration</option>
                                    <option value="15" <?php echo e(old('duration_minutes') == '15' ? 'selected' : ''); ?>>15 minutes</option>
                                    <option value="30" <?php echo e(old('duration_minutes') == '30' ? 'selected' : ''); ?>>30 minutes</option>
                                    <option value="45" <?php echo e(old('duration_minutes') == '45' ? 'selected' : ''); ?>>45 minutes</option>
                                    <option value="60" <?php echo e(old('duration_minutes') == '60' ? 'selected' : ''); ?>>1 hour</option>
                                    <option value="90" <?php echo e(old('duration_minutes') == '90' ? 'selected' : ''); ?>>1.5 hours</option>
                                    <option value="120" <?php echo e(old('duration_minutes') == '120' ? 'selected' : ''); ?>>2 hours</option>
                                </select>
                                <?php $__errorArgs = ['duration_minutes'];
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

                            <!-- Mode -->
                            <div>
                                <label for="mode" class="block text-sm font-medium text-gray-700">Interview Mode</label>
                                <select name="mode" id="mode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select mode</option>
                                    <option value="onsite" <?php echo e(old('mode') == 'onsite' ? 'selected' : ''); ?>>Onsite</option>
                                    <option value="remote" <?php echo e(old('mode') == 'remote' ? 'selected' : ''); ?>>Remote</option>
                                    <option value="phone" <?php echo e(old('mode') == 'phone' ? 'selected' : ''); ?>>Phone</option>
                                </select>
                                <?php $__errorArgs = ['mode'];
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

                            <!-- Location (shown when onsite) -->
                            <div id="location-field" class="hidden">
                                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                                <input type="text" 
                                       name="location" 
                                       id="location" 
                                       value="<?php echo e(old('location')); ?>"
                                       placeholder="e.g., Conference Room A, 123 Main St"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php $__errorArgs = ['location'];
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

                            <!-- Meeting Link (shown when remote) -->
                            <div id="meeting-link-field" class="hidden">
                                <label for="meeting_link" class="block text-sm font-medium text-gray-700">Meeting Link</label>
                                <input type="url" 
                                       name="meeting_link" 
                                       id="meeting_link" 
                                       value="<?php echo e(old('meeting_link')); ?>"
                                       placeholder="https://meet.google.com/abc-defg-hij"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <?php $__errorArgs = ['meeting_link'];
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

                            <!-- Panelists -->
                            <div class="md:col-span-2">
                                <label for="panelists" class="block text-sm font-medium text-gray-700">Panelists</label>
                                <div class="mt-1 space-y-2 max-h-32 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <label class="flex items-center">
                                            <input type="checkbox" 
                                                   name="panelists[]" 
                                                   value="<?php echo e($user->id); ?>"
                                                   <?php echo e(in_array($user->id, old('panelists', [])) ? 'checked' : ''); ?>

                                                   class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <span class="ml-2 text-sm text-gray-700"><?php echo e($user->name); ?></span>
                                        </label>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['panelists'];
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

                            <!-- Notes -->
                            <div class="md:col-span-2">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                                <textarea name="notes" 
                                          id="notes" 
                                          rows="3" 
                                          placeholder="Any additional notes about this interview..."
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
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

                        <!-- Form Actions -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="<?php echo e(route('tenant.candidates.show', ['tenant' => $tenant, 'candidate' => $candidate])); ?>" 
                               class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Schedule Interview
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide location and meeting link fields based on mode selection
        document.getElementById('mode').addEventListener('change', function() {
            const locationField = document.getElementById('location-field');
            const meetingLinkField = document.getElementById('meeting-link-field');
            
            // Hide both fields first
            locationField.classList.add('hidden');
            meetingLinkField.classList.add('hidden');
            
            // Show appropriate field based on selection
            if (this.value === 'onsite') {
                locationField.classList.remove('hidden');
            } else if (this.value === 'remote') {
                meetingLinkField.classList.remove('hidden');
            }
        });

        // Trigger change event on page load if mode is already selected
        document.addEventListener('DOMContentLoaded', function() {
            const modeSelect = document.getElementById('mode');
            if (modeSelect.value) {
                modeSelect.dispatchEvent(new Event('change'));
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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\tenant\interviews\create.blade.php ENDPATH**/ ?>