<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <?php
            $seoTitle = (isset($header) ? strip_tags($header) . ' – ' : '') . 'TalentLit ATS';
            $seoDescription = 'Manage your recruitment process with TalentLit. Track candidates, schedule interviews, and make data-driven hiring decisions with our modern ATS platform.';
            $tenant = tenant();
        ?>
        <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="font-sans antialiased" x-data x-init="$store.sidebar = { open: false }">
        <div class="min-h-screen bg-gray-100 flex">
            <!-- Sidebar -->
            <?php if (isset($component)) { $__componentOriginal2880b66d47486b4bfeaf519598a469d6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2880b66d47486b4bfeaf519598a469d6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.sidebar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('sidebar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $attributes = $__attributesOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__attributesOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2880b66d47486b4bfeaf519598a469d6)): ?>
<?php $component = $__componentOriginal2880b66d47486b4bfeaf519598a469d6; ?>
<?php unset($__componentOriginal2880b66d47486b4bfeaf519598a469d6); ?>
<?php endif; ?>
            
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col lg:ml-0">
                <!-- Mobile Header -->
                <?php if (isset($component)) { $__componentOriginal415cf90115c14f51a96642adfc4a4cc2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal415cf90115c14f51a96642adfc4a4cc2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.mobile-header','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('mobile-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal415cf90115c14f51a96642adfc4a4cc2)): ?>
<?php $attributes = $__attributesOriginal415cf90115c14f51a96642adfc4a4cc2; ?>
<?php unset($__attributesOriginal415cf90115c14f51a96642adfc4a4cc2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal415cf90115c14f51a96642adfc4a4cc2)): ?>
<?php $component = $__componentOriginal415cf90115c14f51a96642adfc4a4cc2; ?>
<?php unset($__componentOriginal415cf90115c14f51a96642adfc4a4cc2); ?>
<?php endif; ?>
                
                <!-- Desktop Navigation -->
                <div class="hidden lg:block">
                    <?php echo $__env->make('layouts.navigation', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <!-- Page Heading -->
                <?php if(isset($header)): ?>
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            <?php echo e($header); ?>

                        </div>
                    </header>
                <?php endif; ?>

                <!-- Page Content -->
                <main class="flex-1">
                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>
        
        <?php echo $__env->make('layouts.partials.mobile-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/layouts/app.blade.php ENDPATH**/ ?>