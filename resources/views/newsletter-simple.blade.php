<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Newsletter Form</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">Newsletter Subscription</h1>
        
        <!-- Success Message -->
        @if(isset($success))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ $success }}
            </div>
        @endif
        
        <!-- Error Messages -->
        @if(isset($errors) && $errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
        
        <form id="newsletterForm" method="POST" action="{{ route('newsletter.subscribe') }}" class="space-y-4">
            @csrf
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ isset($old_email) ? $old_email : '' }}"
                       placeholder="Enter your email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>
            
            <!-- reCAPTCHA Widget -->
            <div class="flex justify-center">
                <div class="g-recaptcha" data-sitekey="{{ config('recaptcha.site_key') }}"></div>
            </div>
            
            <!-- Error Message -->
            <div id="recaptcha-error" class="text-red-500 text-sm text-center" style="display:none;"></div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Subscribe
            </button>
        </form>
    </div>

    <!-- Google reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <script>
        document.getElementById('newsletterForm').addEventListener('submit', function(e) {
            // ALWAYS prevent default form submission
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Form submitted - checking reCAPTCHA...');
            
            // Check if reCAPTCHA is completed
            const token = grecaptcha.getResponse();
            console.log('reCAPTCHA token:', token ? 'Present' : 'Missing');
            
            if (!token) {
                console.log('reCAPTCHA not completed - showing error');
                
                // Show error message
                const errorDiv = document.getElementById('recaptcha-error');
                if (errorDiv) {
                    errorDiv.textContent = 'Please complete the reCAPTCHA verification by clicking "I am not a robot".';
                    errorDiv.style.display = 'block';
                }
                
                // Scroll to reCAPTCHA
                const recaptchaWidget = document.querySelector('.g-recaptcha');
                if (recaptchaWidget) {
                    recaptchaWidget.scrollIntoView({behavior: 'smooth'});
                }
                
                return false;
            }
            
            console.log('reCAPTCHA completed - submitting via AJAX');
            
            // Clear error if token exists
            const errorDiv = document.getElementById('recaptcha-error');
            if (errorDiv) {
                errorDiv.style.display = 'none';
            }
            
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
            .then(response => response.text())
            .then(html => {
                // Replace the form content with the response (includes success/error messages)
                document.querySelector('.bg-white').innerHTML = html;
                
                // Re-initialize reCAPTCHA if needed
                if (typeof grecaptcha !== 'undefined') {
                    grecaptcha.render(document.querySelector('.g-recaptcha'), {
                        'sitekey': '{{ config("recaptcha.site_key") }}'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
            })
            .finally(() => {
                // Reset button state
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
