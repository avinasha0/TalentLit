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
        
        $branding->save();
        
        return redirect()->route('tenant.settings.careers', $tenant)
            ->with('success', 'Careers settings updated successfully.');
    }
}