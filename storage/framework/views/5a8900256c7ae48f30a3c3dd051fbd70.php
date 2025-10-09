<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="h-full">
    <head>
        <?php
            $seoTitle = ($title ?? 'Welcome') . ' – TalentLit ATS';
            $seoDescription = 'Access TalentLit, the modern Applicant Tracking System for smarter recruitment. Sign in to manage your hiring pipeline, track candidates, and streamline your recruitment process.';
        ?>
        <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </head>
    <body class="h-full <?php echo e($attributes->get('class', 'bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800')); ?>">
        <div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8">
            <div class="sm:mx-auto sm:w-full <?php echo e($attributes->get('max-width', 'sm:max-w-md')); ?>">
                <div class="flex justify-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.png')); ?>" alt="TalentLit Logo" class="h-10">
                    </a>
                </div>
                <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                    <?php echo e($title ?? 'Welcome'); ?>

                </h2>
                <?php if(isset($subtitle)): ?>
                    <p class="mt-2 text-center text-sm text-gray-600 dark:text-gray-400">
                        <?php echo e($subtitle); ?>

                    </p>
                <?php endif; ?>
            </div>

            <div class="mt-8 sm:mx-auto sm:w-full <?php echo e($attributes->get('max-width', 'sm:max-w-md')); ?>">
                <div class="bg-white dark:bg-gray-800 py-8 px-4 shadow-xl sm:rounded-lg sm:px-10 border border-gray-200 dark:border-gray-700">
                    <?php if(isset($slot)): ?>
                        <?php echo e($slot); ?>

                    <?php else: ?>
                        <?php echo $__env->yieldContent('content'); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/layouts/guest.blade.php ENDPATH**/ ?>