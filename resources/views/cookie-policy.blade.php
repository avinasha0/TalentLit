<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Policy — TalentLit ATS</title>
    <meta name="description" content="Learn about how TalentLit uses cookies and similar technologies to enhance your experience.">
    <meta name="keywords" content="TalentLit cookies, cookie policy, privacy, data collection">
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
                        <img src="/logo-talentlit-small.png" alt="TalentLit Logo" class="h-8">
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
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Cookie Policy</h1>
            <p class="text-xl text-indigo-100 max-w-3xl mx-auto mb-8">
                Learn about how TalentLit uses cookies and similar technologies to enhance your experience
            </p>
            <p class="text-indigo-200">Last updated: December 2023</p>
        </div>
    </section>

    <!-- Content Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">What Are Cookies?</h2>
                <p class="text-gray-600 mb-8">
                    Cookies are small text files that are placed on your computer or mobile device when you visit our website. 
                    They help us provide you with a better experience by remembering your preferences and enabling certain 
                    functionality on our site.
                </p>

                <h2 class="text-3xl font-bold text-gray-900 mb-6">How We Use Cookies</h2>
                <p class="text-gray-600 mb-8">
                    TalentLit uses cookies for several purposes to improve your experience on our website and services:
                </p>

                <div class="space-y-8">
                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Essential Cookies</h3>
                        <p class="text-gray-600 mb-4">
                            These cookies are necessary for the website to function properly. They enable basic functions like 
                            page navigation, access to secure areas, and remembering your login status.
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Session management and authentication</li>
                            <li>Security and fraud prevention</li>
                            <li>Load balancing and performance</li>
                            <li>Remembering your preferences</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Analytics Cookies</h3>
                        <p class="text-gray-600 mb-4">
                            These cookies help us understand how visitors interact with our website by collecting and 
                            reporting information anonymously.
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Page views and user behavior</li>
                            <li>Website performance metrics</li>
                            <li>Error tracking and debugging</li>
                            <li>Feature usage statistics</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Marketing Cookies</h3>
                        <p class="text-gray-600 mb-4">
                            These cookies are used to track visitors across websites to display relevant and engaging 
                            advertisements.
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Advertising personalization</li>
                            <li>Campaign performance tracking</li>
                            <li>Social media integration</li>
                            <li>Retargeting and remarketing</li>
                        </ul>
                    </div>

                    <div class="bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Functional Cookies</h3>
                        <p class="text-gray-600 mb-4">
                            These cookies enable enhanced functionality and personalization, such as remembering your 
                            language preference or region.
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Language and region preferences</li>
                            <li>User interface customization</li>
                            <li>Chat and support features</li>
                            <li>Third-party integrations</li>
                        </ul>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Managing Your Cookie Preferences</h2>
                <p class="text-gray-600 mb-8">
                    You have control over cookies. You can choose to accept or decline cookies when you first visit our 
                    website, and you can change your preferences at any time.
                </p>

                <div class="bg-blue-50 rounded-2xl p-8 mb-8">
                    <h3 class="text-xl font-semibold text-blue-900 mb-4">Cookie Settings</h3>
                    <p class="text-blue-800 mb-4">
                        You can manage your cookie preferences through your browser settings or by using our cookie 
                        preference center.
                    </p>
                    <button class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                        Manage Cookie Preferences
                    </button>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6">Third-Party Cookies</h2>
                <p class="text-gray-600 mb-8">
                    Some cookies on our website are set by third-party services that appear on our pages. These include:
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Service</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Purpose</th>
                                <th class="border border-gray-300 px-4 py-3 text-left font-semibold">More Information</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-3">Google Analytics</td>
                                <td class="border border-gray-300 px-4 py-3">Website analytics</td>
                                <td class="border border-gray-300 px-4 py-3">
                                    <a href="https://policies.google.com/privacy" class="text-indigo-600 hover:text-indigo-700">Google Privacy Policy</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-3">Intercom</td>
                                <td class="border border-gray-300 px-4 py-3">Customer support</td>
                                <td class="border border-gray-300 px-4 py-3">
                                    <a href="https://www.intercom.com/privacy" class="text-indigo-600 hover:text-indigo-700">Intercom Privacy Policy</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-3">HubSpot</td>
                                <td class="border border-gray-300 px-4 py-3">Marketing automation</td>
                                <td class="border border-gray-300 px-4 py-3">
                                    <a href="https://legal.hubspot.com/privacy-policy" class="text-indigo-600 hover:text-indigo-700">HubSpot Privacy Policy</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Browser Settings</h2>
                <p class="text-gray-600 mb-8">
                    Most web browsers allow you to control cookies through their settings preferences. However, if you 
                    limit the ability of websites to set cookies, you may worsen your overall user experience, as it 
                    will no longer be personalized to you.
                </p>

                <div class="grid md:grid-cols-2 gap-8">
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Chrome</h3>
                        <p class="text-gray-600 mb-4">Settings → Privacy and security → Cookies and other site data</p>
                        <a href="https://support.google.com/chrome/answer/95647" class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Learn more →
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Firefox</h3>
                        <p class="text-gray-600 mb-4">Options → Privacy & Security → Cookies and Site Data</p>
                        <a href="https://support.mozilla.org/en-US/kb/enhanced-tracking-protection-firefox-desktop" class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Learn more →
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Safari</h3>
                        <p class="text-gray-600 mb-4">Preferences → Privacy → Manage Website Data</p>
                        <a href="https://support.apple.com/guide/safari/manage-cookies-and-website-data-sfri11471/mac" class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Learn more →
                        </a>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Edge</h3>
                        <p class="text-gray-600 mb-4">Settings → Cookies and site permissions → Cookies and site data</p>
                        <a href="https://support.microsoft.com/en-us/microsoft-edge/delete-cookies-in-microsoft-edge-63947406-40ac-c3b8-57b9-2a946a29ae09" class="text-indigo-600 hover:text-indigo-700 text-sm">
                            Learn more →
                        </a>
                    </div>
                </div>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Updates to This Policy</h2>
                <p class="text-gray-600 mb-8">
                    We may update this Cookie Policy from time to time to reflect changes in our practices or for other 
                    operational, legal, or regulatory reasons. We will notify you of any material changes by posting the 
                    new Cookie Policy on this page.
                </p>

                <h2 class="text-3xl font-bold text-gray-900 mb-6">Contact Us</h2>
                <p class="text-gray-600 mb-8">
                    If you have any questions about this Cookie Policy, please contact us:
                </p>
                
                <div class="bg-gray-50 rounded-2xl p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Email</h3>
                            <a href="mailto:privacy@talentlit.com" class="text-indigo-600 hover:text-indigo-700">
                                privacy@talentlit.com
                            </a>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">General Contact</h3>
                            <a href="/contact.html" class="text-indigo-600 hover:text-indigo-700">
                                Contact Us
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
