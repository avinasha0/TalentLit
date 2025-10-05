<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for <?php echo e($job->title); ?> - <?php echo e($tenantModel->name); ?></title>
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
                        <a href="<?php echo e(route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                            View Job
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
                        <a href="<?php echo e(route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" class="hover:text-white transition-colors"><?php echo e($job->title); ?></a>
                        <span>></span>
                        <span class="text-white">Apply</span>
                    </nav>
                    
                    <!-- Job Title -->
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
                        Apply for <?php echo e($job->title); ?>

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
                </div>
            </div>
        </section>

        <!-- Application Form Section -->
        <section class="py-20 bg-white">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-br from-gray-50 to-indigo-50 rounded-2xl p-8 lg:p-12 border border-gray-100">
                    <!-- Form Header -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Application Form</h2>
                        <p class="text-gray-600">Fill out the form below to apply for this position. We'll review your application and get back to you soon.</p>
                    </div>

                    <!-- Application Form -->
                    <div class="bg-white rounded-xl p-8 shadow-lg">

                        <?php if($errors->any()): ?>
                            <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-6 w-6 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-lg font-semibold text-red-800">Please correct the following errors:</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($error); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('careers.apply.store', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" 
                              method="POST" 
                              enctype="multipart/form-data"
                              class="space-y-8">
                            <?php echo csrf_field(); ?>

                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="first_name" 
                                               id="first_name"
                                               value="<?php echo e(old('first_name')); ?>"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="last_name" 
                                               id="last_name"
                                               value="<?php echo e(old('last_name')); ?>"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Contact Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                            Email Address <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" 
                                               name="email" 
                                               id="email"
                                               value="<?php echo e(old('email')); ?>"
                                               required
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                            Phone Number
                                        </label>
                                        <input type="tel" 
                                               name="phone" 
                                               id="phone"
                                               value="<?php echo e(old('phone')); ?>"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Resume Upload -->
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Resume Upload</h3>
                                <div>
                                    <label for="resume" class="block text-sm font-medium text-gray-700 mb-2">
                                        Resume (PDF, DOC, DOCX) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="file" 
                                           name="resume" 
                                           id="resume"
                                           accept=".pdf,.doc,.docx"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?php $__errorArgs = ['resume'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <p class="mt-2 text-sm text-gray-500">Maximum file size: 5MB</p>
                                    <?php $__errorArgs = ['resume'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Custom Questions -->
                            <?php if($job->applicationQuestions->count() > 0): ?>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Additional Information</h3>
                            <div class="space-y-6">
                                <?php $__currentLoopData = $job->applicationQuestions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $isRequired = $question->pivot->required_override ?? $question->required;
                                        $fieldName = "question_{$question->id}";
                                        $oldValue = old($fieldName);
                                    ?>
                                    
                                    <div>
                                        <label for="<?php echo e($fieldName); ?>" class="block text-sm font-medium text-gray-700 mb-1">
                                            <?php echo e($question->label); ?>

                                            <?php if($isRequired): ?>
                                                <span class="text-red-500">*</span>
                                            <?php endif; ?>
                                        </label>
                                        
                                        <?php switch($question->type):
                                            case ('short_text'): ?>
                                                <input type="text" 
                                                       name="<?php echo e($fieldName); ?>" 
                                                       id="<?php echo e($fieldName); ?>"
                                                       value="<?php echo e($oldValue); ?>"
                                                       <?php if($isRequired): ?> required <?php endif; ?>
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php break; ?>
                                            
                                            <?php case ('long_text'): ?>
                                                <textarea name="<?php echo e($fieldName); ?>" 
                                                          id="<?php echo e($fieldName); ?>"
                                                          rows="4"
                                                          <?php if($isRequired): ?> required <?php endif; ?>
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e($oldValue); ?></textarea>
                                                <?php break; ?>
                                            
                                            <?php case ('email'): ?>
                                                <input type="email" 
                                                       name="<?php echo e($fieldName); ?>" 
                                                       id="<?php echo e($fieldName); ?>"
                                                       value="<?php echo e($oldValue); ?>"
                                                       <?php if($isRequired): ?> required <?php endif; ?>
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php break; ?>
                                            
                                            <?php case ('phone'): ?>
                                                <input type="tel" 
                                                       name="<?php echo e($fieldName); ?>" 
                                                       id="<?php echo e($fieldName); ?>"
                                                       value="<?php echo e($oldValue); ?>"
                                                       <?php if($isRequired): ?> required <?php endif; ?>
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <?php break; ?>
                                            
                                            <?php case ('select'): ?>
                                                <select name="<?php echo e($fieldName); ?>" 
                                                        id="<?php echo e($fieldName); ?>"
                                                        <?php if($isRequired): ?> required <?php endif; ?>
                                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                    <option value="">Please select...</option>
                                                    <?php if($question->options): ?>
                                                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($option); ?>" <?php echo e($oldValue == $option ? 'selected' : ''); ?>>
                                                                <?php echo e($option); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </select>
                                                <?php break; ?>
                                            
                                            <?php case ('multi_select'): ?>
                                                <div class="space-y-2">
                                                    <?php if($question->options): ?>
                                                        <?php $__currentLoopData = $question->options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <label class="flex items-center">
                                                                <input type="checkbox" 
                                                                       name="<?php echo e($fieldName); ?>[]" 
                                                                       value="<?php echo e($option); ?>"
                                                                       <?php echo e(in_array($option, (array)$oldValue) ? 'checked' : ''); ?>

                                                                       class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                                <span class="ml-2 text-sm text-gray-700"><?php echo e($option); ?></span>
                                                            </label>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                </div>
                                                <?php break; ?>
                                            
                                            <?php case ('checkbox'): ?>
                                                <label class="flex items-center">
                                                    <input type="checkbox" 
                                                           name="<?php echo e($fieldName); ?>" 
                                                           value="1"
                                                           <?php echo e($oldValue ? 'checked' : ''); ?>

                                                           <?php if($isRequired): ?> required <?php endif; ?>
                                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                    <span class="ml-2 text-sm text-gray-700">Yes</span>
                                                </label>
                                                <?php break; ?>
                                            
                                            <?php case ('file'): ?>
                                                <input type="file" 
                                                       name="<?php echo e($fieldName); ?>" 
                                                       id="<?php echo e($fieldName); ?>"
                                                       <?php if($isRequired): ?> required <?php endif; ?>
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                                <p class="mt-1 text-sm text-gray-600">Maximum file size: 5MB</p>
                                                <?php break; ?>
                                        <?php endswitch; ?>
                                        
                                        <?php $__errorArgs = [$fieldName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                            <!-- Consent Checkbox -->
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="checkbox" 
                                           name="consent" 
                                           id="consent"
                                           value="1"
                                           <?php echo e(old('consent') ? 'checked' : ''); ?>

                                           required
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded <?php $__errorArgs = ['consent'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                </div>
                                <div class="ml-3">
                                    <label for="consent" class="text-sm font-medium text-gray-700">
                                        <?php if($tenantModel->consent_text): ?>
                                            <?php echo e($tenantModel->consent_text); ?> <span class="text-red-500">*</span>
                                        <?php else: ?>
                                            I agree to the terms and conditions and consent to the processing of my personal data for recruitment purposes. <span class="text-red-500">*</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="flex justify-center">
                                <?php if (isset($component)) { $__componentOriginalc8680908dbd82701772537a108fb92cd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalc8680908dbd82701772537a108fb92cd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.recaptcha','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('recaptcha'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalc8680908dbd82701772537a108fb92cd)): ?>
<?php $attributes = $__attributesOriginalc8680908dbd82701772537a108fb92cd; ?>
<?php unset($__attributesOriginalc8680908dbd82701772537a108fb92cd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc8680908dbd82701772537a108fb92cd)): ?>
<?php $component = $__componentOriginalc8680908dbd82701772537a108fb92cd; ?>
<?php unset($__componentOriginalc8680908dbd82701772537a108fb92cd); ?>
<?php endif; ?>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-8">
                                <a href="<?php echo e(route('careers.show', ['tenant' => $tenantModel->slug, 'job' => $job->slug])); ?>" 
                                   class="px-8 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors text-center">
                                    Cancel
                                </a>
                                <button type="submit" 
                                        class="px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                    Submit Application
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\careers\apply.blade.php ENDPATH**/ ?>