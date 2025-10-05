<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SetupController extends Controller
{
    public function index($tenant)
    {
        // Tenant is already resolved by the ResolveTenantFromPath middleware
        $tenant = tenant();
        
        \Log::info('Setup page accessed', [
            'tenant_slug' => $tenant->slug,
            'user_agent' => request()->userAgent(),
            'is_mobile' => request()->isMobile(),
            'ip' => request()->ip()
        ]);
        
        return view('onboarding.setup', compact('tenant'));
    }

    public function store(Request $request, $tenant)
    {
        // Tenant is already resolved by the ResolveTenantFromPath middleware
        $tenant = tenant();
        
        $request->validate([
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'primary_color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'careers_enabled' => ['boolean'],
            'careers_intro' => ['nullable', 'string', 'max:1000'],
            'consent_text' => ['nullable', 'string', 'max:1000'],
            'timezone' => ['nullable', 'string', 'max:255'],
            'locale' => ['nullable', 'string', 'max:10'],
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store("branding/{$tenant->slug}", 'public');
            $tenant->update(['logo' => $logoPath]);
        }

        // Update tenant settings
        $tenant->update([
            'primary_color' => $request->primary_color,
            'careers_enabled' => $request->boolean('careers_enabled'),
            'careers_intro' => $request->careers_intro,
            'consent_text' => $request->consent_text,
            'timezone' => $request->timezone ?? 'Asia/Kolkata',
            'locale' => $request->locale ?? 'en',
        ]);

        return redirect()->route('tenant.dashboard', $tenant->slug)
            ->with('success', 'Setup completed successfully! You can now start creating jobs and managing candidates.');
    }

}