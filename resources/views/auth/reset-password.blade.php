<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Reset Password - TalentLit ATS';
        $seoDescription = 'Securely reset your TalentLit account password to regain access to your hiring workspace.';
        $seoKeywords = 'TalentLit, reset password, ATS login';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.png');
    @endphp
    @include('layouts.partials.head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-[#0c1630] via-[#101d3f] to-[#0c1630] text-gray-900">
    <div class="min-h-screen flex flex-col">
        <nav class="p-6 flex items-center justify-between">
            <a href="{{ route('login') }}" class="flex items-center space-x-2">
                <img src="{{ asset('logo-talentlit-small.png') }}" alt="TalentLit Logo" class="h-10">
                <span class="text-white font-semibold text-lg">TalentLit</span>
            </a>
            <a href="{{ route('login') }}" class="text-sm font-medium text-white hover:text-gray-200 transition">
                Back to Sign In
            </a>
        </nav>

        <div class="flex-1 flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
            <div class="max-w-lg w-full bg-white/95 backdrop-blur rounded-3xl shadow-2xl border border-white/30 px-8 py-10">
                <div class="text-center mb-8">
                    <p class="text-sm uppercase tracking-[0.3em] text-[#00B6B4] font-semibold">Security Check</p>
                    <h1 class="text-3xl sm:text-4xl font-bold text-[#101d3f] mt-2">Reset your password</h1>
                    <p class="text-gray-500 mt-3">Choose a strong password to keep your TalentLit workspace secure.</p>
                </div>

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6" x-data="{ showPassword: false, showConfirm: false }">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email address</label>
                        <input id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $request->email) }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-[#6E46AE] focus:ring-2 focus:ring-[#6E46AE]/40 transition"
                            placeholder="name@company.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">New password</label>
                        <div class="relative">
                            <input id="password"
                                :type="showPassword ? 'text' : 'password'"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-[#00B6B4] focus:ring-2 focus:ring-[#00B6B4]/40 transition"
                                placeholder="Create a strong password">
                            <button type="button"
                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                                @click="showPassword = !showPassword"
                                aria-label="Toggle password visibility">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm password</label>
                        <div class="relative">
                            <input id="password_confirmation"
                                :type="showConfirm ? 'text' : 'password'"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="block w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-gray-900 placeholder-gray-400 shadow-sm focus:border-[#6E46AE] focus:ring-2 focus:ring-[#6E46AE]/40 transition"
                                placeholder="Repeat the password">
                            <button type="button"
                                class="absolute inset-y-0 right-0 px-3 text-gray-400 hover:text-gray-600"
                                @click="showConfirm = !showConfirm"
                                aria-label="Toggle confirm password visibility">
                                <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex justify-center">
                        <x-recaptcha />
                    </div>

                    <div id="recaptcha-error" class="text-center text-sm text-red-500" style="display:none;"></div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-[#6E46AE] to-[#00B6B4] py-3 text-white font-semibold shadow-lg shadow-[#6E46AE]/30 hover:shadow-[#00B6B4]/40 transition-transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Reset Password
                    </button>
                </form>

                <p class="text-center text-sm text-gray-500 mt-8">
                    Need help? <a href="mailto:support@talentlit.com" class="text-[#6E46AE] font-semibold hover:text-[#00B6B4] transition">Contact TalentLit Support</a>
                </p>
            </div>
        </div>

        <div class="py-6 text-center text-xs text-white/70">
            &copy; {{ now()->year }} TalentLit. All rights reserved.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form[action="{{ route('password.store') }}"]');
            const errorDiv = document.getElementById('recaptcha-error');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                const recaptchaWidget = document.querySelector('.g-recaptcha');
                if (!recaptchaWidget) {
                    return true;
                }

                const responseField = document.querySelector('textarea[name="g-recaptcha-response"]');
                const token = responseField ? responseField.value.trim() : '';

                if (!token) {
                    e.preventDefault();
                    if (errorDiv) {
                        errorDiv.textContent = 'Please complete the reCAPTCHA verification.';
                        errorDiv.style.display = 'block';
                    }
                    recaptchaWidget.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return false;
                }

                if (errorDiv) {
                    errorDiv.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
