<?php $__env->startSection('title', 'How to Login — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to sign in to your TalentLit account and troubleshoot common login issues.'); ?>

<?php
    $seoTitle = 'How to Login — TalentLit Help';
    $seoDescription = 'Learn how to sign in to your TalentLit account and troubleshoot common login issues.';
    $seoKeywords = 'TalentLit login, sign in, ATS login, account access';
    $seoAuthor = 'TalentLit';
    $seoImage = asset('logo-talentlit-small.svg');
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('help.index')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        ← Back to Help Center
                    </a>
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        Sign In
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    How to Login to TalentLit
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Access your TalentLit account and get back to managing your recruitment process.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Table of Contents -->
                <div class="lg:col-span-3">
                    <div class="sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Table of Contents</h3>
                        <nav class="space-y-2">
                            <a href="#step-1" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">1. Access Login Page</a>
                            <a href="#step-2" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">2. Enter Credentials</a>
                            <a href="#step-3" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">3. Access Dashboard</a>
                            <a href="#forgot-password" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Forgot Password</a>
                            <a href="#logout" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">How to Logout</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <h2 id="step-1" class="text-2xl font-bold text-gray-900 mb-6">1. Access Login Page</h2>
                        <p class="text-gray-600 mb-6">
                            To sign in to your TalentLit account, you'll need to navigate to the login page. Here are the different ways to access it:
                        </p>
                        
                        <div class="bg-gray-50 p-6 rounded-lg mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ways to Access Login:</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Click the <strong>"Sign In"</strong> button on the homepage</li>
                                <li>• Click the <strong>"Login"</strong> link in the navigation menu</li>
                                <li>• Visit <code class="bg-gray-200 px-2 py-1 rounded text-sm"><?php echo e(url('/login')); ?></code> directly</li>
                                <li>• Click <strong>"Already have an account?"</strong> on the registration page</li>
                            </ul>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Screenshot</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <img src="<?php echo e(asset('images/help/login-step1.png')); ?>" alt="Login page access" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-2" class="text-2xl font-bold text-gray-900 mb-6">2. Enter Your Credentials</h2>
                        <p class="text-gray-600 mb-6">
                            Once you're on the login page, enter your account credentials to sign in.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Login Information Required:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Email Address:</strong> Enter the email address you used to register
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Password:</strong> Enter your account password
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Remember Me (Optional):</strong> Check this box to stay logged in
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.726-1.36 3.491 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Security Tips:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Never share your login credentials with others</li>
                                            <li>Use a strong, unique password</li>
                                            <li>Only check "Remember Me" on trusted devices</li>
                                            <li>Log out when using shared computers</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Screenshot</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <img src="<?php echo e(asset('images/help/login-step2.png')); ?>" alt="Login form" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-3" class="text-2xl font-bold text-gray-900 mb-6">3. Access Your Dashboard</h2>
                        <p class="text-gray-600 mb-6">
                            After successful login, you'll be redirected to your dashboard where you can manage your recruitment process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">What You'll See After Login:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Dashboard Overview:</strong> Key metrics and recent activity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Navigation Menu:</strong> Access to all features and settings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Quick Actions:</strong> Create jobs, manage candidates, schedule interviews
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="forgot-password" class="text-2xl font-bold text-gray-900 mb-6">Forgot Password?</h2>
                        <p class="text-gray-600 mb-6">
                            If you can't remember your password, you can reset it using the "Forgot Password" feature.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Password Reset Process:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Click "Forgot Password":</strong> On the login page, click the "Forgot your password?" link
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Enter Email:</strong> Enter the email address associated with your account
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Check Email:</strong> Look for a password reset link in your email
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Create New Password:</strong> Click the link and create a new password
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="logout" class="text-2xl font-bold text-gray-900 mb-6">How to Logout</h2>
                        <p class="text-gray-600 mb-6">
                            When you're done using TalentLit, it's important to log out for security reasons.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Logout Methods:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>User Menu:</strong> Click your profile picture/name in the top-right corner, then select "Logout"
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Direct URL:</strong> Visit <code class="bg-gray-200 px-2 py-1 rounded text-sm"><?php echo e(url('/logout')); ?></code> to logout
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Session Timeout:</strong> You'll be automatically logged out after a period of inactivity
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-red-50 border border-red-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Important Security Note:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <p>Always log out when using shared or public computers to protect your account security.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Invalid Credentials Error</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Double-check your email address and password</li>
                                        <li>• Make sure Caps Lock is not enabled</li>
                                        <li>• Try typing your password in a text editor first to verify it</li>
                                        <li>• Use the "Forgot Password" feature to reset your password</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Account Not Found</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify you're using the correct email address</li>
                                        <li>• Check if you need to register first</li>
                                        <li>• Contact support if you believe your account exists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Login Page Not Loading</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Clear your browser cache and cookies</li>
                                        <li>• Try using a different browser</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="related-topics" class="bg-indigo-50 p-8 rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Topics</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Getting Started</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'register')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">How to Register</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'onboarding')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Setup</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'dashboard')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Dashboard Overview</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'troubleshooting')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'contact')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact Support</a></li>
                                        <li><a href="<?php echo e(route('help.index')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/login.blade.php ENDPATH**/ ?>