<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Settings', 'url' => null],
        ['label' => 'Careers', 'url' => null]
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
                <h1 class="text-2xl font-bold text-black">Careers Settings</h1>
                <p class="mt-1 text-sm text-black">Customize your careers page branding and appearance</p>
            </div>
        </div>

        <!-- Form -->
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
            <form method="POST" action="<?php echo e(route('tenant.settings.careers.update', $tenant->slug)); ?>" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Form Fields -->
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-black mb-1">Company Logo</label>
                            <input type="file"
                                   name="logo"
                                   id="logo"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if($branding->logo_path): ?>
                                <p class="mt-1 text-sm text-gray-600">Current: <?php echo e(basename($branding->logo_path)); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Hero Image Upload -->
                        <div>
                            <label for="hero_image" class="block text-sm font-medium text-black mb-1">Hero Background Image</label>
                            <input type="file"
                                   name="hero_image"
                                   id="hero_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['hero_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php if($branding->hero_image_path): ?>
                                <p class="mt-1 text-sm text-gray-600">Current: <?php echo e(basename($branding->hero_image_path)); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-black mb-1">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       name="primary_color"
                                       id="primary_color"
                                       value="<?php echo e($branding->primary_color ?? '#4f46e5'); ?>"
                                       class="w-12 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text"
                                       value="<?php echo e($branding->primary_color ?? '#4f46e5'); ?>"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="#4f46e5"
                                       readonly>
                            </div>
                            <?php $__errorArgs = ['primary_color'];
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

                        <!-- Intro Headline -->
                        <div>
                            <label for="intro_headline" class="block text-sm font-medium text-black mb-1">Hero Headline</label>
                            <input type="text"
                                   name="intro_headline"
                                   id="intro_headline"
                                   value="<?php echo e(old('intro_headline', $branding->intro_headline)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Join Our Amazing Team">
                            <?php $__errorArgs = ['intro_headline'];
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

                        <!-- Intro Subtitle -->
                        <div>
                            <label for="intro_subtitle" class="block text-sm font-medium text-black mb-1">Hero Subtitle</label>
                            <textarea name="intro_subtitle"
                                      id="intro_subtitle"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="We're looking for talented individuals to join our growing team..."><?php echo e(old('intro_subtitle', $branding->intro_subtitle)); ?></textarea>
                            <?php $__errorArgs = ['intro_subtitle'];
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

                    <!-- Right Column - Preview -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-black">Preview</h3>
                        
                        <!-- Hero Section Preview -->
                        <div class="relative bg-gray-200 rounded-lg overflow-hidden" style="height: 300px;">
                            <?php if($branding->hero_image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $branding->hero_image_path)); ?>" 
                                     alt="Hero Preview" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600"></div>
                            <?php endif; ?>
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                            
                            <!-- Content -->
                            <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-6 text-center">
                                <?php if($branding->logo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $branding->logo_path)); ?>" 
                                         alt="Logo" 
                                         class="h-12 mb-4">
                                <?php endif; ?>
                                
                                <h2 class="text-2xl font-bold mb-2">
                                    <?php echo e($branding->intro_headline ?? 'Join Our Amazing Team'); ?>

                                </h2>
                                
                                <p class="text-lg opacity-90">
                                    <?php echo e($branding->intro_subtitle ?? 'We\'re looking for talented individuals to join our growing team...'); ?>

                                </p>
                                
                                <button class="mt-4 px-6 py-2 rounded-md text-white font-medium"
                                        style="background-color: <?php echo e($branding->primary_color ?? '#4f46e5'); ?>">
                                    View Open Positions
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Settings
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
        // Sync color picker with text input
        document.getElementById('primary_color').addEventListener('input', function() {
            this.nextElementSibling.value = this.value;
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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/settings/careers.blade.php ENDPATH**/ ?>