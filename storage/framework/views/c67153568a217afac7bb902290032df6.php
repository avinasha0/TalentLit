<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        $seoTitle = 'Terms of Service — TalentLit ATS';
        $seoDescription = 'Read TalentLit\'s Terms of Service to understand your rights and responsibilities when using our recruitment software platform.';
        $seoKeywords = 'terms of service, terms and conditions, legal, TalentLit, ATS, recruitment software';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    ?>
    <?php echo $__env->make('layouts.partials.head', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-white">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-8">
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
                <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-gray-200">
                    <!-- Features Section -->
                    <div class="px-3 py-2">
                        <div class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Features</div>
                        <div class="space-y-1">
                            <a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Candidate Sourcing
                            </a>
                            <a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="<?php echo e(route('subscription.pricing')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="<?php echo e(route('register')); ?>" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Terms of Service</h1>
                <p class="text-lg text-gray-600">Last updated: <?php echo e(date('F j, Y')); ?></p>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <div class="space-y-8">
                    <!-- Introduction -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Agreement to Terms</h2>
                        <p class="text-gray-600 leading-relaxed">
                            These Terms of Service ("Terms") govern your use of TalentLit's recruitment software platform and related services ("Service"). By accessing or using our Service, you agree to be bound by these Terms. If you disagree with any part of these terms, you may not access the Service.
                        </p>
                    </section>

                    <!-- Service Description -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Service Description</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TalentLit provides a comprehensive applicant tracking system (ATS) that enables organizations to:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Post and manage job openings</li>
                            <li>Track candidate applications and resumes</li>
                            <li>Schedule and conduct interviews</li>
                            <li>Collaborate on hiring decisions</li>
                            <li>Generate recruitment analytics and reports</li>
                            <li>Integrate with third-party recruitment tools</li>
                        </ul>
                    </section>

                    <!-- User Accounts -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">User Accounts and Registration</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Account Creation</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            To access our Service, you must create an account by providing accurate and complete information. You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Account Responsibilities</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Provide accurate and up-to-date information</li>
                            <li>Maintain the security of your password</li>
                            <li>Notify us immediately of any unauthorized use</li>
                            <li>Comply with all applicable laws and regulations</li>
                            <li>Use the Service only for lawful business purposes</li>
                        </ul>
                    </section>

                    <!-- Acceptable Use -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Acceptable Use Policy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You agree not to use the Service for any unlawful purpose or in any way that could damage, disable, overburden, or impair our servers or networks. Prohibited activities include:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Violating any applicable laws or regulations</li>
                            <li>Infringing on intellectual property rights</li>
                            <li>Transmitting malicious code or harmful content</li>
                            <li>Attempting to gain unauthorized access to our systems</li>
                            <li>Interfering with other users' enjoyment of the Service</li>
                            <li>Using automated systems to access the Service without permission</li>
                            <li>Collecting personal information of other users</li>
                        </ul>
                    </section>

                    <!-- Data and Privacy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data and Privacy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Your privacy is important to us. Our collection and use of personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference. By using our Service, you consent to the collection and use of information as described in our Privacy Policy.
                        </p>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Data Ownership</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You retain ownership of all data you upload to our Service. We do not claim ownership of your content, but you grant us a limited license to use, store, and process your data to provide the Service.
                        </p>
                    </section>

                    <!-- Payment Terms -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Payment Terms</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Subscription Fees</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Some features of our Service require a paid subscription. Subscription fees are billed in advance on a monthly or annual basis. All fees are non-refundable unless otherwise specified.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Payment Processing</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We use third-party payment processors to handle payments. By providing payment information, you authorize us to charge your account for the applicable fees.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Free Trial</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may offer free trials or free tiers of our Service. Free accounts are subject to usage limitations as specified in our pricing plans.
                        </p>
                    </section>

                    <!-- Intellectual Property -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Intellectual Property Rights</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Our Rights</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            The Service and its original content, features, and functionality are owned by TalentLit and are protected by international copyright, trademark, patent, trade secret, and other intellectual property laws.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Your Rights</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You retain ownership of content you upload to our Service. By uploading content, you grant us a limited, non-exclusive license to use, store, and process your content to provide the Service.
                        </p>
                    </section>

                    <!-- Service Availability -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Service Availability</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We strive to maintain high service availability, but we do not guarantee uninterrupted access to the Service. We may temporarily suspend or restrict access for maintenance, updates, or other operational reasons.
                        </p>
                    </section>

                    <!-- Termination -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Termination</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Termination by You</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You may terminate your account at any time by contacting our support team. Upon termination, your access to the Service will cease, and your data may be deleted according to our data retention policy.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Termination by Us</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may terminate or suspend your account immediately, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, us, or third parties.
                        </p>
                    </section>

                    <!-- Disclaimers -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Disclaimers</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            THE SERVICE IS PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED. WE DISCLAIM ALL WARRANTIES, INCLUDING BUT NOT LIMITED TO WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.
                        </p>
                    </section>

                    <!-- Limitation of Liability -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Limitation of Liability</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TO THE MAXIMUM EXTENT PERMITTED BY LAW, TALENTLIT SHALL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO LOSS OF PROFITS, DATA, OR USE, ARISING OUT OF OR RELATING TO YOUR USE OF THE SERVICE.
                        </p>
                    </section>

                    <!-- Indemnification -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Indemnification</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You agree to indemnify and hold harmless TalentLit and its officers, directors, employees, and agents from any claims, damages, losses, or expenses (including reasonable attorneys' fees) arising out of or relating to your use of the Service or violation of these Terms.
                        </p>
                    </section>

                    <!-- Governing Law -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Governing Law</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            These Terms shall be governed by and construed in accordance with the laws of [Jurisdiction], without regard to its conflict of law provisions. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts in [Jurisdiction].
                        </p>
                    </section>

                    <!-- Changes to Terms -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to Terms</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We reserve the right to modify these Terms at any time. We will notify users of material changes by posting the updated Terms on our website and updating the "Last updated" date. Your continued use of the Service after such changes constitutes acceptance of the new Terms.
                        </p>
                    </section>

                    <!-- Contact Information -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Information</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you have any questions about these Terms of Service, please contact us:
                        </p>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-700 mb-2"><strong>Email:</strong> legal@talentlit.com</p>
                            <p class="text-gray-700 mb-2"><strong>Address:</strong> TalentLit Legal Team</p>
                            <p class="text-gray-700">We will respond to your inquiry within 30 days.</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <img src="<?php echo e(asset('logo-talentlit-small.svg')); ?>" alt="TalentLit Logo" class="h-10">
                        <span class="text-xl font-bold">TalentLit</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md text-lg leading-relaxed">
                        The modern ATS that helps you hire smarter, faster, and more efficiently. 
                        Streamline your recruitment process today.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Product Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('features')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="<?php echo e(route('subscription.pricing')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="<?php echo e(route('features.candidate-sourcing')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                        <li><a href="<?php echo e(route('features.hiring-pipeline')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                        <li><a href="<?php echo e(route('features.hiring-analytics')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                    </ul>
                </div>
                
                <!-- Company Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="<?php echo e(route('about')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="<?php echo e(route('careers')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="<?php echo e(route('blog')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="<?php echo e(route('contact')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                        <li><a href="<?php echo e(route('press')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
                    </ul>
                </div>
                
                <!-- Support Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Community</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Status</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="bg-gray-800 rounded-2xl p-8 mb-12">
                <div class="max-w-2xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">Stay Updated</h3>
                    <p class="text-gray-400 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
                    <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">
                        © <?php echo e(date('Y')); ?> TalentLit. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="<?php echo e(route('privacy-policy')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                        <a href="<?php echo e(route('terms-of-service')); ?>" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/legal/terms-of-service.blade.php ENDPATH**/ ?>