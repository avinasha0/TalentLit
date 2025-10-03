<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role' => null, 'size' => 'sm']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['role' => null, 'size' => 'sm']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $role = $role ?? auth()->user()->roles->first();
    $sizeClasses = [
        'xs' => 'px-2 py-0.5 text-xs',
        'sm' => 'px-2.5 py-1 text-sm',
        'md' => 'px-3 py-1.5 text-base',
        'lg' => 'px-4 py-2 text-lg'
    ];
?>

<?php if($role): ?>
    <span class="inline-flex items-center <?php echo e($sizeClasses[$size]); ?> rounded-full font-medium
        <?php if($role->name === 'Owner'): ?> bg-purple-100 text-purple-800 border border-purple-200
        <?php elseif($role->name === 'Admin'): ?> bg-blue-100 text-blue-800 border border-blue-200
        <?php elseif($role->name === 'Recruiter'): ?> bg-green-100 text-green-800 border border-green-200
        <?php elseif($role->name === 'Hiring Manager'): ?> bg-yellow-100 text-yellow-800 border border-yellow-200
        <?php else: ?> bg-gray-100 text-gray-800 border border-gray-200 <?php endif; ?>">
        
        <?php if($size !== 'xs'): ?>
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
            </svg>
        <?php endif; ?>
        
        <?php echo e($role->name); ?>

    </span>
<?php else: ?>
    <span class="inline-flex items-center <?php echo e($sizeClasses[$size]); ?> rounded-full font-medium bg-gray-100 text-gray-800 border border-gray-200">
        No Role
    </span>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\components\role-badge.blade.php ENDPATH**/ ?>