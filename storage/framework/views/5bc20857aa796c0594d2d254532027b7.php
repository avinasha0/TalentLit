<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Status — TalentLit ATS</title>
    <meta name="description" content="Check the current status of TalentLit ATS services and infrastructure.">
    <meta name="keywords" content="TalentLit status, ATS uptime, service status, system health">
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
            <h1 class="text-4xl md:text-5xl font-bold mb-6">System Status</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Real-time status of TalentLit ATS services and infrastructure
            </p>
            
            <!-- Overall Status -->
            <div class="max-w-2xl mx-auto">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-2xl p-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-2xl font-semibold">All Systems Operational</span>
                    </div>
                    <p class="text-indigo-100">Last updated: <span id="last-updated">Just now</span></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Status -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Service Status</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Current status of all TalentLit services
                </p>
            </div>
            
            <div class="space-y-6">
                <!-- Core Application -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-semibold text-gray-900">Core Application</h3>
                        </div>
                        <span class="text-green-600 font-semibold">Operational</span>
                    </div>
                    <p class="text-gray-600 mb-4">Main TalentLit ATS platform and user interface</p>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response Time:</span>
                            <span class="text-gray-900 font-semibold ml-2">45ms</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <span class="text-gray-900 font-semibold ml-2">99.9%</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Incident:</span>
                            <span class="text-gray-900 font-semibold ml-2">None</span>
                        </div>
                    </div>
                </div>
                
                <!-- API Services -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-semibold text-gray-900">API Services</h3>
                        </div>
                        <span class="text-green-600 font-semibold">Operational</span>
                    </div>
                    <p class="text-gray-600 mb-4">REST API endpoints and webhook services</p>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response Time:</span>
                            <span class="text-gray-900 font-semibold ml-2">32ms</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <span class="text-gray-900 font-semibold ml-2">99.95%</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Incident:</span>
                            <span class="text-gray-900 font-semibold ml-2">None</span>
                        </div>
                    </div>
                </div>
                
                <!-- Database -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-semibold text-gray-900">Database</h3>
                        </div>
                        <span class="text-green-600 font-semibold">Operational</span>
                    </div>
                    <p class="text-gray-600 mb-4">Primary and replica database services</p>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response Time:</span>
                            <span class="text-gray-900 font-semibold ml-2">8ms</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <span class="text-gray-900 font-semibold ml-2">99.99%</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Incident:</span>
                            <span class="text-gray-900 font-semibold ml-2">None</span>
                        </div>
                    </div>
                </div>
                
                <!-- Email Services -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-semibold text-gray-900">Email Services</h3>
                        </div>
                        <span class="text-green-600 font-semibold">Operational</span>
                    </div>
                    <p class="text-gray-600 mb-4">Email notifications and communication services</p>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response Time:</span>
                            <span class="text-gray-900 font-semibold ml-2">120ms</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <span class="text-gray-900 font-semibold ml-2">99.8%</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Incident:</span>
                            <span class="text-gray-900 font-semibold ml-2">None</span>
                        </div>
                    </div>
                </div>
                
                <!-- File Storage -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-3"></div>
                            <h3 class="text-xl font-semibold text-gray-900">File Storage</h3>
                        </div>
                        <span class="text-green-600 font-semibold">Operational</span>
                    </div>
                    <p class="text-gray-600 mb-4">Resume and document storage services</p>
                    <div class="grid md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Response Time:</span>
                            <span class="text-gray-900 font-semibold ml-2">85ms</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Uptime:</span>
                            <span class="text-gray-900 font-semibold ml-2">99.9%</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Incident:</span>
                            <span class="text-gray-900 font-semibold ml-2">None</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Performance Metrics -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Performance Metrics</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Real-time performance data for the last 30 days
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">99.9%</div>
                    <div class="text-gray-600 mb-2">Overall Uptime</div>
                    <div class="text-sm text-gray-500">Last 30 days</div>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">45ms</div>
                    <div class="text-gray-600 mb-2">Avg Response Time</div>
                    <div class="text-sm text-gray-500">Last 30 days</div>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="text-3xl font-bold text-purple-600 mb-2">0</div>
                    <div class="text-gray-600 mb-2">Incidents</div>
                    <div class="text-sm text-gray-500">Last 30 days</div>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="text-3xl font-bold text-indigo-600 mb-2">24/7</div>
                    <div class="text-gray-600 mb-2">Monitoring</div>
                    <div class="text-sm text-gray-500">Always active</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Incident History -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Recent Incidents</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    No incidents reported in the last 30 days
                </p>
            </div>
            
            <div class="bg-gray-50 rounded-2xl p-8 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-4">All Systems Running Smoothly</h3>
                <p class="text-gray-600 mb-6">We haven't experienced any service disruptions in the past 30 days.</p>
                <a href="/contact.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">
                    Report an Issue →
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
        // Update last updated time
        function updateLastUpdated() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            document.getElementById('last-updated').textContent = timeString;
        }
        
        // Update every minute
        setInterval(updateLastUpdated, 60000);
        updateLastUpdated();

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
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/status.blade.php ENDPATH**/ ?>