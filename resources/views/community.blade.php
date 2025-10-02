<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community — TalentLit ATS</title>
    <meta name="description" content="Join the TalentLit community. Connect with other users, share experiences, and get support.">
    <meta name="keywords" content="TalentLit community, ATS users, recruitment community, user forum">
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
            <h1 class="text-4xl md:text-5xl font-bold mb-6">TalentLit Community</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Connect with fellow recruiters, share best practices, and get support from the community
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#join" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Join Community
                </a>
                <a href="#discussions" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200">
                    Browse Discussions
                </a>
            </div>
        </div>
    </section>

    <!-- Community Stats -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Our Growing Community</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Join thousands of recruiters and HR professionals
                </p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">10,000+</div>
                    <div class="text-gray-600">Active Members</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">50,000+</div>
                    <div class="text-gray-600">Discussions</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">95%</div>
                    <div class="text-gray-600">Satisfaction Rate</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">24/7</div>
                    <div class="text-gray-600">Community Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Community Features -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Community Features</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Everything you need to connect and learn
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Discussion Forums -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Discussion Forums</h3>
                    <p class="text-gray-600 mb-6">Ask questions, share experiences, and get advice from fellow recruiters</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• General recruitment discussions</li>
                        <li>• Feature requests and feedback</li>
                        <li>• Best practices sharing</li>
                        <li>• Troubleshooting help</li>
                    </ul>
                </div>
                
                <!-- Knowledge Base -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Knowledge Base</h3>
                    <p class="text-gray-600 mb-6">Access community-contributed guides, tutorials, and resources</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• User-created tutorials</li>
                        <li>• Integration guides</li>
                        <li>• Workflow templates</li>
                        <li>• Industry insights</li>
                    </ul>
                </div>
                
                <!-- Events & Webinars -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Events & Webinars</h3>
                    <p class="text-gray-600 mb-6">Join virtual events, webinars, and networking sessions</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Monthly webinars</li>
                        <li>• Virtual networking events</li>
                        <li>• Product demos</li>
                        <li>• Industry expert talks</li>
                    </ul>
                </div>
                
                <!-- User Groups -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">User Groups</h3>
                    <p class="text-gray-600 mb-6">Connect with users in your industry or region</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Industry-specific groups</li>
                        <li>• Regional meetups</li>
                        <li>• Company size groups</li>
                        <li>• Special interest groups</li>
                    </ul>
                </div>
                
                <!-- Success Stories -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Success Stories</h3>
                    <p class="text-gray-600 mb-6">Read about how others have transformed their hiring process</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Case studies</li>
                        <li>• ROI improvements</li>
                        <li>• Time savings stories</li>
                        <li>• Best practice examples</li>
                    </ul>
                </div>
                
                <!-- Expert Support -->
                <div class="bg-white rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Expert Support</h3>
                    <p class="text-gray-600 mb-6">Get help from TalentLit experts and power users</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Expert Q&A sessions</li>
                        <li>• Power user mentorship</li>
                        <li>• Advanced training</li>
                        <li>• Custom implementation help</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Join Community CTA -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Ready to Join Our Community?</h2>
            <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                Connect with thousands of recruiters and HR professionals who are transforming their hiring process.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-indigo-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                    Join Now - It's Free
                </a>
                <a href="/contact.html" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-indigo-600 transition-colors duration-200">
                    Learn More
                </a>
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
