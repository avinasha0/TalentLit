

<?php $__env->startSection('title', 'Frequently Asked Questions — TalentLit'); ?>
<?php $__env->startSection('description', 'Find answers to common questions about TalentLit ATS. Get help with account setup, features, and troubleshooting.'); ?>

<?php
    $seoTitle = 'Frequently Asked Questions — TalentLit ATS';
    $seoDescription = 'Find answers to common questions about TalentLit ATS. Get help with account setup, features, and troubleshooting.';
    $seoKeywords = 'TalentLit FAQ, ATS questions, recruitment software help, common issues, support';
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
                    <a href="<?php echo e(route('home')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Home
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
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
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 1000 1000" fill="none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)"/>
            </svg>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Frequently Asked Questions
                </h1>
                <p class="text-xl text-indigo-100 max-w-3xl mx-auto">
                    Find quick answers to the most common questions about TalentLit ATS
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ Content -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-8">
                
                <!-- Getting Started -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Getting Started</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I create an account?</h3>
                            <p class="text-gray-600">Click the "Get Started Free" button on our homepage, fill out the registration form with your organization details, and verify your email address. You'll be guided through the setup process.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a free trial?</h3>
                            <p class="text-gray-600">Yes! We offer a free trial with full access to all features. No credit card required to get started.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">How many users can I invite to my organization?</h3>
                            <p class="text-gray-600">Our free plan includes up to 5 team members. Paid plans offer unlimited team members with different feature tiers.</p>
                        </div>
                    </div>
                </div>

                <!-- Features & Functionality -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Features & Functionality</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I customize the application process?</h3>
                            <p class="text-gray-600">Yes! You can create custom application forms with specific questions, required fields, and file uploads tailored to each job posting.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Does TalentLit integrate with job boards?</h3>
                            <p class="text-gray-600">Yes, we integrate with major job boards including Indeed, LinkedIn, and Glassdoor. You can also post to multiple boards simultaneously.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I schedule interviews through the platform?</h3>
                            <p class="text-gray-600">Absolutely! You can schedule interviews, send calendar invites, and manage your interview pipeline all within TalentLit.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a mobile app?</h3>
                            <p class="text-gray-600">TalentLit is fully responsive and works great on mobile devices. We're also developing dedicated mobile apps for iOS and Android.</p>
                        </div>
                    </div>
                </div>

                <!-- Data & Security -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Data & Security</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is my data secure?</h3>
                            <p class="text-gray-600">Yes, we use enterprise-grade security including SSL encryption, secure data centers, and regular security audits. Your data is protected and never shared with third parties.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I export my data?</h3>
                            <p class="text-gray-600">Yes, you can export all your data including candidate information, job postings, and analytics at any time in various formats (CSV, PDF, etc.).</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you comply with GDPR?</h3>
                            <p class="text-gray-600">Yes, we're fully GDPR compliant and provide tools to help you manage candidate data privacy and consent.</p>
                        </div>
                    </div>
                </div>

                <!-- Billing & Plans -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Billing & Plans</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What payment methods do you accept?</h3>
                            <p class="text-gray-600">We accept all major credit cards, PayPal, and bank transfers for annual subscriptions.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Can I change my plan anytime?</h3>
                            <p class="text-gray-600">Yes, you can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any billing differences.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Is there a setup fee?</h3>
                            <p class="text-gray-600">No setup fees! You only pay for your chosen subscription plan.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What's your refund policy?</h3>
                            <p class="text-gray-600">We offer a 30-day money-back guarantee. If you're not satisfied, contact us for a full refund.</p>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Support</h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">How can I get help?</h3>
                            <p class="text-gray-600">You can reach us through our help center, email support, or live chat. We also provide comprehensive documentation and video tutorials.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">What are your support hours?</h3>
                            <p class="text-gray-600">Our support team is available Monday-Friday, 9 AM - 6 PM EST. Premium customers get priority support and extended hours.</p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Do you offer training?</h3>
                            <p class="text-gray-600">Yes! We provide onboarding sessions, webinars, and custom training for teams. Contact us to schedule a session.</p>
                        </div>
                    </div>
                </div>

                <!-- Still have questions? -->
                <div class="bg-indigo-50 p-8 rounded-lg">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Still have questions?</h2>
                    <p class="text-gray-600 mb-6">Can't find what you're looking for? Our support team is here to help.</p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="<?php echo e(route('help.page', 'contact')); ?>" class="bg-indigo-600 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors">
                            Contact Support
                        </a>
                        <a href="<?php echo e(route('help.index')); ?>" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-medium border border-indigo-600 hover:bg-indigo-50 transition-colors">
                            Browse Help Center
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.help', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\help\faq.blade.php ENDPATH**/ ?>