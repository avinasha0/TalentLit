@extends('layouts.help')

@section('title', 'Notes & Tags — TalentLit Help')
@section('description', 'Learn how to add notes and tags to candidates, organize information, and improve collaboration in TalentLit ATS.')

@php
    $seoTitle = 'Notes & Tags — TalentLit Help';
    $seoDescription = 'Learn how to add notes and tags to candidates, organize information, and improve collaboration in TalentLit ATS.';
    $seoKeywords = 'TalentLit notes, candidate tags, ATS organization, candidate management';
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
                    Notes & Tags
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to add notes and tags to candidates, organize information, and improve collaboration.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes & Tags Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#adding-notes" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Adding Notes</a>
                            <a href="#managing-notes" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Managing Notes</a>
                            <a href="#using-tags" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Using Tags</a>
                            <a href="#tag-management" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Tag Management</a>
                            <a href="#collaboration" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Collaboration</a>
                            <a href="#search-filter" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Search & Filter</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Notes & Tags Overview</h2>
                            <p class="text-gray-600 mb-4">
                                Notes and tags help you organize candidate information and improve team collaboration. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Add detailed notes about candidates and their progress</li>
                                <li>Create and assign tags to categorize candidates</li>
                                <li>Share information with team members</li>
                                <li>Search and filter candidates by notes and tags</li>
                                <li>Track candidate history and interactions</li>
                                <li>Improve hiring decision-making</li>
                            </ul>
                        </div>

                        <h2 id="adding-notes" class="text-2xl font-bold text-gray-900 mb-6">Adding Notes</h2>
                        <p class="text-gray-600 mb-6">
                            Add detailed notes about candidates to track their progress, skills, and interactions.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Add Notes:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Select Candidate:</strong> Go to the candidate's profile or application
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click "Add Note":</strong> Click the add note button in the notes section
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Enter Note Content:</strong> Type your note in the text area
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Set Visibility:</strong> Choose if the note is private or visible to team
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Add Tags (Optional):</strong> Add relevant tags to the note
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Save Note:</strong> Click save to add the note
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="managing-notes" class="text-2xl font-bold text-gray-900 mb-6">Managing Notes</h2>
                        <p class="text-gray-600 mb-6">
                            Edit, delete, and organize notes to keep candidate information up-to-date and relevant.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Note Management Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✏️</span>
                                        <div>
                                            <strong>Edit Notes:</strong> Update note content and details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗑️</span>
                                        <div>
                                            <strong>Delete Notes:</strong> Remove outdated or irrelevant notes
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Privacy Settings:</strong> Control note visibility and access
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Timestamps:</strong> Track when notes were created and modified
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="using-tags" class="text-2xl font-bold text-gray-900 mb-6">Using Tags</h2>
                        <p class="text-gray-600 mb-6">
                            Create and assign tags to categorize candidates and make them easier to find and organize.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Use Tags:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Create Tags:</strong> Create new tags in the tag management section
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Assign Tags:</strong> Add tags to candidates from their profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Filter by Tags:</strong> Use tags to filter and search candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Bulk Tagging:</strong> Apply tags to multiple candidates at once
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="tag-management" class="text-2xl font-bold text-gray-900 mb-6">Tag Management</h2>
                        <p class="text-gray-600 mb-6">
                            Organize and manage your tag library to maintain consistency across your hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Tag Management Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏷️</span>
                                        <div>
                                            <strong>Create Tags:</strong> Create new tags with custom names and colors
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎨</span>
                                        <div>
                                            <strong>Customize Colors:</strong> Set custom colors for easy identification
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Add Descriptions:</strong> Add descriptions to explain tag purpose
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗑️</span>
                                        <div>
                                            <strong>Delete Tags:</strong> Remove unused or outdated tags
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="collaboration" class="text-2xl font-bold text-gray-900 mb-6">Collaboration</h2>
                        <p class="text-gray-600 mb-6">
                            Share information and collaborate with team members using notes and tags.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Collaboration Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Team Notes:</strong> Share notes with team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Private Notes:</strong> Keep sensitive information private
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Notifications:</strong> Get notified when notes are added or updated
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Activity Feed:</strong> Track all note and tag activity
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="search-filter" class="text-2xl font-bold text-gray-900 mb-6">Search & Filter</h2>
                        <p class="text-gray-600 mb-6">
                            Use notes and tags to quickly find and filter candidates based on specific criteria.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Search & Filter Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Text Search:</strong> Search within note content
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏷️</span>
                                        <div>
                                            <strong>Tag Filter:</strong> Filter candidates by specific tags
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👤</span>
                                        <div>
                                            <strong>Author Filter:</strong> Filter notes by who created them
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Date Filter:</strong> Filter notes by creation or modification date
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Add Notes</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "add notes" access</li>
                                        <li>• Verify the candidate profile is not locked</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tags Not Appearing</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check if the tag was created successfully</li>
                                        <li>• Verify the tag is assigned to the candidate</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Search Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your search terms and spelling</li>
                                        <li>• Try using different search filters</li>
                                        <li>• Clear browser cache and try again</li>
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
                                        <li><a href="{{ route('help.page', 'candidates') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Candidate Management</a></li>
                                        <li><a href="{{ route('help.page', 'applications') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Application Management</a></li>
                                        <li><a href="{{ route('help.page', 'pipeline') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Hiring Pipeline</a></li>
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
