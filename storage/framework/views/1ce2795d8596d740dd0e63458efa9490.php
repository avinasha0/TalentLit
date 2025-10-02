<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = $job->title . ' – Careers at ' . $tenantModel->name . ' | TalentLit';
        $jobDescSummary = $job->description 
            ? Str::limit(strip_tags($job->description), 150) 
            : 'Join ' . $tenantModel->name . ' as a ' . $job->title . '. Apply now to be part of our team.';
        $seoDescription = $job->title . ' position at ' . $tenantModel->name . '. ' . $jobDescSummary;
        $seoKeywords = $job->title . ', ' . $tenantModel->name . ', ' . $job->department->name . ', ' . $job->location->name . ', careers, jobs, hiring, employment, TalentLit';
        $seoAuthor = $tenantModel->name;
        $seoImage = ($branding && $branding->logo_path) 
            ? asset('storage/' . $branding->logo_path) 
            : asset('logo-talentlit-small.svg');
    ?>
    <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --brand: <?php echo e($branding && $branding->primary_color ? $branding->primary_color : '#4f46e5'); ?>;
        }
    </style>
</head>
<body class="bg-white">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="<?php echo e(route('careers.index', ['tenant' => $tenantModel->slug])); ?>" class="flex items-center space-x-2">
                            <?php if($branding && $branding->logo_path): ?>
                                <img src="<?php echo e(asset('storage/' . $branding->logo_path)); ?>" alt="<?php echo e($tenantModel->name); ?> Logo" class="h-8">
                            <?php else: ?>
                                <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="<?php echo e(route('careers.index', ['tenant' => $tenantModel->slug])); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                            All Jobs
                        </a>
                        <a href="<?php echo e(route('careers.index', ['tenant' => $tenantModel->slug])); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            View All Positions
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 overflow-hidden">
            <!-- Background Pattern -->
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
            
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
                <div class="text-center">
                    <!-- Breadcrumb -->
                    <nav class="flex items-center justify-center space-x-2 text-sm text-indigo-200 mb-6">
                        <a href="<?php echo e(route('careers.index', ['tenant' => $tenantModel->slug])); ?>" class="hover:text-white transition-colors">Careers</a>
                        <span>></span>
                        <span class="text-white"><?php echo e($job->title); ?></span>
                    </nav>
                    
                    <!-- Job Title -->
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                        <?php echo e($job->title); ?>

                    </h1>
                    
                    <!-- Job Meta -->
                    <div class="flex flex-wrap items-center justify-center gap-6 text-indigo-100 mb-8">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span><?php echo e($job->department->name); ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span><?php echo e($job->location->name); ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span><?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?></span>
                        </div>
                    </div>
                    
                    <!-- Apply Button -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="<?php echo e(route('careers.apply.create', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" 
                           class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
                            Apply Now
                        </a>
                        <a href="<?php echo e(route('careers.index', ['tenant' => $tenantModel->slug])); ?>" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-indigo-600 transition-all duration-200">
                            View All Jobs
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Job Details Section -->
        <section class="py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-2xl p-8 lg:p-12 border border-gray-100">
                    <!-- Job Description -->
                    <div class="prose prose-lg max-w-none">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Job Description</h2>
                        <div class="text-gray-700 leading-relaxed">
                            <?php if($job->description): ?>
                                <?php echo nl2br(e($job->description)); ?>

                            <?php else: ?>
                                <p class="text-gray-500 italic">No description provided.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Job Details Grid -->
                    <div class="mt-12 grid md:grid-cols-2 gap-8">
                        <!-- Department -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Department</h3>
                                    <p class="text-gray-600"><?php echo e($job->department->name); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Location -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-purple-600 to-pink-600 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Location</h3>
                                    <p class="text-gray-600"><?php echo e($job->location->name); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Employment Type -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-pink-600 to-red-600 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Employment Type</h3>
                                    <p class="text-gray-600"><?php echo e(ucfirst(str_replace('_', ' ', $job->employment_type))); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Openings -->
                        <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-600 to-teal-600 rounded-lg flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Openings</h3>
                                    <p class="text-gray-600"><?php echo e($job->openings_count); ?> position<?php echo e($job->openings_count > 1 ? 's' : ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Job Stages (if any) -->
                    <?php if($job->jobStages->count() > 0): ?>
                        <div class="mt-12">
                            <h3 class="text-2xl font-bold text-gray-900 mb-8">Application Process</h3>
                            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <?php $__currentLoopData = $job->jobStages->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="bg-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition-all duration-300">
                                        <div class="flex items-center mb-4">
                                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center mr-4">
                                                <span class="text-sm font-bold text-white"><?php echo e($stage->sort_order); ?></span>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-semibold text-gray-900"><?php echo e($stage->name); ?></h4>
                                                <?php if($stage->is_terminal): ?>
                                                    <p class="text-sm text-gray-600">Final stage</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Apply CTA -->
                    <div class="mt-12 text-center">
                        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
                            <h3 class="text-2xl font-bold mb-4">Ready to Join Our Team?</h3>
                            <p class="text-indigo-100 mb-6">Take the next step in your career and apply for this position today.</p>
                            <a href="<?php echo e(route('careers.apply.create', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" 
                               class="bg-white text-indigo-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-50 transition-all duration-200 shadow-xl hover:shadow-2xl transform hover:-translate-y-1 inline-block">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/careers/show.blade.php ENDPATH**/ ?>