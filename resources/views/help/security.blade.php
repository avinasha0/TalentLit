@extends('layouts.help')

@section('title', 'Security Best Practices — TalentLit Help')
@section('description', 'Learn about security best practices for TalentLit ATS, including password management, data protection, and account security.')

@php
    $seoTitle = 'Security Best Practices — TalentLit Help';
    $seoDescription = 'Learn about security best practices for TalentLit ATS, including password management, data protection, and account security.';
    $seoKeywords = 'TalentLit security, ATS security, data protection, account security';
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
                    Security Best Practices
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn about security best practices for TalentLit ATS, including password management and data protection.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Security Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#password-security" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Password Security</a>
                            <a href="#account-protection" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Account Protection</a>
                            <a href="#data-security" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Data Security</a>
                            <a href="#team-security" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Team Security</a>
                            <a href="#compliance" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Compliance</a>
                            <a href="#incident-response" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Incident Response</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Overview</h2>
                            <p class="text-gray-600 mb-4">
                                Security is a top priority for TalentLit. We provide multiple layers of protection to keep your data safe. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Use strong passwords and two-factor authentication</li>
                                <li>Control access with role-based permissions</li>
                                <li>Monitor account activity and security events</li>
                                <li>Encrypt sensitive data in transit and at rest</li>
                                <li>Comply with industry security standards</li>
                                <li>Respond quickly to security incidents</li>
                            </ul>
                        </div>

                        <h2 id="password-security" class="text-2xl font-bold text-gray-900 mb-6">Password Security</h2>
                        <p class="text-gray-600 mb-6">
                            Strong passwords are your first line of defense against unauthorized access to your account.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Password Best Practices:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Use Strong Passwords:</strong> At least 12 characters with mixed case, numbers, and symbols
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔄</span>
                                        <div>
                                            <strong>Change Regularly:</strong> Update passwords every 90 days
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🚫</span>
                                        <div>
                                            <strong>Unique Passwords:</strong> Don't reuse passwords across different accounts
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>Password Manager:</strong> Use a password manager to generate and store passwords
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="account-protection" class="text-2xl font-bold text-gray-900 mb-6">Account Protection</h2>
                        <p class="text-gray-600 mb-6">
                            Additional security measures help protect your account from unauthorized access and threats.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Account Protection Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>Two-Factor Authentication:</strong> Enable 2FA for additional security
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Security:</strong> Use mobile apps with biometric authentication
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🌐</span>
                                        <div>
                                            <strong>Secure Connections:</strong> Always use HTTPS and secure networks
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👀</span>
                                        <div>
                                            <strong>Activity Monitoring:</strong> Monitor account activity and login attempts
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="data-security" class="text-2xl font-bold text-gray-900 mb-6">Data Security</h2>
                        <p class="text-gray-600 mb-6">
                            Protect sensitive candidate and company data with proper security measures and practices.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Security Measures:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Data Encryption:</strong> All data is encrypted in transit and at rest
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏢</span>
                                        <div>
                                            <strong>Access Controls:</strong> Role-based access controls limit data access
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Data Backup:</strong> Regular automated backups ensure data availability
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🗑️</span>
                                        <div>
                                            <strong>Data Retention:</strong> Automatic data retention and deletion policies
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="team-security" class="text-2xl font-bold text-gray-900 mb-6">Team Security</h2>
                        <p class="text-gray-600 mb-6">
                            Ensure your team follows security best practices and maintains a secure working environment.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Security Guidelines:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>User Training:</strong> Train team members on security best practices
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔑</span>
                                        <div>
                                            <strong>Access Management:</strong> Regularly review and update user access
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Device Security:</strong> Ensure all devices are secure and updated
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🚪</span>
                                        <div>
                                            <strong>Offboarding:</strong> Properly revoke access when team members leave
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="compliance" class="text-2xl font-bold text-gray-900 mb-6">Compliance</h2>
                        <p class="text-gray-600 mb-6">
                            TalentLit helps you maintain compliance with industry security standards and regulations.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Compliance Standards:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>GDPR Compliance:</strong> European data protection regulations
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏢</span>
                                        <div>
                                            <strong>CCPA Compliance:</strong> California consumer privacy regulations
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔐</span>
                                        <div>
                                            <strong>SOC 2 Type II:</strong> Security and availability controls
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>ISO 27001:</strong> Information security management standards
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="incident-response" class="text-2xl font-bold text-gray-900 mb-6">Incident Response</h2>
                        <p class="text-gray-600 mb-6">
                            Know how to respond to security incidents and protect your data in case of a breach.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Incident Response Steps:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Identify:</strong> Quickly identify and assess the security incident
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Contain:</strong> Isolate affected systems and prevent further damage
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Eradicate:</strong> Remove the threat and restore system integrity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Recover:</strong> Restore normal operations and monitor for issues
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Learn:</strong> Document lessons learned and improve security measures
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Access Account</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Verify your login credentials</li>
                                        <li>• Try resetting your password</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Two-Factor Authentication Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your mobile device and app</li>
                                        <li>• Verify the time is correct on your device</li>
                                        <li>• Try using backup codes if available</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Suspicious Activity</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Change your password immediately</li>
                                        <li>• Review your account activity</li>
                                        <li>• Contact support to report the issue</li>
                                        <li>• Consider enabling additional security measures</li>
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
                                        <li><a href="{{ route('help.page', 'roles-permissions') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Roles & Permissions</a></li>
                                        <li><a href="{{ route('help.page', 'integrations') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">Integrations</a></li>
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
