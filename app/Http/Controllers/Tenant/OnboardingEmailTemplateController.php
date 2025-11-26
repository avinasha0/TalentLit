<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\OnboardingEmailTemplate;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class OnboardingEmailTemplateController extends Controller
{
    /**
     * Display a listing of onboarding email templates
     */
    public function index(string $tenant)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        // Get templates for current tenant (including global templates)
        $templates = OnboardingEmailTemplate::forTenant($tenantId)
            ->with('tenant')
            ->orderBy('name')
            ->paginate(20);

        // Log view action
        Log::info('OnboardingEmailTemplate.View', [
            'user_id' => auth()->id(),
            'user_role' => $this->getUserRole(),
            'tenant_id' => $tenantId,
            'tenant_slug' => $currentTenant->slug ?? null,
            'tenant_subdomain' => $currentTenant->subdomain ?? null,
            'templates_count' => $templates->total(),
        ]);

        return view('tenant.onboarding-email-templates.index', compact('templates'))->with('tenant', $currentTenant);
    }

    /**
     * Show the form for creating a new template
     */
    public function create(string $tenant)
    {
        $currentTenant = tenant();
        $availableTokens = OnboardingEmailTemplate::getAvailableTokens();

        return view('tenant.onboarding-email-templates.create', compact('availableTokens'))->with('tenant', $currentTenant);
    }

    /**
     * Store a newly created template
     */
    public function store(Request $request, string $tenant)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        $validated = $request->validate([
            'template_key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9._-]+$/i',
                Rule::unique('onboarding_email_templates', 'template_key')
                    ->where(function ($query) use ($tenantId) {
                        // Check uniqueness within tenant scope or global
                        if ($request->input('scope') === 'global') {
                            return $query->whereNull('tenant_id');
                        }
                        return $query->where('tenant_id', $tenantId);
                    }),
            ],
            'name' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'subject' => 'required|string|max:255',
            'body' => 'nullable|string',
            'scope' => 'required|in:tenant,global',
        ]);

        // Warn if body is empty
        if (empty($validated['body'])) {
            $request->session()->flash('warning', 'Template body is empty. Please add content before using this template.');
        }

        // Set tenant_id based on scope
        $validated['tenant_id'] = $validated['scope'] === 'global' ? null : $tenantId;
        unset($validated['scope']);

        $template = OnboardingEmailTemplate::create($validated);

        // Log create action
        Log::info('OnboardingEmailTemplate.Create', [
            'user_id' => auth()->id(),
            'user_role' => $this->getUserRole(),
            'tenant_id' => $tenantId,
            'tenant_slug' => $currentTenant->slug ?? null,
            'tenant_subdomain' => $currentTenant->subdomain ?? null,
            'template_key' => $template->template_key,
            'template_id' => $template->id,
            'template_name' => $template->name,
            'scope' => $template->isGlobal() ? 'global' : 'tenant',
        ]);

        return redirect()
            ->route('tenant.onboarding-email-templates.show', ['tenant' => $tenant, 'template' => $template])
            ->with('success', 'Email template created successfully.');
    }

    /**
     * Display the specified template
     */
    public function show(string $tenant, OnboardingEmailTemplate $template)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        // Ensure user can view this template (must be global or belong to current tenant)
        if (!$template->isGlobal() && $template->tenant_id !== $tenantId) {
            abort(403, 'Access denied');
        }

        $template->load('tenant');
        $availableTokens = OnboardingEmailTemplate::getAvailableTokens();

        // Log view action
        Log::info('OnboardingEmailTemplate.View', [
            'user_id' => auth()->id(),
            'user_role' => $this->getUserRole(),
            'tenant_id' => $tenantId,
            'tenant_slug' => $currentTenant->slug ?? null,
            'tenant_subdomain' => $currentTenant->subdomain ?? null,
            'template_key' => $template->template_key,
            'template_id' => $template->id,
        ]);

        return view('tenant.onboarding-email-templates.show', compact('template', 'availableTokens'))->with('tenant', $currentTenant);
    }

    /**
     * Show the form for editing the specified template
     */
    public function edit(string $tenant, OnboardingEmailTemplate $template)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        // Ensure user can edit this template
        if (!$template->isGlobal() && $template->tenant_id !== $tenantId) {
            abort(403, 'Access denied');
        }

        $availableTokens = OnboardingEmailTemplate::getAvailableTokens();

        return view('tenant.onboarding-email-templates.edit', compact('template', 'availableTokens'))->with('tenant', $currentTenant);
    }

    /**
     * Update the specified template
     */
    public function update(Request $request, string $tenant, OnboardingEmailTemplate $template)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        // Ensure user can update this template
        if (!$template->isGlobal() && $template->tenant_id !== $tenantId) {
            abort(403, 'Access denied');
        }

        $validated = $request->validate([
            'template_key' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9._-]+$/i',
                Rule::unique('onboarding_email_templates', 'template_key')
                    ->ignore($template->id)
                    ->where(function ($query) use ($tenantId, $template) {
                        // Maintain scope - if global, check global uniqueness; if tenant, check tenant uniqueness
                        if ($template->isGlobal()) {
                            return $query->whereNull('tenant_id');
                        }
                        return $query->where('tenant_id', $tenantId);
                    }),
            ],
            'name' => 'required|string|max:255',
            'purpose' => 'nullable|string|max:500',
            'subject' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        // Warn if body is empty
        if (empty($validated['body'])) {
            $request->session()->flash('warning', 'Template body is empty. Please add content before using this template.');
        }

        $template->update($validated);

        // Log update action
        Log::info('OnboardingEmailTemplate.Update', [
            'user_id' => auth()->id(),
            'user_role' => $this->getUserRole(),
            'tenant_id' => $tenantId,
            'tenant_slug' => $currentTenant->slug ?? null,
            'tenant_subdomain' => $currentTenant->subdomain ?? null,
            'template_key' => $template->template_key,
            'template_id' => $template->id,
            'template_name' => $template->name,
            'scope' => $template->isGlobal() ? 'global' : 'tenant',
        ]);

        return redirect()
            ->route('tenant.onboarding-email-templates.show', ['tenant' => $tenant, 'template' => $template])
            ->with('success', 'Email template updated successfully.');
    }

    /**
     * Remove the specified template (if delete is allowed)
     */
    public function destroy(string $tenant, OnboardingEmailTemplate $template)
    {
        $currentTenant = tenant();
        $tenantId = $currentTenant->id ?? null;

        // Ensure user can delete this template
        if (!$template->isGlobal() && $template->tenant_id !== $tenantId) {
            abort(403, 'Access denied');
        }

        $templateKey = $template->template_key;
        $templateId = $template->id;
        $templateName = $template->name;
        $scope = $template->isGlobal() ? 'global' : 'tenant';

        $template->delete();

        // Log delete action
        Log::info('OnboardingEmailTemplate.Delete', [
            'user_id' => auth()->id(),
            'user_role' => $this->getUserRole(),
            'tenant_id' => $tenantId,
            'tenant_slug' => $currentTenant->slug ?? null,
            'tenant_subdomain' => $currentTenant->subdomain ?? null,
            'template_key' => $templateKey,
            'template_id' => $templateId,
            'template_name' => $templateName,
            'scope' => $scope,
        ]);

        return redirect()
            ->route('tenant.onboarding-email-templates.index', $tenant)
            ->with('success', 'Email template deleted successfully.');
    }

    /**
     * Get user role for logging
     */
    private function getUserRole(): ?string
    {
        $user = auth()->user();
        if (!$user) {
            return null;
        }

        $tenant = tenant();
        if (!$tenant) {
            return null;
        }

        return DB::table('custom_user_roles')
            ->where('user_id', $user->id)
            ->where('tenant_id', $tenant->id)
            ->value('role_name');
    }
}
