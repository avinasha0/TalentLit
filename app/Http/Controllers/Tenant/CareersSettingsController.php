<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\TenantBranding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CareersSettingsController extends Controller
{
    public function edit(string $tenant)
    {
        $tenantModel = tenant();
        
        // Get or create branding record
        $branding = $tenantModel->branding ?? new TenantBranding(['tenant_id' => $tenantModel->id]);
        
        return view('tenant.settings.careers', [
            'tenant' => $tenantModel,
            'branding' => $branding,
        ]);
    }

    public function update(Request $request, string $tenant)
    {
        $tenantModel = tenant();
        
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'primary_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'intro_headline' => 'nullable|string|max:255',
            'intro_subtitle' => 'nullable|string|max:1000',
            'company_description' => 'nullable|string|max:2000',
            'benefits_text' => 'nullable|string|max:1000',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'linkedin_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'facebook_url' => 'nullable|url|max:255',
            'show_benefits' => 'nullable|boolean',
            'show_company_info' => 'nullable|boolean',
            'show_social_links' => 'nullable|boolean',
        ]);

        // Get or create branding record
        $branding = $tenantModel->branding ?? new TenantBranding(['tenant_id' => $tenantModel->id]);
        
        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($branding->logo_path && Storage::disk('public')->exists($branding->logo_path)) {
                Storage::disk('public')->delete($branding->logo_path);
            }
            
            $logoPath = $request->file('logo')->store('tenant-branding/' . $tenantModel->id, 'public');
            $branding->logo_path = $logoPath;
        }
        
        // Handle hero image upload
        if ($request->hasFile('hero_image')) {
            // Delete old hero image if exists
            if ($branding->hero_image_path && Storage::disk('public')->exists($branding->hero_image_path)) {
                Storage::disk('public')->delete($branding->hero_image_path);
            }
            
            $heroPath = $request->file('hero_image')->store('tenant-branding/' . $tenantModel->id, 'public');
            $branding->hero_image_path = $heroPath;
        }
        
        // Update other fields
        $branding->primary_color = $request->primary_color;
        $branding->intro_headline = $request->intro_headline;
        $branding->intro_subtitle = $request->intro_subtitle;
        $branding->company_description = $request->company_description;
        $branding->benefits_text = $request->benefits_text;
        $branding->contact_email = $request->contact_email;
        $branding->contact_phone = $request->contact_phone;
        $branding->linkedin_url = $request->linkedin_url;
        $branding->twitter_url = $request->twitter_url;
        $branding->facebook_url = $request->facebook_url;
        $branding->show_benefits = $request->has('show_benefits');
        $branding->show_company_info = $request->has('show_company_info');
        $branding->show_social_links = $request->has('show_social_links');
        
        $branding->save();
        
        return redirect()->route('tenant.settings.careers', $tenant)
            ->with('success', 'Careers settings updated successfully.');
    }
}