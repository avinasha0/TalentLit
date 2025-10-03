<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'TalentLit — Smarter Recruitment Software';
        $seoDescription = 'TalentLit is a modern ATS that helps companies post jobs, manage candidates, and schedule interviews all in one place. Streamline your hiring process today.';
        $seoKeywords = 'TalentLit, ATS, applicant tracking system, recruitment software, hiring, jobs, candidates, interviews, HR software';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    @endphp
    @include('layouts.partials.head')
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
                        <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-8">
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
                                        <a href="{{ route('features.candidate-sourcing') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Candidate Sourcing</div>
                                        </a>
                                        <a href="{{ route('features.career-site') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Career Site</div>
                                        </a>
                                        <a href="{{ route('features.job-advertising') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Job Advertising</div>
                                        </a>
                                        <a href="{{ route('features.employee-referral') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Employee Referral</div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Track Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Track</div>
                                    <div class="space-y-1">
                                        <a href="{{ route('features.hiring-pipeline') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Pipeline</div>
                                        </a>
                                        <a href="{{ route('features.resume-management') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Resume Management</div>
                                        </a>
                                        <a href="{{ route('features.manage-submission') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Manage Submission</div>
                                        </a>
                                        <a href="{{ route('features.hiring-analytics') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Analytics</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('subscription.pricing') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                            <a href="{{ route('features.candidate-sourcing') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Candidate Sourcing
                            </a>
                            <a href="{{ route('features.hiring-pipeline') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="{{ route('subscription.pricing') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
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
                        TalentLit — <span class="text-yellow-300">Smarter</span> Hiring, Simplified
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed">
                        All-in-one recruitment software to manage jobs, candidates, and interviews. 
                        <span class="font-semibold text-white">95% faster hiring</span> with our modern ATS.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('register') }}" 
                           class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Get Started Free
                        </a>
                        <a href="{{ route('register') }}" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                            Free Account Available
                        </a>
                    </div>
                    <p class="text-sm text-indigo-200 mt-4">
                        ✨ No credit card required • Free Forever Available • Setup in 5 minutes
                    </p>
                </div>
                
                <!-- Hero Illustration -->
                <div class="relative">
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                        <div class="bg-white rounded-xl p-6 shadow-2xl">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            </div>
                            <div class="space-y-3">
                                <div class="h-4 bg-gradient-to-r from-indigo-200 to-purple-200 rounded w-3/4"></div>
                                <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                                <div class="grid grid-cols-3 gap-2 mt-4">
                                    <div class="h-16 bg-gradient-to-br from-indigo-100 to-purple-100 rounded"></div>
                                    <div class="h-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded"></div>
                                    <div class="h-16 bg-gradient-to-br from-pink-100 to-red-100 rounded"></div>
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
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Post Jobs & Manage Candidates</h3>
                    <p class="text-gray-600">Create job postings, track applications, and manage your entire candidate pipeline in one intuitive dashboard.</p>
                    </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Visual Pipeline & Team Collaboration</h3>
                    <p class="text-gray-600">Drag-and-drop candidate management with real-time collaboration tools for your entire hiring team.</p>
                    </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-pink-50 to-red-50 p-8 rounded-2xl border border-pink-100 hover:shadow-xl transition-all duration-300">
                    <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Interview Scheduling & Analytics</h3>
                    <p class="text-gray-600">Automated scheduling, video interviews, and powerful analytics to optimize your hiring process.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Trusted by recruiters worldwide
                </h2>
                <p class="text-xl text-indigo-100">
                    Join thousands of companies already using TalentLit to streamline their hiring
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0, target: 1200 }" x-init="setInterval(() => { if (count < target) count += 10 }, 20)">
                        <span x-text="count + '+'"></span>
                    </div>
                    <p class="text-xl text-indigo-100">Active Recruiters</p>
                </div>
                
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0, target: 50000 }" x-init="setInterval(() => { if (count < target) count += 500 }, 20)">
                        <span x-text="count.toLocaleString() + '+'"></span>
                    </div>
                    <p class="text-xl text-indigo-100">Candidates Managed</p>
                </div>
                
                <div class="text-white">
                    <div class="text-4xl md:text-5xl font-bold mb-2" x-data="{ count: 0, target: 95 }" x-init="setInterval(() => { if (count < target) count += 1 }, 30)">
                        <span x-text="count + '%'"></span>
                    </div>
                    <p class="text-xl text-indigo-100">Faster Hiring</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Screenshots Section -->
    <section id="demo" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    See TalentLit in action
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Powerful features designed to streamline your recruitment process and help you find the perfect candidates faster.
                </p>
            </div>
            
            <div class="space-y-20">
                <!-- Dashboard Preview -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Intuitive Dashboard</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Get a complete overview of your hiring pipeline with our modern, easy-to-use dashboard. 
                            Track applications, manage candidates, and monitor your team's progress all in one place.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Real-time candidate tracking
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Visual pipeline management
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Team collaboration tools
                            </li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl p-4">
                            <img src="{{ asset('images/demo/dashboard-preview.svg') }}" alt="TalentLit Dashboard" class="w-full rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Candidates Management -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="lg:order-2">
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Smart Candidate Management</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Organize and manage your candidates with powerful filtering, tagging, and search capabilities. 
                            Never lose track of a potential hire again.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Advanced search and filtering
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Custom candidate tags
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Automated status tracking
                            </li>
                        </ul>
                    </div>
                    <div class="lg:order-1 relative">
                        <div class="bg-white rounded-2xl shadow-2xl p-4">
                            <img src="{{ asset('images/demo/candidates-preview.svg') }}" alt="TalentLit Candidate Management" class="w-full rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Powerful Analytics</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Make data-driven hiring decisions with comprehensive analytics and reporting. 
                            Track your hiring metrics and optimize your recruitment process.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Real-time hiring metrics
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Custom reports and dashboards
                            </li>
                            <li class="flex items-center text-gray-600">
                                <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Performance insights
                            </li>
                        </ul>
                    </div>
                    <div class="relative">
                        <div class="bg-white rounded-2xl shadow-2xl p-4">
                            <img src="{{ asset('images/demo/analytics-preview.svg') }}" alt="TalentLit Analytics" class="w-full rounded-lg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-white">
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
                <div class="bg-gray-50 p-8 rounded-2xl">
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
                <div class="bg-gray-50 p-8 rounded-2xl">
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
                <div class="bg-gray-50 p-8 rounded-2xl">
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

    <!-- Pricing Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Simple, transparent pricing
                </h2>
                <p class="text-xl text-gray-600">
                    Choose the plan that's right for your team. No hidden fees, no surprises.
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                @foreach($plans as $plan)
                <div class="bg-white rounded-2xl p-8 border border-gray-200 relative {{ $plan->is_popular ? 'border-2 border-indigo-600 shadow-xl' : '' }} hover:shadow-lg transition-all duration-300">
                    @if($plan->is_popular)
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Most Popular</span>
                    </div>
                    @endif
                    
                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $plan->name }}</h3>
                        <p class="text-gray-600 mb-6">{{ $plan->description }}</p>
                        
                        <div class="mb-6">
                            @if($plan->requiresContactForPricing())
                                <span class="text-4xl font-bold text-gray-900">Contact for Pricing</span>
                            @else
                                <span class="text-6xl font-bold text-gray-900">
                                    @subscriptionPrice($plan->price, $plan->currency)
                                </span>
                                <span class="text-gray-600 text-lg">/{{ $plan->billing_cycle }}</span>
                            @endif
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4 mb-8">
                        <!-- Core Features -->
                        <div class="space-y-3">
                            @if($plan->slug === 'free')
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Up to 3 job postings</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">50 candidates per month</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Basic analytics</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Email support</span>
                                </div>
                            @elseif($plan->slug === 'pro')
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Unlimited job postings</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Unlimited candidates</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Advanced analytics</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Team collaboration</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Priority support</span>
                                </div>
                            @elseif($plan->slug === 'enterprise')
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Everything in Pro</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Custom integrations</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">Dedicated support</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm">On-premise deployment</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- CTA Button -->
                    <div class="mt-auto">
                        @if($plan->isFree())
                            <a href="{{ route('register') }}" 
                               class="w-full bg-gray-900 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-800 transition-colors text-center block">
                                Get Started Free
                            </a>
                        @elseif($plan->slug === 'pro')
                            @if(config('razorpay.pro_plan_mode') === 'active' && config('razorpay.key_id'))
                                @if(auth()->check())
                                    @php
                                        $user = auth()->user();
                                        $tenant = $user->tenants->first();
                                        $hasFreePlan = $tenant ? $tenant->hasFreePlan() : false;
                                    @endphp
                                    
                                    @if($hasFreePlan)
                                        <button onclick="initiatePayment('{{ $plan->id }}', '{{ $plan->name }}', {{ $plan->price }}, '{{ $plan->currency }}')" 
                                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                                            Upgrade to Pro - ₹{{ number_format($plan->price, 0) }}/month
                                        </button>
                                    @else
                                        <div class="w-full bg-gray-100 text-gray-600 px-6 py-3 rounded-lg font-semibold text-center">
                                            Start with Free Plan First
                                        </div>
                                    @endif
                                @else
                                    <a href="{{ route('register') }}" 
                                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 text-center block">
                                        Get Started - ₹{{ number_format($plan->price, 0) }}/month
                                    </a>
                                @endif
                            @else
                                @if(auth()->check())
                                    <button onclick="openWaitlistModal('pro')" 
                                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                                        Join Waitlist
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" 
                                       class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 text-center block">
                                        Login to Join Waitlist
                                    </a>
                                @endif
                            @endif
                        @elseif($plan->slug === 'enterprise')
                            <a href="{{ route('contact') }}" 
                               class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 text-center block">
                                Contact for Pricing
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- View All Plans Link -->
            <div class="text-center mt-12">
                <a href="{{ route('subscription.pricing') }}" 
                   class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-semibold">
                    View detailed pricing
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Banner -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Ready to simplify hiring?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Join thousands of companies already using TalentLit to find and hire the best talent.
            </p>
            <a href="{{ route('register') }}" 
               class="inline-block bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                Get Started Today — It's Free
            </a>
        </div>
    </section>

    <!-- Centralized Footer Component -->
    <x-footer />

    <!-- Waitlist Modal -->
    <div id="waitlistModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
        <div class="relative top-10 mx-auto p-0 w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
            <!-- Modal Header with Gradient Background -->
            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-purple-800 px-8 py-6 text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-20">
                    <svg class="w-full h-full" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <pattern id="modalGrid" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="0.5"/>
                            </pattern>
                        </defs>
                        <rect width="100%" height="100%" fill="url(#modalGrid)"/>
                    </svg>
                </div>
                
                <div class="relative flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Join the Waitlist</h3>
                            <p class="text-indigo-100 text-sm">Be the first to access Pro features</p>
                        </div>
                    </div>
                    <button onclick="closeWaitlistModal()" class="text-white/80 hover:text-white transition-colors p-2 hover:bg-white/10 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="px-8 py-6">
                <!-- Step 1: Email Verification -->
                <div id="waitlistStep1" class="space-y-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Verify Your Email</h3>
                        <p class="text-sm text-gray-600">We'll send a verification code to your email address</p>
                    </div>

                    <form id="waitlistEmailForm" class="space-y-4">
                        <div class="space-y-2">
                            <label for="waitlistEmail" class="block text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email Address
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="email" id="waitlistEmail" name="email" required 
                                   placeholder="Enter your email address"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                        </div>
                        
                        <button type="submit" id="waitlistEmailBtn"
                                class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Verification Code
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Step 2: OTP Verification -->
                <div id="waitlistStep2" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Enter Verification Code</h3>
                        <p class="text-sm text-gray-600">We sent a 6-digit code to <span id="waitlistEmailDisplay" class="font-semibold text-indigo-600"></span></p>
                    </div>

                    <form id="waitlistOtpForm" class="space-y-4">
                        <div class="space-y-2">
                            <label for="waitlistOtp" class="block text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Verification Code
                                <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" id="waitlistOtp" name="otp" required maxlength="6"
                                   placeholder="Enter 6-digit code"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 text-center text-2xl font-mono tracking-widest">
                        </div>
                        
                        <div class="flex space-x-3">
                            <button type="button" id="waitlistResendBtn" 
                                    class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200">
                                Resend Code
                            </button>
                            <button type="submit" id="waitlistVerifyBtn"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verify & Continue
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Step 3: Complete Registration -->
                <div id="waitlistStep3" class="space-y-6 hidden">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Complete Your Profile</h3>
                        <p class="text-sm text-gray-600">Tell us a bit about yourself to complete your waitlist registration</p>
                    </div>

                    <form id="waitlistForm" class="space-y-4">
                        <!-- Name Field -->
                        <div class="space-y-2">
                            <label for="waitlistName" class="block text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Full Name
                            </label>
                            <input type="text" id="waitlistName" name="name" 
                                   placeholder="Enter your full name"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                        </div>
                        
                        <!-- Company Field -->
                        <div class="space-y-2">
                            <label for="waitlistCompany" class="block text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Company
                            </label>
                            <input type="text" id="waitlistCompany" name="company" 
                                   placeholder="Enter your company name"
                                   class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                        </div>
                        
                        <!-- Message Field -->
                        <div class="space-y-2">
                            <label for="waitlistMessage" class="block text-sm font-semibold text-gray-900 flex items-center">
                                <svg class="w-4 h-4 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                Message
                                <span class="text-gray-500 text-xs font-normal ml-1">(Optional)</span>
                            </label>
                            <textarea id="waitlistMessage" name="message" rows="3" 
                                      placeholder="Tell us about your recruitment needs..."
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 resize-none"></textarea>
                        </div>
                        
                        <input type="hidden" id="waitlistPlanSlug" name="plan_slug" value="">
                        <input type="hidden" id="waitlistVerifiedEmail" name="email" value="">
                        
                        <!-- reCAPTCHA -->
                        <div class="flex justify-center">
                            <x-recaptcha />
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-4 pt-4">
                            <button type="button" onclick="closeWaitlistModal()" 
                                    class="flex-1 bg-gray-100 text-gray-700 py-3 px-6 rounded-xl font-semibold hover:bg-gray-200 transition-all duration-200 transform hover:-translate-y-0.5">
                                Cancel
                            </button>
                            <button type="submit" id="waitlistSubmitBtn"
                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Join Waitlist
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Success Message -->
                <div id="waitlistMessage" class="mt-6 hidden">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-xl p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="h-6 w-6 text-white" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-green-800">Welcome to the waitlist!</h4>
                                <p class="text-sm text-green-700 mt-1" id="waitlistSuccessMessage">
                                    Thank you! You've been added to our waitlist.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Trust Indicators -->
                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            Secure & Private
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            No Spam
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Early Access
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    let currentStep = 1;
    let verifiedEmail = '';

    function openWaitlistModal(planSlug) {
        document.getElementById('waitlistPlanSlug').value = planSlug;
        document.getElementById('waitlistVerifiedEmail').value = '';
        const modal = document.getElementById('waitlistModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset to step 1
        currentStep = 1;
        showStep(1);
        
        // Add entrance animation
        setTimeout(() => {
            modal.querySelector('.relative').style.transform = 'scale(1)';
            modal.querySelector('.relative').style.opacity = '1';
        }, 10);
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
            
            // Reset all forms
            document.getElementById('waitlistEmailForm').reset();
            document.getElementById('waitlistOtpForm').reset();
            document.getElementById('waitlistForm').reset();
            document.getElementById('waitlistMessage').classList.add('hidden');
            
            // Reset to step 1
            currentStep = 1;
            showStep(1);
            
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
            
            const response = await fetch('{{ route("waitlist.store") }}', {
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
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
            // Validate reCAPTCHA before proceeding
            if (!window.validateRecaptcha()) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                submitBtnText.innerHTML = originalText;
                return;
            }
            
            // Get reCAPTCHA response
            const recaptchaResponse = window.getRecaptchaResponse();

            // Add reCAPTCHA response to form data
            formData.append('g-recaptcha-response', recaptchaResponse);

            const response = await fetch('/waitlist', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
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
            const response = await fetch('{{ route("payment.create-order") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : ''
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
                    form.action = '{{ route("payment.success") }}';
                    
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
        
        // Initialize newsletter OTP functionality
        initializeNewsletterOTP();
        
        // Add direct newsletter form handler as backup
        setupNewsletterFormHandler();
    });
    
    // Simple reCAPTCHA validation for newsletter form (handled in initializeNewsletterOTP)
    function setupNewsletterFormHandler() {
        // reCAPTCHA validation is now handled in the main newsletter form handler
        // This function is kept for compatibility but does nothing
    }
    
    // Newsletter subscription - simple redirect to /newsletter/subscribe
    function initializeNewsletterOTP() {
        // No JavaScript needed - form will submit normally to /newsletter/subscribe
        console.log('Newsletter form will redirect to /newsletter/subscribe');
    }
    </script>

</body>
</html>