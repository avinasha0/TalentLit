<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class' => '']));

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

foreach (array_filter((['class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-200 ' . $class])); ?>>
    <?php if(isset($header)): ?>
        <div class="px-6 py-4 border-b border-gray-200">
            <?php echo e($header); ?>

        </div>
    <?php endif; ?>
    
    <div class="p-6">
        <?php echo e($slot); ?>

    </div>
    
    <?php if(isset($footer)): ?>
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/card.blade.php ENDPATH**/ ?>