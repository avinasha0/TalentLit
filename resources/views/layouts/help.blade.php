<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        @php
            $seoTitle = ($title ?? 'Help Center') . ' – TalentLit ATS';
            $seoDescription = $description ?? 'Get help with TalentLit ATS. Find guides, tutorials, and support for managing your recruitment process.';
            $seoKeywords = $keywords ?? 'TalentLit help, ATS support, recruitment software help, hiring guide, user manual';
            $seoAuthor = 'TalentLit';
            $seoImage = asset('logo-talentlit-small.png');
        @endphp
        @include('layouts.partials.head')
    </head>
    <body class="h-full bg-white">
        @yield('content')
        <x-footer />
    </body>
</html>
