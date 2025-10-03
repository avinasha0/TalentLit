@extends('layouts.help')

@section('title', 'Hiring Pipeline — TalentLit Help')
@section('description', 'Learn how to navigate and manage your hiring pipeline, move candidates through stages, and track hiring progress in TalentLit ATS.')

@php
    $seoTitle = 'Hiring Pipeline — TalentLit Help';
    $seoDescription = 'Learn how to navigate and manage your hiring pipeline, move candidates through stages, and track hiring progress in TalentLit ATS.';
    $seoKeywords = 'TalentLit pipeline, hiring pipeline, candidate stages, ATS pipeline';
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
                    Hiring Pipeline
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to navigate and manage your hiring pipeline, move candidates through stages, and track hiring progress.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pipeline Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#pipeline-stages" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Pipeline Stages</a>
                            <a href="#moving-candidates" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Moving Candidates</a>
                            <a href="#pipeline-customization" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Customization</a>
                            <a href="#pipeline-analytics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Analytics</a>
                            <a href="#bulk-operations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Bulk Operations</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Pipeline Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The hiring pipeline is a visual representation of your hiring process, showing candidates at different stages. It helps you:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Track candidate progress through hiring stages</li>
                                <li>Identify bottlenecks in your hiring process</li>
                                <li>Move candidates between stages easily</li>
                                <li>Monitor hiring metrics and performance</li>
                                <li>Collaborate with team members on candidate decisions</li>
                            </ul>
                        </div>

                        <h2 id="pipeline-stages" class="text-2xl font-bold text-gray-900 mb-6">Pipeline Stages</h2>
                        <p class="text-gray-600 mb-6">
                            Your hiring pipeline consists of predefined stages that candidates move through during the hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Default Pipeline Stages:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Applied:</strong> Initial application received
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Reviewing:</strong> Application under review
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Interview:</strong> Interview scheduled or completed
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Offered:</strong> Job offer extended
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Hired:</strong> Candidate successfully hired
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Rejected:</strong> Application not selected
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="moving-candidates" class="text-2xl font-bold text-gray-900 mb-6">Moving Candidates</h2>
                        <p class="text-gray-600 mb-6">
                            Easily move candidates between pipeline stages to reflect their progress in your hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Move Candidates:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Access Pipeline:</strong> Navigate to the Pipeline section in your dashboard
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Select Candidate:</strong> Click on the candidate card you want to move
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Drag and Drop:</strong> Drag the candidate card to the new stage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Confirm Move:</strong> Confirm the stage change when prompted
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Add Notes:</strong> Optionally add notes about the stage change
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
                                    <h3 class="text-sm font-medium text-green-800">Pro Tips:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Use keyboard shortcuts for faster candidate movement</li>
                                            <li>Set up automated stage transitions based on actions</li>
                                            <li>Add stage-specific notes to track decision rationale</li>
                                            <li>Use bulk operations to move multiple candidates</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="pipeline-customization" class="text-2xl font-bold text-gray-900 mb-6">Pipeline Customization</h2>
                        <p class="text-gray-600 mb-6">
                            Customize your pipeline stages to match your organization's specific hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customization Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Add Stages:</strong> Create new pipeline stages for your process
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎨</span>
                                        <div>
                                            <strong>Customize Colors:</strong> Set custom colors for each stage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Set Limits:</strong> Define maximum candidates per stage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Configure Rules:</strong> Set up automated stage transitions
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="pipeline-analytics" class="text-2xl font-bold text-gray-900 mb-6">Pipeline Analytics</h2>
                        <p class="text-gray-600 mb-6">
                            Monitor your hiring pipeline performance with built-in analytics and reporting features.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Metrics:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📈</span>
                                        <div>
                                            <strong>Stage Conversion Rates:</strong> Track how many candidates move between stages
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏱️</span>
                                        <div>
                                            <strong>Time in Stage:</strong> Monitor how long candidates spend in each stage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Pipeline Health:</strong> Identify bottlenecks and inefficiencies
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎯</span>
                                        <div>
                                            <strong>Hiring Velocity:</strong> Track overall hiring speed and efficiency
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="bulk-operations" class="text-2xl font-bold text-gray-900 mb-6">Bulk Operations</h2>
                        <p class="text-gray-600 mb-6">
                            Save time by performing bulk operations on multiple candidates in your pipeline.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Bulk Actions Available:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Move Multiple Candidates:</strong> Move several candidates to the same stage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Assign Team Members:</strong> Assign multiple candidates to team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Send Bulk Emails:</strong> Send emails to multiple candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Export Data:</strong> Export pipeline data for analysis
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Move Candidates</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "edit pipeline" access</li>
                                        <li>• Verify the candidate is not locked by another user</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pipeline Not Loading</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Clear your browser cache</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bulk Operations Failing</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Make sure you have selected candidates</li>
                                        <li>• Check your permissions for bulk operations</li>
                                        <li>• Try selecting fewer candidates at once</li>
                                        <li>• Contact support if the issue persists</li>
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
                                        <li><a href="{{ route('help.page', 'applications') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Application Management</a></li>
                                        <li><a href="{{ route('help.page', 'candidates') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Candidate Management</a></li>
                                        <li><a href="{{ route('help.page', 'interviews') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Interview Scheduling</a></li>
                                        <li><a href="{{ route('help.page', 'analytics') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Analytics & Reports</a></li>
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
