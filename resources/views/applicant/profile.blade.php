<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My profile — Applicant portal — {{ $tenant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
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
        $careersUrl = request()->routeIs('subdomain.applicant.*')
            ? route('subdomain.careers.index')
            : route('careers.index', ['tenant' => $tenant->slug]);
        $isSubdomainApplicant = request()->routeIs('subdomain.applicant.*');
        $emailAction = $isSubdomainApplicant
            ? route('subdomain.applicant.profile.email')
            : route('tenant.applicant.profile.email', ['tenant' => $tenant->slug]);
        $passwordAction = $isSubdomainApplicant
            ? route('subdomain.applicant.profile.password')
            : route('tenant.applicant.profile.password', ['tenant' => $tenant->slug]);
    @endphp
    <style>
        :root { --applicant-brand: {{ $brandHex }}; }
        .bg-app { background-color: #f1f5f9; background-image: radial-gradient(1200px 400px at 0% -10%, rgba(15, 118, 110, 0.06), transparent), radial-gradient(800px 320px at 100% 0%, rgb(226 232 240), transparent); }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-app">
    <div class="min-h-full flex flex-col">
        @include('applicant.partials.portal-nav', ['active' => 'profile'])

        <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
                <div>
                    <nav class="text-sm text-slate-500 mb-2">
                        <span class="text-slate-400">Account</span>
                        <span class="mx-2 text-slate-300">/</span>
                        <span class="font-medium text-slate-700">My profile</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">My profile</h1>
                    <p class="mt-2 text-slate-600 max-w-2xl text-sm sm:text-base leading-relaxed">
                        Update the email and password you use to sign in to this applicant portal for <span class="font-medium text-slate-800">{{ $tenant->name }}</span>.
                    </p>
                </div>
                <a href="{{ $careersUrl }}" class="inline-flex items-center justify-center gap-2 self-start rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-slate-900/10 hover:opacity-95 transition-opacity" style="background: var(--applicant-brand);">
                    <svg class="w-4 h-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Browse open roles
                </a>
            </div>

            <div class="grid gap-8 lg:grid-cols-2">
                <section class="rounded-2xl bg-white p-6 sm:p-8 ring-1 ring-slate-200/80 shadow-md shadow-slate-900/5">
                    <h2 class="text-lg font-bold text-slate-900">Change email</h2>
                    <p class="mt-1 text-sm text-slate-600">Your sign-in address must stay unique for this organization.</p>

                    @if (session('status') === 'email-updated')
                        <p class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-900">Email updated successfully.</p>
                    @endif

                    <form method="POST" action="{{ $emailAction }}" class="mt-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="email_current_password" class="block text-sm font-medium text-slate-700">Current password</label>
                            <input type="password" name="current_password" id="email_current_password" required autocomplete="current-password"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 @error('current_password', 'updateEmail') border-red-300 @enderror">
                            @error('current_password', 'updateEmail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700">New email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $account->email) }}" required autocomplete="email"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 @error('email', 'updateEmail') border-red-300 @enderror">
                            @error('email', 'updateEmail')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:opacity-95 transition-opacity" style="background: var(--applicant-brand);">
                            Save email
                        </button>
                    </form>
                </section>

                <section class="rounded-2xl bg-white p-6 sm:p-8 ring-1 ring-slate-200/80 shadow-md shadow-slate-900/5">
                    <h2 class="text-lg font-bold text-slate-900">Change password</h2>
                    <p class="mt-1 text-sm text-slate-600">Use a strong password you have not reused elsewhere.</p>

                    @if (session('status') === 'password-updated')
                        <p class="mt-4 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-medium text-emerald-900">Password updated successfully.</p>
                    @endif

                    <form method="POST" action="{{ $passwordAction }}" class="mt-6 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label for="password_current_password" class="block text-sm font-medium text-slate-700">Current password</label>
                            <input type="password" name="current_password" id="password_current_password" required autocomplete="current-password"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 @error('current_password', 'updatePassword') border-red-300 @enderror">
                            @error('current_password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700">New password</label>
                            <input type="password" name="password" id="password" required autocomplete="new-password"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200 @error('password', 'updatePassword') border-red-300 @enderror">
                            @error('password', 'updatePassword')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Confirm new password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 px-3 py-2 text-sm shadow-sm focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-200">
                        </div>
                        <button type="submit" class="inline-flex w-full sm:w-auto items-center justify-center rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-md hover:opacity-95 transition-opacity" style="background: var(--applicant-brand);">
                            Save password
                        </button>
                    </form>
                </section>
            </div>

            @if($candidate)
                <p class="mt-8 text-xs text-slate-500">Application records for this organization are linked to your account. If you change email, future messages will use the new address where applicable.</p>
            @endif
        </main>

        <footer class="mt-auto border-t border-slate-200/80 bg-white/80 py-6">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</p>
                <p class="text-center sm:text-right">You are signed in to the applicant portal only for this organization.</p>
            </div>
        </footer>
    </div>
</body>
</html>
