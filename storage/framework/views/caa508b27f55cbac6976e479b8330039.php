<form method="POST" action="<?php echo e(route('logout')); ?>" class="inline">
    <?php echo csrf_field(); ?>
    <button type="submit" class="<?php echo e($attributes->get('class', 'text-gray-700 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white')); ?>">
        <?php echo e($slot); ?>

    </button>
</form><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\components\logout-button.blade.php ENDPATH**/ ?>