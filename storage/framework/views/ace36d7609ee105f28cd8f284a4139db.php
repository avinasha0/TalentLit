<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Create Organization - TalentLit ATS';
        $seoDescription = 'Set up your organization on TalentLit to start managing your recruitment process. Create your company profile and begin hiring smarter.';
        $seoKeywords = 'TalentLit, organization setup, company profile, ATS, recruitment, onboarding';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.png');
    ?>
    <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.png')); ?>" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Features Dropdown -->
                    <div class="relative group" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium flex items-center gap-1">
                            Features
                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute top-full left-0 mt-2 w-96 bg-white rounded-lg shadow-lg border border-gray-200 py-4 z-50">
                            <div class="grid grid-cols-2 gap-8 px-4">
                                <!-- Source Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Source</div>
                                    <div class="space-y-1">
                                        <a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Candidate Sourcing</div>
                                        </a>
                                        <a href="<?php echo e(route('features.career-site')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Career Site</div>
                                        </a>
                                        <a href="<?php echo e(route('features.job-advertising')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Job Advertising</div>
                                        </a>
                                        <a href="<?php echo e(route('features.employee-referral')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Employee Referral</div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Track Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Track</div>
                                    <div class="space-y-1">
                                        <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Pipeline</div>
                                        </a>
                                        <a href="<?php echo e(route('features.resume-management')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Resume Management</div>
                                        </a>
                                        <a href="<?php echo e(route('features.manage-submission')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Manage Submission</div>
                                        </a>
                                        <a href="<?php echo e(route('features.hiring-analytics')); ?>" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Analytics</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                        Get Started Free
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-500 hover:text-gray-700 p-2">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Navigation Menu -->
            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="md:hidden">
                <div class="px-4 py-4 space-y-3 bg-white border-t border-gray-200">
                    <!-- Features Section -->
                    <div>
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-3">Features</div>
                        <div class="space-y-2">
                            <a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="block px-3 py-3 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                Candidate Sourcing
                            </a>
                            <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-3 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <!-- Other Links -->
                    <div class="space-y-2">
                        <a href="<?php echo e(route('subscription.pricing')); ?>" class="block px-3 py-3 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            Pricing
                        </a>
                        <a href="<?php echo e(route('login')); ?>" class="block px-3 py-3 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition-colors duration-200">
                            Sign In
                        </a>
                        <a href="<?php echo e(route('register')); ?>" class="block px-3 py-3 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-lg transition-all duration-200">
                            Get Started Free
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl sm:max-w-2xl mx-auto space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="flex justify-center mb-6">
                    <img src="<?php echo e(asset('logo-talentlit-small.png')); ?>" alt="TalentLit Logo" class="h-12">
                </div>
                <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                    Create Organization
                </h2>
                <p class="text-base sm:text-lg text-gray-600 mb-4">
                    Set up your organization to get started with TalentLit
                </p>
            </div>

            <!-- Organization Form -->
            <div class="bg-white rounded-2xl shadow-xl p-4 sm:p-8 border border-gray-100">
    <form class="space-y-6" action="<?php echo e(route('onboarding.organization.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        
        <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 mb-2">Organization Name</label>
            <input id="name" name="name" type="text" required
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                            placeholder="DigitalXBrand" />
            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
                        <label for="slug" class="block text-sm font-semibold text-gray-900 mb-2">Organization URL</label>
                        <div class="flex flex-col sm:flex-row rounded-xl shadow-sm overflow-hidden">
                            <span class="inline-flex items-center px-3 sm:px-4 py-2 sm:py-0 sm:rounded-l-xl border-2 sm:border-r-0 border-gray-200 bg-gray-50 text-gray-600 text-sm font-medium break-all">
                    <?php echo e(config('app.url')); ?>/
                </span>
                <input id="slug" name="slug" type="text" required
                                class="flex-1 min-w-0 block w-full sm:rounded-none sm:rounded-r-xl border-2 border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 px-4 py-3"
                    placeholder="DigitalXBrand" />
            </div>
                        <p class="mt-2 text-sm text-gray-500">This will be your organization's unique URL</p>
            <?php $__errorArgs = ['slug'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
                        <label for="website" class="block text-sm font-semibold text-gray-900 mb-2">Website (Optional)</label>
            <input id="website" name="website" type="url"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                            placeholder="https://digitalxbrand.com" />
            <?php $__errorArgs = ['website'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
                        <label for="location" class="block text-sm font-semibold text-gray-900 mb-2">Location (Optional)</label>
                        <input id="location" name="location" type="text"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300"
                            placeholder="Bangalore, India" />
            <?php $__errorArgs = ['location'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
                        <label for="company_size" class="block text-sm font-semibold text-gray-900 mb-2">Company Size (Optional)</label>
            <select id="company_size" name="company_size"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                <option value="">Select company size</option>
                <option value="1-10">1–10</option>
                <option value="11-50">11–50</option>
                <option value="51-200">51–200</option>
                <option value="201-500">201–500</option>
                <option value="500+">500+</option>
            </select>
            <?php $__errorArgs = ['company_size'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div>
            <button type="submit"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl">
                Create Organization
            </button>
        </div>
    </form>
            </div>

            <!-- Help Section -->
            <div class="mt-8 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gradient-to-br from-indigo-50 via-white to-purple-50 text-gray-500">Need help?</span>
                    </div>
                </div>

                <div class="mt-6 px-4">
                    <p class="text-sm text-gray-600 leading-relaxed">
                        If you need assistance setting up your organization, please 
                        <a href="<?php echo e(route('contact')); ?>" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                            contact our support team
                        </a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Centralized Footer Component -->
    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-convert slug input to lowercase
            const slugInput = document.getElementById('slug');
            if (slugInput) {
                slugInput.addEventListener('input', function(e) {
                    // Convert to lowercase and remove any invalid characters
                    let value = e.target.value.toLowerCase();
                    // Remove any characters that are not lowercase letters, numbers, or hyphens
                    value = value.replace(/[^a-z0-9-]/g, '');
                    // Remove consecutive hyphens
                    value = value.replace(/-+/g, '-');
                    // Remove leading/trailing hyphens
                    value = value.replace(/^-+|-+$/g, '');
                    
                    e.target.value = value;
                });

                // Also handle paste events
                slugInput.addEventListener('paste', function(e) {
                    setTimeout(() => {
                        let value = e.target.value.toLowerCase();
                        value = value.replace(/[^a-z0-9-]/g, '');
                        value = value.replace(/-+/g, '-');
                        value = value.replace(/^-+|-+$/g, '');
                        e.target.value = value;
                    }, 10);
                });
            }

            // Auto-generate slug from organization name
            const nameInput = document.getElementById('name');
            if (nameInput && slugInput) {
                nameInput.addEventListener('input', function(e) {
                    // Only auto-generate if slug field is empty
                    if (!slugInput.value) {
                        let slug = e.target.value.toLowerCase();
                        slug = slug.replace(/[^a-z0-9\s-]/g, ''); // Remove special characters
                        slug = slug.replace(/\s+/g, '-'); // Replace spaces with hyphens
                        slug = slug.replace(/-+/g, '-'); // Remove consecutive hyphens
                        slug = slug.replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
                        slugInput.value = slug;
                    }
                });
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/onboarding/organization.blade.php ENDPATH**/ ?>