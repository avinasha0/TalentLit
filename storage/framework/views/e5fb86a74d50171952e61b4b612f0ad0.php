<?php $__env->startSection('title', 'Integrations — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to integrate TalentLit ATS with other tools and services to streamline your hiring process and improve productivity.'); ?>

<?php
    $seoTitle = 'Integrations — TalentLit Help';
    $seoDescription = 'Learn how to integrate TalentLit ATS with other tools and services to streamline your hiring process and improve productivity.';
    $seoKeywords = 'TalentLit integrations, ATS integrations, hiring tools, productivity tools';
    $seoAuthor = 'TalentLit';
    $seoImage = asset('logo-talentlit-small.svg');
?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="<?php echo e(route('help.index')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        ← Back to Help Center
                    </a>
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                    Integrations
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to integrate TalentLit ATS with other tools and services to streamline your hiring process.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Integrations Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#available-integrations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Available Integrations</a>
                            <a href="#setting-up" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Setting Up Integrations</a>
                            <a href="#email-integrations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Email Integrations</a>
                            <a href="#calendar-integrations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Calendar Integrations</a>
                            <a href="#hr-integrations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">HR Integrations</a>
                            <a href="#api-integrations" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">API Integrations</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Integrations Overview</h2>
                            <p class="text-gray-600 mb-4">
                                Integrations help you connect TalentLit with your existing tools and workflows. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Sync data between TalentLit and other systems</li>
                                <li>Automate repetitive tasks and workflows</li>
                                <li>Improve team collaboration and productivity</li>
                                <li>Centralize hiring data and processes</li>
                                <li>Reduce manual data entry and errors</li>
                                <li>Enhance reporting and analytics capabilities</li>
                            </ul>
                        </div>

                        <h2 id="available-integrations" class="text-2xl font-bold text-gray-900 mb-6">Available Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            TalentLit offers integrations with popular tools and services to enhance your hiring process.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Supported Integrations:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Email Services:</strong> Gmail, Outlook, Yahoo Mail
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Calendar Apps:</strong> Google Calendar, Outlook Calendar
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>HR Systems:</strong> BambooHR, Workday, ADP
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Analytics Tools:</strong> Google Analytics, Mixpanel
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💬</span>
                                        <div>
                                            <strong>Communication:</strong> Slack, Microsoft Teams
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔧</span>
                                        <div>
                                            <strong>Custom APIs:</strong> REST API for custom integrations
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="setting-up" class="text-2xl font-bold text-gray-900 mb-6">Setting Up Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            Configure integrations to connect TalentLit with your preferred tools and services.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Set Up Integrations:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Access Integrations:</strong> Go to Settings > Integrations
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Select Integration:</strong> Choose the integration you want to set up
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Authenticate:</strong> Sign in to your account for the external service
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Configure Settings:</strong> Set up integration preferences and options
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Test Connection:</strong> Test the integration to ensure it's working
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Save & Activate:</strong> Save the configuration and activate the integration
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="email-integrations" class="text-2xl font-bold text-gray-900 mb-6">Email Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            Connect your email service to send and receive emails directly from TalentLit.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Email Integration Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Send Emails:</strong> Send emails directly from candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📨</span>
                                        <div>
                                            <strong>Receive Emails:</strong> Receive candidate emails in TalentLit
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Email Templates:</strong> Use pre-built email templates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Email Tracking:</strong> Track email opens and responses
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="calendar-integrations" class="text-2xl font-bold text-gray-900 mb-6">Calendar Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            Sync your calendar to automatically schedule interviews and manage availability.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Calendar Integration Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Auto-Scheduling:</strong> Automatically schedule interviews based on availability
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏰</span>
                                        <div>
                                            <strong>Availability Sync:</strong> Sync your availability with TalentLit
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔄</span>
                                        <div>
                                            <strong>Two-Way Sync:</strong> Sync changes between TalentLit and your calendar
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Access:</strong> Access calendar integration from mobile devices
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="hr-integrations" class="text-2xl font-bold text-gray-900 mb-6">HR Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            Connect with HR systems to streamline the transition from hiring to onboarding.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">HR Integration Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Employee Data Sync:</strong> Sync new hire data to HR systems
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📋</span>
                                        <div>
                                            <strong>Onboarding Automation:</strong> Automate onboarding processes
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Reporting Sync:</strong> Sync hiring reports with HR analytics
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔒</span>
                                        <div>
                                            <strong>Data Security:</strong> Secure data transfer between systems
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="api-integrations" class="text-2xl font-bold text-gray-900 mb-6">API Integrations</h2>
                        <p class="text-gray-600 mb-6">
                            Use TalentLit's REST API to build custom integrations and automate workflows.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">API Integration Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔧</span>
                                        <div>
                                            <strong>REST API:</strong> Full REST API for all TalentLit features
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔑</span>
                                        <div>
                                            <strong>API Keys:</strong> Secure API key authentication
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📚</span>
                                        <div>
                                            <strong>Documentation:</strong> Comprehensive API documentation
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🧪</span>
                                        <div>
                                            <strong>Testing Tools:</strong> Built-in API testing and debugging tools
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Integration Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your internet connection</li>
                                        <li>• Verify the external service is accessible</li>
                                        <li>• Check your API credentials and permissions</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Data Not Syncing</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check integration settings and configuration</li>
                                        <li>• Verify data format and requirements</li>
                                        <li>• Check for error messages in the integration log</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Authentication Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Re-authenticate with the external service</li>
                                        <li>• Check if your credentials have expired</li>
                                        <li>• Verify API permissions and access rights</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'settings')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'security')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Security Best Practices</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'deploy')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Deployment Guide</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'troubleshooting')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'contact')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Contact Support</a></li>
                                        <li><a href="<?php echo e(route('help.index')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Help Center</a></li>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\help\integrations.blade.php ENDPATH**/ ?>