<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documentation — TalentLit ATS</title>
    <meta name="description" content="Complete documentation for TalentLit ATS. API guides, tutorials, and developer resources.">
    <meta name="keywords" content="TalentLit documentation, ATS API, developer docs, integration guides">
    <meta name="author" content="TalentLit">
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
                        <img src="/logo-talentlit-small.svg" alt="TalentLit Logo" class="h-8">
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="/features.html" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Features
                    </a>
                    <a href="/pricing.html" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Pricing
                    </a>
                    <a href="/about.html" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        About
                    </a>
                    <a href="/login" class="text-gray-500 hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        Sign In
                    </a>
                    <a href="/register" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
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
                    <a href="/features.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Features
                    </a>
                    <a href="/pricing.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Pricing
                    </a>
                    <a href="/about.html" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        About
                    </a>
                    <a href="/login" class="block px-3 py-2 text-base text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-md">
                        Sign In
                    </a>
                    <a href="/register" class="block px-3 py-2 text-base bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 rounded-md">
                        Get Started Free
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-indigo-600 via-purple-600 to-purple-800 py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Documentation</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Everything you need to integrate and customize TalentLit ATS
            </p>
            
            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" placeholder="Search documentation..." class="w-full px-6 py-4 bg-white rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <button class="absolute right-2 top-2 bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                        Search
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Documentation Sections -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Developer Resources</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Comprehensive guides and resources for developers
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- API Reference -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">API Reference</h3>
                    <p class="text-gray-600 mb-6">Complete API documentation with examples and endpoints</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• REST API endpoints</li>
                        <li>• Authentication methods</li>
                        <li>• Request/response examples</li>
                        <li>• Error handling</li>
                    </ul>
                </div>
                
                <!-- SDKs -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">SDKs & Libraries</h3>
                    <p class="text-gray-600 mb-6">Official SDKs for popular programming languages</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• JavaScript/Node.js SDK</li>
                        <li>• Python SDK</li>
                        <li>• PHP SDK</li>
                        <li>• Ruby SDK</li>
                    </ul>
                </div>
                
                <!-- Webhooks -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Webhooks</h3>
                    <p class="text-gray-600 mb-6">Real-time notifications for your applications</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Event types and payloads</li>
                        <li>• Webhook security</li>
                        <li>• Retry mechanisms</li>
                        <li>• Testing webhooks</li>
                    </ul>
                </div>
                
                <!-- Integration Guides -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Integration Guides</h3>
                    <p class="text-gray-600 mb-6">Step-by-step integration tutorials</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• HR system integrations</li>
                        <li>• Calendar integrations</li>
                        <li>• Email service setup</li>
                        <li>• Single sign-on (SSO)</li>
                    </ul>
                </div>
                
                <!-- Best Practices -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Best Practices</h3>
                    <p class="text-gray-600 mb-6">Recommended approaches for optimal performance</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Rate limiting guidelines</li>
                        <li>• Data handling best practices</li>
                        <li>• Security recommendations</li>
                        <li>• Performance optimization</li>
                    </ul>
                </div>
                
                <!-- Changelog -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Changelog</h3>
                    <p class="text-gray-600 mb-6">Track API changes and new features</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Version history</li>
                        <li>• Breaking changes</li>
                        <li>• New features</li>
                        <li>• Deprecation notices</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Start -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Quick Start Guide</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Get up and running with TalentLit API in minutes
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-2xl p-8 shadow-lg">
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">1. Get Your API Key</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <code class="text-green-400">curl -X POST https://api.talentlit.com/v1/auth/token \
  -H "Content-Type: application/json" \
  -d '{"email": "your@email.com", "password": "your-password"}'</code>
                    </div>
                    
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">2. Create Your First Job</h3>
                    <div class="bg-gray-900 rounded-lg p-4 mb-6">
                        <code class="text-green-400">curl -X POST https://api.talentlit.com/v1/jobs \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{"title": "Software Engineer", "description": "Join our team...", "location": "Remote"}'</code>
                    </div>
                    
                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">3. Retrieve Candidates</h3>
                    <div class="bg-gray-900 rounded-lg p-4">
                        <code class="text-green-400">curl -X GET https://api.talentlit.com/v1/jobs/JOB_ID/candidates \
  -H "Authorization: Bearer YOUR_API_KEY"</code>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <!-- Main Footer Content -->
            <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
                <!-- Brand Section -->
                <div class="lg:col-span-2">
                    <div class="flex items-center space-x-2 mb-6">
                        <img src="/logo-talentlit-small.svg" alt="TalentLit Logo" class="h-10">
                        <span class="text-xl font-bold">TalentLit</span>
                    </div>
                    <p class="text-gray-400 mb-6 max-w-md text-lg leading-relaxed">
                        The modern ATS that helps you hire smarter, faster, and more efficiently. 
                        Streamline your recruitment process today.
                    </p>
                </div>
                
                <!-- Product Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Product</h3>
                    <ul class="space-y-3">
                        <li><a href="/features.html" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                        <li><a href="/pricing.html" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                        <li><a href="/features/candidate-sourcing.html" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                        <li><a href="/features/hiring-pipeline.html" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                        <li><a href="/features/hiring-analytics.html" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                    </ul>
                </div>
                
                <!-- Company Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                    <ul class="space-y-3">
                        <li><a href="/about.html" class="text-gray-400 hover:text-white transition-colors text-sm">About Us</a></li>
                        <li><a href="/careers.html" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                        <li><a href="/blog.html" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                        <li><a href="/contact.html" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                        <li><a href="/press.html" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
                    </ul>
                </div>
                
                <!-- Support Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-6 text-white">Support</h3>
                    <ul class="space-y-3">
                        <li><a href="/help-center.html" class="text-gray-400 hover:text-white transition-colors text-sm">Help Center</a></li>
                        <li><a href="/documentation.html" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                        <li><a href="/community.html" class="text-gray-400 hover:text-white transition-colors text-sm">Community</a></li>
                        <li><a href="/status.html" class="text-gray-400 hover:text-white transition-colors text-sm">Status</a></li>
                        <li><a href="/security.html" class="text-gray-400 hover:text-white transition-colors text-sm">Security</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Newsletter Signup -->
            <div class="bg-gray-800 rounded-2xl p-8 mb-12">
                <div class="max-w-2xl mx-auto text-center">
                    <h3 class="text-2xl font-bold text-white mb-4">Stay Updated</h3>
                    <p class="text-gray-400 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
                    <form id="newsletter-form" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                        <input type="email" id="newsletter-email" placeholder="Enter your email" class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                        <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Bottom Footer -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm mb-4 md:mb-0">
                        © 2023 TalentLit. All rights reserved.
                    </p>
                    <div class="flex space-x-6">
                        <a href="/privacy-policy.html" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                        <a href="/terms-of-service.html" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                        <a href="/cookie-policy.html" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Newsletter subscription
        document.getElementById('newsletter-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const email = document.getElementById('newsletter-email').value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            
            // Show loading state
            button.textContent = 'Subscribing...';
            button.disabled = true;
            
            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ email: email })
                });
                
                if (response.ok) {
                    button.textContent = 'Subscribed!';
                    document.getElementById('newsletter-email').value = '';
                    setTimeout(() => {
                        button.textContent = originalText;
                        button.disabled = false;
                    }, 2000);
                } else {
                    throw new Error('Subscription failed');
                }
            } catch (error) {
                button.textContent = 'Try Again';
                setTimeout(() => {
                    button.textContent = originalText;
                    button.disabled = false;
                }, 2000);
            }
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/documentation.blade.php ENDPATH**/ ?>