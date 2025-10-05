<?php if (isset($component)) { $__componentOriginal1340697896d119c6603f503f3b3d235a = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal1340697896d119c6603f503f3b3d235a = $attributes; } ?>
<?php $component = App\View\Components\OnboardingLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('onboarding-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\OnboardingLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Create Organization'),'subtitle' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute('Set up your organization to get started')]); ?>
    <form class="space-y-6" action="<?php echo e(route('onboarding.organization.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-200">Organization Name</label>
            <input id="name" name="name" type="text" required
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="Acme Corporation" />
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="slug" class="block text-sm font-medium text-gray-200">Organization URL</label>
            <div class="flex rounded-lg shadow-sm">
                <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-gray-600 bg-gray-800 text-gray-400 sm:text-sm">
                    <?php echo e(config('app.url')); ?>/
                </span>
                <input id="slug" name="slug" type="text" required
                    class="flex-1 min-w-0 block w-full rounded-none rounded-r-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                    placeholder="acme-corp" />
            </div>
            <p class="mt-1 text-xs text-gray-400">This will be your organization's unique URL</p>
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="website" class="block text-sm font-medium text-gray-200">Website (Optional)</label>
            <input id="website" name="website" type="url"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="https://acme.com" />
            <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="location" class="block text-sm font-medium text-gray-200">Location (Optional)</label>
            <input id="location" name="location" type="text"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2"
                placeholder="New York, NY" />
            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <label for="company_size" class="block text-sm font-medium text-gray-200">Company Size (Optional)</label>
            <select id="company_size" name="company_size"
                class="mt-1 block w-full rounded-lg border-gray-600 bg-gray-900/50 text-white placeholder-gray-400 focus:border-blue-500 focus:ring-blue-500 sm:text-sm px-3 py-2">
                <option value="">Select company size</option>
                <option value="1-10">1–10</option>
                <option value="11-50">11–50</option>
                <option value="51-200">51–200</option>
                <option value="201-500">201–500</option>
                <option value="500+">500+</option>
            </select>
            <?php $__errorArgs = ['company_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <p class="mt-2 text-sm text-red-400"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Create Organization
            </button>
        </div>
    </form>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal1340697896d119c6603f503f3b3d235a)): ?>
<?php $attributes = $__attributesOriginal1340697896d119c6603f503f3b3d235a; ?>
<?php unset($__attributesOriginal1340697896d119c6603f503f3b3d235a); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal1340697896d119c6603f503f3b3d235a)): ?>
<?php $component = $__componentOriginal1340697896d119c6603f503f3b3d235a; ?>
<?php unset($__componentOriginal1340697896d119c6603f503f3b3d235a); ?>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/onboarding/organization.blade.php ENDPATH**/ ?>