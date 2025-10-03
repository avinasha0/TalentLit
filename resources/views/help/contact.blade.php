@extends('layouts.help')

@section('title', 'Contact Support — TalentLit Help')
@section('description', 'Get in touch with TalentLit support team. Find contact information, support hours, and how to report issues.')

@php
    $seoTitle = 'Contact Support — TalentLit Help';
    $seoDescription = 'Get in touch with TalentLit support team. Find contact information, support hours, and how to report issues.';
    $seoKeywords = 'TalentLit support, contact help, technical support, customer service';
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
                    Contact Support
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Get in touch with our support team. We're here to help you succeed with TalentLit.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Options</h3>
                        <nav class="space-y-2">
                            <a href="#support-form" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Support Form</a>
                            <a href="#email" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Email Support</a>
                            <a href="#response-times" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Response Times</a>
                            <a href="#before-contact" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Before Contacting</a>
                            <a href="#bug-reports" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Bug Reports</a>
                            <a href="#feature-requests" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Feature Requests</a>
                            <a href="#emergency" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Emergency Support</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="support-form" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Support Form</h2>
                            <p class="text-gray-600 mb-6">
                                The quickest way to get help is through our support form. This ensures your request is properly categorized and routed to the right team member.
                            </p>
                            
                            <div class="bg-white p-6 rounded-lg border border-blue-200">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Submit a Support Request</h3>
                                <form class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Enter your full name">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                                        <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="your.email@company.com">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                        <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Brief description of your issue">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Issue Type</label>
                                        <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                            <option>Technical Issue</option>
                                            <option>Account/Billing</option>
                                            <option>Feature Request</option>
                                            <option>Bug Report</option>
                                            <option>General Question</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                        <textarea rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Please provide detailed information about your issue..."></textarea>
                                    </div>
                                    <div>
                                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 transition-colors">
                                            Submit Support Request
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <h2 id="email" class="text-2xl font-bold text-gray-900 mb-6">Email Support</h2>
                        <p class="text-gray-600 mb-6">
                            You can also reach us directly via email for non-urgent matters or follow-ups.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Addresses:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>General Support:</strong> <a href="mailto:support@talentlit.com" class="text-indigo-600 hover:text-indigo-700">support@talentlit.com</a>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💳</span>
                                        <div>
                                            <strong>Billing & Account:</strong> <a href="mailto:billing@talentlit.com" class="text-green-600 hover:text-green-700">billing@talentlit.com</a>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🐛</span>
                                        <div>
                                            <strong>Bug Reports:</strong> <a href="mailto:bugs@talentlit.com" class="text-purple-600 hover:text-purple-700">bugs@talentlit.com</a>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💡</span>
                                        <div>
                                            <strong>Feature Requests:</strong> <a href="mailto:features@talentlit.com" class="text-yellow-600 hover:text-yellow-700">features@talentlit.com</a>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="response-times" class="text-2xl font-bold text-gray-900 mb-6">Response Times</h2>
                        <p class="text-gray-600 mb-6">
                            We strive to respond to all support requests as quickly as possible. Here are our typical response times:
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Response Time Guidelines:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🚨</span>
                                        <div>
                                            <strong>Critical Issues:</strong> Within 2 hours (business hours)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚠️</span>
                                        <div>
                                            <strong>High Priority:</strong> Within 4 hours (business hours)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📋</span>
                                        <div>
                                            <strong>General Support:</strong> Within 24 hours (business days)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💡</span>
                                        <div>
                                            <strong>Feature Requests:</strong> Within 3-5 business days
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
                                    <h3 class="text-sm font-medium text-yellow-800">Business Hours:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Monday - Friday: 9:00 AM - 6:00 PM (EST)</p>
                                        <p>Saturday: 10:00 AM - 4:00 PM (EST)</p>
                                        <p>Sunday: Closed</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="before-contact" class="text-2xl font-bold text-gray-900 mb-6">Before Contacting Support</h2>
                        <p class="text-gray-600 mb-6">
                            To help us assist you more effectively, please check these common solutions first:
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Checklist:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Check the Help Center:</strong> Browse our comprehensive help articles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Try Basic Troubleshooting:</strong> Refresh the page, clear cache, try different browser
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Check Your Permissions:</strong> Ensure you have the right access level
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Gather Information:</strong> Note your browser, OS, and steps to reproduce the issue
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="bug-reports" class="text-2xl font-bold text-gray-900 mb-6">Bug Reports</h2>
                        <p class="text-gray-600 mb-6">
                            Help us improve TalentLit by reporting bugs and issues you encounter.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">When Reporting Bugs, Include:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Steps to Reproduce:</strong> Detailed steps that led to the bug
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Expected Behavior:</strong> What should have happened
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Actual Behavior:</strong> What actually happened
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Environment:</strong> Browser, OS, and any error messages
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Screenshots/Video:</strong> Visual evidence of the issue
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="feature-requests" class="text-2xl font-bold text-gray-900 mb-6">Feature Requests</h2>
                        <p class="text-gray-600 mb-6">
                            We love hearing your ideas for improving TalentLit. Submit feature requests to help shape our product roadmap.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Feature Request Guidelines:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💡</span>
                                        <div>
                                            <strong>Describe the Problem:</strong> What challenge are you trying to solve?
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🎯</span>
                                        <div>
                                            <strong>Proposed Solution:</strong> How would you like it to work?
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Business Impact:</strong> How would this help your workflow?
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>User Impact:</strong> Who else would benefit from this feature?
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="emergency" class="text-2xl font-bold text-gray-900 mb-6">Emergency Support</h2>
                        <p class="text-gray-600 mb-6">
                            For critical issues that are blocking your business operations, we provide emergency support.
                        </p>

                        <div class="bg-red-50 border border-red-200 p-8 rounded-lg mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Emergency Support Criteria:</h3>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🚨</span>
                                    <div>
                                        <strong>System Down:</strong> Complete service unavailability
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💾</span>
                                    <div>
                                        <strong>Data Loss:</strong> Critical data has been lost or corrupted
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                    <div>
                                        <strong>Security Issue:</strong> Suspected security breach or vulnerability
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏰</span>
                                    <div>
                                        <strong>Time-Critical:</strong> Issue affecting time-sensitive business operations
                                    </div>
                                </li>
                            </ul>
                            
                            <div class="mt-6 p-4 bg-white rounded-lg border border-red-200">
                                <h4 class="font-semibold text-gray-800 mb-2">Emergency Contact:</h4>
                                <p class="text-gray-600">
                                    <strong>Phone:</strong> +1 (555) 123-4567<br>
                                    <strong>Email:</strong> <a href="mailto:emergency@talentlit.com" class="text-red-600 hover:text-red-700">emergency@talentlit.com</a><br>
                                    <strong>Available:</strong> 24/7 for critical issues
                                </p>
                            </div>
                        </div>

                        <div class="bg-indigo-50 p-8 rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Additional Resources</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Self-Service</h3>
                                    <ul class="space-y-2">
                                        <li><a href="{{ route('help.index') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a></li>
                                        <li><a href="{{ route('help.page', 'troubleshooting') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Troubleshooting Guide</a></li>
                                        <li><a href="{{ route('help.page', 'faq') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Frequently Asked Questions</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Community</h3>
                                    <ul class="space-y-2">
                                        <li><a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">User Forum</a></li>
                                        <li><a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Feature Voting</a></li>
                                        <li><a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">User Groups</a></li>
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
