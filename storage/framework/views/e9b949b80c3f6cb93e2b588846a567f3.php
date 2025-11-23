<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['role' => null, 'permission' => null]));

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

foreach (array_filter((['role' => null, 'permission' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if($role): ?>
    <?php
        $userRole = \DB::table('custom_user_roles')
            ->where('user_id', auth()->id())
            ->where('tenant_id', $tenant->id ?? '')
            ->value('role_name');
    ?>
    <?php if($userRole === $role): ?>
        <?php echo e($slot); ?>

    <?php endif; ?>
<?php elseif($permission): ?>
    <?php if (app('App\Support\CustomPermissionChecker')->check($permission, $tenant ?? tenant())): ?>
        <?php echo e($slot); ?>

    <?php endif; ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/auth/for.blade.php ENDPATH**/ ?>