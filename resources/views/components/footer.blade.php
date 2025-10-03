<!-- Centralized Footer Component -->
<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Main Footer Content -->
        <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-8 mb-12">
            <!-- Brand Section -->
            <div class="lg:col-span-2">
                <div class="flex items-center space-x-2 mb-6">
                    <img src="{{ asset('logo-talentlit-small.svg') }}" alt="TalentLit Logo" class="h-10">
                </div>
                <p class="text-gray-400 mb-6 max-w-md text-lg leading-relaxed">
                    The modern ATS that helps you hire smarter, faster, and more efficiently. 
                    Streamline your recruitment process today.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors p-2 bg-gray-800 rounded-lg hover:bg-gray-700">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Product Section -->
            <div>
                <h3 class="text-lg font-semibold mb-6 text-white">Product</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('features') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Features</a></li>
                    <li><a href="{{ route('subscription.pricing') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Pricing</a></li>
                    <li><a href="{{ route('features.candidate-sourcing') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Candidate Sourcing</a></li>
                    <li><a href="{{ route('features.hiring-pipeline') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Hiring Pipeline</a></li>
                    <li><a href="{{ route('features.hiring-analytics') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Analytics</a></li>
                </ul>
            </div>
            
            <!-- Company Section -->
            <div>
                <h3 class="text-lg font-semibold mb-6 text-white">Company</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('about') }}" class="text-gray-400 hover:text-white transition-colors text-sm">About</a></li>
                    <li><a href="{{ route('careers') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Careers</a></li>
                    <li><a href="{{ route('blog') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Blog</a></li>
                    <li><a href="{{ route('press') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Press</a></li>
                    <li><a href="{{ route('contact') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Contact</a></li>
                </ul>
            </div>
            
            <!-- Support Section -->
            <div>
                <h3 class="text-lg font-semibold mb-6 text-white">Support</h3>
                <ul class="space-y-3">
                    <li><a href="/help-center.html" class="text-gray-400 hover:text-white transition-colors text-sm">Help Center</a></li>
                    <li><a href="/documentation.html" class="text-gray-400 hover:text-white transition-colors text-sm">Documentation</a></li>
                    <li><a href="/security.html" class="text-gray-400 hover:text-white transition-colors text-sm">Security</a></li>
                    <li><a href="/status.html" class="text-gray-400 hover:text-white transition-colors text-sm">Status</a></li>
                    <li><a href="/community.html" class="text-gray-400 hover:text-white transition-colors text-sm">Community</a></li>
                </ul>
            </div>
        </div>
        
        <!-- Newsletter Section -->
        <div class="bg-gray-800 rounded-lg p-8 mb-12">
            <div class="max-w-2xl mx-auto text-center">
                <h3 class="text-2xl font-bold mb-2">Stay Updated</h3>
                <p class="text-gray-400 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
                
                <form id="footer-newsletter-form" method="POST" action="{{ route('newsletter.redirect') }}" class="max-w-md mx-auto">
                    @csrf
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               name="email" 
                               id="footer-newsletter-email" 
                               placeholder="Enter your email" 
                               class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent" 
                               required>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200">
                            Subscribe
                        </button>
                    </div>
                </form>
                
                <!-- Success Message (Hidden Initially) -->
                <div id="success-message" class="hidden mt-4">
                    <div class="bg-green-800 rounded-lg p-6 text-center">
                        <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <h4 class="text-white font-semibold mb-2">Successfully Subscribed!</h4>
                        <p class="text-green-200 text-sm">You'll receive our latest updates and insights.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Bottom Footer -->
        <div class="border-t border-gray-800 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm mb-4 md:mb-0">
                    © {{ date('Y') }} TalentLit. All rights reserved.
                </p>
                <div class="flex space-x-6">
                    <a href="{{ route('privacy-policy') }}" class="text-gray-400 hover:text-white transition-colors text-sm">Privacy Policy</a>
                    <a href="/terms-of-service.html" class="text-gray-400 hover:text-white transition-colors text-sm">Terms of Service</a>
                    <a href="/cookie-policy.html" class="text-gray-400 hover:text-white transition-colors text-sm">Cookie Policy</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footerForm = document.getElementById('footer-newsletter-form');
    if (!footerForm) return;
    
    footerForm.addEventListener('submit', function(e) {
        console.log('Footer newsletter form submitted - redirecting to newsletter page');
        
        // Allow normal form submission to redirect route
        // No reCAPTCHA validation needed
    });
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.className += ` ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
</script>
