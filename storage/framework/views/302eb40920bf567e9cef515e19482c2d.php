<?php $__env->startSection('title', 'Managing Jobs — TalentLit Help'); ?>
<?php $__env->startSection('description', 'Complete guide to creating, editing, publishing, and managing job postings in TalentLit ATS.'); ?>

<?php
    $seoTitle = 'Managing Jobs — TalentLit Help';
    $seoDescription = 'Complete guide to creating, editing, publishing, and managing job postings in TalentLit ATS.';
    $seoKeywords = 'TalentLit jobs, job posting, job management, ATS jobs';
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
                    Managing Jobs in TalentLit
                </h1>
                <p class="text-xl md:text-2xl mb-8 text-indigo-100 leading-relaxed max-w-3xl mx-auto">
                    Learn how to create, edit, publish, and manage job postings to attract the best candidates.
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
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Table of Contents</h3>
                        <nav class="space-y-2">
                            <a href="#overview" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Job Management Overview</a>
                            <a href="#create" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Creating a Job</a>
                            <a href="#edit" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Editing Jobs</a>
                            <a href="#publish" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Publishing Jobs</a>
                            <a href="#close" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Closing Jobs</a>
                            <a href="#slug-rules" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Job Slug Rules</a>
                            <a href="#visibility" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Career Page Visibility</a>
                            <a href="#troubleshooting" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Troubleshooting</a>
                            <a href="#related-topics" class="block text-sm text-gray-600 hover:text-indigo-600 py-1">Related Topics</a>
                        </nav>
                    </div>
                </div>

                <!-- Content -->
                <div class="lg:col-span-9">
                    <div class="max-w-none">
                        <div id="overview" class="bg-blue-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Job Management Overview</h2>
                            <p class="text-gray-600 mb-4">
                                TalentLit provides a comprehensive job management system that allows you to:
                            </p>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Create detailed job postings with rich formatting</li>
                                <li>Set up custom application questions</li>
                                <li>Manage job visibility on your career page</li>
                                <li>Track application status and progress</li>
                                <li>Collaborate with team members on hiring decisions</li>
                            </ul>
                        </div>

                        <h2 id="create" class="text-2xl font-bold text-gray-900 mb-6">Creating a New Job</h2>
                        <p class="text-gray-600 mb-6">
                            To create a new job posting, navigate to the Jobs section and click "Create New Job".
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Information:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Job Title:</strong> Clear, descriptive title that candidates will search for
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Job Description:</strong> Detailed description including responsibilities, requirements, and benefits
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Location:</strong> Job location (remote, office, hybrid, or specific city)
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Employment Type:</strong> Full-time, part-time, contract, internship, etc.
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Department:</strong> Which department this role belongs to
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
                                    <h3 class="text-sm font-medium text-yellow-800">Writing Effective Job Descriptions:</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Use clear, action-oriented language</li>
                                            <li>Include specific requirements and qualifications</li>
                                            <li>Highlight company culture and benefits</li>
                                            <li>Use bullet points for easy scanning</li>
                                            <li>Include salary range if possible</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="edit" class="text-2xl font-bold text-gray-900 mb-6">Editing Existing Jobs</h2>
                        <p class="text-gray-600 mb-6">
                            You can edit job postings at any time, even after they've been published. Changes will be reflected on your career page.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Edit a Job:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Navigate to Jobs:</strong> Go to the Jobs section in your dashboard
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Find the Job:</strong> Locate the job you want to edit in the list
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Click Edit:</strong> Click the "Edit" button next to the job title
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Make Changes:</strong> Update the information you need to change
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">5</span>
                                        <div>
                                            <strong>Save Changes:</strong> Click "Update Job" to save your changes
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="publish" class="text-2xl font-bold text-gray-900 mb-6">Publishing Jobs</h2>
                        <p class="text-gray-600 mb-6">
                            Once you've created a job, you need to publish it to make it visible on your career page and start receiving applications.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Publishing Process:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Review Job Details:</strong> Make sure all information is accurate and complete
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click Publish:</strong> Click the "Publish Job" button
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Job Goes Live:</strong> The job will appear on your career page immediately
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Start Receiving Applications:</strong> Candidates can now apply for the position
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
                                    <h3 class="text-sm font-medium text-green-800">Pro Tips for Publishing:</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Double-check all details before publishing</li>
                                            <li>Consider publishing during business hours for better visibility</li>
                                            <li>Share the job on social media after publishing</li>
                                            <li>Monitor applications regularly after going live</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="close" class="text-2xl font-bold text-gray-900 mb-6">Closing Jobs</h2>
                        <p class="text-gray-600 mb-6">
                            When you've found the right candidate or no longer need to fill a position, you can close the job posting.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">How to Close a Job:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Go to Job Details:</strong> Navigate to the job you want to close
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Click Close Job:</strong> Click the "Close Job" button
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Confirm Action:</strong> Confirm that you want to close the job
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Job is Closed:</strong> The job will no longer accept new applications
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <h2 id="slug-rules" class="text-2xl font-bold text-gray-900 mb-6">Job Slug Rules</h2>
                        <p class="text-gray-600 mb-6">
                            Job slugs are used in URLs and must follow specific rules to ensure they work properly.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Slug Requirements:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Lowercase letters only</strong>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Numbers are allowed</strong>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✓</span>
                                        <div>
                                            <strong>Hyphens (-) are allowed</strong>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✗</span>
                                        <div>
                                            <strong>No spaces or special characters</strong>
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">✗</span>
                                        <div>
                                            <strong>No uppercase letters</strong>
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
                                    <h3 class="text-sm font-medium text-blue-800">Good Slug Examples:</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li><code>senior-software-engineer</code></li>
                                            <li><code>marketing-manager-remote</code></li>
                                            <li><code>data-scientist-2024</code></li>
                                            <li><code>product-designer-ux</code></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h2 id="visibility" class="text-2xl font-bold text-gray-900 mb-6">Career Page Visibility</h2>
                        <p class="text-gray-600 mb-6">
                            Published jobs automatically appear on your public career page where candidates can discover and apply for positions.
                        </p>

                        <div class="space-y-6 mb-8">
                            <div class="border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Career Page Features:</h3>
                                <ul class="space-y-3 text-gray-600">
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">1</span>
                                        <div>
                                            <strong>Job Listings:</strong> All published jobs are displayed with key details
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">2</span>
                                        <div>
                                            <strong>Search & Filter:</strong> Candidates can search and filter by location, department, etc.
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">3</span>
                                        <div>
                                            <strong>Company Branding:</strong> Your logo and brand colors are displayed
                                        </div>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="flex-shrink-0 w-6 h-6 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-sm font-semibold mr-3">4</span>
                                        <div>
                                            <strong>Easy Application:</strong> One-click application process for candidates
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div id="troubleshooting" class="bg-gray-50 p-8 rounded-lg mb-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>
                            <div class="space-y-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Job Not Appearing on Career Page</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Make sure the job has been published (not just saved as draft)</li>
                                        <li>• Check that the job is not closed</li>
                                        <li>• Verify your career page settings are correct</li>
                                        <li>• Clear your browser cache and refresh the page</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Cannot Edit Published Job</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Check your permissions - you need "edit jobs" permission</li>
                                        <li>• Make sure you're logged in with the correct account</li>
                                        <li>• Try refreshing the page and attempting again</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Slug Already Exists Error</h3>
                                    <ul class="text-gray-600 space-y-1">
                                        <li>• Try adding numbers or hyphens to make it unique</li>
                                        <li>• Use a different variation of the job title</li>
                                        <li>• Check if you have another job with the same slug</li>
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
                                        <li><a href="<?php echo e(route('help.page', 'candidates')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Managing Candidates</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'applications')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Application Management</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'careers')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Career Pages</a></li>
                                        <li><a href="<?php echo e(route('help.page', 'pipeline')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">Hiring Pipeline</a></li>
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

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/help/jobs.blade.php ENDPATH**/ ?>