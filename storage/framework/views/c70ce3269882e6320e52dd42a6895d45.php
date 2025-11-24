<?php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Settings', 'url' => null],
        ['label' => 'Careers', 'url' => null]
    ];
?>

<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['tenant' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($tenant)]); ?>
     <?php $__env->slot('breadcrumbs', null, []); ?> 
        <?php if (isset($component)) { $__componentOriginal360d002b1b676b6f84d43220f22129e2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal360d002b1b676b6f84d43220f22129e2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.breadcrumbs','data' => ['items' => $breadcrumbs]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('breadcrumbs'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($breadcrumbs)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $attributes = $__attributesOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__attributesOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal360d002b1b676b6f84d43220f22129e2)): ?>
<?php $component = $__componentOriginal360d002b1b676b6f84d43220f22129e2; ?>
<?php unset($__componentOriginal360d002b1b676b6f84d43220f22129e2); ?>
<?php endif; ?>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- Success Message -->
        <?php if(session('success')): ?>
            <div class="bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <h1 class="text-2xl font-bold text-black">Careers Settings</h1>
                <p class="mt-1 text-sm text-black">Customize your careers page branding and appearance</p>
            </div>
        </div>

        <!-- Form -->
        <?php if (isset($component)) { $__componentOriginal53747ceb358d30c0105769f8471417f6 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal53747ceb358d30c0105769f8471417f6 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.card','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
            <form method="POST" action="<?php echo e(route('tenant.settings.careers.update', $tenant->slug)); ?>" enctype="multipart/form-data" class="space-y-6">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Left Column - Form Fields -->
                    <div class="space-y-6">
                        <!-- Logo Upload -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-black mb-1">Company Logo</label>
                            <input type="file"
                                   name="logo"
                                   id="logo"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            
                            <!-- Size Requirements -->
                            <div class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md">
                                <h4 class="text-sm font-medium text-blue-900 mb-2">Logo Requirements:</h4>
                                <ul class="text-xs text-blue-800 space-y-1">
                                    <li>• <strong>Recommended size:</strong> 200x60 pixels (3.3:1 aspect ratio)</li>
                                    <li>• <strong>Minimum size:</strong> 150x45 pixels</li>
                                    <li>• <strong>Maximum size:</strong> 400x120 pixels</li>
                                    <li>• <strong>File formats:</strong> PNG, JPG, SVG</li>
                                    <li>• <strong>Maximum file size:</strong> 2MB</li>
                                    <li>• <strong>Background:</strong> Transparent PNG recommended</li>
                                </ul>
                            </div>
                            
                            <?php if($branding->logo_path): ?>
                                <p class="mt-2 text-sm text-gray-600">Current: <?php echo e(basename($branding->logo_path)); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Hero Image Upload -->
                        <div>
                            <label for="hero_image" class="block text-sm font-medium text-black mb-1">Hero Background Image</label>
                            <input type="file"
                                   name="hero_image"
                                   id="hero_image"
                                   accept="image/*"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $__errorArgs = ['hero_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            
                            <!-- Size Requirements -->
                            <div class="mt-2 p-3 bg-green-50 border border-green-200 rounded-md">
                                <h4 class="text-sm font-medium text-green-900 mb-2">Background Image Requirements:</h4>
                                <ul class="text-xs text-green-800 space-y-1">
                                    <li>• <strong>Recommended size:</strong> 1920x1080 pixels (16:9 aspect ratio)</li>
                                    <li>• <strong>Minimum size:</strong> 1200x675 pixels</li>
                                    <li>• <strong>Maximum size:</strong> 2560x1440 pixels</li>
                                    <li>• <strong>File formats:</strong> JPG, PNG, WebP</li>
                                    <li>• <strong>Maximum file size:</strong> 5MB</li>
                                    <li>• <strong>Quality:</strong> High resolution for best display</li>
                                    <li>• <strong>Style:</strong> Professional, not too busy (text overlay will be added)</li>
                                </ul>
                            </div>
                            
                            <?php if($branding->hero_image_path): ?>
                                <p class="mt-2 text-sm text-gray-600">Current: <?php echo e(basename($branding->hero_image_path)); ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Primary Color -->
                        <div>
                            <label for="primary_color" class="block text-sm font-medium text-black mb-1">Primary Color</label>
                            <div class="flex items-center space-x-2">
                                <input type="color"
                                       name="primary_color"
                                       id="primary_color"
                                       value="<?php echo e($branding->primary_color ?? '#4f46e5'); ?>"
                                       class="w-12 h-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <input type="text"
                                       value="<?php echo e($branding->primary_color ?? '#4f46e5'); ?>"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="#4f46e5"
                                       readonly>
                            </div>
                            <?php $__errorArgs = ['primary_color'];
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

                        <!-- Intro Headline -->
                        <div>
                            <label for="intro_headline" class="block text-sm font-medium text-black mb-1">Hero Headline</label>
                            <input type="text"
                                   name="intro_headline"
                                   id="intro_headline"
                                   value="<?php echo e(old('intro_headline', $branding->intro_headline)); ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="Join Our Amazing Team">
                            <?php $__errorArgs = ['intro_headline'];
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

                        <!-- Intro Subtitle -->
                        <div>
                            <label for="intro_subtitle" class="block text-sm font-medium text-black mb-1">Hero Subtitle</label>
                            <textarea name="intro_subtitle"
                                      id="intro_subtitle"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="We're looking for talented individuals to join our growing team..."><?php echo e(old('intro_subtitle', $branding->intro_subtitle)); ?></textarea>
                            <?php $__errorArgs = ['intro_subtitle'];
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

                        <!-- Company Description -->
                        <div>
                            <label for="company_description" class="block text-sm font-medium text-black mb-1">Company Description</label>
                            <textarea name="company_description"
                                      id="company_description"
                                      rows="4"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Tell candidates about your company culture, values, and what makes you unique..."><?php echo e(old('company_description', $branding->company_description)); ?></textarea>
                            <?php $__errorArgs = ['company_description'];
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

                        <!-- Benefits Section -->
                        <div>
                            <label for="benefits_text" class="block text-sm font-medium text-black mb-1">Benefits & Perks</label>
                            <textarea name="benefits_text"
                                      id="benefits_text"
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Health insurance, flexible hours, remote work, professional development..."><?php echo e(old('benefits_text', $branding->benefits_text)); ?></textarea>
                            <?php $__errorArgs = ['benefits_text'];
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

                        <!-- Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="contact_email" class="block text-sm font-medium text-black mb-1">Contact Email</label>
                                <input type="email"
                                       name="contact_email"
                                       id="contact_email"
                                       value="<?php echo e(old('contact_email', $branding->contact_email)); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="careers@company.com">
                                <?php $__errorArgs = ['contact_email'];
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
                                <label for="contact_phone" class="block text-sm font-medium text-black mb-1">Contact Phone</label>
                                <input type="tel"
                                       name="contact_phone"
                                       id="contact_phone"
                                       value="<?php echo e(old('contact_phone', $branding->contact_phone)); ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                       placeholder="+91 98765 43210">
                                <?php $__errorArgs = ['contact_phone'];
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

                        <!-- Social Media Links -->
                        <div>
                            <label class="block text-sm font-medium text-black mb-2">Social Media Links</label>
                            <div class="space-y-3">
                                <div>
                                    <label for="linkedin_url" class="block text-xs text-gray-600 mb-1">LinkedIn</label>
                                    <input type="url"
                                           name="linkedin_url"
                                           id="linkedin_url"
                                           value="<?php echo e(old('linkedin_url', $branding->linkedin_url)); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="https://linkedin.com/company/your-company">
                                </div>
                                
                                <div>
                                    <label for="twitter_url" class="block text-xs text-gray-600 mb-1">Twitter/X</label>
                                    <input type="url"
                                           name="twitter_url"
                                           id="twitter_url"
                                           value="<?php echo e(old('twitter_url', $branding->twitter_url)); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="https://twitter.com/yourcompany">
                                </div>
                                
                                <div>
                                    <label for="facebook_url" class="block text-xs text-gray-600 mb-1">Facebook</label>
                                    <input type="url"
                                           name="facebook_url"
                                           id="facebook_url"
                                           value="<?php echo e(old('facebook_url', $branding->facebook_url)); ?>"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="https://facebook.com/yourcompany">
                                </div>
                            </div>
                        </div>

                        <!-- Careers Page Settings -->
                        <div class="border-t pt-6">
                            <h3 class="text-lg font-medium text-black mb-4">Page Settings</h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="show_benefits" 
                                           id="show_benefits" 
                                           value="1" 
                                           <?php echo e(old('show_benefits', $branding->show_benefits) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show_benefits" class="ml-2 block text-sm text-gray-900">
                                        Show benefits section on careers page
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="show_company_info" 
                                           id="show_company_info" 
                                           value="1" 
                                           <?php echo e(old('show_company_info', $branding->show_company_info) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show_company_info" class="ml-2 block text-sm text-gray-900">
                                        Show company description on careers page
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           name="show_social_links" 
                                           id="show_social_links" 
                                           value="1" 
                                           <?php echo e(old('show_social_links', $branding->show_social_links) ? 'checked' : ''); ?>

                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="show_social_links" class="ml-2 block text-sm text-gray-900">
                                        Show social media links
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Preview -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-medium text-black">Live Preview</h3>
                        
                        <!-- Hero Section Preview -->
                        <div class="relative bg-gray-200 rounded-lg overflow-hidden" style="height: 300px;">
                            <?php if($branding->hero_image_path): ?>
                                <img src="<?php echo e(asset('storage/' . $branding->hero_image_path)); ?>" 
                                     alt="Hero Preview" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-r from-blue-500 to-purple-600"></div>
                            <?php endif; ?>
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                            
                            <!-- Content -->
                            <div class="absolute inset-0 flex flex-col justify-center items-center text-white p-6 text-center">
                                <?php if($branding->logo_path): ?>
                                    <img src="<?php echo e(asset('storage/' . $branding->logo_path)); ?>" 
                                         alt="Logo" 
                                         class="h-12 mb-4">
                                <?php endif; ?>
                                
                                <h2 class="text-2xl font-bold mb-2">
                                    <?php echo e($branding->intro_headline ?? 'Join Our Amazing Team'); ?>

                                </h2>
                                
                                <p class="text-lg opacity-90">
                                    <?php echo e($branding->intro_subtitle ?? 'We\'re looking for talented individuals to join our growing team...'); ?>

                                </p>
                                
                                <button class="mt-4 px-6 py-2 rounded-md text-white font-medium"
                                        style="background-color: <?php echo e($branding->primary_color ?? '#4f46e5'); ?>">
                                    View Open Positions
                                </button>
                            </div>
                        </div>

                        <!-- Company Info Preview -->
                        <?php if($branding->show_company_info && $branding->company_description): ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-black mb-2">About Us</h4>
                            <p class="text-sm text-gray-700"><?php echo e(Str::limit($branding->company_description, 150)); ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Benefits Preview -->
                        <?php if($branding->show_benefits && $branding->benefits_text): ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-black mb-2">Benefits & Perks</h4>
                            <p class="text-sm text-gray-700"><?php echo e(Str::limit($branding->benefits_text, 150)); ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Contact Info Preview -->
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-black mb-2">Contact Us</h4>
                            <div class="space-y-1 text-sm text-gray-700">
                                <?php if($branding->contact_email): ?>
                                    <p><strong>Email:</strong> <?php echo e($branding->contact_email); ?></p>
                                <?php endif; ?>
                                <?php if($branding->contact_phone): ?>
                                    <p><strong>Phone:</strong> <?php echo e($branding->contact_phone); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Social Links Preview -->
                        <?php if($branding->show_social_links && ($branding->linkedin_url || $branding->twitter_url || $branding->facebook_url)): ?>
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-black mb-2">Follow Us</h4>
                            <div class="flex space-x-3">
                                <?php if($branding->linkedin_url): ?>
                                    <a href="<?php echo e($branding->linkedin_url); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if($branding->twitter_url): ?>
                                    <a href="<?php echo e($branding->twitter_url); ?>" target="_blank" class="text-blue-400 hover:text-blue-600">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                <?php if($branding->facebook_url): ?>
                                    <a href="<?php echo e($branding->facebook_url); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Preview Actions -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-lg font-medium text-black mb-2">Preview Actions</h4>
                            <div class="space-y-2">
                                <a href="<?php echo e(route('careers.index', $tenant->slug)); ?>" 
                                   target="_blank"
                                   class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    View Live Careers Page
                                </a>
                                <p class="text-xs text-gray-600">Opens in a new tab</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Save Settings
                    </button>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $attributes = $__attributesOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__attributesOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal53747ceb358d30c0105769f8471417f6)): ?>
<?php $component = $__componentOriginal53747ceb358d30c0105769f8471417f6; ?>
<?php unset($__componentOriginal53747ceb358d30c0105769f8471417f6); ?>
<?php endif; ?>
    </div>

    <script>
        // Sync color picker with text input
        document.getElementById('primary_color').addEventListener('input', function() {
            this.nextElementSibling.value = this.value;
        });

        // Image preview functionality
        function previewImage(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById(previewId);
                    if (preview) {
                        preview.src = e.target.result;
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Add preview functionality to file inputs
        document.getElementById('logo').addEventListener('change', function() {
            previewImage(this, 'logo-preview');
        });

        document.getElementById('hero_image').addEventListener('change', function() {
            previewImage(this, 'hero-preview');
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const logoInput = document.getElementById('logo');
            const heroInput = document.getElementById('hero_image');
            
            // Check file sizes
            if (logoInput.files[0] && logoInput.files[0].size > 2 * 1024 * 1024) {
                alert('Logo file size must be less than 2MB');
                e.preventDefault();
                return;
            }
            
            if (heroInput.files[0] && heroInput.files[0].size > 5 * 1024 * 1024) {
                alert('Hero image file size must be less than 5MB');
                e.preventDefault();
                return;
            }
        });

        // Auto-save draft functionality (optional)
        let saveTimeout;
        function autoSave() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // You can implement auto-save functionality here
                console.log('Auto-saving draft...');
            }, 5000);
        }

        // Add auto-save to text inputs
        document.querySelectorAll('input[type="text"], textarea').forEach(input => {
            input.addEventListener('input', autoSave);
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/tenant/settings/careers.blade.php ENDPATH**/ ?>