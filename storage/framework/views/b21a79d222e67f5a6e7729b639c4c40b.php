<?php $__env->startSection('title', 'Account Setup & Onboarding — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Complete guide to setting up your TalentLit account, creating your organization, and getting started with your first job posting.'); ?>

<?php
    $seoTitle = 'Account Setup & Onboarding — TalentLit Help';
    $seoDescription = 'Complete guide to setting up your TalentLit account, creating your organization, and getting started with your first job posting.';
    $seoKeywords = 'TalentLit onboarding, account setup, organization setup, first job posting';
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
                    Account Setup & Onboarding
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Complete guide to setting up your TalentLit account, creating your organization, and getting started with your first job posting.
                </p>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12">
                <!-- Table of Contents -->
                <div class="lg:col-span-3">
                    <div class="sticky top-24">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Table of Contents</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Onboarding Overview</a>
                            <a href="#step-1" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">1. Create Organization</a>
                            <a href="#step-2" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">2. Set Up Branding</a>
                            <a href="#step-3" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">3. Create First Job</a>
                            <a href="#step-4" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">4. Invite Team Members</a>
                            <a href="#tips" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Pro Tips</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Onboarding Overview</h2>
                            <p class="text-gray-600 mb-4">
                                The TalentLit onboarding process is designed to get you up and running quickly. You'll complete these steps:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Create your organization profile</li>
                                <li>Set up your company branding</li>
                                <li>Post your first job opening</li>
                                <li>Invite team members to collaborate</li>
                            </ul>
                            <p class="text-gray-600 mt-4">
                                <strong>Time required:</strong> 10-15 minutes
                            </p>
                        </div>

                        <h2 id="step-1" class="text-2xl font-bold text-gray-900 mb-6">1. Create Your Organization</h2>
                        <p class="text-gray-600 mb-6">
                            After email verification, you'll be redirected to create your organization. This is where you'll set up your company profile.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Organization Information Required:</h3>
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
                                            <strong>Organization Slug:</strong> A unique URL-friendly identifier (e.g., "acme-corp")
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Industry:</strong> Select your industry from the dropdown
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Company Size:</strong> Choose your company size range
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
                                            <li>The organization slug will be used in your career page URL</li>
                                            <li>Choose a slug that's easy to remember and represents your brand</li>
                                            <li>You can change some details later, but the slug is permanent</li>
                                        </ul>
                                    </div>
                                </div>
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
                                    <h3 class="text-sm font-medium text-blue-800">Screenshot</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <img src="<?php echo e(asset('images/help/onboarding-step1.png')); ?>" alt="Organization creation form" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-2" class="text-2xl font-bold text-gray-900 mb-6">2. Set Up Your Branding</h2>
                        <p class="text-gray-600 mb-6">
                            Customize your organization's appearance to match your brand identity. This will be visible on your career pages and in communications.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Branding Options:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Logo Upload:</strong> Upload your company logo (PNG, JPG, SVG)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Brand Colors:</strong> Set primary and secondary brand colors
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Company Description:</strong> Write a brief description of your company
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Contact Information:</strong> Add your company's contact details
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
                                            <li>Use high-resolution logos for best quality</li>
                                            <li>Choose colors that match your existing brand guidelines</li>
                                            <li>Keep your company description concise but informative</li>
                                            <li>You can update branding anytime in settings</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-3" class="text-2xl font-bold text-gray-900 mb-6">3. Create Your First Job</h2>
                        <p class="text-gray-600 mb-6">
                            Now it's time to create your first job posting. This will help you understand how the job management system works.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Job Information Required:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Job Title:</strong> Clear, descriptive job title
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Job Description:</strong> Detailed description of the role and responsibilities
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Location:</strong> Job location (remote, office, hybrid)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Employment Type:</strong> Full-time, part-time, contract, etc.
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-pink-100 text-pink-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Salary Range:</strong> Optional salary information
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
                                    <h3 class="text-sm font-medium text-blue-800">Screenshot</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <img src="<?php echo e(asset('images/help/onboarding-step3.png')); ?>" alt="Job creation form" class="w-full rounded-lg shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="step-4" class="text-2xl font-bold text-gray-900 mb-6">4. Invite Team Members</h2>
                        <p class="text-gray-600 mb-6">
                            Invite colleagues to join your TalentLit workspace so you can collaborate on hiring.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Team Invitation Process:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Add Email Addresses:</strong> Enter email addresses of team members
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Assign Roles:</strong> Choose appropriate roles for each team member
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Send Invitations:</strong> Invitations will be sent via email
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Team Members Accept:</strong> They'll create accounts and join your workspace
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg mb-8">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-indigo-800">Available Roles:</h3>
                                    <div class="mt-2 text-sm text-indigo-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li><strong>Owner:</strong> Full access to all features and settings</li>
                                            <li><strong>Admin:</strong> Manage users and most settings</li>
                                            <li><strong>Recruiter:</strong> Create jobs, manage candidates, schedule interviews</li>
                                            <li><strong>Hiring Manager:</strong> View candidates, provide feedback, participate in interviews</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="tips" class="bg-gradient-to-r from-purple-50 to-pink-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Pro Tips for Success</h2>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Getting Started</h3>
                                    <ul class="space-y-2 text-gray-600">
                                        <li>• Start with one job posting to learn the system</li>
                                        <li>• Use clear, descriptive job titles</li>
                                        <li>• Write detailed job descriptions</li>
                                        <li>• Set realistic expectations for your first hire</li>
                                    </ul>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Team Collaboration</h3>
                                    <ul class="space-y-2 text-gray-600">
                                        <li>• Invite key stakeholders early</li>
                                        <li>• Assign appropriate roles to team members</li>
                                        <li>• Use the comment system for communication</li>
                                        <li>• Set up regular check-ins on hiring progress</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Organization Slug Already Taken</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Try adding numbers or hyphens to make it unique</li>
                                        <li>• Use your company's full name or abbreviation</li>
                                        <li>• Consider using your domain name</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Job Creation Issues</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Make sure all required fields are filled out</li>
                                        <li>• Check that your job description is detailed enough</li>
                                        <li>• Verify your location information is correct</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Team Invitation Problems</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Verify email addresses are correct</li>
                                        <li>• Check that team members haven't already been invited</li>
                                        <li>• Ask team members to check their spam folders</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'dashboard')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Dashboard Overview</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'invite-team')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Team Management</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/onboarding.blade.php ENDPATH**/ ?>