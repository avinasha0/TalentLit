<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeneralSettingsController extends Controller
{
    public function edit(string $tenant)
    {
        $tenantModel = tenant();
        
        return view('tenant.settings.general', [
            'tenant' => $tenantModel,
        ]);
    }

    public function update(Request $request, string $tenant)
    {
        $tenantModel = tenant();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:tenants,slug,' . $tenantModel->id,
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'timezone' => 'required|string|max:255',
            'locale' => 'required|string|max:10',
            'careers_enabled' => 'boolean',
            'careers_intro' => 'nullable|string|max:1000',
            'consent_text' => 'nullable|string|max:1000',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($tenantModel->logo && Storage::disk('public')->exists($tenantModel->logo)) {
                Storage::disk('public')->delete($tenantModel->logo);
            }
            
            $logoPath = $request->file('logo')->store('tenant-logos/' . $tenantModel->id, 'public');
            $tenantModel->logo = $logoPath;
        }

        // Update tenant settings
        $tenantModel->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'website' => $request->website,
            'location' => $request->location,
            'company_size' => $request->company_size,
            'primary_color' => $request->primary_color,
            'timezone' => $request->timezone,
            'locale' => $request->locale,
            'careers_enabled' => $request->boolean('careers_enabled'),
            'careers_intro' => $request->careers_intro,
            'consent_text' => $request->consent_text,
        ]);

        return redirect()
            ->route('tenant.settings.general', $tenantModel->slug)
            ->with('success', 'General settings updated successfully.');
    }
}
