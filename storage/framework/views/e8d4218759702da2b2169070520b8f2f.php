<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Newsletter Subscription - TalentLit ATS';
        $seoDescription = 'Subscribe to TalentLit newsletter and get the latest updates on recruitment trends, hiring tips, and ATS features.';
        $seoKeywords = 'TalentLit, newsletter, subscription, recruitment, hiring, ATS, updates';
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
                    <a href="<?php echo e(route('register')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign Up
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
                    <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-12">
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    Stay Updated
                </h2>
                <p class="text-lg text-gray-600">
                    Get the latest updates on new features, tips, and best practices for recruitment.
                </p>
            </div>

            <!-- Newsletter Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
                <!-- Success Message -->
                <?php if(isset($success)): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800"><?php echo e($success); ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Error Messages -->
                <?php if(isset($errors) && count($errors) > 0): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <p class="text-sm text-red-800"><?php echo e($error); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <!-- Email Form -->
                <form id="newsletter-form" method="POST" action="<?php echo e(route('newsletter.subscribe')); ?>" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               name="email" 
                               id="newsletter-email" 
                               value="<?php echo e(isset($old_email) ? $old_email : ''); ?>"
                               placeholder="Enter your email" 
                               class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                               required>
                        <button type="submit" 
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Subscribe
                        </button>
                    </div>
                    
                    <!-- reCAPTCHA -->
                    <div class="flex justify-center">
                        <?php if (isset($component)) { $__componentOriginalc8680908dbd82701772537a108fb92cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8680908dbd82701772537a108fb92cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.recaptcha','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('recaptcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8680908dbd82701772537a108fb92cd)): ?>
<?php $attributes = $__attributesOriginalc8680908dbd82701772537a108fb92cd; ?>
<?php unset($__attributesOriginalc8680908dbd82701772537a108fb92cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8680908dbd82701772537a108fb92cd)): ?>
<?php $component = $__componentOriginalc8680908dbd82701772537a108fb92cd; ?>
<?php unset($__componentOriginalc8680908dbd82701772537a108fb92cd); ?>
<?php endif; ?>
                    </div>
                    
                    <!-- Error Message -->
                    <div id="recapError" class="text-red-500 text-sm mt-2 text-center" style="display:none;"></div>
                </form>

                <!-- OTP Form (Hidden Initially) -->
                <form id="otp-form" method="POST" action="<?php echo e(route('newsletter.verify-otp')); ?>" class="space-y-4 hidden">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="email" id="otp-email">
                    
                    <div class="text-center mb-4">
                        <p class="text-sm text-gray-600">
                            We've sent a verification code to <span id="otp-email-display" class="font-semibold text-gray-900"></span>
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Please check your email and enter the code below</p>
                    </div>
                    
                    <div class="flex justify-center">
                        <input type="text" 
                               name="otp" 
                               id="otp-input" 
                               placeholder="Enter 6-digit code" 
                               maxlength="6"
                               class="w-32 px-4 py-3 text-center text-lg font-mono border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                               required>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Verify & Subscribe
                        </button>
                        <button type="button" 
                                id="resend-otp-btn"
                                class="px-4 py-3 border border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition-all duration-200">
                            Resend
                        </button>
                    </div>
                </form>
            </div>

            <!-- Benefits Preview -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 mb-4">What you'll get with our newsletter:</p>
                <div class="flex justify-center space-x-6 text-xs text-gray-400">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Latest Features
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Hiring Tips
                    </div>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Industry News
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize newsletter OTP functionality
            initializeNewsletterOTP();
        });
        
        // Newsletter subscription with OTP verification
        function initializeNewsletterOTP() {
            let currentEmail = '';
            const newsletterForm = document.getElementById('newsletter-form');
            const otpForm = document.getElementById('otp-form');
            const resendBtn = document.getElementById('resend-otp-btn');
            
            if (newsletterForm) {
                newsletterForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    console.log('Newsletter form submitted - checking reCAPTCHA...');
                    
                    // Check reCAPTCHA first
                    const recaptchaTextarea = document.querySelector('textarea[name="g-recaptcha-response"]');
                    const recaptchaToken = recaptchaTextarea ? recaptchaTextarea.value.trim() : '';
                    
                    if (!recaptchaToken) {
                        console.log('reCAPTCHA not completed - showing error');
                        
                        // Show error message
                        const errorDiv = document.getElementById('recapError');
                        if (errorDiv) {
                            errorDiv.textContent = 'Please complete the reCAPTCHA verification by clicking "I am not a robot".';
                            errorDiv.style.display = 'block';
                        }
                        
                        // Scroll to reCAPTCHA
                        const recaptchaWidget = document.querySelector('.g-recaptcha');
                        if (recaptchaWidget) {
                            recaptchaWidget.scrollIntoView({behavior: 'smooth'});
                        }
                        
                        return false;
                    }
                    
                    console.log('reCAPTCHA completed - submitting via AJAX');
                    
                    const email = document.getElementById('newsletter-email').value;
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.textContent;
                    
                    // Show loading state
                    button.textContent = 'Sending...';
                    button.disabled = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('email', email);
                        formData.append('g-recaptcha-response', recaptchaToken);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        const response = await fetch('/newsletter/subscribe', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.requires_verification) {
                            currentEmail = email;
                            document.getElementById('otp-email-display').textContent = email;
                            document.getElementById('otp-email').value = email;
                            document.getElementById('newsletter-form').classList.add('hidden');
                            document.getElementById('otp-form').classList.remove('hidden');
                        } else {
                            showNotification(data.message || 'Something went wrong. Please try again.', 'error');
                            button.textContent = originalText;
                            button.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Something went wrong. Please try again.', 'error');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                });
            }
            
            // OTP form submission
            if (otpForm) {
                otpForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const otp = document.getElementById('otp-input').value;
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.textContent;
                    
                    // Show loading state
                    button.textContent = 'Verifying...';
                    button.disabled = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('email', currentEmail);
                        formData.append('otp', otp);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        const response = await fetch('/newsletter/verify-otp', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            showNotification(data.message || 'Successfully subscribed!', 'success');
                            // Reset forms
                            document.getElementById('newsletter-form').classList.remove('hidden');
                            document.getElementById('otp-form').classList.add('hidden');
                            document.getElementById('newsletter-email').value = '';
                            document.getElementById('otp-input').value = '';
                        } else {
                            showNotification(data.message || 'Invalid verification code. Please try again.', 'error');
                            button.textContent = originalText;
                            button.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Something went wrong. Please try again.', 'error');
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                });
            }
            
            // Resend OTP
            if (resendBtn) {
                resendBtn.addEventListener('click', async function() {
                    const button = this;
                    const originalText = button.textContent;
                    
                    button.textContent = 'Sending...';
                    button.disabled = true;
                    
                    try {
                        const formData = new FormData();
                        formData.append('email', currentEmail);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        const response = await fetch('/newsletter/resend-otp', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            showNotification(data.message || 'Verification code resent!', 'success');
                        } else {
                            showNotification(data.message || 'Failed to resend code. Please try again.', 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showNotification('Something went wrong. Please try again.', 'error');
                    } finally {
                        button.textContent = originalText;
                        button.disabled = false;
                    }
                });
            }
        }
        
        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            
            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            notification.className += ` ${colors[type]}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/newsletter/subscribe.blade.php ENDPATH**/ ?>