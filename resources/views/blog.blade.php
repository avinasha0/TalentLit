<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Blog — TalentLit Recruitment Insights';
        $seoDescription = 'Stay updated with the latest recruitment trends, hiring tips, and industry insights from TalentLit experts.';
        $seoKeywords = 'TalentLit blog, recruitment blog, hiring tips, HR insights, talent acquisition, recruitment trends';
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
                    <a href="{{ route('blog') }}" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium">
                        Blog
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
                    <a href="{{ route('blog') }}" class="block px-3 py-2 text-base text-indigo-600 bg-indigo-50 rounded-md">
                        Blog
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
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 text-white text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Recruitment Insights</h1>
            <p class="text-xl md:text-2xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Stay ahead of the curve with expert insights, industry trends, and practical tips for modern recruitment.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#latest-posts" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Read Latest Posts
                </a>
                <a href="{{ route('register') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200">
                    Start Free Trial
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Post Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Article</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Our most popular and impactful content on recruitment and hiring.
                </p>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 md:p-12 text-white">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <div class="text-indigo-100 text-sm font-medium mb-4">December 20, 2023 • 8 min read</div>
                        <h3 class="text-3xl md:text-4xl font-bold mb-6">The Future of Recruitment: How AI is Transforming Talent Acquisition</h3>
                        <p class="text-xl text-indigo-100 mb-8">
                            Discover how artificial intelligence is revolutionizing the way companies find, evaluate, and hire the best talent. Learn about the latest trends and technologies shaping the future of recruitment.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200 text-center">
                                Read Full Article
                            </a>
                            <a href="#" class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200 text-center">
                                Share Article
                            </a>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                            <div class="grid grid-cols-2 gap-6 text-center">
                                <div>
                                    <div class="text-3xl font-bold mb-2">2.5K</div>
                                    <div class="text-indigo-100 text-sm">Views</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold mb-2">180</div>
                                    <div class="text-indigo-100 text-sm">Shares</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold mb-2">45</div>
                                    <div class="text-indigo-100 text-sm">Comments</div>
                                </div>
                                <div>
                                    <div class="text-3xl font-bold mb-2">4.9</div>
                                    <div class="text-indigo-100 text-sm">Rating</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Posts Section -->
    <section id="latest-posts" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Latest Posts</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Fresh insights and practical advice for modern recruiters and HR professionals.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 18, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">10 Strategies to Reduce Time-to-Hire by 50%</h3>
                        <p class="text-gray-600 mb-4">Learn proven techniques to streamline your hiring process and find the right candidates faster than ever before.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                5 min read
                            </div>
                        </div>
                    </div>
                </article>
                
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 15, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Building a Diverse and Inclusive Hiring Process</h3>
                        <p class="text-gray-600 mb-4">Discover how to create recruitment practices that promote diversity and inclusion while maintaining high-quality hires.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                7 min read
                            </div>
                        </div>
                    </div>
                </article>
                
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 12, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">The Complete Guide to Candidate Experience</h3>
                        <p class="text-gray-600 mb-4">Learn how to create a positive candidate experience that attracts top talent and builds your employer brand.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                6 min read
                            </div>
                        </div>
                    </div>
                </article>
                
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 10, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Remote Hiring Best Practices for 2024</h3>
                        <p class="text-gray-600 mb-4">Navigate the challenges of remote hiring with these proven strategies and tools for virtual recruitment.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                4 min read
                            </div>
                        </div>
                    </div>
                </article>
                
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 8, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">How to Write Job Descriptions That Attract Top Talent</h3>
                        <p class="text-gray-600 mb-4">Master the art of crafting compelling job descriptions that stand out and attract the right candidates.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                5 min read
                            </div>
                        </div>
                    </div>
                </article>
                
                <article class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-200">
                    <div class="h-48 bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-indigo-600 font-medium mb-2">December 5, 2023</div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">Recruitment Analytics: Measuring What Matters</h3>
                        <p class="text-gray-600 mb-4">Learn which recruitment metrics to track and how to use data to improve your hiring process and outcomes.</p>
                        <div class="flex items-center justify-between">
                            <a href="#" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Read More →</a>
                            <div class="flex items-center text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                6 min read
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                    View All Posts
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-8 md:p-12 text-white text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Stay Updated</h2>
                <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Get the latest recruitment insights, industry trends, and hiring tips delivered straight to your inbox.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-indigo-200 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent">
                    <button class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                        Subscribe
                    </button>
                </div>
                <p class="text-sm text-indigo-200 mt-4">No spam, unsubscribe at any time.</p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-10">
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
                        <li><a href="{{ route('features') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="{{ route('subscription.pricing') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="{{ route('features.candidate-sourcing') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                        <li><a href="{{ route('features.hiring-pipeline') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                        <li><a href="{{ route('features.hiring-analytics') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                    </ul>
                </div>
                
                <!-- Company Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="{{ route('careers') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="{{ route('blog') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                        <li><a href="{{ route('press') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
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
                        © {{ date('Y') }} TalentLit. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                        <a href="{{ route('terms-of-service') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
