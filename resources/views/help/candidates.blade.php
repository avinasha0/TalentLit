@extends('layouts.help')

@section('title', 'Managing Candidates — TalentLit Help')
@section('description', 'Learn how to manage candidates, view profiles, add notes and tags, and organize your talent pipeline in TalentLit.')

@php
    $seoTitle = 'Managing Candidates — TalentLit Help';
    $seoDescription = 'Learn how to manage candidates, view profiles, add notes and tags, and organize your talent pipeline in TalentLit.';
    $seoKeywords = 'TalentLit candidates, candidate management, talent pipeline, ATS candidates';
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
                    Managing Candidates
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to manage candidates, view profiles, add notes and tags, and organize your talent pipeline.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Candidate Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#viewing-profiles" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Viewing Profiles</a>
                            <a href="#searching-filtering" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Search & Filter</a>
                            <a href="#notes-tags" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Notes & Tags</a>
                            <a href="#resume-management" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Resume Management</a>
                            <a href="#status-updates" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Status Updates</a>
                            <a href="#communication" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Communication</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Candidate Management Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The candidate management system in TalentLit helps you organize, track, and collaborate on potential hires. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>View detailed candidate profiles with contact information and application history</li>
                                <li>Search and filter candidates by various criteria</li>
                                <li>Add notes and tags for better organization</li>
                                <li>Manage resumes and documents</li>
                                <li>Track application status and progress</li>
                                <li>Communicate with candidates and team members</li>
                            </ul>
                        </div>

                        <h2 id="viewing-profiles" class="text-2xl font-bold text-gray-900 mb-6">Viewing Candidate Profiles</h2>
                        <p class="text-gray-600 mb-6">
                            Each candidate has a detailed profile containing all their information, application history, and interactions.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Profile Information Includes:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Contact Details:</strong> Name, email, phone, location
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Application History:</strong> All jobs they've applied for
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Resumes & Documents:</strong> Uploaded files and attachments
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Notes & Comments:</strong> Team notes and feedback
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Tags & Labels:</strong> Custom tags for organization
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
                                    <h3 class="text-sm font-medium text-green-800">Quick Actions:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Click on any candidate name to view their full profile</li>
                                            <li>Use the "View Details" button for a comprehensive overview</li>
                                            <li>Download resumes directly from the profile page</li>
                                            <li>Add notes and tags without leaving the profile view</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="searching-filtering" class="text-2xl font-bold text-gray-900 mb-6">Searching and Filtering Candidates</h2>
                        <p class="text-gray-600 mb-6">
                            Find the right candidates quickly using powerful search and filtering options.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Search Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Name Search:</strong> Search by candidate name or email
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Job Filter:</strong> Filter by specific job applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Status Filter:</strong> Filter by application status
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏷️</span>
                                        <div>
                                            <strong>Tag Filter:</strong> Filter by assigned tags
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Date Filter:</strong> Filter by application date range
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="notes-tags" class="text-2xl font-bold text-gray-900 mb-6">Notes and Tags</h2>
                        <p class="text-gray-600 mb-6">
                            Organize and collaborate on candidates using notes and tags for better team coordination.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Adding Notes:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Access Notes Section:</strong> Go to the candidate profile and find the Notes section
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click "Add Note":</strong> Click the button to create a new note
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Write Your Note:</strong> Add your observations, feedback, or next steps
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Save Note:</strong> Click save to add the note to the candidate's profile
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Managing Tags:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Create Tags:</strong> Go to Settings > Tags to create new tag categories
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Assign Tags:</strong> Select tags from the dropdown in candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Filter by Tags:</strong> Use tag filters to find candidates with specific labels
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Remove Tags:</strong> Click the X on any tag to remove it from a candidate
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="resume-management" class="text-2xl font-bold text-gray-900 mb-6">Resume Management</h2>
                        <p class="text-gray-600 mb-6">
                            Manage candidate resumes and documents efficiently with our built-in document management system.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Resume Operations:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>View Resumes:</strong> Click on any resume to view it in the browser
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⬇️</span>
                                        <div>
                                            <strong>Download Resumes:</strong> Download resumes to your computer
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">➕</span>
                                        <div>
                                            <strong>Upload Additional Files:</strong> Add more documents to candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗑️</span>
                                        <div>
                                            <strong>Delete Files:</strong> Remove outdated or unnecessary documents
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="status-updates" class="text-2xl font-bold text-gray-900 mb-6">Status Updates</h2>
                        <p class="text-gray-600 mb-6">
                            Track candidate progress through your hiring pipeline by updating their application status.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Status Updates:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">A</span>
                                        <div>
                                            <strong>Applied:</strong> Initial application received
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">R</span>
                                        <div>
                                            <strong>Reviewing:</strong> Application under review
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">I</span>
                                        <div>
                                            <strong>Interview:</strong> Interview scheduled or completed
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">H</span>
                                        <div>
                                            <strong>Hired:</strong> Candidate successfully hired
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">R</span>
                                        <div>
                                            <strong>Rejected:</strong> Application not selected
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="communication" class="text-2xl font-bold text-gray-900 mb-6">Communication</h2>
                        <p class="text-gray-600 mb-6">
                            Keep track of all communication with candidates and team members in one centralized location.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Communication Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Email Integration:</strong> Send emails directly from candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📞</span>
                                        <div>
                                            <strong>Call Logs:</strong> Record phone conversations and outcomes
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💬</span>
                                        <div>
                                            <strong>Internal Comments:</strong> Team communication about candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Meeting Notes:</strong> Record interview and meeting outcomes
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot View Candidate Profiles</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "view candidates" access</li>
                                        <li>• Make sure you're logged in with the correct account</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Search Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Try different search terms</li>
                                        <li>• Clear any active filters</li>
                                        <li>• Check if you have the right permissions</li>
                                        <li>• Refresh the page and try again</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Add Notes or Tags</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify you have edit permissions for candidates</li>
                                        <li>• Check if the candidate profile is locked</li>
                                        <li>• Try logging out and back in</li>
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
                                        <li><a href="{{ route('help.page', 'applications') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Application Management</a></li>
                                        <li><a href="{{ route('help.page', 'pipeline') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Hiring Pipeline</a></li>
                                        <li><a href="{{ route('help.page', 'interviews') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Scheduling Interviews</a></li>
                                        <li><a href="{{ route('help.page', 'notes-tags') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Notes & Tags Guide</a></li>
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
