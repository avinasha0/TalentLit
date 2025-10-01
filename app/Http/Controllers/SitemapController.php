<?php

namespace App\Http\Controllers;

use App\Models\JobOpening;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate sitemap XML for all tenants and their published jobs
     */
    public function index(): Response
    {
        $tenants = Tenant::where('careers_enabled', true)->get();
        
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Add homepage
        $xml .= '<url>';
        $xml .= '<loc>' . url('/') . '</loc>';
        $xml .= '<lastmod>' . now()->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>daily</changefreq>';
        $xml .= '<priority>1.0</priority>';
        $xml .= '</url>';
        
        foreach ($tenants as $tenant) {
            // Add tenant careers index page
            $xml .= '<url>';
            $xml .= '<loc>' . route('careers.index', ['tenant' => $tenant->slug]) . '</loc>';
            $xml .= '<lastmod>' . $tenant->updated_at->toAtomString() . '</lastmod>';
            $xml .= '<changefreq>daily</changefreq>';
            $xml .= '<priority>0.9</priority>';
            $xml .= '</url>';
            
            // Add published jobs for this tenant
            $jobs = JobOpening::where('tenant_id', $tenant->id)
                ->where('status', 'published')
                ->whereNotNull('published_at')
                ->get();
            
            foreach ($jobs as $job) {
                $xml .= '<url>';
                $xml .= '<loc>' . route('careers.show', ['tenant' => $tenant->slug, 'job' => $job->slug]) . '</loc>';
                $xml .= '<lastmod>' . ($job->updated_at ?? $job->published_at)->toAtomString() . '</lastmod>';
                $xml .= '<changefreq>weekly</changefreq>';
                $xml .= '<priority>0.8</priority>';
                $xml .= '</url>';
            }
        }
        
        $xml .= '</urlset>';
        
        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }
}
