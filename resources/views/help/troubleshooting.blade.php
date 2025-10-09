@extends('layouts.help')

@section('title', 'Troubleshooting — TalentLit Help')
@section('description', 'Common issues and solutions for TalentLit ATS. Find quick fixes for login, performance, and technical problems.')

@php
    $seoTitle = 'Troubleshooting — TalentLit Help';
    $seoDescription = 'Common issues and solutions for TalentLit ATS. Find quick fixes for login, performance, and technical problems.';
    $seoKeywords = 'TalentLit troubleshooting, ATS issues, technical support, error fixes';
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
                    Troubleshooting Guide
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Quick solutions to common issues and problems you might encounter while using TalentLit.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Common Issues</h3>
                        <nav class="space-y-2">
                            <a href="#login-issues" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Login Problems</a>
                            <a href="#performance" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Performance Issues</a>
                            <a href="#vite-manifest" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Vite Manifest Error</a>
                            <a href="#logout-405" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">405 Logout Error</a>
                            <a href="#missing-styles" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Missing Styles</a>
                            <a href="#database" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Database Issues</a>
                            <a href="#email" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Email Problems</a>
                            <a href="#browser" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Browser Issues</a>
                            <a href="#contact" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Still Need Help?</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="login-issues" class="bg-red-50 border border-red-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Login Problems</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Cannot Login / Invalid Credentials</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Try these solutions:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Double-check your email address and password</li>
                                            <li>Make sure Caps Lock is not enabled</li>
                                            <li>Try typing your password in a text editor first to verify it</li>
                                            <li>Use the "Forgot Password" feature to reset your password</li>
                                            <li>Clear your browser cache and cookies</li>
                                            <li>Try logging in from a different browser or incognito mode</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Account Not Found</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Possible causes:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>You may need to register first</li>
                                            <li>Check if you're using the correct email address</li>
                                            <li>Your account might be deactivated</li>
                                            <li>Contact support if you believe your account should exist</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="performance" class="bg-yellow-50 border border-yellow-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Performance Issues</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Slow Loading / Timeout Errors</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Quick fixes:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Check your internet connection</li>
                                            <li>Close unnecessary browser tabs</li>
                                            <li>Clear your browser cache and cookies</li>
                                            <li>Try refreshing the page</li>
                                            <li>Wait a few minutes and try again</li>
                                            <li>Try using a different browser</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Page Not Loading Completely</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Solutions:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Disable browser extensions temporarily</li>
                                            <li>Check if JavaScript is enabled</li>
                                            <li>Try incognito/private browsing mode</li>
                                            <li>Update your browser to the latest version</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="vite-manifest" class="bg-blue-50 border border-blue-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Vite Manifest Error</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Error: "Vite manifest not found"</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">This is a development issue. Solutions:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li><strong>For developers:</strong> Run <code class="bg-gray-100 px-2 py-1 rounded text-sm">npm run build</code></li>
                                            <li><strong>For users:</strong> Contact support - this needs to be fixed on the server</li>
                                            <li>Try refreshing the page after a few minutes</li>
                                            <li>Clear your browser cache completely</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Missing CSS/JS Files</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">If styles are missing:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>This is usually a temporary server issue</li>
                                            <li>Wait 5-10 minutes and try again</li>
                                            <li>Contact support if the issue persists</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="logout-405" class="bg-purple-50 border border-purple-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">405 Logout Error</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Error: "Method Not Allowed" when logging out</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Solutions:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Use the logout button in the user menu (top-right corner)</li>
                                            <li>Try visiting <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ url('/logout') }}</code> directly</li>
                                            <li>Clear your browser cache and cookies</li>
                                            <li>Close all browser tabs and reopen</li>
                                            <li>Contact support if the issue continues</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="missing-styles" class="bg-green-50 border border-green-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Missing Styles / Layout Issues</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Page looks broken or unstyled</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Try these fixes:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Hard refresh the page (Ctrl+F5 or Cmd+Shift+R)</li>
                                            <li>Clear your browser cache completely</li>
                                            <li>Disable ad blockers temporarily</li>
                                            <li>Check if JavaScript is enabled</li>
                                            <li>Try a different browser</li>
                                            <li>Wait a few minutes and try again</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Images not loading</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Solutions:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Check your internet connection</li>
                                            <li>Disable image blocking in your browser</li>
                                            <li>Try refreshing the page</li>
                                            <li>Contact support if images are consistently missing</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="database" class="bg-orange-50 border border-orange-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Issues</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Data not saving / "Database error"</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">What to do:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Try refreshing the page and attempting again</li>
                                            <li>Check your internet connection</li>
                                            <li>Wait a few minutes and try again</li>
                                            <li>Contact support immediately - this is a server issue</li>
                                            <li>Don't continue entering data until the issue is resolved</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Lost data / Missing information</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">If you've lost data:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Check if the data appears after refreshing</li>
                                            <li>Look in other sections of the application</li>
                                            <li>Contact support with details about what was lost</li>
                                            <li>We may be able to recover your data from backups</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="email" class="bg-teal-50 border border-teal-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Email Problems</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Not receiving verification emails</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Check these:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Check your spam/junk folder</li>
                                            <li>Wait up to 10 minutes for the email to arrive</li>
                                            <li>Verify you entered the correct email address</li>
                                            <li>Try requesting a new verification email</li>
                                            <li>Check if your email provider is blocking our emails</li>
                                        </ol>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Application emails not being sent</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Troubleshooting steps:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Check your SMTP settings in account settings</li>
                                            <li>Verify your email configuration is correct</li>
                                            <li>Test email functionality in settings</li>
                                            <li>Contact support if emails are still not working</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="browser" class="bg-gray-50 border border-gray-200 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Browser Issues</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Browser compatibility</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Supported browsers:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Chrome (latest version)</li>
                                            <li>Firefox (latest version)</li>
                                            <li>Safari (latest version)</li>
                                            <li>Edge (latest version)</li>
                                        </ul>
                                        <p class="mt-2 text-gray-600">Update your browser if you're experiencing issues.</p>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">JavaScript errors</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">If you see JavaScript errors:</h4>
                                        <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                            <li>Make sure JavaScript is enabled in your browser</li>
                                            <li>Disable browser extensions temporarily</li>
                                            <li>Clear your browser cache and cookies</li>
                                            <li>Try incognito/private browsing mode</li>
                                            <li>Update your browser to the latest version</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="contact" class="bg-indigo-50 border border-indigo-200 p-8 rounded-lg">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Still Need Help?</h2>
                            
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact Support</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <p class="text-gray-600 mb-4">If none of the solutions above worked, our support team is here to help:</p>
                                        <ul class="space-y-2 text-gray-600">
                                            <li>• <a href="{{ route('help.page', 'contact') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact Support Form</a></li>
                                            <li>• Email: support@talentlit.com</li>
                                            <li>• Include details about the issue and steps you've tried</li>
                                            <li>• Screenshots are helpful if applicable</li>
                                        </ul>
                                    </div>
                                </div>

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Before Contacting Support</h3>
                                    <div class="bg-white p-4 rounded-lg">
                                        <h4 class="font-semibold text-gray-800 mb-2">Please include:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-gray-600">
                                            <li>Your browser and version</li>
                                            <li>Operating system</li>
                                            <li>Steps to reproduce the issue</li>
                                            <li>Error messages (if any)</li>
                                            <li>What you were trying to do when the issue occurred</li>
                                        </ul>
                                    </div>
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
