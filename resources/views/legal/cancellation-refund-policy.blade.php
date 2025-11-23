<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Cancellation and Refund Policy — TalentLit';
        $seoDescription = 'Learn about TalentLit\'s cancellation and refund policy. Understand how to cancel your subscription and request refunds for our recruitment software platform.';
        $seoKeywords = 'cancellation policy, refund policy, subscription cancellation, TalentLit, ATS, recruitment software';
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
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Cancellation and Refund Policy</h1>
                <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <div class="space-y-8">
                    <!-- Introduction -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                        <p class="text-gray-600 leading-relaxed">
                            At TalentLit ("we," "our," or "us"), we want to ensure transparency and fairness in our cancellation and refund processes. This Cancellation and Refund Policy explains your rights and our procedures regarding subscription cancellations and refunds for our recruitment software platform and related services.
                        </p>
                    </section>

                    <!-- Subscription Cancellation -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Subscription Cancellation</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">How to Cancel</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            You may cancel your subscription at any time through one of the following methods:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Account Settings:</strong> Log into your TalentLit account and navigate to your subscription settings to cancel directly</li>
                            <li><strong>Email Request:</strong> Send a cancellation request to our support team at thetalentlit@gmail.com</li>
                            <li><strong>Support Portal:</strong> Contact our customer support team through the in-app support feature</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Cancellation Effective Date</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Your cancellation will take effect at the end of your current billing period. You will continue to have access to all paid features until the end of the billing cycle for which you have already paid. No partial refunds will be provided for the unused portion of the billing period.
                        </p>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Immediate Cancellation</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you request immediate cancellation, your subscription will be terminated at the end of the current billing period. You will retain access to your account and data until that time. After cancellation, your account will be downgraded to the free tier (if available) or deactivated.
                        </p>
                    </section>

                    <!-- Refund Policy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Refund Policy</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">General Refund Terms</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            All subscription fees are generally non-refundable. However, we may provide refunds in the following circumstances:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Service Failure:</strong> If we are unable to provide the service due to technical issues on our end</li>
                            <li><strong>Billing Error:</strong> If you were charged incorrectly due to an error on our part</li>
                            <li><strong>Duplicate Charges:</strong> If you were charged multiple times for the same subscription period</li>
                            <li><strong>Unauthorized Transaction:</strong> If your payment method was used without your authorization</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">30-Day Money-Back Guarantee</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            For new subscriptions, we offer a 30-day money-back guarantee. If you are not satisfied with our service within the first 30 days of your initial subscription, you may request a full refund. This guarantee applies only to:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>First-time subscribers only</li>
                            <li>Monthly and annual subscription plans</li>
                            <li>Refund requests made within 30 days of the initial subscription date</li>
                            <li>Accounts that have not violated our Terms of Service</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Non-Refundable Items</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            The following are not eligible for refunds:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Subscriptions cancelled after the 30-day guarantee period</li>
                            <li>Partial billing periods (no prorated refunds)</li>
                            <li>Add-on services or premium features purchased separately</li>
                            <li>Accounts terminated due to Terms of Service violations</li>
                            <li>Refund requests made more than 30 days after the initial subscription</li>
                        </ul>
                    </section>

                    <!-- Refund Processing -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Refund Processing</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">How to Request a Refund</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            To request a refund, please contact our support team with the following information:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Your account email address</li>
                            <li>Subscription plan details</li>
                            <li>Reason for the refund request</li>
                            <li>Transaction ID or invoice number</li>
                            <li>Any relevant documentation or screenshots</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Refund Processing Time</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Once your refund request is approved:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Refunds will be processed within 5-10 business days</li>
                            <li>The refund will be issued to the original payment method used</li>
                            <li>You will receive a confirmation email once the refund is processed</li>
                            <li>Bank or credit card processing times may vary (typically 3-5 additional business days)</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Refund Method</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Refunds will be issued to the original payment method used for the subscription. If the original payment method is no longer available, please contact our support team to arrange an alternative refund method.
                        </p>
                    </section>

                    <!-- Free Trial -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Free Trial and Free Plans</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you are using a free trial or free plan:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>No charges will be applied during the free trial period</li>
                            <li>You may cancel at any time during the trial without charges</li>
                            <li>If you do not cancel before the trial ends, you will be automatically charged</li>
                            <li>Free plans do not require cancellation and can be used indefinitely</li>
                        </ul>
                    </section>

                    <!-- Data After Cancellation -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data Retention After Cancellation</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            After cancelling your subscription:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Your account data will be retained for 30 days after cancellation</li>
                            <li>You may export your data during this period</li>
                            <li>After 30 days, your data may be permanently deleted</li>
                            <li>We recommend exporting your data before cancellation if you wish to retain it</li>
                        </ul>
                    </section>

                    <!-- Reinstatement -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Subscription Reinstatement</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you wish to reactivate a cancelled subscription:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>You may reactivate your subscription at any time within 30 days of cancellation</li>
                            <li>Your previous data and settings will be restored</li>
                            <li>You will be charged for a new billing period upon reactivation</li>
                            <li>After 30 days, you may need to create a new account</li>
                        </ul>
                    </section>

                    <!-- Disputes -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Disputes and Chargebacks</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you have a dispute regarding charges or refunds:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Please contact us first at thetalentlit@gmail.com before initiating a chargeback</li>
                            <li>We will work with you to resolve the issue promptly</li>
                            <li>Chargebacks may result in account suspension or termination</li>
                            <li>We reserve the right to dispute chargebacks that violate our Terms of Service</li>
                        </ul>
                    </section>

                    <!-- Changes to Policy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to This Policy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may update this Cancellation and Refund Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by posting the updated policy on our website and updating the "Last updated" date. Your continued use of our service after such changes constitutes acceptance of the new policy.
                        </p>
                    </section>

                    <!-- Contact Information -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you have any questions about this Cancellation and Refund Policy, need to cancel your subscription, or wish to request a refund, please contact us:
                        </p>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-700 mb-2"><strong>Email:</strong> thetalentlit@gmail.com</p>
                            <p class="text-gray-700 mb-2"><strong>Subject Line:</strong> Cancellation/Refund Request</p>
                            <p class="text-gray-700 mb-2"><strong>Response Time:</strong> We will respond to your inquiry within 2-3 business days</p>
                            <p class="text-gray-700">For faster assistance, please include your account email and subscription details in your request.</p>
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

