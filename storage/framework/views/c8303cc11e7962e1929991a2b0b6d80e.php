<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Pricing Plans — TalentLit ATS';
        $seoDescription = 'Choose the perfect TalentLit plan for your team. Simple, transparent pricing with no hidden fees. Start free and scale as you grow.';
        $seoKeywords = 'TalentLit pricing, ATS pricing, recruitment software pricing, hiring software cost';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    ?>
    <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Features Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                            Features
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 py-4 z-50">
                            <div class="grid grid-cols-2 gap-8 px-4">
                                <!-- Source Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Source</div>
                                    <div class="space-y-1">
                                        <a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Candidate Sourcing</div>
                                        </a>
                                        <a href="<?php echo e(route('features.career-site')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Career Site</div>
                                        </a>
                                        <a href="<?php echo e(route('features.job-advertising')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Job Advertising</div>
                                        </a>
                                        <a href="<?php echo e(route('features.employee-referral')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Employee Referral</div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Track Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Track</div>
                                    <div class="space-y-1">
                                        <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Pipeline</div>
                                        </a>
                                        <a href="<?php echo e(route('features.resume-management')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Resume Management</div>
                                        </a>
                                        <a href="<?php echo e(route('features.manage-submission')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Manage Submission</div>
                                        </a>
                                        <a href="<?php echo e(route('features.hiring-analytics')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Analytics</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        Get Started Free
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 p-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                    <!-- Features Section -->
                    <div class="px-3 py-2">
                        <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Features</div>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Candidate Sourcing
                            </a>
                            <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="block px-3 py-2 text-base text-indigo-600 bg-indigo-50 rounded-md">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-90"></div>
        <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)"/>
        </svg>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="text-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Simple, <span class="text-yellow-300">Transparent</span> Pricing
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-4xl mx-auto">
                Choose the perfect plan for your team. Start free and scale as you grow. 
                <span class="font-semibold text-white">No hidden fees, no surprises.</span>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#pricing" 
                   class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    View Plans
                </a>
                <a href="#features" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                    See Features
                </a>
            </div>
            <p class="text-sm text-indigo-200 mt-6">
                ✨ 14-day free trial • No credit card required • Cancel anytime
            </p>
        </div>
    </div>
</section>

<!-- Pricing Cards Section -->
<section id="pricing" class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 max-w-6xl mx-auto">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="relative bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden hover:shadow-3xl transition-all duration-300 <?php echo e($plan->is_popular ? 'ring-4 ring-indigo-500 scale-105 z-10' : 'hover:scale-105'); ?>">
                <?php if($plan->is_popular): ?>
                <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-center py-3 text-sm font-bold">
                    ⭐ Most Popular
                </div>
                <?php endif; ?>

                <div class="p-8 <?php echo e($plan->is_popular ? 'pt-16' : 'pt-8'); ?>">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 mx-auto mb-4 <?php echo e($plan->is_popular ? 'bg-gradient-to-r from-indigo-600 to-purple-600' : 'bg-gradient-to-r from-gray-600 to-gray-700'); ?> rounded-2xl flex items-center justify-center">
                            <?php if($plan->isFree()): ?>
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            <?php endif; ?>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($plan->name); ?></h3>
                        <p class="text-gray-600 mb-6"><?php echo e($plan->description); ?></p>
                        
                        <div class="mb-6">
                            <?php if($plan->requiresContactForPricing()): ?>
                                <span class="text-4xl font-bold text-gray-900">Contact for Pricing</span>
                            <?php else: ?>
                                <span class="text-6xl font-bold text-gray-900">
                                    <?php echo subscriptionPrice($plan->price, $plan->currency); ?>
                                </span>
                                <span class="text-gray-600 text-lg">/<?php echo e($plan->billing_cycle); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <!-- Core Features -->
                        <div class="border-b border-gray-100 pb-4">
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Core Features</h4>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_users == -1 ? 'Unlimited' : $plan->max_users); ?> Team Members
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_job_openings == -1 ? 'Unlimited' : $plan->max_job_openings); ?> Job Postings
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_candidates == -1 ? 'Unlimited' : number_format($plan->max_candidates)); ?> Candidates
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_applications_per_month == -1 ? 'Unlimited' : number_format($plan->max_applications_per_month)); ?> Applications/Month
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_interviews_per_month == -1 ? 'Unlimited' : number_format($plan->max_interviews_per_month)); ?> Interviews/Month
                                    </span>
                                </div>

                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">
                                        <?php echo e($plan->max_storage_gb); ?>GB File Storage
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Advanced Features -->
                        <?php if($plan->analytics_enabled || $plan->custom_branding || $plan->api_access || $plan->priority_support || $plan->white_label): ?>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3">Advanced Features</h4>
                            <div class="space-y-3">
                                <?php if($plan->analytics_enabled): ?>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">Advanced Analytics & Reports</span>
                                </div>
                                <?php endif; ?>

                                <?php if($plan->custom_branding): ?>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">Custom Branding & White Label</span>
                                </div>
                                <?php endif; ?>

                                <?php if($plan->api_access): ?>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-indigo-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">API Access & Integrations</span>
                                </div>
                                <?php endif; ?>

                                <?php if($plan->priority_support): ?>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">Priority Support</span>
                                </div>
                                <?php endif; ?>

                                <?php if($plan->white_label): ?>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 bg-pink-100 rounded-full flex items-center justify-center mr-3">
                                        <svg class="w-3 h-3 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <span class="text-gray-700 font-medium">White Label Solution</span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-auto">
                        <?php if($plan->slug === 'pro'): ?>
                            <?php if(config('razorpay.pro_plan_mode') === 'active' && config('razorpay.key_id')): ?>
                                <!-- Pro Plan - Payment Button -->
                                <?php if(auth()->check()): ?>
                                    <?php
                                        $user = auth()->user();
                                        $tenant = $user->tenants->first();
                                        $hasFreePlan = $tenant ? $tenant->hasFreePlan() : false;
                                    ?>
                                    
                                    <?php if($tenant): ?>
                                        <?php if($hasFreePlan): ?>
                                            <!-- User has Free plan, can upgrade to Pro -->
                                            <button onclick="initiatePayment('<?php echo e($plan->id); ?>', '<?php echo e($plan->name); ?>', <?php echo e($plan->price); ?>, '<?php echo e($plan->currency); ?>')" 
                                                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                                                🔄 Upgrade to Pro - ₹<?php echo e(number_format($plan->price, 0)); ?>/month
                                            </button>
                                            <p class="text-center text-sm text-gray-500 mt-3">
                                                Upgrade from your Free plan
                                            </p>
                                        <?php else: ?>
                                            <!-- User doesn't have Free plan, need to start with Free first -->
                                            <div class="w-full bg-gray-100 text-gray-600 font-bold py-4 px-6 rounded-xl text-center">
                                                📋 Start with Free Plan First
                                            </div>
                                            <p class="text-center text-sm text-gray-500 mt-3">
                                                Subscribe to Free plan to unlock Pro upgrade
                                            </p>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <!-- User authenticated but no tenant, redirect to onboarding -->
                                        <a href="<?php echo e(route('onboarding.organization')); ?>" 
                                           class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 block text-center shadow-lg hover:shadow-xl">
                                            🚀 Get Started - ₹<?php echo e(number_format($plan->price, 0)); ?>/month
                                        </a>
                                        <p class="text-center text-sm text-gray-500 mt-3">
                                            Create your organization first
                                        </p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <!-- User not authenticated, redirect to register -->
                                    <a href="<?php echo e(route('register')); ?>" 
                                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 block text-center shadow-lg hover:shadow-xl">
                                        🚀 Get Started - ₹<?php echo e(number_format($plan->price, 0)); ?>/month
                                    </a>
                                    <p class="text-center text-sm text-gray-500 mt-3">
                                        Sign up to subscribe
                                    </p>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- Pro Plan - Waitlist Button -->
                                <?php if(auth()->check()): ?>
                                    <button onclick="openWaitlistModal('<?php echo e($plan->slug); ?>')" 
                                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                                        📋 Join Waitlist
                                    </button>
                                    <p class="text-center text-sm text-gray-500 mt-3">
                                        Be the first to know when Pro plan launches
                                    </p>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" 
                                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 block text-center shadow-lg hover:shadow-xl">
                                        🔐 Login to Join Waitlist
                                    </a>
                                    <p class="text-center text-sm text-gray-500 mt-3">
                                        Login required to join waitlist
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php elseif($plan->requiresContactForPricing()): ?>
                            <!-- Enterprise Plan - Contact for Pricing -->
                            <a href="<?php echo e(route('contact')); ?>" 
                               class="w-full bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl block text-center">
                                📞 Contact for Pricing
                            </a>
                            <p class="text-center text-sm text-gray-500 mt-3">
                                Custom pricing for your organization
                            </p>
                        <?php else: ?>
                            <!-- Free Plan - Regular CTA -->
                            <?php if(auth()->check()): ?>
                                <?php
                                    $user = auth()->user();
                                    $tenant = $user->tenants->first();
                                ?>
                                
                                <?php if($tenant): ?>
                                    <!-- User has a tenant, can subscribe -->
                                    <form method="POST" action="<?php echo e(route('subscription.subscribe', $tenant->slug)); ?>" class="w-full">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="plan_id" value="<?php echo e($plan->id); ?>">
                                        <button type="submit" 
                                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                                            🚀 Get Started Free
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <!-- User authenticated but no tenant, redirect to onboarding -->
                                    <a href="<?php echo e(route('onboarding.organization')); ?>" 
                                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 block text-center shadow-lg hover:shadow-xl">
                                        🚀 Get Started Free
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <!-- User not authenticated, redirect to register -->
                                <a href="<?php echo e(route('register')); ?>" 
                                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 transform hover:-translate-y-1 block text-center shadow-lg hover:shadow-xl">
                                    🚀 Get Started Free
                                </a>
                            <?php endif; ?>
                            
                            <p class="text-center text-sm text-gray-500 mt-3">
                                No credit card required
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<!-- Features Showcase Section -->
<section id="features" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Everything you need to hire better
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Powerful features designed to streamline your recruitment process and help you find the perfect candidates faster.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Smart Job Management</h3>
                <p class="text-gray-600">Create, publish, and manage job postings with our intuitive interface. Track applications and manage your entire hiring pipeline.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Visual Pipeline Management</h3>
                <p class="text-gray-600">Drag-and-drop candidate management with real-time collaboration tools for your entire hiring team.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-gradient-to-br from-pink-50 to-red-50 p-8 rounded-2xl border border-pink-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Interview Scheduling</h3>
                <p class="text-gray-600">Automated scheduling, video interviews, and powerful analytics to optimize your hiring process.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Analytics</h3>
                <p class="text-gray-600">Make data-driven hiring decisions with comprehensive analytics and reporting tools.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Team Collaboration</h3>
                <p class="text-gray-600">Work together seamlessly with your team using real-time collaboration tools and shared workspaces.</p>
            </div>

            <!-- Feature 6 -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-2xl border border-yellow-100 hover:shadow-xl transition-all duration-300">
                <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-3">Custom Branding</h3>
                <p class="text-gray-600">Customize the platform with your brand colors, logo, and domain for a professional look.</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                What our customers say
            </h2>
            <p class="text-xl text-gray-600">
                Join thousands of recruiters who have transformed their hiring process with TalentLit
            </p>
        </div>
        
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Testimonial 1 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                        SM
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Sarah Mitchell</h4>
                        <p class="text-sm text-gray-600">Head of Talent, TechCorp</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "TalentLit has completely transformed our hiring process. We've reduced our time-to-hire by 60% and our team loves the intuitive interface."
                </p>
            </div>

            <!-- Testimonial 2 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center text-white font-bold">
                        MJ
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Michael Johnson</h4>
                        <p class="text-sm text-gray-600">Recruitment Manager, StartupXYZ</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "The analytics dashboard gives us insights we never had before. We can now make data-driven decisions about our hiring strategy."
                </p>
            </div>

            <!-- Testimonial 3 -->
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-red-600 rounded-full flex items-center justify-center text-white font-bold">
                        AL
                    </div>
                    <div class="ml-4">
                        <h4 class="font-semibold text-gray-900">Alex Lee</h4>
                        <p class="text-sm text-gray-600">HR Director, GlobalTech</p>
                    </div>
                </div>
                <p class="text-gray-600 italic">
                    "Setting up was incredibly easy. Within a day, we had our entire hiring pipeline migrated and our team was already more productive."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Frequently Asked Questions
            </h2>
            <p class="text-xl text-gray-600">
                Everything you need to know about our pricing and features
            </p>
        </div>
            
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8 border border-indigo-100 hover:shadow-lg transition-all duration-300">
                <h3 class="text-xl font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-6 h-6 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Can I change my plan later?
                </h3>
                <p class="text-gray-600 text-lg">
                    Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any billing differences.
                </p>
            </div>

            <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-8 border border-purple-100 hover:shadow-lg transition-all duration-300">
                <h3 class="text-xl font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    What happens if I exceed my limits?
                </h3>
                <p class="text-gray-600 text-lg">
                    We'll notify you when you're approaching your limits. You can upgrade your plan or contact us to discuss custom solutions for your needs.
                </p>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl p-8 border border-green-100 hover:shadow-lg transition-all duration-300">
                <h3 class="text-xl font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Is there a free trial?
                </h3>
                <p class="text-gray-600 text-lg">
                    Our Free plan is always free with no time limits. You can use it indefinitely with the included features and limits.
                </p>
            </div>

            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-8 border border-blue-100 hover:shadow-lg transition-all duration-300">
                <h3 class="text-xl font-semibold text-gray-900 mb-3 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Do you offer custom enterprise plans?
                </h3>
                <p class="text-gray-600 text-lg">
                    Yes! Contact our sales team to discuss custom enterprise solutions with unlimited features and dedicated support.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Final CTA Section -->
<section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
    <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
            Ready to transform your hiring process?
        </h2>
        <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
            Join thousands of companies already using TalentLit to find and hire the best talent. 
            Start your free trial today and see the difference.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo e(route('register')); ?>" 
               class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                🚀 Get Started Free
            </a>
            <a href="<?php echo e(route('contact')); ?>" 
               class="inline-block border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                📞 Contact Sales
            </a>
        </div>
        <p class="text-sm text-indigo-200 mt-6">
            ✨ No credit card required • Free Forever Available • Setup in 5 minutes
        </p>
    </div>
</section>

<!-- Waitlist Modal -->
<div id="waitlistModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="waitlistModalContent">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Join the Waitlist</h3>
                            <p class="text-indigo-100 text-sm">Be the first to know when Pro plan launches</p>
                        </div>
                    </div>
                    <button onclick="closeWaitlistModal()" class="text-white hover:text-indigo-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- Modal Body -->
            <div class="p-6">
                <!-- User Info Display -->
                <?php if(auth()->check()): ?>
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-sm text-gray-600"><?php echo e(auth()->user()->email); ?></p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="mb-6 p-4 bg-red-50 rounded-lg border border-red-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-red-900">Login Required</p>
                            <p class="text-sm text-red-700">You must be logged in to join the waitlist</p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Waitlist Form -->
                <?php if(auth()->check()): ?>
                <form id="waitlistForm" class="space-y-4" action="<?php echo e(route('waitlist.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="plan_slug" id="plan_slug_input" value="">
                    
                    <div>
                        <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">
                            Company (Optional)
                        </label>
                        <input type="text" id="company" name="company"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                               placeholder="Enter your company name">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            Message (Optional)
                        </label>
                        <textarea id="message" name="message" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Tell us about your hiring needs..."></textarea>
                    </div>

                    <button type="submit" id="waitlistSubmitBtn"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                        <span id="waitlistBtnText">📋 Join Waitlist</span>
                        <span id="waitlistBtnLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Joining...
                        </span>
                    </button>
                </form>
                <?php else: ?>
                <div class="text-center">
                    <a href="<?php echo e(route('login')); ?>" 
                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl inline-block">
                        🔐 Login to Join Waitlist
                    </a>
                    <p class="text-sm text-gray-500 mt-3">
                        You need to be logged in to join our waitlist
                    </p>
                </div>
                <?php endif; ?>

                <!-- Success Message -->
                <div id="waitlistSuccess" class="hidden text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">You're on the Waitlist!</h3>
                    <p class="text-gray-600 mb-6">Thank you for joining. We'll notify you as soon as the Pro plan is available.</p>
                    <button onclick="closeWaitlistModal()"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Waitlist functionality
let currentPlanSlug = '';

function openWaitlistModal(planSlug) {
    currentPlanSlug = planSlug;
    
    // Set the plan_slug in the hidden input
    const planSlugInput = document.getElementById('plan_slug_input');
    if (planSlugInput) {
        planSlugInput.value = planSlug;
    }
    
    const modal = document.getElementById('waitlistModal');
    const modalContent = document.getElementById('waitlistModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    const modalContent = document.getElementById('waitlistModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        resetWaitlistModal();
    }, 300);
}

function resetWaitlistModal() {
    document.getElementById('waitlistForm').classList.remove('hidden');
    document.getElementById('waitlistSuccess').classList.add('hidden');
    document.getElementById('waitlistForm').reset();
}

function closeWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    const modalContent = modal.querySelector('.relative');
    
    // Add exit animation
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        
        // Reset waitlist form
        if (document.getElementById('waitlistForm')) {
            document.getElementById('waitlistForm').reset();
        }
        if (document.getElementById('waitlistSuccess')) {
            document.getElementById('waitlistSuccess').classList.add('hidden');
        }
        
        // Reset modal content styles
        modalContent.style.transform = '';
        modalContent.style.opacity = '';
    }, 200);
}

function showStep(step) {
    // Hide all steps
    document.getElementById('waitlistStep1').classList.add('hidden');
    document.getElementById('waitlistStep2').classList.add('hidden');
    document.getElementById('waitlistStep3').classList.add('hidden');
    
    // Show current step
    document.getElementById(`waitlistStep${step}`).classList.remove('hidden');
    
    // Add step transition animation
    const stepElement = document.getElementById(`waitlistStep${step}`);
    stepElement.style.transform = 'translateX(20px)';
    stepElement.style.opacity = '0';
    setTimeout(() => {
        stepElement.style.transform = 'translateX(0)';
        stepElement.style.opacity = '1';
    }, 100);
}

// Waitlist form submission
document.getElementById('waitlistForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.plan_slug = currentPlanSlug;
    
    const submitBtn = document.getElementById('waitlistSubmitBtn');
    const btnText = document.getElementById('waitlistBtnText');
    const btnLoading = document.getElementById('waitlistBtnLoading');
    
    // Show loading state
    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch('<?php echo e(route("waitlist.store")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            // Show success message
            document.getElementById('waitlistForm').classList.add('hidden');
            document.getElementById('waitlistSuccess').classList.remove('hidden');
        } else {
            showNotification(result.message || 'Something went wrong. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    }
});

// Step 2: OTP verification
document.getElementById('waitlistOtpForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const otp = document.getElementById('waitlistOtp').value;
    const submitBtn = document.getElementById('waitlistVerifyBtn');
    const submitBtnText = submitBtn.querySelector('span');
    const originalText = submitBtnText.innerHTML;
    
    // Disable submit button and show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    submitBtnText.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Verifying...
    `;
    
    try {
        const formData = new FormData();
        formData.append('email', verifiedEmail);
        formData.append('otp', otp);
        formData.append('plan_slug', document.getElementById('waitlistPlanSlug').value);
        
        // const response = await fetch('#', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            currentStep = 3;
            showStep(3);
            showNotification('Email verified successfully!', 'success');
        } else {
            showNotification(data.message || 'Invalid verification code. Please try again.', 'error');
        }
    } catch (error) {
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        submitBtnText.innerHTML = originalText;
    }
});

// Resend OTP
document.getElementById('waitlistResendBtn').addEventListener('click', async function() {
    const submitBtn = this;
    const originalText = submitBtn.textContent;
    
    submitBtn.disabled = true;
    submitBtn.textContent = 'Sending...';
    
    try {
        const formData = new FormData();
        formData.append('email', verifiedEmail);
        
        // const response = await fetch('#', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification('New verification code sent!', 'success');
        } else {
            showNotification(data.message || 'Failed to resend code. Please try again.', 'error');
        }
    } catch (error) {
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// Step 3: Complete registration
document.getElementById('waitlistForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('waitlistSubmitBtn');
    const submitBtnText = submitBtn.querySelector('span');
    const originalText = submitBtnText.innerHTML;
    const formData = new FormData(this);
    
    // Disable submit button and show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
    submitBtnText.innerHTML = `
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Joining Waitlist...
    `;
    
    try {
        // const response = await fetch('#', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Show success message
            document.getElementById('waitlistSuccessMessage').textContent = data.message;
            document.getElementById('waitlistMessage').classList.remove('hidden');
            this.reset();
            
            // Add success animation to the success message
            const successDiv = document.getElementById('waitlistMessage');
            successDiv.style.transform = 'translateY(-10px)';
            successDiv.style.opacity = '0';
            setTimeout(() => {
                successDiv.style.transform = 'translateY(0)';
                successDiv.style.opacity = '1';
            }, 100);
            
            // Close modal after 3 seconds
            setTimeout(() => {
                closeWaitlistModal();
            }, 3000);
        } else {
            showNotification(data.message || 'Something went wrong. Please try again.', 'error');
        }
    } catch (error) {
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
        submitBtnText.innerHTML = originalText;
    }
});

// OTP input formatting
document.getElementById('waitlistOtp').addEventListener('input', function(e) {
    // Only allow numbers
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Auto-submit when 6 digits are entered
    if (this.value.length === 6) {
        document.getElementById('waitlistOtpForm').dispatchEvent(new Event('submit'));
    }
});

// Close modal when clicking outside
document.getElementById('waitlistModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeWaitlistModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('waitlistModal').classList.contains('hidden')) {
        closeWaitlistModal();
    }
});

// Add input focus animations
document.querySelectorAll('#waitlistEmailForm input, #waitlistOtpForm input, #waitlistForm input, #waitlistForm textarea').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.classList.add('transform', 'scale-105');
    });
    
    input.addEventListener('blur', function() {
        this.parentElement.classList.remove('transform', 'scale-105');
    });
});

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'error' ? 'bg-red-500 text-white' : 
        type === 'success' ? 'bg-green-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 100);
    
    // Remove after 4 seconds
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            if (document.body.contains(notification)) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

// Add smooth transitions to modal content
document.addEventListener('DOMContentLoaded', function() {
    const modalContent = document.querySelector('#waitlistModal .relative');
    modalContent.style.transition = 'all 0.3s ease-out';
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    // Add step transitions
    document.querySelectorAll('[id^="waitlistStep"]').forEach(step => {
        step.style.transition = 'all 0.3s ease-out';
    });
});

// Payment functionality
async function initiatePayment(planId, planName, amount, currency) {
    try {
        // Show loading state
        showNotification('Creating payment order...', 'info');
        
        // Create payment order
        const response = await fetch('<?php echo e(route("payment.create-order")); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                plan_id: planId
            })
        });
        
        const data = await response.json();
        
        if (!data.success) {
            showNotification(data.message || 'Failed to create payment order', 'error');
            return;
        }
        
        // Configure RazorPay options
        const options = {
            key: data.key_id,
            amount: data.amount * 100, // Convert to paise
            currency: data.currency,
            name: data.name,
            description: data.description,
            order_id: data.order.id,
            prefill: data.prefill,
            theme: {
                color: '#4f46e5'
            },
            handler: function(response) {
                // Payment successful
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '<?php echo e(route("payment.success")); ?>';
                
                const paymentId = document.createElement('input');
                paymentId.type = 'hidden';
                paymentId.name = 'razorpay_payment_id';
                paymentId.value = response.razorpay_payment_id;
                form.appendChild(paymentId);
                
                const orderId = document.createElement('input');
                orderId.type = 'hidden';
                orderId.name = 'razorpay_order_id';
                orderId.value = response.razorpay_order_id;
                form.appendChild(orderId);
                
                const signature = document.createElement('input');
                signature.type = 'hidden';
                signature.name = 'razorpay_signature';
                signature.value = response.razorpay_signature;
                form.appendChild(signature);
                
                document.body.appendChild(form);
                form.submit();
            },
            modal: {
                ondismiss: function() {
                    showNotification('Payment cancelled', 'info');
                }
            }
        };
        
        // Open RazorPay checkout
        const rzp = new Razorpay(options);
        rzp.open();
        
    } catch (error) {
        console.error('Payment error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    }
}

// Load RazorPay script dynamically
function loadRazorPayScript() {
    return new Promise((resolve, reject) => {
        if (window.Razorpay) {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://checkout.razorpay.com/v1/checkout.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Initialize RazorPay when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadRazorPayScript().catch(error => {
        console.error('Failed to load RazorPay script:', error);
        showNotification('Payment system unavailable', 'error');
    });
});
</script>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-10">
                        <span class="text-xl font-bold">TalentLit</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md text-lg leading-relaxed">
                        The modern ATS that helps you hire smarter, faster, and more efficiently. 
                        Streamline your recruitment process today.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Product Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('features')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="<?php echo e(route('subscription.pricing')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                        <li><a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                        <li><a href="<?php echo e(route('features.hiring-analytics')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                    </ul>
                </div>
                
                <!-- Company Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('about')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="<?php echo e(route('careers')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="<?php echo e(route('blog')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                        <li><a href="<?php echo e(route('press')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
                    </ul>
                </div>
                
                <!-- Support Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Community</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Status</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="bg-gray-800 rounded-2xl p-8 mb-12">
                <div class="max-w-2xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">Stay Updated</h3>
                    <p class="text-gray-400 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
                    <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">
                        © <?php echo e(date('Y')); ?> TalentLit. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="<?php echo e(route('privacy-policy')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                        <a href="<?php echo e(route('terms-of-service')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/subscription/pricing.blade.php ENDPATH**/ ?>