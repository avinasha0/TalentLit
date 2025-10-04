<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Verify Email - TalentLit ATS';
        $seoDescription = 'Verify your email address to complete your TalentLit account setup and start using our modern ATS platform.';
        $seoKeywords = 'TalentLit, email verification, ATS, account setup, recruitment';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    ?>
    <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
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
        <div class="w-full max-w-md mx-auto space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-12">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Verify Your Email
                </h2>
                <p class="text-lg text-gray-600 mb-4">
                    We've sent a verification code to
                </p>
                <p class="text-lg font-semibold text-indigo-600 bg-indigo-50 px-4 py-2 rounded-lg inline-block">
                    <?php echo e($email); ?>

                </p>
            </div>

            <!-- Verification Form -->
            <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 border border-gray-100">
                <?php if(session('success')): ?>
                    <div class="rounded-lg bg-green-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    <?php echo e(session('success')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="rounded-lg bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">
                                    <?php echo e(session('error')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Initial State: Send OTP Button -->
                <div id="send-otp-section" class="space-y-6">
                    <div class="text-center">
                        <p class="text-lg text-gray-600 mb-6">
                            Click the button below to send a verification code to your email address.
                        </p>
                        
                        <form action="<?php echo e(route('verification.send')); ?>" method="POST" class="inline">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="email" value="<?php echo e($email); ?>">
                            <button 
                                type="submit" 
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Verification Code
                            </button>
                        </form>
                    </div>
                </div>

                <!-- After OTP Sent: OTP Input Form -->
                <div id="otp-form-section" class="space-y-6" style="display: none;">
                    <form class="space-y-6" action="<?php echo e(route('verification.verify-otp')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="email" value="<?php echo e($email); ?>">
                        
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
                                <form action="<?php echo e(route('verification.resend')); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
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

                <div class="mt-6 px-4">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        If you're having trouble receiving the verification code, please check your spam folder or 
                        <a href="<?php echo e(route('register')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                            try registering again
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Check if OTP was sent (success message contains "sent" or "code")
        const successMessage = document.querySelector('.bg-green-50 p');
        const hasOtpSent = successMessage && (
            successMessage.textContent.includes('sent') || 
            successMessage.textContent.includes('code') ||
            successMessage.textContent.includes('verification')
        );

        // Show OTP form if OTP was already sent
        if (hasOtpSent) {
            document.getElementById('send-otp-section').style.display = 'none';
            document.getElementById('otp-form-section').style.display = 'block';
            
            // Auto-focus on OTP input
            const otpInput = document.getElementById('otp');
            if (otpInput) {
                otpInput.focus();

                // Auto-submit when 6 digits are entered
                otpInput.addEventListener('input', function(e) {
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
                otpInput.addEventListener('keypress', function(e) {
                    if (!/\d/.test(e.key)) {
                        e.preventDefault();
                    }
                });
            }
        }

        // Handle Send OTP button click
        document.addEventListener('DOMContentLoaded', function() {
            const sendOtpForm = document.querySelector('form[action="<?php echo e(route('verification.send')); ?>"]');
            if (sendOtpForm) {
                sendOtpForm.addEventListener('submit', function(e) {
                    // Show loading state
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    button.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Sending...';
                    button.disabled = true;
                });
            }
        });
    </script>
</body>
</html><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>