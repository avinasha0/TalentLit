@extends('layouts.help')

@section('title', 'Roles & Permissions — TalentLit Help')
@section('description', 'Learn about user roles and permissions in TalentLit ATS, how to assign roles, and manage team access effectively.')

@php
    $seoTitle = 'Roles & Permissions — TalentLit Help';
    $seoDescription = 'Learn about user roles and permissions in TalentLit ATS, how to assign roles, and manage team access effectively.';
    $seoKeywords = 'TalentLit roles, user permissions, team management, ATS access control';
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
                    Roles & Permissions
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn about user roles and permissions, how to assign roles, and manage team access effectively.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Roles & Permissions Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#default-roles" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Default Roles</a>
                            <a href="#permissions" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Permissions</a>
                            <a href="#assigning-roles" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Assigning Roles</a>
                            <a href="#custom-roles" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Custom Roles</a>
                            <a href="#role-management" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Role Management</a>
                            <a href="#best-practices" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Best Practices</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-700 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Roles & Permissions Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The roles and permissions system helps you control access to different features and data in TalentLit. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Assign different roles to team members</li>
                                <li>Control access to sensitive information</li>
                                <li>Create custom roles for specific needs</li>
                                <li>Manage team permissions centrally</li>
                                <li>Ensure data security and compliance</li>
                                <li>Streamline team collaboration</li>
                            </ul>
                        </div>

                        <h2 id="default-roles" class="text-2xl font-bold text-gray-900 mb-6">Default Roles</h2>
                        <p class="text-gray-600 mb-6">
                            TalentLit comes with predefined roles that cover most common organizational needs.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Default Roles:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👑</span>
                                        <div>
                                            <strong>Super Admin:</strong> Full access to all features and settings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👨‍💼</span>
                                        <div>
                                            <strong>Admin:</strong> Manage users, settings, and all hiring activities
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👨‍💻</span>
                                        <div>
                                            <strong>Hiring Manager:</strong> Manage job postings, applications, and interviews
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👨‍🎓</span>
                                        <div>
                                            <strong>Recruiter:</strong> Source candidates and manage applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👨‍🏫</span>
                                        <div>
                                            <strong>Interviewer:</strong> Conduct interviews and provide feedback
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👀</span>
                                        <div>
                                            <strong>Viewer:</strong> Read-only access to most features
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="permissions" class="text-2xl font-bold text-gray-900 mb-6">Permissions</h2>
                        <p class="text-gray-600 mb-6">
                            Permissions control what actions users can perform and what data they can access.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Permission Categories:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>User Management:</strong> Create, edit, and delete users
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Job Management:</strong> Create, edit, and manage job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📋</span>
                                        <div>
                                            <strong>Application Management:</strong> View and manage job applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👤</span>
                                        <div>
                                            <strong>Candidate Management:</strong> View and manage candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Analytics:</strong> View reports and analytics
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Settings:</strong> Modify system settings and configurations
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="assigning-roles" class="text-2xl font-bold text-gray-900 mb-6">Assigning Roles</h2>
                        <p class="text-gray-600 mb-6">
                            Assign roles to team members to control their access to different features and data.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Assign Roles:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Go to Team Management:</strong> Navigate to the team management section
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Select User:</strong> Click on the user you want to assign a role to
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Choose Role:</strong> Select the appropriate role from the dropdown
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Review Permissions:</strong> Review the permissions that come with the role
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Save Changes:</strong> Click save to apply the role
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="custom-roles" class="text-2xl font-bold text-gray-900 mb-6">Custom Roles</h2>
                        <p class="text-gray-600 mb-6">
                            Create custom roles to match your organization's specific needs and workflows.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Creating Custom Roles:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Access Role Management:</strong> Go to the role management section
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Create New Role:</strong> Click "Create New Role"
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Define Role Name:</strong> Enter a descriptive name for the role
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Set Permissions:</strong> Select the specific permissions for this role
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Add Description:</strong> Add a description explaining the role's purpose
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Save Role:</strong> Save the custom role
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="role-management" class="text-2xl font-bold text-gray-900 mb-6">Role Management</h2>
                        <p class="text-gray-600 mb-6">
                            Manage existing roles, update permissions, and maintain role consistency across your organization.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Management Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✏️</span>
                                        <div>
                                            <strong>Edit Roles:</strong> Modify existing role permissions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗑️</span>
                                        <div>
                                            <strong>Delete Roles:</strong> Remove unused or outdated roles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Role Analytics:</strong> View role usage and assignment statistics
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Permission Audit:</strong> Audit role permissions and access
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="best-practices" class="text-2xl font-bold text-gray-900 mb-6">Best Practices</h2>
                        <p class="text-gray-600 mb-6">
                            Follow these best practices to effectively manage roles and permissions in your organization.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Role Management Best Practices:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎯</span>
                                        <div>
                                            <strong>Principle of Least Privilege:</strong> Give users only the permissions they need
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔄</span>
                                        <div>
                                            <strong>Regular Reviews:</strong> Regularly review and update role assignments
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Documentation:</strong> Document role purposes and permissions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Security:</strong> Regularly audit permissions for security compliance
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Assign Roles</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "manage users" access</li>
                                        <li>• Verify the user account is active</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Role Changes Not Taking Effect</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Ask the user to log out and log back in</li>
                                        <li>• Clear browser cache and try again</li>
                                        <li>• Check if the role was saved correctly</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Create Custom Roles</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "manage roles" access</li>
                                        <li>• Verify the role name is unique</li>
                                        <li>• Make sure you have selected at least one permission</li>
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
                                        <li><a href="{{ route('help.page', 'settings') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
                                        <li><a href="{{ route('help.page', 'invite-team') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Team Invitations</a></li>
                                        <li><a href="{{ route('help.page', 'security') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Security Best Practices</a></li>
                                        <li><a href="{{ route('help.page', 'troubleshooting') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
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
