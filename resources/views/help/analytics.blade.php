@extends('layouts.help')

@section('title', 'Analytics & Reports — TalentLit Help')
@section('description', 'Learn how to use TalentLit analytics to track hiring metrics, generate reports, and make data-driven recruitment decisions.')

@php
    $seoTitle = 'Analytics & Reports — TalentLit Help';
    $seoDescription = 'Learn how to use TalentLit analytics to track hiring metrics, generate reports, and make data-driven recruitment decisions.';
    $seoKeywords = 'TalentLit analytics, hiring reports, recruitment metrics, ATS analytics';
    $seoAuthor = 'TalentLit';
    $seoImage = asset('logo-talentlit-small.png');
@endphp

@section('content')
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="{{ asset('logo-talentlit-small.png') }}" alt="TalentLit Logo" class="h-8">
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
                    Analytics & Reports
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to use TalentLit analytics to track hiring metrics, generate reports, and make data-driven recruitment decisions.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Analytics Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#key-metrics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Key Metrics</a>
                            <a href="#charts-graphs" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Charts & Graphs</a>
                            <a href="#filters" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Filters & Date Ranges</a>
                            <a href="#reports" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Generating Reports</a>
                            <a href="#exporting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Exporting Data</a>
                            <a href="#customization" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Customization</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Analytics Overview</h2>
                            <p class="text-gray-600 mb-4">
                                TalentLit analytics provides comprehensive insights into your hiring process, helping you make data-driven decisions. The analytics dashboard includes:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Real-time hiring metrics and KPIs</li>
                                <li>Interactive charts and visualizations</li>
                                <li>Customizable date ranges and filters</li>
                                <li>Exportable reports in multiple formats</li>
                                <li>Team performance insights</li>
                                <li>Source tracking and attribution</li>
                            </ul>
                        </div>

                        <h2 id="key-metrics" class="text-2xl font-bold text-gray-900 mb-6">Key Metrics Explained</h2>
                        <p class="text-gray-600 mb-6">
                            Understanding the key metrics will help you track your hiring performance and identify areas for improvement.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Core Hiring Metrics:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Time to Hire:</strong> Average days from job posting to hire
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Applications per Job:</strong> Average number of applications received per job posting
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📈</span>
                                        <div>
                                            <strong>Hire Rate:</strong> Percentage of applications that result in hires
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎯</span>
                                        <div>
                                            <strong>Source Effectiveness:</strong> Which channels bring the best candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏱️</span>
                                        <div>
                                            <strong>Interview to Hire Ratio:</strong> How many interviews lead to offers
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
                                    <h3 class="text-sm font-medium text-green-800">Pro Tips for Metrics:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Track metrics over time to identify trends</li>
                                            <li>Compare metrics across different job types</li>
                                            <li>Set benchmarks based on industry standards</li>
                                            <li>Use metrics to justify hiring decisions to stakeholders</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="charts-graphs" class="text-2xl font-bold text-gray-900 mb-6">Charts and Graphs</h2>
                        <p class="text-gray-600 mb-6">
                            Visual representations of your data help you quickly understand trends and patterns in your hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Chart Types:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Line Charts:</strong> Track metrics over time (applications, hires, etc.)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📈</span>
                                        <div>
                                            <strong>Bar Charts:</strong> Compare metrics across different categories
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🥧</span>
                                        <div>
                                            <strong>Pie Charts:</strong> Show distribution of applications by source
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📋</span>
                                        <div>
                                            <strong>Funnel Charts:</strong> Visualize the hiring pipeline stages
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Heat Maps:</strong> Show activity patterns by day/time
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="filters" class="text-2xl font-bold text-gray-900 mb-6">Filters and Date Ranges</h2>
                        <p class="text-gray-600 mb-6">
                            Use filters to focus on specific data and time periods that are most relevant to your analysis.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Filters:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Date Range:</strong> Last 7 days, 30 days, 90 days, or custom range
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Job Filter:</strong> Analyze specific job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Team Member:</strong> Filter by specific team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏷️</span>
                                        <div>
                                            <strong>Department:</strong> Filter by job department
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📍</span>
                                        <div>
                                            <strong>Location:</strong> Filter by job location
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="reports" class="text-2xl font-bold text-gray-900 mb-6">Generating Reports</h2>
                        <p class="text-gray-600 mb-6">
                            Create detailed reports to share with stakeholders or for your own analysis and record-keeping.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Report Types:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Hiring Summary:</strong> Overview of all hiring activity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Team Performance:</strong> Individual team member metrics
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Job Performance:</strong> Analysis of specific job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📈</span>
                                        <div>
                                            <strong>Trend Analysis:</strong> Historical trends and patterns
                                        </div>
                                    </li>
                                </ul>
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
                                    <h3 class="text-sm font-medium text-blue-800">How to Generate Reports:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ol class="list-decimal list-inside space-y-1">
                                            <li>Go to the Analytics section</li>
                                            <li>Apply your desired filters and date range</li>
                                            <li>Click "Generate Report" button</li>
                                            <li>Choose your report format (PDF, Excel, CSV)</li>
                                            <li>Download or email the report</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="exporting" class="text-2xl font-bold text-gray-900 mb-6">Exporting Data</h2>
                        <p class="text-gray-600 mb-6">
                            Export your analytics data in various formats for further analysis or sharing with stakeholders.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Formats:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>PDF:</strong> Formatted reports for presentations
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Excel:</strong> Raw data for further analysis
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📋</span>
                                        <div>
                                            <strong>CSV:</strong> Comma-separated values for data import
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Email:</strong> Send reports directly via email
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="customization" class="text-2xl font-bold text-gray-900 mb-6">Customization</h2>
                        <p class="text-gray-600 mb-6">
                            Customize your analytics dashboard to focus on the metrics that matter most to your organization.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customization Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎛️</span>
                                        <div>
                                            <strong>Dashboard Layout:</strong> Arrange widgets to your preference
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Chart Types:</strong> Choose your preferred visualization styles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎨</span>
                                        <div>
                                            <strong>Color Schemes:</strong> Match your brand colors
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Default Date Ranges:</strong> Set your preferred time periods
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Charts Not Loading</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Refresh the page and try again</li>
                                        <li>• Clear your browser cache</li>
                                        <li>• Try a different browser</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Not Updating</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Wait a few minutes for data to refresh</li>
                                        <li>• Check if you have the latest data permissions</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Export Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your browser's popup blocker settings</li>
                                        <li>• Ensure you have sufficient storage space</li>
                                        <li>• Try a different export format</li>
                                        <li>• Contact support if the issue continues</li>
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
                                        <li><a href="{{ route('help.page', 'dashboard') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Dashboard Overview</a></li>
                                        <li><a href="{{ route('help.page', 'jobs') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Job Management</a></li>
                                        <li><a href="{{ route('help.page', 'candidates') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Candidate Management</a></li>
                                        <li><a href="{{ route('help.page', 'settings') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
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
