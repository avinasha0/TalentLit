<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant sign in — {{ $tenant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    @php
        $brandHex = $tenant->branding?->primary_color ?: '#0f766e';
        $careersUrl = request()->routeIs('subdomain.candidate.login')
            ? route('subdomain.careers.index')
            : route('careers.index', ['tenant' => $tenant->slug]);
    @endphp
    <style>:root { --signin-brand: {{ $brandHex }}; }</style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100">
    <div class="min-h-full flex flex-col items-center justify-center px-4 py-10 sm:py-14 relative overflow-hidden">
        <div class="absolute inset-0 pointer-events-none opacity-40" style="background: radial-gradient(800px 400px at 20% -10%, rgba(15, 118, 110, 0.12), transparent), radial-gradient(600px 360px at 100% 20%, rgb(224 231 255 / 0.5), transparent);"></div>

        <div class="relative w-full max-w-md">
            <div class="text-center mb-8">
                @if($tenant->branding?->logo_path)
                    <img src="{{ asset('storage/'.$tenant->branding->logo_path) }}" alt="{{ $tenant->name }}" class="h-11 mx-auto mb-4 object-contain">
                @else
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl text-lg font-bold text-white shadow-lg ring-1 ring-black/5" style="background-color: var(--signin-brand);">
                        {{ strtoupper(substr($tenant->name, 0, 1)) }}
                    </div>
                @endif
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Applicant portal</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900 tracking-tight">Sign in</h1>
                <p class="mt-2 text-sm text-slate-600">{{ $tenant->name }}</p>
                <p class="mt-4 text-xs text-slate-500 leading-relaxed max-w-sm mx-auto">
                    For job applicants only. Staff and hiring team should use the
                    <a href="{{ route('login') }}" class="font-medium underline decoration-slate-300 hover:decoration-slate-500 text-slate-700">organization sign in</a>.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-xl shadow-slate-900/10 ring-1 ring-slate-200/80">
                <form method="POST" action="{{ $loginAction }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Work email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-teal-700/30 focus:ring-2 focus:ring-teal-600/25 outline-none transition-shadow">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1.5">Password</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password"
                               class="w-full rounded-xl border border-slate-200 bg-slate-50/50 px-4 py-3 text-sm text-slate-900 focus:border-teal-700/30 focus:ring-2 focus:ring-teal-600/25 outline-none transition-shadow">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3 pt-1">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input id="remember" type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-teal-700 focus:ring-teal-600">
                            <span class="text-sm text-slate-600">Remember this device</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl py-3.5 px-4 text-sm font-semibold text-white shadow-md hover:brightness-105 active:brightness-95 transition-all"
                            style="background-color: var(--signin-brand);">
                        Continue to dashboard
                    </button>
                </form>
            </div>

            <p class="text-center mt-8">
                <a href="{{ $careersUrl }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 underline decoration-slate-300 hover:decoration-slate-500 underline-offset-4">← Back to careers</a>
            </p>
        </div>
    </div>
</body>
</html>
