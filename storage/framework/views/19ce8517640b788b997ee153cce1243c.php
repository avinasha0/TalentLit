<?php $__env->startSection('title', 'Application Management — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to manage job applications, track candidate progress, and update application statuses in TalentLit ATS.'); ?>

<?php
    $seoTitle = 'Application Management — TalentLit Help';
    $seoDescription = 'Learn how to manage job applications, track candidate progress, and update application statuses in TalentLit ATS.';
    $seoKeywords = 'TalentLit applications, application management, candidate tracking, ATS applications';
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
                    Application Management
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to manage job applications, track candidate progress, and update application statuses.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Application Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#viewing-applications" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Viewing Applications</a>
                            <a href="#application-status" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Application Status</a>
                            <a href="#updating-status" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Updating Status</a>
                            <a href="#filtering-sorting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Filtering & Sorting</a>
                            <a href="#bulk-actions" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Bulk Actions</a>
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
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Application Management Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The application management system helps you track and manage all job applications in one place. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>View all applications across all job postings</li>
                                <li>Track application status and progress</li>
                                <li>Filter and sort applications by various criteria</li>
                                <li>Update application statuses in bulk</li>
                                <li>Communicate with candidates</li>
                                <li>Add notes and feedback</li>
                            </ul>
                        </div>

                        <h2 id="viewing-applications" class="text-2xl font-bold text-gray-900 mb-6">Viewing Applications</h2>
                        <p class="text-gray-600 mb-6">
                            Access and view all job applications from the Applications section in your dashboard.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Application List View:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Candidate Information:</strong> Name, email, and contact details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Job Applied For:</strong> Job title and department
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Application Status:</strong> Current status in the hiring process
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Application Date:</strong> When the application was submitted
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Last Updated:</strong> When the application was last modified
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
                                            <li>Click on any application to view full details</li>
                                            <li>Use the status dropdown to quickly update status</li>
                                            <li>Add notes directly from the list view</li>
                                            <li>Schedule interviews with one click</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="application-status" class="text-2xl font-bold text-gray-900 mb-6">Application Status</h2>
                        <p class="text-gray-600 mb-6">
                            Understanding application statuses helps you track candidate progress through your hiring pipeline.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Definitions:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">A</span>
                                        <div>
                                            <strong>Applied:</strong> Initial application received, awaiting review
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
                                            <strong>Interview:</strong> Candidate scheduled for or completed interview
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">O</span>
                                        <div>
                                            <strong>Offered:</strong> Job offer extended to candidate
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

                        <h2 id="updating-status" class="text-2xl font-bold text-gray-900 mb-6">Updating Application Status</h2>
                        <p class="text-gray-600 mb-6">
                            Keep your hiring pipeline organized by regularly updating application statuses as candidates progress.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Update Status:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Select Application:</strong> Click on the application you want to update
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click Status Dropdown:</strong> Click on the current status dropdown
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Select New Status:</strong> Choose the appropriate new status
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Add Notes (Optional):</strong> Add any relevant notes about the status change
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Save Changes:</strong> Click save to update the status
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="filtering-sorting" class="text-2xl font-bold text-gray-900 mb-6">Filtering and Sorting</h2>
                        <p class="text-gray-600 mb-6">
                            Use filters and sorting options to find specific applications quickly and efficiently.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Filters:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Status Filter:</strong> Filter by application status
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💼</span>
                                        <div>
                                            <strong>Job Filter:</strong> Filter by specific job postings
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Date Range:</strong> Filter by application date
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Team Member:</strong> Filter by assigned team member
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Search:</strong> Search by candidate name or email
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="bulk-actions" class="text-2xl font-bold text-gray-900 mb-6">Bulk Actions</h2>
                        <p class="text-gray-600 mb-6">
                            Save time by performing actions on multiple applications at once.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Bulk Actions:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Update Status:</strong> Change status for multiple applications
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">👥</span>
                                        <div>
                                            <strong>Assign Team Member:</strong> Assign applications to team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Send Email:</strong> Send bulk emails to candidates
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📄</span>
                                        <div>
                                            <strong>Export Data:</strong> Export selected applications to CSV
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
                                            <strong>Email Integration:</strong> Send emails directly from application details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Internal Notes:</strong> Add notes for team collaboration
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📞</span>
                                        <div>
                                            <strong>Call Logs:</strong> Record phone conversations and outcomes
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
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot View Applications</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "view applications" access</li>
                                        <li>• Make sure you're logged in with the correct account</li>
                                        <li>• Try refreshing the page</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Update Status</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify you have edit permissions for applications</li>
                                        <li>• Check if the application is locked by another user</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Bulk Actions Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Make sure you have selected applications</li>
                                        <li>• Check your permissions for bulk operations</li>
                                        <li>• Try selecting fewer applications at once</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'candidates')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Candidate Management</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'pipeline')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Hiring Pipeline</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'interviews')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Interview Scheduling</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'analytics')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Analytics & Reports</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\help\applications.blade.php ENDPATH**/ ?>