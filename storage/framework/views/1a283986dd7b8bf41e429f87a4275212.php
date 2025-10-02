<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['siteKey' => null]));

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

foreach (array_filter((['siteKey' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $siteKey = $siteKey ?? config('recaptcha.site_key');
?>

<?php if($siteKey): ?>
    <div class="g-recaptcha" data-sitekey="<?php echo e($siteKey); ?>"></div>
    
    <?php if (! $__env->hasRenderedOnce('115eacfc-349e-4b99-b8a5-e50d089d2b6d')): $__env->markAsRenderedOnce('115eacfc-349e-4b99-b8a5-e50d089d2b6d'); ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
<?php else: ?>
    <div class="text-red-500 text-sm">reCAPTCHA not configured</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/recaptcha.blade.php ENDPATH**/ ?>