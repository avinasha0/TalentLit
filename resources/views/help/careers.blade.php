@extends('layouts.help')

@section('title', 'Career Pages — TalentLit Help')
@section('description', 'Learn how to customize your public career page, manage job visibility, and optimize your candidate experience in TalentLit.')

@php
    $seoTitle = 'Career Pages — TalentLit Help';
    $seoDescription = 'Learn how to customize your public career page, manage job visibility, and optimize your candidate experience in TalentLit.';
    $seoKeywords = 'TalentLit career pages, job board, candidate experience, public careers';
    $seoAuthor = 'TalentLit';
    $seoImage = asset('logo-talentlit-small.svg');
@endphp

@section('content')
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('help.index') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        ← Back to Help Center
                    </a>
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                    Career Pages
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to customize your public career page, manage job visibility, and optimize your candidate experience.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Table of Contents -->
                <div class="lg:col-span-9">
                    <div class="sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Career Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#customization" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Page Customization</a>
                            <a href="#job-visibility" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Job Visibility</a>
                            <a href="#candidate-experience" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Candidate Experience</a>
                            <a href="#sharing" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Sharing & Promotion</a>
                            <a href="#seo" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">SEO & Discoverability</a>
                            <a href="#analytics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Career Page Analytics</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Career Pages Overview</h2>
                            <p class="text-gray-600 mb-4">
                                Your public career page is where candidates discover and apply for your job openings. It's automatically generated and includes:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Your company branding and information</li>
                                <li>All published job openings</li>
                                <li>Search and filter functionality</li>
                                <li>Mobile-responsive design</li>
                                <li>Easy application process</li>
                                <li>SEO optimization</li>
                            </ul>
                        </div>

                        <h2 id="customization" class="text-2xl font-bold text-gray-900 mb-6">Page Customization</h2>
                        <p class="text-gray-600 mb-6">
                            Customize your career page to match your brand and attract the right candidates.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customization Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎨</span>
                                        <div>
                                            <strong>Branding:</strong> Logo, colors, and visual identity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Company Description:</strong> About your company and culture
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏢</span>
                                        <div>
                                            <strong>Office Information:</strong> Location and office details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔗</span>
                                        <div>
                                            <strong>Social Links:</strong> LinkedIn, Twitter, and other social media
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Custom Pages:</strong> Additional pages like benefits, culture, etc.
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-green-50 border border-green-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Pro Tips for Customization:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Use high-quality images and logos for best results</li>
                                            <li>Write compelling company descriptions that showcase your culture</li>
                                            <li>Keep your branding consistent across all elements</li>
                                            <li>Test your career page on different devices and browsers</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="job-visibility" class="text-2xl font-bold text-gray-900 mb-6">Job Visibility</h2>
                        <p class="text-gray-600 mb-6">
                            Control which jobs appear on your career page and how they're displayed.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Visibility Controls:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👁️</span>
                                        <div>
                                            <strong>Published Jobs:</strong> Only published jobs appear on the career page
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Draft Jobs:</strong> Draft jobs are not visible to candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">❌</span>
                                        <div>
                                            <strong>Closed Jobs:</strong> Closed jobs are removed from the career page
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Expiration Dates:</strong> Set automatic job expiration if needed
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="candidate-experience" class="text-2xl font-bold text-gray-900 mb-6">Candidate Experience</h2>
                        <p class="text-gray-600 mb-6">
                            Optimize the candidate experience to increase application rates and attract top talent.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Candidate Experience Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Search & Filter:</strong> Easy job discovery with search and filters
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Responsive:</strong> Optimized for all devices
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚡</span>
                                        <div>
                                            <strong>Fast Loading:</strong> Quick page load times for better experience
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Easy Application:</strong> Streamlined application process
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔔</span>
                                        <div>
                                            <strong>Application Confirmation:</strong> Immediate confirmation of applications
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="sharing" class="text-2xl font-bold text-gray-900 mb-6">Sharing & Promotion</h2>
                        <p class="text-gray-600 mb-6">
                            Promote your career page to attract more candidates and increase visibility.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Promotion Strategies:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔗</span>
                                        <div>
                                            <strong>Direct Link:</strong> Share your career page URL directly
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Email Signatures:</strong> Include career page link in email signatures
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Social Media:</strong> Share job postings on social platforms
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🌐</span>
                                        <div>
                                            <strong>Website Integration:</strong> Link to career page from your main website
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Job Boards:</strong> Cross-post to external job boards
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="seo" class="text-2xl font-bold text-gray-900 mb-6">SEO & Discoverability</h2>
                        <p class="text-gray-600 mb-6">
                            Optimize your career page for search engines to increase organic traffic and candidate discovery.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">SEO Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Meta Tags:</strong> Optimized title and description tags
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Structured Data:</strong> Job posting structured data for search engines
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔗</span>
                                        <div>
                                            <strong>Clean URLs:</strong> SEO-friendly URLs for job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Optimization:</strong> Mobile-friendly design for better rankings
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="analytics" class="text-2xl font-bold text-gray-900 mb-6">Career Page Analytics</h2>
                        <p class="text-gray-600 mb-6">
                            Track the performance of your career page to understand candidate behavior and optimize your hiring strategy.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Metrics to Track:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👀</span>
                                        <div>
                                            <strong>Page Views:</strong> Number of visitors to your career page
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Job Views:</strong> Individual job posting views
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Application Rate:</strong> Percentage of views that result in applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Search Terms:</strong> What candidates are searching for
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Device Usage:</strong> Mobile vs desktop traffic
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Career Page Not Loading</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check if your organization slug is correct</li>
                                        <li>• Verify that you have published jobs</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Jobs Not Appearing</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Make sure jobs are published (not draft)</li>
                                        <li>• Check if jobs are closed or expired</li>
                                        <li>• Verify your job visibility settings</li>
                                        <li>• Try republishing the job</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Customization Not Showing</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Clear your browser cache</li>
                                        <li>• Wait a few minutes for changes to propagate</li>
                                        <li>• Check if you saved the changes</li>
                                        <li>• Try viewing in incognito mode</li>
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
                                        <li><a href="{{ route('help.page', 'jobs') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Job Management</a></li>
                                        <li><a href="{{ route('help.page', 'settings') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
                                        <li><a href="{{ route('help.page', 'analytics') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Analytics & Reports</a></li>
                                        <li><a href="{{ route('help.page', 'candidates') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Candidate Management</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('help.page', 'troubleshooting') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
                                        <li><a href="{{ route('help.page', 'contact') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact Support</a></li>
                                        <li><a href="{{ route('help.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a></li>
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
@endsection
