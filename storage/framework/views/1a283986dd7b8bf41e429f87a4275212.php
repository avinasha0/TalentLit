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
    $host = request()->getHost();
    $hostWithoutPort = explode(':', $host)[0];
    
    // Check if we should skip reCAPTCHA widget for localhost in development
    $skipLocalhostInDev = config('recaptcha.skip_localhost_in_dev', true);
    $isLocalhost = in_array($hostWithoutPort, ['localhost', '127.0.0.1', '0.0.0.0', '::1']) 
        || (app()->environment(['local', 'development']) && 
            (str_contains($hostWithoutPort, 'localhost') || str_contains($hostWithoutPort, '127.0.0.1')));
    
    $shouldSkip = $skipLocalhostInDev && app()->environment(['local', 'development']) && $isLocalhost;
?>

<?php if($siteKey && !$shouldSkip): ?>
    <div class="g-recaptcha" data-sitekey="<?php echo e($siteKey); ?>"></div>
    
    <?php if (! $__env->hasRenderedOnce('69ff6091-9142-45c6-9e16-b65a95c796f4')): $__env->markAsRenderedOnce('69ff6091-9142-45c6-9e16-b65a95c796f4'); ?>
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <?php endif; ?>
<?php elseif($shouldSkip): ?>
    
    <input type="hidden" name="g-recaptcha-response" value="dev-skip">
    <div class="text-xs text-gray-500 text-center italic">
        reCAPTCHA skipped in development mode
    </div>
<?php else: ?>
    <div class="text-red-500 text-sm">reCAPTCHA not configured</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/components/recaptcha.blade.php ENDPATH**/ ?>