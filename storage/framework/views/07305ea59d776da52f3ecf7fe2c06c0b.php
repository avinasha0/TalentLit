<nav class="flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="inline-flex items-center">
                <?php if($index > 0): ?>
                    <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                <?php endif; ?>
                
                <?php if(isset($item['url']) && $index < count($items) - 1): ?>
                    <a href="<?php echo e($item['url']); ?>" class="text-gray-700 hover:text-blue-600 text-sm font-medium">
                        <?php echo e($item['label']); ?>

                    </a>
                <?php else: ?>
                    <span class="text-gray-500 text-sm font-medium" aria-current="page">
                        <?php echo e($item['label']); ?>

                    </span>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </ol>
</nav>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/breadcrumbs.blade.php ENDPATH**/ ?>