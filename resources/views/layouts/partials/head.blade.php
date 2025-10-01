@php
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
@endphp

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- SEO Meta Tags -->
<title>{{ $seoTitle }}</title>
<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="author" content="{{ $seoAuthor }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Open Graph Meta Tags -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:site_name" content="{{ $ogSiteName }}">
@if(isset($tenant) && $tenant->branding && $tenant->branding->logo_path)
    <meta property="og:image" content="{{ asset('storage/' . $tenant->branding->logo_path) }}">
@else
    <meta property="og:image" content="{{ $seoImage }}">
@endif

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
@if(isset($tenant) && $tenant->branding && $tenant->branding->logo_path)
    <meta name="twitter:image" content="{{ asset('storage/' . $tenant->branding->logo_path) }}">
@else
    <meta name="twitter:image" content="{{ $seoImage }}">
@endif

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
<link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

<!-- Web App Manifest -->
<link rel="manifest" href="{{ asset('site.webmanifest') }}">

<!-- Theme Colors -->
<meta name="theme-color" content="#6E46AE">
<meta name="msapplication-TileColor" content="#6E46AE">
<meta name="msapplication-config" content="{{ asset('browserconfig.xml') }}">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

<!-- Scripts -->
@unless (app()->environment('testing'))
    @vite(['resources/css/app.css', 'resources/js/app.js'])
@endunless

