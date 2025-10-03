<!-- Newsletter Subscription Footer - Duplicate -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold mb-2">Stay Updated</h3>
            <p class="text-purple-100 mb-6">Get the latest updates on new features, tips, and best practices for recruitment.</p>
            
            <form id="footer-newsletter-form-duplicate" method="POST" action="{{ route('newsletter.subscribe') }}" class="max-w-md mx-auto">
                @csrf
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="email" 
                           name="email" 
                           id="footer-newsletter-email-duplicate" 
                           placeholder="Enter your email" 
                           class="flex-1 px-4 py-3 bg-white border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white focus:border-transparent" 
                           required>
                    <button type="submit" 
                            class="bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-all duration-200">
                        Subscribe
                    </button>
                </div>
                
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const footerFormDuplicate = document.getElementById('footer-newsletter-form-duplicate');
    if (!footerFormDuplicate) return;
    
    footerFormDuplicate.addEventListener('submit', function(e) {
        console.log('Footer newsletter form duplicate submitted - redirecting to newsletter page');
        
        // Submit via AJAX (NO PAGE RELOAD)
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Show loading state
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        fetch(this.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (response.ok && data.requires_verification) {
                // Redirect to newsletter page for OTP verification
                window.location.href = '{{ route("newsletter.subscribe") }}';
            } else if (data.success) {
                showNotificationDuplicate(data.message || 'Successfully subscribed!', 'success');
                // Reset form
                document.getElementById('footer-newsletter-email-duplicate').value = '';
            } else {
                showNotificationDuplicate(data.message || 'Something went wrong. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotificationDuplicate('Something went wrong. Please try again.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
});

// Notification system for duplicate
function showNotificationDuplicate(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 left-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
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
