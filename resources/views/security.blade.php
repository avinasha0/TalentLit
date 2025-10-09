<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security — TalentLit ATS</title>
    <meta name="description" content="Learn about TalentLit's security measures, compliance, and data protection practices.">
    <meta name="keywords" content="TalentLit security, ATS security, data protection, compliance, SOC2, GDPR">
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
                        <img src="{{ asset('logo-talentlit-small.png') }}" alt="TalentLit Logo" class="h-8">
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
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Security & Compliance</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Your data security is our top priority. Learn about our comprehensive security measures and compliance standards.
            </p>
            
            <!-- Security Badges -->
            <div class="flex flex-wrap justify-center gap-6 mt-12">
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3">
                    <span class="text-lg font-semibold">SOC 2 Type II</span>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3">
                    <span class="text-lg font-semibold">GDPR Compliant</span>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3">
                    <span class="text-lg font-semibold">ISO 27001</span>
                </div>
                <div class="bg-white bg-opacity-10 backdrop-blur-sm rounded-lg px-6 py-3">
                    <span class="text-lg font-semibold">256-bit SSL</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Overview -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Security Overview</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Multi-layered security approach protecting your sensitive recruitment data
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Data Encryption -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Data Encryption</h3>
                    <p class="text-gray-600 mb-6">All data is encrypted in transit and at rest using industry-standard encryption</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• AES-256 encryption at rest</li>
                        <li>• TLS 1.3 for data in transit</li>
                        <li>• Encrypted database backups</li>
                        <li>• Secure key management</li>
                    </ul>
                </div>
                
                <!-- Access Control -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Access Control</h3>
                    <p class="text-gray-600 mb-6">Granular access controls and authentication mechanisms</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• Multi-factor authentication</li>
                        <li>• Role-based access control</li>
                        <li>• Single sign-on (SSO)</li>
                        <li>• Session management</li>
                    </ul>
                </div>
                
                <!-- Infrastructure Security -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Infrastructure Security</h3>
                    <p class="text-gray-600 mb-6">Secure cloud infrastructure with enterprise-grade security</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• AWS/Azure certified infrastructure</li>
                        <li>• Network security and firewalls</li>
                        <li>• DDoS protection</li>
                        <li>• Regular security audits</li>
                    </ul>
                </div>
                
                <!-- Data Privacy -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Data Privacy</h3>
                    <p class="text-gray-600 mb-6">Comprehensive data privacy and protection measures</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• GDPR compliance</li>
                        <li>• Data retention policies</li>
                        <li>• Right to be forgotten</li>
                        <li>• Privacy by design</li>
                    </ul>
                </div>
                
                <!-- Monitoring & Logging -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Monitoring & Logging</h3>
                    <p class="text-gray-600 mb-6">Continuous monitoring and comprehensive audit logging</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• 24/7 security monitoring</li>
                        <li>• Comprehensive audit logs</li>
                        <li>• Intrusion detection</li>
                        <li>• Real-time alerts</li>
                    </ul>
                </div>
                
                <!-- Compliance -->
                <div class="bg-white border border-gray-200 rounded-2xl p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="w-16 h-16 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Compliance</h3>
                    <p class="text-gray-600 mb-6">Meeting industry standards and regulatory requirements</p>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li>• SOC 2 Type II certified</li>
                        <li>• ISO 27001 compliant</li>
                        <li>• GDPR ready</li>
                        <li>• Regular compliance audits</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Compliance Certifications -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Compliance & Certifications</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We maintain the highest standards of security and compliance
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">SOC 2 Type II</h3>
                    <p class="text-gray-600">Independently audited security controls and processes</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">ISO 27001</h3>
                    <p class="text-gray-600">International standard for information security management</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">GDPR Ready</h3>
                    <p class="text-gray-600">Full compliance with European data protection regulations</p>
                </div>
                
                <div class="bg-white rounded-2xl p-8 text-center">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">CCPA Compliant</h3>
                    <p class="text-gray-600">California Consumer Privacy Act compliance</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Security Contact -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Security Contact</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Have security concerns or questions? We're here to help.
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Report Security Issues</h3>
                            <p class="text-gray-600 mb-6">Found a security vulnerability? Please report it responsibly.</p>
                            <a href="mailto:security@talentlit.com" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">
                                security@talentlit.com
                            </a>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-4">Security Questions</h3>
                            <p class="text-gray-600 mb-6">Have questions about our security practices?</p>
                            <a href="/contact.html" class="text-indigo-600 font-semibold hover:text-indigo-700 transition-colors">
                                Contact Security Team
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Centralized Footer Component -->
    <x-footer />


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
                // Validate reCAPTCHA before proceeding
                if (!window.validateRecaptcha()) {
                    button.textContent = originalText;
                    button.disabled = false;
                    return;
                }
                
                // Get reCAPTCHA response
                const recaptchaResponse = window.getRecaptchaResponse();

                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ 
                        email: email,
                        'g-recaptcha-response': recaptchaResponse
                    })
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
