<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Hiring Pipeline — TalentLit ATS';
        $seoDescription = 'Streamline your hiring pipeline with TalentLit. Visual pipeline management, automated workflows, and collaborative tools to track candidates through every stage of recruitment.';
        $seoKeywords = 'hiring pipeline, recruitment pipeline, candidate tracking, ATS pipeline, hiring workflow, recruitment management';
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
                                        <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-sm text-indigo-600 bg-indigo-50 rounded-md transition-colors duration-200">
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
                            <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-base text-indigo-600 bg-indigo-50 rounded-md">
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
                        <span class="text-yellow-300">Visual</span> Hiring Pipeline
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed">
                        Track candidates through every stage with our intuitive pipeline management. 
                        <span class="font-semibold text-white">Collaborate seamlessly</span> and never lose sight of top talent.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo e(route('register')); ?>" 
                           class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Start Tracking Today
                        </a>
                        <a href="#demo" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                            Watch Demo
                        </a>
                    </div>
                    <p class="text-sm text-indigo-200 mt-4">
                        ✨ Drag & drop interface • Real-time collaboration • Automated workflows
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
                                <div class="flex space-x-2 mt-4">
                                    <div class="h-20 bg-white/10 rounded w-16"></div>
                                    <div class="h-20 bg-white/10 rounded w-16"></div>
                                    <div class="h-20 bg-white/10 rounded w-16"></div>
                                    <div class="h-20 bg-white/10 rounded w-16"></div>
                                </div>
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
                    Powerful Pipeline Management
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to manage your recruitment process efficiently and collaboratively.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Visual Pipeline Board</h3>
                    <p class="text-gray-600">Drag and drop candidates between stages with our intuitive Kanban-style pipeline board.</p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Automated Workflows</h3>
                    <p class="text-gray-600">Set up automated actions, notifications, and follow-ups based on candidate stage changes.</p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-green-50 to-blue-50 p-8 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Team Collaboration</h3>
                    <p class="text-gray-600">Share candidate profiles, add notes, and collaborate with your team in real-time.</p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-yellow-50 to-orange-50 p-8 rounded-2xl border border-yellow-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Advanced Analytics</h3>
                    <p class="text-gray-600">Track pipeline performance, conversion rates, and identify bottlenecks in your hiring process.</p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-red-50 to-pink-50 p-8 rounded-2xl border border-red-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4 19h6v-6H4v6zM4 5h6V1H4v4zM10 5h10V1H10v4zM10 13h10V9H10v4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Custom Stages</h3>
                    <p class="text-gray-600">Create custom pipeline stages that match your unique hiring process and workflow.</p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-indigo-50 to-blue-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Interview Scheduling</h3>
                    <p class="text-gray-600">Integrated interview scheduling with calendar sync and automated reminders.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pipeline Visualization Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    See Your Pipeline in Action
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our visual pipeline makes it easy to track candidates and identify opportunities for improvement.
                </p>
            </div>

            <!-- Pipeline Demo -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                    <!-- Stage 1: Applied -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-200">
                        <h3 class="font-semibold text-blue-900 mb-4">Applied</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                <div class="text-sm font-medium text-gray-900">Sarah Johnson</div>
                                <div class="text-xs text-gray-500">Software Engineer</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-blue-100">
                                <div class="text-sm font-medium text-gray-900">Mike Chen</div>
                                <div class="text-xs text-gray-500">Product Manager</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 2: Screening -->
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                        <h3 class="font-semibold text-yellow-900 mb-4">Screening</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-yellow-100">
                                <div class="text-sm font-medium text-gray-900">Emily Davis</div>
                                <div class="text-xs text-gray-500">UX Designer</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 3: Interview -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                        <h3 class="font-semibold text-purple-900 mb-4">Interview</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                <div class="text-sm font-medium text-gray-900">Alex Rodriguez</div>
                                <div class="text-xs text-gray-500">Data Scientist</div>
                            </div>
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                <div class="text-sm font-medium text-gray-900">Lisa Wang</div>
                                <div class="text-xs text-gray-500">Marketing Manager</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 4: Final Review -->
                    <div class="bg-gradient-to-br from-green-50 to-teal-50 rounded-xl p-6 border border-green-200">
                        <h3 class="font-semibold text-green-900 mb-4">Final Review</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-green-100">
                                <div class="text-sm font-medium text-gray-900">David Kim</div>
                                <div class="text-xs text-gray-500">Senior Developer</div>
                            </div>
                        </div>
                    </div>

                    <!-- Stage 5: Hired -->
                    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-xl p-6 border border-emerald-200">
                        <h3 class="font-semibold text-emerald-900 mb-4">Hired</h3>
                        <div class="space-y-3">
                            <div class="bg-white rounded-lg p-3 shadow-sm border border-emerald-100">
                                <div class="text-sm font-medium text-gray-900">Jessica Brown</div>
                                <div class="text-xs text-gray-500">Sales Director</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Pipeline Performance Metrics
                </h2>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Track your hiring success with detailed analytics and insights.
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 text-center">
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 85; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 1000)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Pipeline Efficiency</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 42; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 1500)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Faster Time-to-Hire</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 78; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 2000)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Candidate Satisfaction</div>
                </div>
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0 }" x-init="setTimeout(() => { let target = 95; let increment = target / 100; let timer = setInterval(() => { count += increment; if (count >= target) { count = target; clearInterval(timer); } }, 20); }, 2500)" x-text="Math.floor(count) + '%'">0%</div>
                    <div class="text-indigo-200 font-medium">Team Collaboration</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    How Pipeline Management Works
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get started with our intuitive pipeline management in just a few simple steps.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">1</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Set Up Your Pipeline</h3>
                    <p class="text-gray-600">Create custom stages that match your hiring process. Drag and drop to reorder stages as needed.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">2</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Add Candidates</h3>
                    <p class="text-gray-600">Import candidates from job applications or add them manually. They'll automatically appear in your pipeline.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-r from-pink-600 to-red-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <span class="text-2xl font-bold text-white">3</span>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Track Progress</h3>
                    <p class="text-gray-600">Move candidates through stages, collaborate with your team, and track performance with detailed analytics.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Ready to Streamline Your Pipeline?
            </h2>
            <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                Join thousands of recruiters who have transformed their hiring process with TalentLit's visual pipeline management.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?php echo e(route('register')); ?>" 
                   class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Start Free Trial
                </a>
                <a href="<?php echo e(route('contact')); ?>" 
                   class="border-2 border-indigo-600 text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-indigo-600 hover:text-white transition-all duration-200">
                    Contact Sales
                </a>
            </div>
            <p class="text-sm text-gray-500 mt-4">
                ✨ No credit card required • Forever Free Plan • Setup in 5 minutes
            </p>
        </div>
    </section>

    <!-- Centralized Footer Component -->
    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>


</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/features/hiring-pipeline.blade.php ENDPATH**/ ?>