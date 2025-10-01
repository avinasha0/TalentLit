# TalentLit SEO & Branding Implementation

This document describes the SEO improvements and branding assets implemented for TalentLit ATS.

## What Was Implemented

### 1. SEO Meta Tags System

**File**: `resources/views/layouts/partials/head.blade.php`

A comprehensive head partial that includes:
- Dynamic page titles
- Meta descriptions (150 char optimized)
- Meta keywords
- Author meta tags
- Canonical URLs
- Open Graph tags (Facebook, LinkedIn)
- Twitter Card tags
- Favicon references
- Font preconnect optimization

**Usage**: The partial automatically pulls SEO data from variables set in each layout/view:
- `$seoTitle` - Page title
- `$seoDescription` - Page description
- `$seoKeywords` - Keywords
- `$seoAuthor` - Author/tenant name
- `$seoImage` - OG/Twitter image

### 2. Updated Layouts

All layouts now include the head partial:
- `resources/views/layouts/guest.blade.php` - Login/register pages
- `resources/views/layouts/app.blade.php` - Internal dashboard pages
- `resources/views/components/app-layout.blade.php` - Main app layout
- `resources/views/layouts/public.blade.php` - Public pages

Each layout sets appropriate SEO defaults for its context.

### 3. Dynamic SEO for Career Pages

**Careers Index** (`resources/views/careers/index.blade.php`):
- Title: "Careers at {Tenant Name} | TalentLit"
- Description: Uses tenant intro or generates from tenant name
- Keywords: Includes tenant name + job-related terms
- OG Image: Tenant logo or TalentLit logo

**Job Detail** (`resources/views/careers/show.blade.php`):
- Title: "{Job Title} – Careers at {Tenant Name} | TalentLit"
- Description: Job description summary (150 chars)
- Keywords: Job title + department + location + tenant
- OG Image: Tenant logo or TalentLit logo

### 4. Branding Assets

**Logo SVG**: `resources/images/logo-talentlit.svg`
- Full TalentLit logo with gradient (Royal Purple #6E46AE → Tiffany Blue #00B6B4)
- "TL" icon mark with company name
- Tagline: "SMARTER RECRUITMENT"

**Small Logo**: `public/logo-talentlit-small.svg`
- Compact version for navigation/headers
- Same gradient and branding

**Favicon Files**: Complete favicon implementation
- `public/favicon.ico` - Main favicon (32x32 ICO)
- `public/favicon-16x16.png` - Small favicon (16x16)
- `public/favicon-32x32.png` - Standard favicon (32x32)
- `public/favicon-48x48.png` - Large favicon (48x48)
- `public/apple-touch-icon.png` - iOS home screen (180x180)
- `public/site.webmanifest` - PWA manifest
- `public/browserconfig.xml` - Windows tiles config

### 5. Robots.txt

**File**: `public/robots.txt`

Configured to:
- ✅ Allow: Career pages (`/*/careers`)
- ❌ Disallow: Dashboard, settings, internal pages
- ❌ Disallow: Auth pages (login, register)

### 6. XML Sitemap

**Controller**: `app/Http/Controllers/SitemapController.php`
**Route**: `GET /sitemap.xml`

Dynamically generates XML sitemap including:
- Homepage
- All tenant career index pages (with `careers_enabled = true`)
- All published jobs for each tenant
- Proper `lastmod`, `changefreq`, and `priority` values

## SEO Best Practices Implemented

✅ **Semantic HTML**: Proper heading structure (h1, h2, h3)
✅ **Alt Attributes**: All images have descriptive alt text
✅ **Meta Tags**: Complete set of meta tags for search engines
✅ **Open Graph**: Full OG tags for social sharing
✅ **Twitter Cards**: Large image cards for Twitter
✅ **Canonical URLs**: Prevent duplicate content
✅ **Performance**: Preconnect for fonts
✅ **Mobile-Friendly**: Responsive viewport meta
✅ **Structured Headings**: Logical content hierarchy

## Testing Instructions

### 1. Start Laravel Server
```bash
php artisan serve
```

### 2. Test Robots.txt
Visit: `http://127.0.0.1:8000/robots.txt`

Expected output:
```
User-agent: *
Disallow: /*/dashboard
...
Allow: /*/careers
```

### 3. Test XML Sitemap
Visit: `http://127.0.0.1:8000/sitemap.xml`

Expected: XML document with all published jobs

### 4. Test Career Pages
Visit: `http://127.0.0.1:8000/{tenant}/careers`

Check in browser:
- Browser tab shows proper title with tenant name
- View page source → check `<head>` section for meta tags
- Verify og:image, og:title, og:description tags
- Logo displays properly

### 5. Test Job Detail Page
Visit: `http://127.0.0.1:8000/{tenant}/careers/{job-slug}`

Check in browser:
- Title includes job title
- Meta description includes job summary
- Keywords include job-specific terms

### 6. Test Social Sharing
Use tools like:
- **Facebook Debugger**: https://developers.facebook.com/tools/debug/
- **Twitter Card Validator**: https://cards-dev.twitter.com/validator
- **LinkedIn Post Inspector**: https://www.linkedin.com/post-inspector/

Paste your career page URL to see how it appears when shared.

## Component: Meta Tag Helper (Optional Enhancement)

For easier page-level SEO control, you can create a Blade component:

**File**: `resources/views/components/meta.blade.php`
```blade
@props(['title', 'description', 'keywords' => null, 'image' => null])

@php
    $seoTitle = $title;
    $seoDescription = $description;
    $seoKeywords = $keywords;
    $seoImage = $image;
@endphp
```

**Usage in Controllers**:
```php
return view('some.view', [
    'metaTitle' => 'Page Title',
    'metaDescription' => 'Page description',
]);
```

**Usage in Views**:
```blade
<x-meta 
    title="Job Title – Company" 
    description="Job description here"
    keywords="job, company, location"
    image="{{ $job->image }}"
/>
```

## Next Steps (Optional Enhancements)

1. **Generate Actual Favicon Files**
   - Use RealFaviconGenerator or Favicon.io
   - Upload `public/logo-talentlit-small.svg`
   - Place generated files in `/public`

2. **Schema.org Markup**
   - Add JobPosting structured data for jobs
   - Add Organization schema for tenant pages

3. **Google Analytics**
   - Add GA4 tracking code to head partial

4. **Performance**
   - Implement lazy loading for images
   - Add resource hints (preload/prefetch)

5. **SEO Monitoring**
   - Submit sitemap to Google Search Console
   - Monitor search performance
   - Track career page rankings

## Files Modified/Created

### Created:
- `resources/views/layouts/partials/head.blade.php`
- `resources/images/logo-talentlit.svg`
- `public/logo-talentlit-small.svg`
- `public/robots.txt`
- `public/favicon-generation-note.txt`
- `app/Http/Controllers/SitemapController.php`
- `SEO_BRANDING_IMPLEMENTATION.md`

### Modified:
- `resources/views/layouts/guest.blade.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/components/app-layout.blade.php`
- `resources/views/layouts/public.blade.php`
- `resources/views/careers/index.blade.php`
- `resources/views/careers/show.blade.php`
- `routes/web.php`

## Branding Colors

- **Royal Purple**: `#6E46AE`
- **Tiffany Blue**: `#00B6B4`
- **Text Gray**: `#1F2937`
- **Light Gray**: `#6B7280`

## Support

For questions or issues with SEO/branding implementation, refer to:
- Laravel SEO Best Practices
- Open Graph Protocol: https://ogp.me/
- Twitter Cards: https://developer.twitter.com/en/docs/twitter-for-websites/cards

