<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class GeneralSettingsController extends Controller
{
    public function edit(string $tenant = null)
    {
        $tenantModel = tenant();
        
        return view('tenant.settings.general', [
            'tenant' => $tenantModel,
        ]);
    }

    public function update(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        $validationRules = [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|alpha_dash|unique:tenants,slug,' . $tenantModel->id,
            'website' => 'nullable|url|max:255',
            'location' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'primary_color' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/',
            'timezone' => 'required|string|max:255',
            'locale' => 'required|string|max:10',
            'currency' => 'required|string|in:INR',
            'careers_enabled' => 'boolean',
            'careers_intro' => 'nullable|string|max:1000',
            'consent_text' => 'nullable|string|max:1000',
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ];

        // Add subdomain validation only for Enterprise plans
        $currentPlan = $tenantModel->activeSubscription?->plan;
        if ($currentPlan && $currentPlan->slug === 'enterprise') {
            $validationRules['subdomain'] = 'nullable|string|max:63|regex:/^[a-z0-9]([a-z0-9-]{0,61}[a-z0-9])?$/|unique:tenants,subdomain,' . $tenantModel->id;
            $validationRules['subdomain_enabled'] = 'boolean';
        }

        $request->validate($validationRules);

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
        $updateData = [
            'name' => $request->name,
            'slug' => $request->slug,
            'website' => $request->website,
            'location' => $request->location,
            'company_size' => $request->company_size,
            'primary_color' => $request->primary_color,
            'timezone' => $request->timezone,
            'locale' => $request->locale,
            'currency' => $request->currency,
            'careers_enabled' => $request->boolean('careers_enabled'),
            'careers_intro' => $request->careers_intro,
            'consent_text' => $request->consent_text,
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_username' => $request->smtp_username,
            'smtp_encryption' => $request->smtp_encryption,
            'smtp_from_address' => $request->smtp_from_address,
            'smtp_from_name' => $request->smtp_from_name,
        ];

        // Add subdomain fields only for Enterprise plans
        $currentPlan = $tenantModel->activeSubscription?->plan;
        if ($currentPlan && $currentPlan->slug === 'enterprise') {
            $updateData['subdomain'] = $request->subdomain ? strtolower($request->subdomain) : null;
            $updateData['subdomain_enabled'] = $request->boolean('subdomain_enabled') && !empty($request->subdomain);
        }

        // Only update password if provided
        if ($request->filled('smtp_password')) {
            $updateData['smtp_password'] = encrypt($request->smtp_password);
        }

        $tenantModel->update($updateData);

        // Redirect based on route name (subdomain or path-based)
        $routeName = $request->route()->getName();
        if (str_starts_with($routeName, 'subdomain.')) {
            return redirect()->route('subdomain.settings.general')->with('success', 'General settings updated successfully.');
        }
        return redirect()
            ->route('tenant.settings.general', $tenantModel->slug)
            ->with('success', 'General settings updated successfully.');
    }

    public function updateSmtp(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string',
            'smtp_encryption' => 'nullable|in:tls,ssl',
            'smtp_from_address' => 'nullable|email|max:255',
            'smtp_from_name' => 'nullable|string|max:255',
        ]);

        $updateData = [
            'smtp_host' => $request->smtp_host,
            'smtp_port' => $request->smtp_port,
            'smtp_username' => $request->smtp_username,
            'smtp_encryption' => $request->smtp_encryption,
            'smtp_from_address' => $request->smtp_from_address,
            'smtp_from_name' => $request->smtp_from_name,
        ];

        // Only update password if provided
        if ($request->filled('smtp_password')) {
            $updateData['smtp_password'] = encrypt($request->smtp_password);
        }

        $tenantModel->update($updateData);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'SMTP configuration saved successfully!']);
        }

        $routeName = $request->route()->getName();
        if (str_starts_with($routeName, 'subdomain.')) {
            return redirect()->route('subdomain.settings.general')->with('success', 'SMTP configuration saved successfully.');
        }
        return redirect()
            ->route('tenant.settings.general', $tenantModel->slug)
            ->with('success', 'SMTP configuration saved successfully.');
    }

    public function testEmail(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        $validator = Validator::make($request->all(), [
            'test_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->with('error', 'Please provide a valid email address.');
        }

        try {
            // Configure mail with tenant SMTP settings
            config([
                'mail.mailers.smtp.host' => $tenantModel->smtp_host,
                'mail.mailers.smtp.port' => $tenantModel->smtp_port,
                'mail.mailers.smtp.username' => $tenantModel->smtp_username,
                'mail.mailers.smtp.password' => $tenantModel->smtp_password ? decrypt($tenantModel->smtp_password) : null,
                'mail.mailers.smtp.encryption' => $tenantModel->smtp_encryption,
                'mail.from.address' => $tenantModel->smtp_from_address ?? config('mail.from.address'),
                'mail.from.name' => $tenantModel->smtp_from_name ?? config('mail.from.name'),
            ]);

            // Send test email
            Mail::raw('This is a test email from ' . $tenantModel->name . '. Your SMTP configuration is working correctly!', function ($message) use ($request, $tenantModel) {
                $message->to($request->test_email)
                        ->subject('SMTP Test Email from ' . $tenantModel->name);
            });

            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Test email sent successfully to ' . $request->test_email . '!']);
            }
            
            return back()->with('success', 'Test email sent successfully to ' . $request->test_email . '!');
            
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()]);
            }
            
            return back()->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }

    public function getPassword(Request $request, string $tenant = null)
    {
        $tenantModel = tenant();
        
        if (!$tenantModel->smtp_password) {
            return response()->json(['success' => false, 'message' => 'No password set']);
        }

        try {
            $decryptedPassword = decrypt($tenantModel->smtp_password);
            return response()->json(['success' => true, 'password' => $decryptedPassword]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to decrypt password']);
        }
    }
}
