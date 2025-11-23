<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Shipping and Delivery Policy — TalentLit';
        $seoDescription = 'Learn about TalentLit\'s service delivery and access policy. Understand how you can access our cloud-based recruitment software platform instantly after signup.';
        $seoKeywords = 'shipping policy, delivery policy, service delivery, instant access, TalentLit, ATS, recruitment software';
        $seoAuthor = 'TalentLit';
        $seoImage = asset('logo-talentlit-small.png');
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
                        <img src="{{ asset('logo-talentlit-small.png') }}" alt="TalentLit Logo" class="h-8">
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
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Shipping and Delivery Policy</h1>
                <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <div class="space-y-8">
                    <!-- Introduction -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                        <p class="text-gray-600 leading-relaxed">
                            TalentLit is a cloud-based Software as a Service (SaaS) platform that provides recruitment and applicant tracking solutions. As a digital service, there is no physical shipping involved. This policy explains how our service is delivered, how you can access it, and what to expect regarding service availability and access credentials.
                        </p>
                    </section>

                    <!-- Digital Service Delivery -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Digital Service Delivery</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">No Physical Shipping</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TalentLit is a web-based software platform accessible through your internet browser. We do not ship any physical products, software packages, or hardware. All services are delivered digitally through our cloud infrastructure.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Instant Access</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Upon successful registration and account creation, you will have immediate access to the TalentLit platform. There are no shipping delays or waiting periods for service delivery.
                        </p>
                    </section>

                    <!-- Account Activation and Access -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Account Activation and Access</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Registration Process</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            To access TalentLit services:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Complete the online registration form on our website</li>
                            <li>Verify your email address through the confirmation link sent to your registered email</li>
                            <li>Set up your account credentials (username and password)</li>
                            <li>Complete your organization profile and initial setup</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Access Credentials Delivery</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Your account credentials and access information are delivered electronically:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Email Confirmation:</strong> You will receive an email confirmation immediately after registration</li>
                            <li><strong>Account Access:</strong> Access credentials are created during registration and available instantly</li>
                            <li><strong>Welcome Email:</strong> A welcome email with login instructions is sent to your registered email address</li>
                            <li><strong>Platform URL:</strong> Access the platform at your organization's unique subdomain or through the main TalentLit portal</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Email Delivery Time</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Confirmation and welcome emails are typically delivered within:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Immediate to 5 minutes:</strong> For most email providers</li>
                            <li><strong>Up to 15 minutes:</strong> For some corporate or international email servers</li>
                            <li><strong>Check Spam Folder:</strong> If you don't receive emails, please check your spam or junk folder</li>
                            <li><strong>Contact Support:</strong> If emails are not received within 30 minutes, contact our support team</li>
                        </ul>
                    </section>

                    <!-- Service Availability -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Service Availability</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">24/7 Cloud Access</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TalentLit is hosted on secure cloud infrastructure, providing:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>24/7 availability from anywhere with internet access</li>
                            <li>Access from any device (desktop, laptop, tablet, mobile)</li>
                            <li>No installation required - access through web browsers</li>
                            <li>Automatic updates and feature releases without user intervention</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">System Requirements</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            To access TalentLit, you need:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>A modern web browser (Chrome, Firefox, Safari, Edge, or Opera)</li>
                            <li>Stable internet connection</li>
                            <li>JavaScript enabled in your browser</li>
                            <li>No specific operating system requirements (works on Windows, macOS, Linux, iOS, Android)</li>
                        </ul>
                    </section>

                    <!-- Subscription Activation -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Subscription Activation</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Free Plans</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Free plan subscriptions are activated immediately upon account creation. You can start using the platform right away with the features available in the free tier.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Paid Subscriptions</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            For paid subscription plans:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Immediate Activation:</strong> Upon successful payment processing, your subscription is activated instantly</li>
                            <li><strong>Payment Processing:</strong> Payment processing typically takes a few seconds to a few minutes</li>
                            <li><strong>Access Upgrade:</strong> Premium features become available immediately after payment confirmation</li>
                            <li><strong>Confirmation Email:</strong> You will receive a subscription confirmation email with invoice details</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Trial Periods</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you are using a free trial:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Trial access begins immediately upon trial activation</li>
                            <li>No payment required during the trial period</li>
                            <li>Full access to premium features during the trial</li>
                            <li>Automatic conversion to paid plan at trial end (unless cancelled)</li>
                        </ul>
                    </section>

                    <!-- Data and Content Delivery -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data and Content Delivery</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Real-Time Data Access</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            All data stored in TalentLit is:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Stored securely in the cloud</li>
                            <li>Accessible in real-time from any authorized device</li>
                            <li>Automatically synchronized across all devices</li>
                            <li>Backed up regularly to ensure data safety</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Export and Download</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You can export and download your data at any time:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Export candidate data, resumes, and reports</li>
                            <li>Download data in various formats (CSV, PDF, etc.)</li>
                            <li>No additional charges for data export</li>
                            <li>Data export is available instantly upon request</li>
                        </ul>
                    </section>

                    <!-- Support and Onboarding -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Support and Onboarding</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Welcome Resources</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Upon account creation, you will receive:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Welcome email with getting started guide</li>
                            <li>Access to our knowledge base and documentation</li>
                            <li>Video tutorials and training materials</li>
                            <li>Onboarding checklist to help you get started</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Customer Support</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Our support team is available to assist you:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Email support with response within 24-48 hours</li>
                            <li>In-app support chat for immediate assistance</li>
                            <li>Help documentation and FAQ sections</li>
                            <li>Video tutorials and webinars</li>
                        </ul>
                    </section>

                    <!-- Service Updates and Maintenance -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Service Updates and Maintenance</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Automatic Updates</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TalentLit is continuously updated with new features and improvements:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Updates are delivered automatically - no action required from you</li>
                            <li>New features become available immediately upon release</li>
                            <li>No downtime required for most updates</li>
                            <li>Release notes are provided for major updates</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Scheduled Maintenance</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            For scheduled maintenance that may require brief service interruption:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Advance notice provided via email and in-app notifications</li>
                            <li>Maintenance typically scheduled during low-usage hours</li>
                            <li>Minimal downtime (usually less than 30 minutes)</li>
                            <li>Status page updates during maintenance periods</li>
                        </ul>
                    </section>

                    <!-- Geographic Availability -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Geographic Availability</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            TalentLit is available globally:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Accessible from any country with internet connectivity</li>
                            <li>No geographic restrictions on service delivery</li>
                            <li>Multi-language support available for various regions</li>
                            <li>Compliance with local data protection regulations where applicable</li>
                        </ul>
                    </section>

                    <!-- Changes to Policy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to This Policy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may update this Shipping and Delivery Policy from time to time to reflect changes in our service delivery methods or legal requirements. We will notify you of any material changes by posting the updated policy on our website and updating the "Last updated" date.
                        </p>
                    </section>

                    <!-- Contact Information -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you have any questions about service delivery, account access, or need assistance with accessing TalentLit, please contact us:
                        </p>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-700 mb-2"><strong>Email:</strong> thetalentlit@gmail.com</p>
                            <p class="text-gray-700 mb-2"><strong>Subject Line:</strong> Service Access Inquiry</p>
                            <p class="text-gray-700 mb-2"><strong>Response Time:</strong> We will respond to your inquiry within 24-48 hours</p>
                            <p class="text-gray-700">For urgent access issues, please mention "URGENT" in your email subject line.</p>
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

