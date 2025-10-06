<?php $__env->startSection('title', 'Dashboard Overview — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to use the TalentLit dashboard to track your hiring metrics, manage applications, and monitor team activity.'); ?>

<?php
    $seoTitle = 'Dashboard Overview — TalentLit Help';
    $seoDescription = 'Learn how to use the TalentLit dashboard to track your hiring metrics, manage applications, and monitor team activity.';
    $seoKeywords = 'TalentLit dashboard, hiring metrics, recruitment analytics, ATS dashboard';
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
                    Dashboard Overview
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to use the TalentLit dashboard to track your hiring metrics, manage applications, and monitor team activity.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Dashboard Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Dashboard Overview</a>
                            <a href="#kpi-cards" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">KPI Cards</a>
                            <a href="#recent-applications" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Recent Applications</a>
                            <a href="#quick-actions" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Quick Actions</a>
                            <a href="#navigation" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Navigation Menu</a>
                            <a href="#customization" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Customization</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Dashboard Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The TalentLit dashboard is your central hub for managing all aspects of your recruitment process. It provides:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Real-time hiring metrics and KPIs</li>
                                <li>Recent application activity</li>
                                <li>Quick access to all major features</li>
                                <li>Team activity and collaboration tools</li>
                                <li>Customizable widgets and views</li>
                            </ul>
                        </div>

                        <h2 id="kpi-cards" class="text-2xl font-bold text-gray-900 mb-6">KPI Cards</h2>
                        <p class="text-gray-600 mb-6">
                            The dashboard displays key performance indicators (KPIs) that help you track your hiring progress at a glance.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Key Metrics Explained:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Total Applications:</strong> Number of applications received across all jobs
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Active Jobs:</strong> Number of currently published job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Interviews Scheduled:</strong> Upcoming interviews in the next 7 days
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Hired This Month:</strong> Successful hires in the current month
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
                                            <li>KPI cards update in real-time as you receive applications</li>
                                            <li>Click on any KPI card to see detailed breakdowns</li>
                                            <li>Use the date range selector to view metrics for specific periods</li>
                                            <li>Compare current metrics with previous periods to track progress</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="recent-applications" class="text-2xl font-bold text-gray-900 mb-6">Recent Applications</h2>
                        <p class="text-gray-600 mb-6">
                            The Recent Applications section shows the latest candidate applications and their current status in your hiring pipeline.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Understanding Application Status:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">A</span>
                                        <div>
                                            <strong>Applied:</strong> New application received, awaiting review
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">R</span>
                                        <div>
                                            <strong>Reviewing:</strong> Application is being reviewed by your team
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">I</span>
                                        <div>
                                            <strong>Interview:</strong> Candidate scheduled for interview
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">H</span>
                                        <div>
                                            <strong>Hired:</strong> Candidate has been successfully hired
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Quick Actions on Applications:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Click on any application to view full candidate details</li>
                                            <li>Use the status dropdown to quickly update application status</li>
                                            <li>Add notes or comments directly from the dashboard</li>
                                            <li>Schedule interviews with one click</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="quick-actions" class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h2>
                        <p class="text-gray-600 mb-6">
                            The dashboard provides quick access to the most commonly used features to streamline your workflow.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Quick Actions:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">+</span>
                                        <div>
                                            <strong>Create New Job:</strong> Quickly post a new job opening
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Invite Team Member:</strong> Add new users to your workspace
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>View Analytics:</strong> Access detailed hiring reports
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Account Settings:</strong> Manage your organization settings
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="navigation" class="text-2xl font-bold text-gray-900 mb-6">Navigation Menu</h2>
                        <p class="text-gray-600 mb-6">
                            The left sidebar provides access to all major features and sections of TalentLit.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Main Navigation Sections:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏠</span>
                                        <div>
                                            <strong>Dashboard:</strong> Overview of your hiring metrics and activity
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Jobs:</strong> Manage job postings and applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👤</span>
                                        <div>
                                            <strong>Candidates:</strong> View and manage candidate profiles
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Interviews:</strong> Schedule and manage interviews
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Analytics:</strong> Detailed reports and insights
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-gray-100 text-gray-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⚙️</span>
                                        <div>
                                            <strong>Settings:</strong> Account and team management
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="customization" class="text-2xl font-bold text-gray-900 mb-6">Dashboard Customization</h2>
                        <p class="text-gray-600 mb-6">
                            You can customize your dashboard to show the information most relevant to your role and workflow.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customization Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Widget Visibility:</strong> Show/hide specific KPI cards
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Date Ranges:</strong> Set default time periods for metrics
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Application Filters:</strong> Customize which applications appear
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Layout Preferences:</strong> Arrange widgets to your preference
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Dashboard Not Loading</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Refresh the page and try again</li>
                                        <li>• Check your internet connection</li>
                                        <li>• Clear your browser cache</li>
                                        <li>• Try logging out and back in</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">KPI Cards Not Updating</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Wait a few minutes for data to refresh</li>
                                        <li>• Check if you have the latest data permissions</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact support if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Missing Quick Actions</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your user permissions</li>
                                        <li>• Verify your role has access to those features</li>
                                        <li>• Contact your admin to update permissions</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'jobs')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Managing Jobs</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'candidates')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Managing Candidates</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'analytics')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Analytics & Reports</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'settings')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Account Settings</a></li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Need Help?</h3>
                                    <ul class="space-y-2">
                                        <li><a href="<?php echo e(route('help.page', 'troubleshooting')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Common Issues</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/dashboard.blade.php ENDPATH**/ ?>