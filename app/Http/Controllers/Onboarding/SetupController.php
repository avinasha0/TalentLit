<?php

namespace App\Http\Controllers\Onboarding;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\JobStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SetupController extends Controller
{
    public function index($tenant)
    {
        $tenant = Tenant::where('slug', $tenant)->firstOrFail();
        
        // Set tenant context
        session(['current_tenant_id' => $tenant->id]);
        session(['last_tenant_slug' => $tenant->slug]);

        return view('onboarding.setup', compact('tenant'));
    }

    public function store(Request $request, $tenant)
    {
        $tenant = Tenant::where('slug', $tenant)->firstOrFail();
        
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

        // Create default job stages if they don't exist
        $this->createDefaultJobStages($tenant);

        return redirect()->route('tenant.dashboard', $tenant->slug)
            ->with('success', 'Setup completed successfully! You can now start creating jobs and managing candidates.');
    }

    private function createDefaultJobStages(Tenant $tenant): void
    {
        $defaultStages = [
            ['name' => 'Applied', 'sort_order' => 1],
            ['name' => 'Screen', 'sort_order' => 2],
            ['name' => 'Interview', 'sort_order' => 3],
            ['name' => 'Offer', 'sort_order' => 4],
            ['name' => 'Hired', 'sort_order' => 5],
        ];

        foreach ($defaultStages as $stage) {
            JobStage::firstOrCreate([
                'tenant_id' => $tenant->id,
                'name' => $stage['name'],
            ], [
                'sort_order' => $stage['sort_order'],
            ]);
        }
    }
}