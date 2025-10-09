<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $seoTitle = 'Privacy Policy — TalentLit';
        $seoDescription = 'Learn How TalentLit protects your privacy and handles your data. Our comprehensive privacy policy outlines our data collection, usage, and protection practices.';
        $seoKeywords = 'privacy policy, data protection, GDPR, privacy, TalentLit, ATS, recruitment software';
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
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
                <p class="text-lg text-gray-600">Last updated: {{ date('F j, Y') }}</p>
            </div>

            <!-- Content -->
            <div class="prose prose-lg max-w-none">
                <div class="space-y-8">
                    <!-- Introduction -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Introduction</h2>
                        <p class="text-gray-600 leading-relaxed">
                            At TalentLit ("we," "our," or "us"), we are committed to protecting your privacy and ensuring the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our recruitment software platform and related services.
                        </p>
                    </section>

                    <!-- Information We Collect -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information We Collect</h2>
                        
                        <h3 class="text-xl font-medium text-gray-800 mb-3">Personal Information</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may collect the following types of personal information:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Name, email address, phone number, and other contact information</li>
                            <li>Company information and job titles</li>
                            <li>Resume/CV files and professional profiles</li>
                            <li>Interview notes and feedback</li>
                            <li>Application data and responses to job-related questions</li>
                            <li>Payment and billing information (processed securely through third-party providers)</li>
                        </ul>

                        <h3 class="text-xl font-medium text-gray-800 mb-3">Usage Information</h3>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We automatically collect certain information about your use of our platform:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>IP address and device information</li>
                            <li>Browser type and version</li>
                            <li>Pages visited and time spent on our platform</li>
                            <li>Click patterns and user interactions</li>
                            <li>Log files and error reports</li>
                        </ul>
                    </section>

                    <!-- How We Use Information -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">How We Use Your Information</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We use the collected information for the following purposes:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>Providing and maintaining our recruitment software services</li>
                            <li>Processing job applications and managing candidate pipelines</li>
                            <li>Facilitating communication between recruiters and candidates</li>
                            <li>Sending important service updates and notifications</li>
                            <li>Improving our platform's functionality and user experience</li>
                            <li>Generating analytics and reports for recruitment insights</li>
                            <li>Ensuring platform security and preventing fraud</li>
                            <li>Complying with legal obligations and industry standards</li>
                        </ul>
                    </section>

                    <!-- Data Sharing -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Information Sharing and Disclosure</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We do not sell, trade, or rent your personal information to third parties. We may share your information in the following circumstances:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Service Providers:</strong> With trusted third-party vendors who assist in operating our platform</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                            <li><strong>Business Transfers:</strong> In connection with mergers, acquisitions, or asset sales</li>
                            <li><strong>Consent:</strong> When you have given explicit consent for specific sharing</li>
                        </ul>
                    </section>

                    <!-- Data Security -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data Security</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We implement industry-standard security measures to protect your information:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li>SSL/TLS encryption for data transmission</li>
                            <li>Secure data storage with encryption at rest</li>
                            <li>Regular security audits and vulnerability assessments</li>
                            <li>Access controls and authentication mechanisms</li>
                            <li>Employee training on data protection best practices</li>
                        </ul>
                    </section>

                    <!-- Your Rights -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Your Rights and Choices</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Depending on your location, you may have the following rights regarding your personal information:
                        </p>
                        <ul class="list-disc list-inside text-gray-600 space-y-2 mb-6">
                            <li><strong>Access:</strong> Request access to your personal information</li>
                            <li><strong>Correction:</strong> Request correction of inaccurate information</li>
                            <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                            <li><strong>Portability:</strong> Request transfer of your data to another service</li>
                            <li><strong>Objection:</strong> Object to certain processing activities</li>
                            <li><strong>Withdrawal:</strong> Withdraw consent for data processing</li>
                        </ul>
                    </section>

                    <!-- Cookies -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Cookies and Tracking Technologies</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We use cookies and similar technologies to enhance your experience on our platform. You can control cookie preferences through your browser settings, but disabling cookies may affect platform functionality.
                        </p>
                    </section>

                    <!-- Data Retention -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Data Retention</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We retain your personal information for as long as necessary to provide our services and fulfill the purposes outlined in this Privacy Policy. We may retain certain information for longer periods to comply with legal obligations or resolve disputes.
                        </p>
                    </section>

                    <!-- International Transfers -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">International Data Transfers</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Your information may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place to protect your information during such transfers, including standard contractual clauses and adequacy decisions.
                        </p>
                    </section>

                    <!-- Children's Privacy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Children's Privacy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            Our services are not intended for individuals under the age of 16. We do not knowingly collect personal information from children under 16. If we become aware of such collection, we will take steps to delete the information promptly.
                        </p>
                    </section>

                    <!-- Changes to Policy -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Changes to This Privacy Policy</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. We will notify you of any material changes by posting the updated policy on our website and updating the "Last updated" date.
                        </p>
                    </section>

                    <!-- Contact Information -->
                    <section>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Contact Us</h2>
                        <p class="text-gray-600 leading-relaxed mb-4">
                            If you have any questions about this Privacy Policy or our data practices, please contact us:
                        </p>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <p class="text-gray-700 mb-2"><strong>Email:</strong> privacy@talentlit.com</p>
                            <p class="text-gray-700 mb-2"><strong>Address:</strong> TalentLit Privacy Team</p>
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
