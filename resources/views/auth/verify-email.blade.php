<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Verify Email - TalentLit ATS';
        $seoDescription = 'Verify your email address to complete your TalentLit account setup and start using our modern ATS platform.';
        $seoKeywords = 'TalentLit, email verification, ATS, account setup, recruitment';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    @endphp
    @include('layouts.partials.head')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-sm shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="/" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-12">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Verify Your Email
                </h2>
                <p class="text-lg text-gray-600 mb-4">
                    We've sent a verification code to
                </p>
                <p class="text-lg font-semibold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg inline-block">
                    {{ $email }}
                </p>
            </div>

            <!-- Verification Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                @if (session('success'))
                    <div class="rounded-lg bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    {{ session('success') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="rounded-lg bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    {{ session('error') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <form class="space-y-6" action="{{ route('verification.verify-otp') }}" method="POST">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <!-- OTP Input -->
                    <div>
                        <label for="otp" class="block text-sm font-semibold text-gray-700 mb-2">
                            Enter Verification Code
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                id="otp" 
                                name="otp" 
                                type="text" 
                                required 
                                maxlength="6"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors text-center text-2xl font-mono tracking-widest"
                                placeholder="000000"
                                autocomplete="off"
                            >
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Enter the 6-digit code sent to your email address.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button 
                            type="submit" 
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verify Email
                        </button>
                    </div>

                    <!-- Resend Code -->
                    <div class="flex items-center justify-between">
                        <div class="text-sm">
                            <p class="text-gray-600">
                                Didn't receive the code?
                            </p>
                        </div>
                        <div class="text-sm">
                            <form action="{{ route('verification.resend') }}" method="POST" class="inline">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="font-semibold text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition-colors"
                                >
                                    Resend Code
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-8 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gradient-to-br from-indigo-50 via-white to-purple-50 text-gray-500">Need help?</span>
                    </div>
                </div>

                <div class="mt-6">
                    <p class="text-sm text-gray-600">
                        If you're having trouble receiving the verification code, please check your spam folder or 
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                            try registering again
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Footer -->
    <x-newsletter-footer />
    
    <!-- Centralized Footer Component -->
    <x-footer />

    <script>
        // Auto-focus on OTP input
        document.getElementById('otp').focus();

        // Auto-submit when 6 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            const value = e.target.value.replace(/\D/g, ''); // Remove non-digits
            e.target.value = value;
            
            if (value.length === 6) {
                // Small delay to show the complete code
                setTimeout(() => {
                    e.target.form.submit();
                }, 100);
            }
        });

        // Only allow digits
        document.getElementById('otp').addEventListener('keypress', function(e) {
            if (!/\d/.test(e.key)) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>