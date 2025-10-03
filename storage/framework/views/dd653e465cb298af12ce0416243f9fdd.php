<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['user' => null, 'showTitle' => true]));

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

foreach (array_filter((['user' => null, 'showTitle' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $user = $user ?? auth()->user();
    $role = $user->roles->first();
    $permissions = $role ? $role->permissions : collect();
    
    $permissionGroups = [
        'Jobs' => ['view jobs', 'create jobs', 'edit jobs', 'delete jobs', 'publish jobs', 'close jobs'],
        'Candidates' => ['view candidates', 'create candidates', 'edit candidates', 'delete candidates', 'move candidates', 'import candidates'],
        'Stages' => ['view stages', 'create stages', 'edit stages', 'delete stages', 'reorder stages', 'manage stages'],
        'System' => ['view dashboard', 'manage users', 'manage settings']
    ];
?>

<?php if($role): ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <?php if($showTitle): ?>
            <h3 class="text-lg font-medium text-gray-900 mb-3">Your Permissions</h3>
        <?php endif; ?>
        
        <div class="space-y-4">
            <?php $__currentLoopData = $permissionGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupName => $groupPermissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 mb-2"><?php echo e($groupName); ?></h4>
                    <div class="flex flex-wrap gap-2">
                        <?php $__currentLoopData = $groupPermissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $hasPermission = $permissions->contains('name', $permission);
                            ?>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                <?php if($hasPermission): ?> bg-green-100 text-green-800 border border-green-200
                                <?php else: ?> bg-gray-100 text-gray-500 border border-gray-200 <?php endif; ?>">
                                <?php if($hasPermission): ?>
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                <?php endif; ?>
                                <?php echo e(str_replace('_', ' ', $permission)); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
<?php else: ?>
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="text-center text-gray-500">
            <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <p>No role assigned. Contact your administrator.</p>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\components\role-permissions.blade.php ENDPATH**/ ?>