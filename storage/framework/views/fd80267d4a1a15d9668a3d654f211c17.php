<?php
    // Default SEO values
    $seoTitle = $seoTitle ?? config('app.name', 'TalentLit') . ' – Smarter Recruitment Platform';
    $seoDescription = $seoDescription ?? 'TalentLit is a modern Applicant Tracking System (ATS) that helps organizations streamline their recruitment process, manage candidates, and hire top talent efficiently.';
    $seoKeywords = $seoKeywords ?? 'TalentLit, ATS, applicant tracking system, recruitment, hiring, jobs, careers, talent management';
    $seoAuthor = $seoAuthor ?? (isset($tenant) ? $tenant->name : 'TalentLit');
    $seoImage = $seoImage ?? asset('images/logo-talentlit.svg');
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
<link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(asset('favicon-16x16.png')); ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(asset('favicon-32x32.png')); ?>">
<link rel="icon" type="image/png" sizes="48x48" href="<?php echo e(asset('favicon-48x48.png')); ?>">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('apple-touch-icon.png')); ?>">
<link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>">

<!-- Web App Manifest -->
<link rel="manifest" href="<?php echo e(asset('site.webmanifest')); ?>">

<!-- Theme Colors -->
<meta name="theme-color" content="#6E46AE">
<meta name="msapplication-TileColor" content="#6E46AE">
<meta name="msapplication-config" content="<?php echo e(asset('browserconfig.xml')); ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

<!-- Scripts -->
<?php if (! (app()->environment('testing'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
<?php endif; ?>

<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\layouts\partials\head.blade.php ENDPATH**/ ?>