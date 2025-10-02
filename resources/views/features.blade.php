<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Features — TalentLit ATS</title>
    <meta name="description" content="Discover TalentLit's comprehensive recruitment features. From candidate sourcing to hiring analytics, streamline your entire hiring process.">
    <meta name="keywords" content="ATS features, recruitment software, hiring tools, candidate management, talent acquisition">
    <meta name="author" content="TalentLit">
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
                        <img src="/logo-talentlit-small.svg" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Features Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
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
                                        <a href="/features/candidate-sourcing.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Candidate Sourcing</div>
                                        </a>
                                        <a href="/features/career-site.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Career Site</div>
                                        </a>
                                        <a href="/features/job-advertising.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Job Advertising</div>
                                        </a>
                                        <a href="/features/employee-referral.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Employee Referral</div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Track Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Track</div>
                                    <div class="space-y-1">
                                        <a href="/features/hiring-pipeline.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Pipeline</div>
                                        </a>
                                        <a href="/features/resume-management.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Resume Management</div>
                                        </a>
                                        <a href="/features/manage-submission.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Manage Submission</div>
                                        </a>
                                        <a href="/features/hiring-analytics.html" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Analytics</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="/pricing.html" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="/about.html" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        About
                    </a>
                    <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="/register" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                            <a href="/features/candidate-sourcing.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Candidate Sourcing
                            </a>
                            <a href="/features/hiring-pipeline.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="/pricing.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="/about.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        About
                    </a>
                    <a href="/login" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="/register" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
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
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Powerful Recruitment Features</h1>
            <p class="text-xl md:text-2xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Everything you need to source, track, and hire the best talent. Streamline your entire recruitment process with our comprehensive ATS platform.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Start Free Trial
                </a>
                <a href="/contact.html" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200">
                    Schedule Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Features Overview Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Complete Recruitment Solution</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    From sourcing candidates to onboarding new hires, TalentLit provides all the tools you need for successful recruitment.
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Candidate Sourcing -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Candidate Sourcing</h3>
                    <p class="text-gray-600 mb-6">Find top talent across multiple channels with our advanced sourcing tools and integrations.</p>
                    <a href="/features/candidate-sourcing.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
                
                <!-- Hiring Pipeline -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Hiring Pipeline</h3>
                    <p class="text-gray-600 mb-6">Manage candidates through customizable stages with automated workflows and notifications.</p>
                    <a href="/features/hiring-pipeline.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
                
                <!-- Resume Management -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Resume Management</h3>
                    <p class="text-gray-600 mb-6">Parse, store, and organize resumes with intelligent document processing and search.</p>
                    <a href="/features/resume-management.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
                
                <!-- Career Site -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Career Site</h3>
                    <p class="text-gray-600 mb-6">Build beautiful, branded career pages that attract and convert top candidates.</p>
                    <a href="/features/career-site.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
                
                <!-- Job Advertising -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a3.76 3.76 0 01-6.417 2.88l-.83-.829A3.76 3.76 0 015.882 19.24V5.882a3.76 3.76 0 016.417-2.88l.83.829A3.76 3.76 0 0111 5.882zM15 5.882V19.24a3.76 3.76 0 006.417 2.88l.83-.829A3.76 3.76 0 0018.118 19.24V5.882a3.76 3.76 0 00-6.417-2.88l-.83.829A3.76 3.76 0 0015 5.882z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Job Advertising</h3>
                    <p class="text-gray-600 mb-6">Post jobs to multiple job boards and social platforms with one-click distribution.</p>
                    <a href="/features/job-advertising.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
                
                <!-- Hiring Analytics -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Hiring Analytics</h3>
                    <p class="text-gray-600 mb-6">Track performance metrics and gain insights to optimize your recruitment process.</p>
                    <a href="/features/hiring-analytics.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">Learn More →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Transform Your Hiring?</h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Experience the power of modern recruitment technology. Start your free trial today.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Start Free Trial
                </a>
                <a href="/contact.html" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200">
                    Schedule Demo
                </a>
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
                        <img src="/logo-talentlit-small.svg" alt="TalentLit Logo" class="h-10">
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
                        <li><a href="/features.html" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="/pricing.html" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="/features/candidate-sourcing.html" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                        <li><a href="/features/hiring-pipeline.html" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                        <li><a href="/features/hiring-analytics.html" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                    </ul>
                </div>
                
                <!-- Company Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="/about.html" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="/careers.html" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="/blog.html" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="/contact.html" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                        <li><a href="/press.html" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
                    </ul>
                </div>
                
                <!-- Support Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="/help-center.html" class="text-gray-400 hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="/documentation.html" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                        <li><a href="/community.html" class="text-gray-400 hover:text-white transition-colors text-sm">Community</a></li>
                        <li><a href="/status.html" class="text-gray-400 hover:text-white transition-colors text-sm">Status</a></li>
                        <li><a href="/security.html" class="text-gray-400 hover:text-white transition-colors text-sm">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="bg-gray-800 rounded-2xl p-8 mb-12">
                <div class="max-w-2xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">Stay Updated</h3>
                    <p class="text-gray-400 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
                    <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" id="newsletter-email" placeholder="Enter your email" class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">
                        © 2023 TalentLit. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="/privacy-policy.html" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                        <a href="/terms-of-service.html" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                        <a href="/cookie-policy.html" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Newsletter subscription
        document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('newsletter-email').value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Show loading state
            button.textContent = 'Subscribing...';
            button.disabled = true;
            
            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ email: email })
                });
                
                if (response.ok) {
                    button.textContent = 'Subscribed!';
                    document.getElementById('newsletter-email').value = '';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                } else {
                    throw new Error('Subscription failed');
                }
            } catch (error) {
                button.textContent = 'Try Again';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        });
    </script>
</body>
</html>
