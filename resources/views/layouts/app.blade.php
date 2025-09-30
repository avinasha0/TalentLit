<!DOCTYPE html>
<html lang="en" class="h-full" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1"/>
  <title>{{ $title ?? 'HireHub' }}</title>
  {{-- Skip Vite in testing OR when the manifest isn't present --}}
  @unless (app()->environment('testing'))
    @if (file_exists(public_path('build/manifest.json')))
      @vite(['resources/css/app.css','resources/js/app.js'])
    @endif
  @endunless
  {{-- Minimal fallback CSS for tests --}}
  @env('testing')
    <style>
      :root{--bg:#f8fafc;--fg:#111827}
      body{background:var(--bg);color:var(--fg);font-family:ui-sans-serif,system-ui}
    </style>
  @endenv
</head>
<body class="h-full bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-gray-100">
  <x-navbar />

  <x-sidebar :tenantSlug="$tenantSlug ?? 'acme'" />

  {{-- Main content; pl is synced with sidebar width on desktop --}}
  <main id="app-main" class="pt-12 lg:pt-14 lg:pl-64 transition-[padding] duration-300">
    <div class="px-4 lg:px-6 py-4 max-w-7xl mx-auto">
      {{ $slot }}
    </div>
  </main>
</body>
</html>