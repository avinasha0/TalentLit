@extends('layouts.help')

@section('title', 'Account Settings — TalentLit Help')
@section('description', 'Learn how to configure your TalentLit account settings, manage team members, and customize your organization preferences.')

@php
    $seoTitle = 'Account Settings — TalentLit Help';
    $seoDescription = 'Learn how to configure your TalentLit account settings, manage team members, and customize your organization preferences.';
    $seoKeywords = 'TalentLit settings, account configuration, team management, organization settings';
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
                    Account Settings
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to configure your TalentLit account settings, manage team members, and customize your organization preferences.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#general-settings" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">General Settings</a>
                            <a href="#team-management" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Team Management</a>
                            <a href="#careers-settings" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Careers Settings</a>
                            <a href="#email-settings" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Email Settings</a>
                            <a href="#security" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Security Settings</a>
                            <a href="#billing" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Billing & Subscription</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Settings Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The Settings section allows you to configure your TalentLit account and organization. You can manage:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Organization information and branding</li>
                                <li>Team members and their permissions</li>
                                <li>Career page customization</li>
                                <li>Email and notification preferences</li>
                                <li>Security and password settings</li>
                                <li>Billing and subscription management</li>
                            </ul>
                        </div>

                        <h2 id="general-settings" class="text-2xl font-bold text-gray-900 mb-6">General Settings</h2>
                        <p class="text-gray-600 mb-6">
                            Configure your organization's basic information and preferences.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Organization Information:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Organization Name:</strong> Your company or organization name
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Organization Slug:</strong> URL-friendly identifier for your career page
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Industry:</strong> Your organization's industry sector
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Company Size:</strong> Number of employees in your organization
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Contact Information:</strong> Address, phone, and website details
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.726-1.36 3.491 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Important Notes:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>The organization slug cannot be changed after creation</li>
                                            <li>Contact information will be displayed on your career page</li>
                                            <li>Industry and company size help with analytics and reporting</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="team-management" class="text-2xl font-bold text-gray-900 mb-6">Team Management</h2>
                        <p class="text-gray-600 mb-6">
                            Manage your team members, their roles, and permissions to ensure proper access control.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Management Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Invite Team Members:</strong> Send email invitations to new team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>Manage Roles:</strong> Assign appropriate roles and permissions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👤</span>
                                        <div>
                                            <strong>User Profiles:</strong> View and edit team member information
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🚫</span>
                                        <div>
                                            <strong>Deactivate Users:</strong> Temporarily disable user accounts
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Resend Invitations:</strong> Resend invitation emails if needed
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="careers-settings" class="text-2xl font-bold text-gray-900 mb-6">Careers Settings</h2>
                        <p class="text-gray-600 mb-6">
                            Customize your public career page to match your brand and attract the right candidates.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Career Page Customization:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎨</span>
                                        <div>
                                            <strong>Branding:</strong> Logo, colors, and visual identity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Company Description:</strong> About your company and culture
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏢</span>
                                        <div>
                                            <strong>Office Information:</strong> Location and office details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔗</span>
                                        <div>
                                            <strong>Social Links:</strong> LinkedIn, Twitter, and other social media
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Custom Pages:</strong> Additional pages like benefits, culture, etc.
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="email-settings" class="text-2xl font-bold text-gray-900 mb-6">Email Settings</h2>
                        <p class="text-gray-600 mb-6">
                            Configure email notifications and SMTP settings for your organization.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Configuration:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>SMTP Settings:</strong> Configure your email server settings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔔</span>
                                        <div>
                                            <strong>Notification Preferences:</strong> Choose which emails to send
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Email Templates:</strong> Customize email content and branding
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🧪</span>
                                        <div>
                                            <strong>Test Email:</strong> Send test emails to verify configuration
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="security" class="text-2xl font-bold text-gray-900 mb-6">Security Settings</h2>
                        <p class="text-gray-600 mb-6">
                            Manage password policies, two-factor authentication, and other security features.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Security Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Password Policy:</strong> Set minimum password requirements
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>Two-Factor Authentication:</strong> Enable 2FA for enhanced security
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏰</span>
                                        <div>
                                            <strong>Session Timeout:</strong> Set automatic logout after inactivity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Device Management:</strong> View and manage active sessions
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="billing" class="text-2xl font-bold text-gray-900 mb-6">Billing & Subscription</h2>
                        <p class="text-gray-600 mb-6">
                            Manage your subscription, view billing history, and update payment information.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Billing Management:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💳</span>
                                        <div>
                                            <strong>Payment Methods:</strong> Update credit card and billing information
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Usage Tracking:</strong> Monitor your plan usage and limits
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Billing History:</strong> View past invoices and payments
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔄</span>
                                        <div>
                                            <strong>Plan Changes:</strong> Upgrade or downgrade your subscription
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Access Settings</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your user permissions - you need admin or owner access</li>
                                        <li>• Make sure you're logged in with the correct account</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact your organization admin if needed</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Changes Not Saving</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Make sure all required fields are filled out</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Email Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify your SMTP settings are correct</li>
                                        <li>• Test your email configuration</li>
                                        <li>• Check if your email provider is blocking our emails</li>
                                        <li>• Contact support for assistance with email setup</li>
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
                                        <li><a href="{{ route('help.page', 'roles-permissions') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Roles & Permissions</a></li>
                                        <li><a href="{{ route('help.page', 'invite-team') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Team Management</a></li>
                                        <li><a href="{{ route('help.page', 'careers') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Career Pages</a></li>
                                        <li><a href="{{ route('help.page', 'security') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Security Guide</a></li>
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
