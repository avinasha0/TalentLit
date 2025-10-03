@extends('layouts.help')

@section('title', 'Help Center — TalentLit')
@section('description', 'Get help with TalentLit ATS. Find guides, tutorials, and support for managing your recruitment process.')

@php
    $seoTitle = 'Help Center — TalentLit ATS';
    $seoDescription = 'Get help with TalentLit ATS. Find guides, tutorials, and support for managing your recruitment process.';
    $seoKeywords = 'TalentLit help, ATS support, recruitment software help, hiring guide, user manual';
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
                    <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
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
        <!-- Background Pattern -->
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
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                    Help Center
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Everything you need to know about using TalentLit. Find guides, tutorials, and get the support you need to streamline your hiring process.
                </p>
                <div class="flex justify-center">
                    <div class="relative max-w-md w-full">
                        <input type="text" id="helpSearch" placeholder="Search help articles..." 
                               class="w-full px-6 py-4 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 shadow-xl">
                        <svg class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Help Categories -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Browse by Category
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Find the help you need organized by topic
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="helpCategories">
                <!-- Getting Started -->
                <div class="help-card bg-gradient-to-br from-indigo-50 to-purple-50 p-8 rounded-2xl border border-indigo-100 hover:shadow-xl transition-all duration-300" data-search="getting started register login onboarding account setup team invite">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Getting Started</h3>
                    <p class="text-gray-600 mb-6">Learn the basics of TalentLit and set up your account</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'register') }}" class="block text-indigo-600 hover:text-indigo-700 font-medium">How to Register</a>
                        <a href="{{ route('help.page', 'login') }}" class="block text-indigo-600 hover:text-indigo-700 font-medium">How to Login</a>
                        <a href="{{ route('help.page', 'onboarding') }}" class="block text-indigo-600 hover:text-indigo-700 font-medium">Account Setup</a>
                        <a href="{{ route('help.page', 'invite-team') }}" class="block text-indigo-600 hover:text-indigo-700 font-medium">Invite Your Team</a>
                    </div>
                </div>

                <!-- Job Management -->
                <div class="help-card bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-2xl border border-purple-100 hover:shadow-xl transition-all duration-300" data-search="job management create post publish careers applications">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Job Management</h3>
                    <p class="text-gray-600 mb-6">Create, manage, and publish job postings</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'jobs') }}" class="block text-purple-600 hover:text-purple-700 font-medium">Managing Jobs</a>
                        <a href="{{ route('help.page', 'careers') }}" class="block text-purple-600 hover:text-purple-700 font-medium">Career Pages</a>
                        <a href="{{ route('help.page', 'applications') }}" class="block text-purple-600 hover:text-purple-700 font-medium">Application Management</a>
                    </div>
                </div>

                <!-- Candidate Management -->
                <div class="help-card bg-gradient-to-br from-pink-50 to-red-50 p-8 rounded-2xl border border-pink-100 hover:shadow-xl transition-all duration-300" data-search="candidate management pipeline notes tags organize track">
                    <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Candidate Management</h3>
                    <p class="text-gray-600 mb-6">Organize and track your candidates</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'candidates') }}" class="block text-pink-600 hover:text-pink-700 font-medium">Managing Candidates</a>
                        <a href="{{ route('help.page', 'pipeline') }}" class="block text-pink-600 hover:text-pink-700 font-medium">Hiring Pipeline</a>
                        <a href="{{ route('help.page', 'notes-tags') }}" class="block text-pink-600 hover:text-pink-700 font-medium">Notes & Tags</a>
                    </div>
                </div>

                <!-- Interviews -->
                <div class="help-card bg-gradient-to-br from-blue-50 to-indigo-50 p-8 rounded-2xl border border-blue-100 hover:shadow-xl transition-all duration-300" data-search="interviews schedule manage dashboard">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Interviews</h3>
                    <p class="text-gray-600 mb-6">Schedule and manage interviews</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'interviews') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Scheduling Interviews</a>
                        <a href="{{ route('help.page', 'dashboard') }}" class="block text-blue-600 hover:text-blue-700 font-medium">Dashboard Overview</a>
                    </div>
                </div>

                <!-- Analytics -->
                <div class="help-card bg-gradient-to-br from-green-50 to-emerald-50 p-8 rounded-2xl border border-green-100 hover:shadow-xl transition-all duration-300" data-search="analytics reports dashboard metrics performance">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-emerald-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics & Reports</h3>
                    <p class="text-gray-600 mb-6">Track your hiring performance</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'analytics') }}" class="block text-green-600 hover:text-green-700 font-medium">Analytics Dashboard</a>
                    </div>
                </div>

                <!-- Settings -->
                <div class="help-card bg-gradient-to-br from-gray-50 to-slate-50 p-8 rounded-2xl border border-gray-100 hover:shadow-xl transition-all duration-300" data-search="settings admin roles permissions integrations configure">
                    <div class="w-12 h-12 bg-gradient-to-r from-gray-600 to-slate-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Settings & Admin</h3>
                    <p class="text-gray-600 mb-6">Configure your account and team</p>
                    <div class="space-y-2">
                        <a href="{{ route('help.page', 'settings') }}" class="block text-gray-600 hover:text-gray-700 font-medium">Account Settings</a>
                        <a href="{{ route('help.page', 'roles-permissions') }}" class="block text-gray-600 hover:text-gray-700 font-medium">Roles & Permissions</a>
                        <a href="{{ route('help.page', 'integrations') }}" class="block text-gray-600 hover:text-gray-700 font-medium">Integrations</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Articles -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Popular Help Articles
                </h2>
                <p class="text-xl text-gray-600">
                    Most frequently accessed help topics
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" id="popularArticles">
                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="getting started guide walkthrough new users">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Getting Started Guide</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Complete walkthrough for new users</p>
                    <a href="{{ route('help.page', 'onboarding') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Read more →</a>
                </div>

                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="creating first job posting tutorial step by step">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Creating Your First Job</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Step-by-step job posting tutorial</p>
                    <a href="{{ route('help.page', 'jobs') }}" class="text-purple-600 hover:text-purple-700 font-medium">Read more →</a>
                </div>

                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="managing candidates organize track applicants effectively">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Managing Candidates</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Organize and track applicants effectively</p>
                    <a href="{{ route('help.page', 'candidates') }}" class="text-pink-600 hover:text-pink-700 font-medium">Read more →</a>
                </div>

                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="scheduling interviews set up manage schedules">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Scheduling Interviews</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Set up and manage interview schedules</p>
                    <a href="{{ route('help.page', 'interviews') }}" class="text-blue-600 hover:text-blue-700 font-medium">Read more →</a>
                </div>

                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="analytics overview understanding hiring metrics">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Analytics Overview</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Understanding your hiring metrics</p>
                    <a href="{{ route('help.page', 'analytics') }}" class="text-green-600 hover:text-green-700 font-medium">Read more →</a>
                </div>

                <div class="help-card bg-white p-6 rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow" data-search="troubleshooting common issues solutions problems">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Troubleshooting</h3>
                    </div>
                    <p class="text-gray-600 mb-4">Common issues and solutions</p>
                    <a href="{{ route('help.page', 'troubleshooting') }}" class="text-red-600 hover:text-red-700 font-medium">Read more →</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Support -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">
                Still need help?
            </h2>
            <p class="text-xl text-indigo-100 mb-8">
                Can't find what you're looking for? Our support team is here to help.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('help.page', 'contact') }}" 
                   class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                    Contact Support
                </a>
                <a href="{{ route('contact') }}" 
                   class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                    Send Feedback
                </a>
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('helpSearch');
    const helpCards = document.querySelectorAll('.help-card');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        helpCards.forEach(card => {
            const searchData = card.getAttribute('data-search').toLowerCase();
            const cardText = card.textContent.toLowerCase();
            
            if (searchTerm === '' || searchData.includes(searchTerm) || cardText.includes(searchTerm)) {
                card.style.display = 'block';
                card.style.opacity = '1';
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
            }
        });
        
        // Show/hide section headers based on visible cards
        const categoriesSection = document.getElementById('helpCategories');
        const popularSection = document.getElementById('popularArticles');
        
        const visibleCategories = Array.from(categoriesSection.querySelectorAll('.help-card')).some(card => card.style.display !== 'none');
        const visiblePopular = Array.from(popularSection.querySelectorAll('.help-card')).some(card => card.style.display !== 'none');
        
        categoriesSection.style.display = visibleCategories ? 'block' : 'none';
        popularSection.style.display = visiblePopular ? 'block' : 'none';
    });
});
</script>
@endsection
