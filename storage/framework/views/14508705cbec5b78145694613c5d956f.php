<?php $__env->startSection('title', 'Invite Team Members — TalentLit'); ?>
<?php $__env->startSection('description', 'Learn how to invite team members to your TalentLit organization and manage user permissions.'); ?>

<?php
    $seoTitle = 'Invite Team Members — TalentLit ATS';
    $seoDescription = 'Learn how to invite team members to your TalentLit organization and manage user permissions.';
    $seoKeywords = 'TalentLit team invitation, user management, ATS collaboration, team setup';
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
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
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
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Invite Team Members
                </h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Learn how to invite team members to your TalentLit organization and manage user permissions
                </p>
            </div>
        </div>
    </section>

    <!-- Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <!-- Overview -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Overview</h2>
                    <p class="text-gray-600 mb-4">
                        Inviting team members to your TalentLit organization allows you to collaborate on recruitment activities, 
                        share candidate information, and manage hiring processes together. Each team member can be assigned 
                        specific roles and permissions based on their responsibilities.
                    </p>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Team Collaboration</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>Team members can work together on job postings, review candidates, schedule interviews, and share notes about applicants.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- How to Invite Team Members -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">How to Invite Team Members</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    1
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Access Team Settings</h3>
                                <p class="text-gray-600">Navigate to Settings → Team Management from your dashboard sidebar.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    2
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Click "Invite Team Member"</h3>
                                <p class="text-gray-600">Click the "Invite Team Member" button in the top-right corner of the team management page.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    3
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Enter Team Member Details</h3>
                                <p class="text-gray-600">Fill in the team member's email address, name, and select their role and permissions.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                    4
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Send Invitation</h3>
                                <p class="text-gray-600">Click "Send Invitation" to send an email invitation to the team member.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- User Roles and Permissions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">User Roles and Permissions</h2>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Admin</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Full access to all features</li>
                                <li>• Manage team members</li>
                                <li>• Configure organization settings</li>
                                <li>• Access billing and subscription</li>
                                <li>• Delete jobs and candidates</li>
                            </ul>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Manager</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• Create and manage jobs</li>
                                <li>• View and manage candidates</li>
                                <li>• Schedule interviews</li>
                                <li>• Access analytics</li>
                                <li>• Invite team members</li>
                            </ul>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Recruiter</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• View assigned jobs</li>
                                <li>• Manage candidates</li>
                                <li>• Schedule interviews</li>
                                <li>• Add notes and tags</li>
                                <li>• View basic analytics</li>
                            </ul>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Viewer</h3>
                            <ul class="space-y-2 text-gray-600">
                                <li>• View jobs and candidates</li>
                                <li>• Read-only access</li>
                                <li>• Cannot make changes</li>
                                <li>• Limited analytics access</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Managing Invitations -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Managing Invitations</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Pending Invitations</h3>
                            <p class="text-gray-600">View all pending invitations in the Team Management section. You can resend invitations or cancel them if needed.</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Resending Invitations</h3>
                            <p class="text-gray-600">If a team member hasn't received their invitation email, you can resend it from the team management page.</p>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Canceling Invitations</h3>
                            <p class="text-gray-600">You can cancel pending invitations at any time before they're accepted by the team member.</p>
                        </div>
                    </div>
                </div>

                <!-- Best Practices -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Best Practices</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Assign Appropriate Roles</h3>
                                <p class="text-sm text-gray-600">Give team members only the permissions they need for their role to maintain security.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Use Clear Email Addresses</h3>
                                <p class="text-sm text-gray-600">Ensure team members use their work email addresses for better organization and security.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400 mt-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-gray-900">Regular Access Reviews</h3>
                                <p class="text-sm text-gray-600">Periodically review team member access and remove inactive users to maintain security.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Troubleshooting -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Invitation Not Received</h3>
                            <p class="text-gray-600 mb-2">If a team member doesn't receive their invitation email:</p>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Check their spam/junk folder</li>
                                <li>Verify the email address is correct</li>
                                <li>Resend the invitation</li>
                                <li>Contact support if the issue persists</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Permission Issues</h3>
                            <p class="text-gray-600 mb-2">If a team member can't access certain features:</p>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Check their assigned role and permissions</li>
                                <li>Verify they've accepted the invitation</li>
                                <li>Contact an admin to update their permissions</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Need Help? -->
                <div class="bg-indigo-50 p-8 rounded-lg">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Need Help?</h2>
                    <p class="text-gray-600 mb-6">Having trouble with team invitations or user management? Our support team is here to help.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo e(route('help.page', 'contact')); ?>" class="bg-indigo-600 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors">
                            Contact Support
                        </a>
                        <a href="<?php echo e(route('help.page', 'settings')); ?>" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-medium border border-indigo-600 hover:bg-indigo-50 transition-colors">
                            Settings Help
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/invite-team.blade.php ENDPATH**/ ?>