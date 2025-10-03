<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>reCAPTCHA Test Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
        <h1 class="text-2xl font-bold text-center mb-6 text-gray-800">reCAPTCHA Test</h1>
        
        <form id="test-form" method="POST" action="/test-recaptcha" class="space-y-4">
            <?php echo csrf_field(); ?>
            
            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       placeholder="Enter your email" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       required>
            </div>
            
            <!-- reCAPTCHA Widget -->
            <div class="flex justify-center">
                <?php if(config('recaptcha.site_key')): ?>
                    <div class="g-recaptcha" data-sitekey="<?php echo e(config('recaptcha.site_key')); ?>"></div>
                <?php else: ?>
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        <strong>reCAPTCHA Not Configured!</strong><br>
                        Add RECAPTCHA_SITE_KEY to your .env file
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Error Message -->
            <div id="error-message" class="text-red-500 text-sm text-center" style="display:none;"></div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                Test Submit
            </button>
        </form>
        
        <!-- Debug Info -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-700 mb-2">Debug Info:</h3>
            <div id="debug-info" class="text-sm text-gray-600">
                <p>reCAPTCHA Site Key: <?php echo e(config('recaptcha.site_key') ? 'Set' : 'Not Set'); ?></p>
                <p>Form Method: <span id="form-method">-</span></p>
                <p>reCAPTCHA Token: <span id="token-status">-</span></p>
            </div>
        </div>
    </div>

    <!-- Google reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('test-form');
            const errorMessage = document.getElementById('error-message');
            const formMethod = document.getElementById('form-method');
            const tokenStatus = document.getElementById('token-status');
            
            // Update debug info
            formMethod.textContent = form.method || 'Not set';
            
            // Update token status every second
            setInterval(function() {
                const token = document.querySelector('textarea[name="g-recaptcha-response"]')?.value || '';
                tokenStatus.textContent = token ? 'Present (' + token.length + ' chars)' : 'Empty';
            }, 1000);
            
            // Form submission handler
            form.addEventListener('submit', function(e) {
                console.log('Form submitted - checking reCAPTCHA...');
                
                // Check if reCAPTCHA is configured
                const recaptchaWidget = document.querySelector('.g-recaptcha');
                if (!recaptchaWidget) {
                    console.log('reCAPTCHA not configured - allowing submission');
                    return true; // Allow submission if reCAPTCHA not configured
                }
                
                // Check if reCAPTCHA is completed
                const recaptchaResponse = document.querySelector('textarea[name="g-recaptcha-response"]');
                const token = recaptchaResponse ? recaptchaResponse.value.trim() : '';
                
                if (!token) {
                    e.preventDefault();
                    errorMessage.textContent = 'Please complete the reCAPTCHA verification by clicking "I am not a robot".';
                    errorMessage.style.display = 'block';
                    
                    // Scroll to reCAPTCHA
                    recaptchaWidget.scrollIntoView({behavior: 'smooth'});
                    
                    console.log('reCAPTCHA not completed - preventing submission');
                    return false;
                }
                
                console.log('reCAPTCHA completed - allowing submission');
                errorMessage.style.display = 'none';
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.textContent = 'Submitting...';
                submitBtn.disabled = true;
            });
        });
    </script>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\test-recaptcha.blade.php ENDPATH**/ ?>