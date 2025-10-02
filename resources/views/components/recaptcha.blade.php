@props(['siteKey' => null])

@php
    $siteKey = $siteKey ?? config('recaptcha.site_key');
@endphp

@if($siteKey)
    <div class="g-recaptcha" data-sitekey="{{ $siteKey }}"></div>
    
    @once
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endonce
@else
    <div class="text-red-500 text-sm">reCAPTCHA not configured</div>
@endif
