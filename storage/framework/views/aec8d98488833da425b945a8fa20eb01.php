<?php $__env->startSection('title', 'Interview Scheduling — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Learn how to schedule, manage, and conduct interviews with candidates using TalentLit ATS interview management features.'); ?>

<?php
    $seoTitle = 'Interview Scheduling — TalentLit Help';
    $seoDescription = 'Learn how to schedule, manage, and conduct interviews with candidates using TalentLit ATS interview management features.';
    $seoKeywords = 'TalentLit interviews, interview scheduling, candidate interviews, ATS interviews';
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
                    Interview Scheduling
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to schedule, manage, and conduct interviews with candidates using TalentLit ATS.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Interview Guide</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Overview</a>
                            <a href="#scheduling-interviews" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Scheduling Interviews</a>
                            <a href="#interview-types" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Interview Types</a>
                            <a href="#managing-interviews" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Managing Interviews</a>
                            <a href="#interview-feedback" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Interview Feedback</a>
                            <a href="#rescheduling-canceling" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Rescheduling & Canceling</a>
                            <a href="#notifications" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Notifications</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Interview Management Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The interview management system helps you schedule, manage, and track interviews with candidates. You can:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Schedule interviews with candidates and team members</li>
                                <li>Set up different types of interviews (phone, video, in-person)</li>
                                <li>Send automatic reminders and notifications</li>
                                <li>Collect and manage interview feedback</li>
                                <li>Reschedule or cancel interviews easily</li>
                                <li>Track interview history and outcomes</li>
                            </ul>
                        </div>

                        <h2 id="scheduling-interviews" class="text-2xl font-bold text-gray-900 mb-6">Scheduling Interviews</h2>
                        <p class="text-gray-600 mb-6">
                            Schedule interviews with candidates and team members in just a few simple steps.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Schedule an Interview:</h3>
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
                                            <strong>Click "Schedule Interview":</strong> Click the schedule interview button
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Choose Interview Type:</strong> Select phone, video, or in-person interview
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Select Date & Time:</strong> Choose a convenient date and time
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Add Interviewers:</strong> Select team members who will conduct the interview
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">6</span>
                                        <div>
                                            <strong>Add Details:</strong> Include interview location, agenda, or special instructions
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">7</span>
                                        <div>
                                            <strong>Send Invitations:</strong> Send calendar invites to all participants
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="interview-types" class="text-2xl font-bold text-gray-900 mb-6">Interview Types</h2>
                        <p class="text-gray-600 mb-6">
                            Choose from different interview types based on your hiring needs and candidate preferences.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Available Interview Types:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📞</span>
                                        <div>
                                            <strong>Phone Interview:</strong> Conduct interviews over the phone
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💻</span>
                                        <div>
                                            <strong>Video Interview:</strong> Conduct interviews via video conferencing
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🏢</span>
                                        <div>
                                            <strong>In-Person Interview:</strong> Conduct interviews at your office
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Technical Assessment:</strong> Conduct technical skills evaluation
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="managing-interviews" class="text-2xl font-bold text-gray-900 mb-6">Managing Interviews</h2>
                        <p class="text-gray-600 mb-6">
                            Keep track of all scheduled interviews and manage them efficiently from one central location.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Interview Management Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📅</span>
                                        <div>
                                            <strong>Calendar View:</strong> View all interviews in calendar format
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>List View:</strong> View interviews in a detailed list format
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔍</span>
                                        <div>
                                            <strong>Filter & Search:</strong> Filter interviews by date, type, or interviewer
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Access:</strong> Manage interviews from mobile devices
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="interview-feedback" class="text-2xl font-bold text-gray-900 mb-6">Interview Feedback</h2>
                        <p class="text-gray-600 mb-6">
                            Collect and manage feedback from interviewers to make informed hiring decisions.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Feedback Collection:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📝</span>
                                        <div>
                                            <strong>Structured Forms:</strong> Use predefined feedback forms
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⭐</span>
                                        <div>
                                            <strong>Rating System:</strong> Rate candidates on various criteria
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">💬</span>
                                        <div>
                                            <strong>Comments:</strong> Add detailed comments and observations
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-yellow-100 text-yellow-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📊</span>
                                        <div>
                                            <strong>Recommendations:</strong> Make hiring recommendations
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="rescheduling-canceling" class="text-2xl font-bold text-gray-900 mb-6">Rescheduling & Canceling</h2>
                        <p class="text-gray-600 mb-6">
                            Easily reschedule or cancel interviews when needed, with automatic notifications to all participants.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Reschedule or Cancel:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Select Interview:</strong> Click on the interview you want to modify
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Choose Action:</strong> Select "Reschedule" or "Cancel"
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Update Details:</strong> For rescheduling, select new date/time
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Add Reason:</strong> Provide reason for the change
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Send Notifications:</strong> Notify all participants of the change
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="notifications" class="text-2xl font-bold text-gray-900 mb-6">Notifications</h2>
                        <p class="text-gray-600 mb-6">
                            Stay informed about interview updates with automatic notifications and reminders.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Types:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📧</span>
                                        <div>
                                            <strong>Email Notifications:</strong> Receive email updates about interviews
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">🔔</span>
                                        <div>
                                            <strong>In-App Notifications:</strong> Get notifications within the application
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">📱</span>
                                        <div>
                                            <strong>Mobile Push:</strong> Receive push notifications on mobile devices
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">⏰</span>
                                        <div>
                                            <strong>Reminders:</strong> Get automatic reminders before interviews
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Schedule Interview</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "schedule interviews" access</li>
                                        <li>• Verify the candidate's contact information is correct</li>
                                        <li>• Make sure the selected time slot is available</li>
                                        <li>• Contact your admin if the issue persists</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Interview Notifications Not Working</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your notification settings in account preferences</li>
                                        <li>• Verify your email address is correct</li>
                                        <li>• Check spam folder for missed notifications</li>
                                        <li>• Contact support if the issue continues</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Reschedule Interview</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check if the interview is locked by another user</li>
                                        <li>• Verify you have edit permissions for interviews</li>
                                        <li>• Try refreshing the page and attempting again</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'applications')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Application Management</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'pipeline')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Hiring Pipeline</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\help\interviews.blade.php ENDPATH**/ ?>