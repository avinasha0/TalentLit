<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - <?php echo e(tenant()->name); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('careers.index', ['tenant' => tenant()->slug])); ?>" class="flex items-center space-x-2">
                            <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('careers.index', ['tenant' => tenant()->slug])); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            All Jobs
                        </a>
                        <a href="<?php echo e(route('careers.index', ['tenant' => tenant()->slug])); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            View All Positions
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-green-600 via-emerald-600 to-teal-800 overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 opacity-90"></div>
                <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                            <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)"/>
                </svg>
            </div>
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="text-center">
                    <!-- Success Icon -->
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-white/20 backdrop-blur-sm mb-8">
                        <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>

                    <!-- Success Message -->
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                        Application Submitted Successfully!
                    </h1>
                    <p class="text-xl text-green-100 mb-8">
                        Thank you for your interest in joining <?php echo e(tenant()->name); ?>.
                    </p>
                </div>
            </div>
        </section>

        <!-- Success Details Section -->
        <section class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-gray-50 to-green-50 rounded-2xl p-8 lg:p-12 border border-gray-100">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Application Confirmed</h2>
                        <p class="text-gray-600">Your application has been received and is being processed.</p>
                    </div>

                    <!-- Application Details -->
                    <div class="bg-white rounded-xl p-8 shadow-lg mb-8">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-6">Application Details</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Position</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($job->title); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Department</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($job->department->name); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Location</p>
                                    <p class="font-semibold text-gray-900"><?php echo e($job->location->name); ?></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-teal-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Employment Type</p>
                                    <p class="font-semibold text-gray-900"><?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?></p>
                                </div>
                            </div>
                            
                            <?php if(session('application_id')): ?>
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m0 0V1a1 1 0 011-1h2a1 1 0 011 1v18a1 1 0 01-1 1H4a1 1 0 01-1-1V1a1 1 0 011-1h2a1 1 0 011 1v3m0 0h8"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Application ID</p>
                                        <p class="font-semibold text-gray-900"><?php echo e(session('application_id')); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Submitted</p>
                                    <p class="font-semibold text-gray-900"><?php echo e(now()->format('F j, Y \a\t g:i A')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Next Steps -->
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white mb-8">
                        <h3 class="text-2xl font-bold mb-6">What's Next?</h3>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">Confirmation Email</h4>
                                    <p class="text-blue-100 text-sm">You will receive a confirmation email shortly</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">Application Review</h4>
                                    <p class="text-blue-100 text-sm">Our recruitment team will review your application</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-4">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold mb-2">Follow-up Contact</h4>
                                    <p class="text-blue-100 text-sm">We'll contact you within 5-7 business days</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-6 justify-center">
                        <a href="<?php echo e(route('careers.index', ['tenant' => tenant()->slug])); ?>" 
                           class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-center">
                            View Other Jobs
                        </a>
                        <a href="<?php echo e(route('careers.show', ['tenant' => tenant()->slug, 'job' => $job->slug])); ?>" 
                           class="border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200 text-center">
                            Back to Job Details
                        </a>
                    </div>

                    <!-- Contact Information -->
                    <div class="text-center mt-12 pt-8 border-t border-gray-200">
                        <div class="bg-white rounded-xl p-6 shadow-lg">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Need Help?</h4>
                            <p class="text-gray-600 mb-4">
                                Questions about your application? Contact our HR department
                            </p>
                            <a href="mailto:hr{{ tenant()->slug }}.com" 
                               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg font-semibold hover:from-green-700 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                hr{{ tenant()->slug }}.com
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/careers/success.blade.php ENDPATH**/ ?>