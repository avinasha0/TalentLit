<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Career Site — TalentLit ATS';
        $seoDescription = 'Build a stunning career site with TalentLit. Create branded job pages, showcase company culture, and attract top talent with our customizable career site builder.';
        $seoKeywords = 'career site, job board, company careers, branded careers, recruitment website, talent attraction';
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
                        <button @click="open = !open" @click.away="open = false" class="text-indigo-600 hover:text-indigo-700 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
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
                                        <a href="<?php echo e(route('features.career-site')); ?>" class="block px-3 py-2 text-sm text-indigo-600 bg-indigo-50 rounded-md transition-colors duration-200">
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
                    
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('contact')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Contact
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
                            <a href="<?php echo e(route('features.career-site')); ?>" class="block px-3 py-2 text-base text-indigo-600 bg-indigo-50 rounded-md">
                                Career Site
                            </a>
                            <a href="<?php echo e(route('features.job-advertising')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Job Advertising
                            </a>
                            <a href="<?php echo e(route('features.employee-referral')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Employee Referral
                            </a>
                            <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                            <a href="<?php echo e(route('features.resume-management')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Resume Management
                            </a>
                            <a href="<?php echo e(route('features.manage-submission')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Manage Submission
                            </a>
                            <a href="<?php echo e(route('features.hiring-analytics')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Analytics
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('contact')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Contact
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
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        <span class="text-yellow-300">Career</span> Site That Converts
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed">
                        Build a stunning career site that showcases your company culture and attracts top talent. 
                        <span class="font-semibold text-white">Custom branding, mobile-responsive design</span>, and seamless integration.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo e(route('register')); ?>" 
                           class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Build Your Site
                        </a>
                        <a href="#demo" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                            View Examples
                        </a>
                    </div>
                    <p class="text-sm text-indigo-200 mt-4">
                        ✨ Custom branding • Mobile responsive • SEO optimized • Analytics included
                    </p>
                </div>
                
                <!-- Hero Illustration -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 bg-white/20 rounded w-3/4"></div>
                                <div class="h-4 bg-white/20 rounded w-1/2"></div>
                                <div class="grid grid-cols-2 gap-2 mt-4">
                                    <div class="h-16 bg-white/10 rounded"></div>
                                    <div class="h-16 bg-white/10 rounded"></div>
                                </div>
                                <div class="h-8 bg-white/20 rounded w-full mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Everything You Need for Your Career Site
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Create a professional career site that reflects your brand and attracts the right candidates.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Custom Branding</h3>
                    <p class="text-gray-600">Match your career site to your company's visual identity with custom colors, fonts, and logos.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Mobile Responsive</h3>
                    <p class="text-gray-600">Perfect experience across all devices. Your career site looks great on desktop, tablet, and mobile.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">SEO Optimized</h3>
                    <p class="text-gray-600">Built-in SEO features help your job postings rank higher in search results and attract more candidates.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-2xl border border-yellow-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics Dashboard</h3>
                    <p class="text-gray-600">Track visitor behavior, application rates, and conversion metrics to optimize your career site performance.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-red-50 to-pink-50 p-8 rounded-2xl border border-red-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Company Culture</h3>
                    <p class="text-gray-600">Showcase your company culture, values, and team with dedicated pages and multimedia content.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Easy Integration</h3>
                    <p class="text-gray-600">Seamlessly integrate with your existing website and ATS. No technical expertise required.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Career Sites That Deliver Results
                </h2>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Join companies that have transformed their talent acquisition with professional career sites.
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 75; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 1000)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">More Applications</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 60; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 1500)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Better Quality Candidates</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 50; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 2000)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Faster Time-to-Hire</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 90; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 2500)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Brand Consistency</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Build Your Career Site in 3 Steps
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get your professional career site up and running in minutes, not months.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Choose Your Template</h3>
                    <p class="text-gray-600">Select from our professionally designed templates that match your industry and company style.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Customize Your Brand</h3>
                    <p class="text-gray-600">Add your logo, colors, and content. Showcase your company culture and values.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Go Live</h3>
                    <p class="text-gray-600">Publish your career site and start attracting top talent with your professional brand presence.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Ready to Build Your Career Site?
            </h2>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Create a professional career site that attracts top talent and showcases your company culture. Start building today.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('register')); ?>" 
                   class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Start Building Free
                </a>
                <a href="<?php echo e(route('contact')); ?>" 
                   class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-600 hover:text-white transition-all duration-200">
                    View Examples
                </a>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                ✨ No credit card required • Professional templates • Mobile responsive
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8 mb-4">
                    <p class="text-gray-400">Modern ATS for smarter recruitment.</p>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Product</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="hover:text-white">Candidate Sourcing</a></li>
                        <li><a href="<?php echo e(route('features.career-site')); ?>" class="hover:text-white">Career Site</a></li>
                        <li><a href="<?php echo e(route('features.job-advertising')); ?>" class="hover:text-white">Job Advertising</a></li>
                        <li><a href="<?php echo e(route('features.employee-referral')); ?>" class="hover:text-white">Employee Referral</a></li>
                        <li><a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="hover:text-white">Hiring Pipeline</a></li>
                        <li><a href="<?php echo e(route('subscription.pricing')); ?>" class="hover:text-white">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Company</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="<?php echo e(route('contact')); ?>" class="hover:text-white">Contact</a></li>
                        <li><a href="#" class="hover:text-white">About</a></li>
                        <li><a href="#" class="hover:text-white">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white">Help Center</a></li>
                        <li><a href="#" class="hover:text-white">Documentation</a></li>
                        <li><a href="#" class="hover:text-white">Status</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 TalentLit. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/features/career-site.blade.php ENDPATH**/ ?>