<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Terms of Service — TalentLit ATS';
        $seoDescription = 'Read TalentLit\'s Terms of Service to understand your rights and responsibilities when using our recruitment software platform.';
        $seoKeywords = 'terms of service, terms and conditions, legal, TalentLit, ATS, recruitment software';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.svg');
    @endphp
    @include('layouts.partials.head')
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
                        <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-8">
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
                                        <a href="{{ route('features.candidate-sourcing') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Candidate Sourcing</div>
                                        </a>
                                        <a href="{{ route('features.career-site') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Career Site</div>
                                        </a>
                                        <a href="{{ route('features.job-advertising') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Job Advertising</div>
                                        </a>
                                        <a href="{{ route('features.employee-referral') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Employee Referral</div>
                                        </a>
                                    </div>
                                </div>
                                
                                <!-- Track Submenu -->
                                <div>
                                    <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3">Track</div>
                                    <div class="space-y-1">
                                        <a href="{{ route('features.hiring-pipeline') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Pipeline</div>
                                        </a>
                                        <a href="{{ route('features.resume-management') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Resume Management</div>
                                        </a>
                                        <a href="{{ route('features.manage-submission') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Manage Submission</div>
                                        </a>
                                        <a href="{{ route('features.hiring-analytics') }}" class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-indigo-600 rounded-md transition-colors duration-200">
                                            <div class="font-medium whitespace-nowrap">Hiring Analytics</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('subscription.pricing') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                            <a href="{{ route('features.candidate-sourcing') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Candidate Sourcing
                            </a>
                            <a href="{{ route('features.hiring-pipeline') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                                Hiring Pipeline
                            </a>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200"></div>
                    
                    <!-- Other Links -->
                    <a href="{{ route('subscription.pricing') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="{{ route('login') }}" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
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
                <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
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

    <!-- Centralized Footer Component -->
    <x-footer />

</body>
</html>
