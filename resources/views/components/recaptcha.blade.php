@props(['siteKey' => null])

@php
    $siteKey = $siteKey ?? config('recaptcha.site_key');
    $host = request()->getHost();
    $hostWithoutPort = explode(':', $host)[0];
    
    // Check if we should skip reCAPTCHA widget for localhost in development
    $skipLocalhostInDev = config('recaptcha.skip_localhost_in_dev', true);
    $isLocalhost = in_array($hostWithoutPort, ['localhost', '127.0.0.1', '0.0.0.0', '::1']) 
        || (app()->environment(['local', 'development']) && 
            (str_contains($hostWithoutPort, 'localhost') || str_contains($hostWithoutPort, '127.0.0.1')));
    
    $shouldSkip = $skipLocalhostInDev && app()->environment(['local', 'development']) && $isLocalhost;
@endphp

@if($siteKey && !$shouldSkip)
    <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
    
    @once
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endonce
@elseif($shouldSkip)
    {{-- Skip reCAPTCHA widget in development for localhost - validation will be skipped server-side --}}
    <input type="hidden" name="g-recaptcha-response" value="dev-skip">
    <div class="text-xs text-gray-500 text-center italic">
        reCAPTCHA skipped in development mode
    </div>
@else
    <div class="text-red-500 text-sm">reCAPTCHA not configured</div>
@endif
