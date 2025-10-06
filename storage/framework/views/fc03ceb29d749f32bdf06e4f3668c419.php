<?php $__env->startSection('title', 'How to Register — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to create a new TalentLit account and get started with your recruitment process.'); ?>

<?php
    $seoTitle = 'How to Register — TalentLit Help';
    $seoDescription = 'Learn how to create a new TalentLit account and get started with your recruitment process.';
    $seoKeywords = 'TalentLit register, create account, sign up, ATS registration';
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
                    <a href="<?php echo e(route('register')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        Get Started Free
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
                    How to Register for TalentLit
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Create your free TalentLit account in just a few minutes and start streamlining your recruitment process.
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
                            <a href="#step-1" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">1. Access Registration Page</a>
                            <a href="#step-2" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">2. Fill Out Registration Form</a>
                            <a href="#step-3" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">3. Verify Your Email</a>
                            <a href="#step-4" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">4. Complete Onboarding</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <h2 id="step-1" class="text-2xl font-bold text-gray-900 mb-6">1. Access Registration Page</h2>
                        <p class="text-gray-600 mb-6">
                            To create a new TalentLit account, you'll need to access the registration page. There are several ways to do this:
                        </p>
                        
                        <div class="bg-gray-50 p-6 rounded-lg mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ways to Access Registration:</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Click the <strong>"Get Started Free"</strong> button on the homepage</li>
                                <li>• Click the <strong>"Sign Up"</strong> link in the navigation menu</li>
                                <li>• Visit <code class="bg-gray-200 px-2 py-1 rounded text-sm"><?php echo e(url('/register')); ?></code> directly</li>
                                <li>• Click <strong>"Create Account"</strong> on the login page</li>
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
                                        <img src="<?php echo e(asset('images/help/register-step1.png')); ?>" alt="Registration page access" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-2" class="text-2xl font-bold text-gray-900 mb-6">2. Fill Out Registration Form</h2>
                        <p class="text-gray-600 mb-6">
                            Once you're on the registration page, you'll need to provide some basic information to create your account.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Information:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Full Name:</strong> Enter your complete name as it should appear in the system
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Email Address:</strong> Use a valid email address you have access to
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Password:</strong> Create a strong password (minimum 8 characters)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Confirm Password:</strong> Re-enter your password to confirm
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
                                    <h3 class="text-sm font-medium text-yellow-800">Important Notes:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Make sure your email address is correct - you'll need it for verification</li>
                                            <li>Choose a strong password that you haven't used elsewhere</li>
                                            <li>You can change your password later in account settings</li>
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
                                        <img src="<?php echo e(asset('images/help/register-step2.png')); ?>" alt="Registration form" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-3" class="text-2xl font-bold text-gray-900 mb-6">3. Verify Your Email</h2>
                        <p class="text-gray-600 mb-6">
                            After submitting the registration form, you'll receive an email verification link. This step is required to activate your account.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Verification Process:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Check Your Email:</strong> Look for an email from TalentLit in your inbox
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click Verification Link:</strong> Click the link in the email to verify your account
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Account Activated:</strong> Your account will be activated and ready to use
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
                                    <h3 class="text-sm font-medium text-red-800">Can't Find the Email?</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Check your spam/junk folder</li>
                                            <li>Wait a few minutes - emails can take time to arrive</li>
                                            <li>Make sure you entered the correct email address</li>
                                            <li>Contact support if you still don't receive the email</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-4" class="text-2xl font-bold text-gray-900 mb-6">4. Complete Onboarding</h2>
                        <p class="text-gray-600 mb-6">
                            After email verification, you'll be redirected to the onboarding process where you can set up your organization and create your first job posting.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Onboarding Steps:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Create Organization:</strong> Set up your company profile and branding
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Create First Job:</strong> Post your first job opening to get started
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Invite Team Members:</strong> Add colleagues to collaborate on hiring
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Registration Form Not Submitting</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check that all required fields are filled out</li>
                                        <li>• Ensure your password meets the minimum requirements</li>
                                        <li>• Verify that your email address is valid</li>
                                        <li>• Try refreshing the page and submitting again</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Verification Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your spam/junk folder</li>
                                        <li>• Wait up to 10 minutes for the email to arrive</li>
                                        <li>• Try requesting a new verification email</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Account Already Exists Error</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Try logging in instead of registering</li>
                                        <li>• Use the "Forgot Password" feature if needed</li>
                                        <li>• Use a different email address</li>
                                        <li>• Contact support for account recovery</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="related-topics" class="bg-indigo-50 p-8 rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Topics</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Next Steps</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'login')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">How to Login</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'onboarding')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Setup Guide</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'jobs')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Creating Your First Job</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/register.blade.php ENDPATH**/ ?>