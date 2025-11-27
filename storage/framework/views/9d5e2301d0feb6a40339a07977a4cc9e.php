<?php
    // Default SEO values
    $seoTitle = $seoTitle ?? config('app.name', 'TalentLit') . ' – Smarter Recruitment Platform';
    $seoDescription = $seoDescription ?? 'TalentLit is a modern Applicant Tracking System (ATS) that helps organizations streamline their recruitment process, manage candidates, and hire top talent efficiently.';
    $seoKeywords = $seoKeywords ?? 'TalentLit, ATS, applicant tracking system, recruitment, hiring, jobs, careers, talent management';
    $seoAuthor = $seoAuthor ?? (isset($tenant) ? $tenant->name : 'TalentLit');
    $seoImage = $seoImage ?? asset('images/logo-talentlit.png');
    $canonicalUrl = $canonicalUrl ?? url()->current();
    
    // Open Graph defaults
    $ogType = $ogType ?? 'website';
    $ogSiteName = $ogSiteName ?? (isset($tenant) ? $tenant->name : 'TalentLit');
    
    // Twitter card defaults
    $twitterCard = $twitterCard ?? 'summary_large_image';
?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<!-- SEO Meta Tags -->
<title><?php echo e($seoTitle); ?></title>
<meta name="description" content="<?php echo e($seoDescription); ?>">
<meta name="keywords" content="<?php echo e($seoKeywords); ?>">
<meta name="author" content="<?php echo e($seoAuthor); ?>">
<link rel="canonical" href="<?php echo e($canonicalUrl); ?>">

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="<?php echo e($ogType); ?>">
<meta property="og:title" content="<?php echo e($seoTitle); ?>">
<meta property="og:description" content="<?php echo e($seoDescription); ?>">
<meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
<meta property="og:site_name" content="<?php echo e($ogSiteName); ?>">
<?php if(isset($tenant) && $tenant->branding && $tenant->branding->logo_path): ?>
    <meta property="og:image" content="<?php echo e(asset('storage/' . $tenant->branding->logo_path)); ?>">
<?php else: ?>
    <meta property="og:image" content="<?php echo e($seoImage); ?>">
<?php endif; ?>

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="<?php echo e($twitterCard); ?>">
<meta name="twitter:title" content="<?php echo e($seoTitle); ?>">
<meta name="twitter:description" content="<?php echo e($seoDescription); ?>">
<?php if(isset($tenant) && $tenant->branding && $tenant->branding->logo_path): ?>
    <meta name="twitter:image" content="<?php echo e(asset('storage/' . $tenant->branding->logo_path)); ?>">
<?php else: ?>
    <meta name="twitter:image" content="<?php echo e($seoImage); ?>">
<?php endif; ?>

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-16x16.png')); ?>?v=<?php echo e(filemtime(public_path('favicon-16x16.png'))); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-32x32.png')); ?>?v=<?php echo e(filemtime(public_path('favicon-32x32.png'))); ?>">
<link rel="icon" type="image/png" sizes="48x48" href="<?php echo e(asset('favicon-48x48.png')); ?>?v=<?php echo e(filemtime(public_path('favicon-48x48.png'))); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>?v=<?php echo e(filemtime(public_path('apple-touch-icon.png'))); ?>">
<link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>?v=<?php echo e(filemtime(public_path('favicon.ico'))); ?>">

<!-- Web App Manifest -->
<link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">

<!-- Theme Colors -->
<meta name="theme-color" content="#6A0DAD">
<meta name="msapplication-TileColor" content="#6A0DAD">
<meta name="msapplication-config" content="<?php echo e(asset('browserconfig.xml')); ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

<!-- Scripts -->
<?php if (! (app()->environment('testing'))): ?>
    <?php if(file_exists(public_path('build/manifest.json'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php else: ?>
        <!-- Fallback for production without Vite build -->
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.0/dist/tailwind.min.css" rel="stylesheet">
        <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        <style>
            /* Custom styles from app.css */
            @media (max-width: 1023px) {
                .mobile-open {
                    transform: translateX(0) !important;
                }
            }
            .collapsed {
                width: 4rem !important;
            }
            .collapsed .sidebar-text {
                display: none;
            }
            .collapsed .sidebar-submenu {
                display: none !important;
            }
            .collapsed .sidebar-user-info {
                display: none;
            }
            .collapsed .sidebar-footer {
                padding: 1rem 0.5rem;
            }
            #sidebar {
                transition: width 0.3s ease-in-out, transform 0.3s ease-in-out;
                z-index: 40;
            }
            .sidebar-overlay {
                z-index: 30;
            }
            .main-content {
                transition: margin-left 0.3s ease-in-out;
            }
            @media (prefers-color-scheme: dark) {
                .collapsed {
                    background-color: rgb(31 41 55);
                }
            }
        </style>
    <?php endif; ?>
<?php endif; ?>

<!-- Global 419 Error Handler -->
<script>
(function() {
    'use strict';
    
    // Store original fetch
    const originalFetch = window.fetch;
    
    // Intercept fetch requests to handle 419 errors
    window.fetch = function(...args) {
        return originalFetch.apply(this, args)
            .then(response => {
                // Check for 419 errors
                if (response.status === 419) {
                    // For AJAX requests, try to get JSON response
                    if (args[1] && args[1].headers && args[1].headers['Accept'] && args[1].headers['Accept'].includes('application/json')) {
                        return response.json().then(data => {
                            // Show user-friendly message
                            if (data.redirect) {
                                // Show notification before redirect
                                if (typeof window.showNotification === 'function') {
                                    window.showNotification('Your session has expired. Redirecting to login...', 'warning');
                                } else {
                                    alert('Your session has expired. You will be redirected to the login page.');
                                }
                                setTimeout(() => {
                                    window.location.href = data.redirect;
                                }, 1500);
                            }
                            throw new Error(data.message || 'Session expired');
                        });
                    }
                    
                    // For regular requests, redirect to login
                    const loginUrl = '<?php echo e(route("login")); ?>';
                    if (typeof window.showNotification === 'function') {
                        window.showNotification('Your session has expired. Redirecting to login...', 'warning');
                    }
                    setTimeout(() => {
                        window.location.href = loginUrl;
                    }, 1500);
                    
                    return Promise.reject(new Error('Session expired'));
                }
                return response;
            })
            .catch(error => {
                // Re-throw non-419 errors
                if (error.message !== 'Session expired') {
                    throw error;
                }
                return Promise.reject(error);
            });
    };
    
    // Handle form submissions that might result in 419 errors
    document.addEventListener('submit', function(e) {
        const form = e.target;
        if (form.tagName === 'FORM' && form.method.toUpperCase() === 'POST') {
            const originalSubmit = form.onsubmit;
            form.addEventListener('submit', function(event) {
                // This will be handled by the fetch interceptor above
                // or by Laravel's default form handling
            }, true);
        }
    });
    
    // Handle page load with 419 error in URL or response
    if (document.body && document.body.innerHTML.includes('419') || 
        document.body && document.body.innerHTML.includes('Page Expired')) {
        const loginUrl = '<?php echo e(route("login")); ?>';
        // Check if we're not already on the login or 419 error page
        if (!window.location.pathname.includes('login') && 
            !window.location.pathname.includes('errors/419')) {
            window.location.href = loginUrl;
        }
    }
})();
</script>

<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views/layouts/partials/head.blade.php ENDPATH**/ ?>