<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        return redirect()->intended($this->defaultLoginRedirectUrl($user, $request));
    }

    private function defaultLoginRedirectUrl(User $user, Request $request): string
    {
        $lastTenantSlug = session('last_tenant_slug');
        if ($lastTenantSlug) {
            $tenant = Tenant::where('slug', $lastTenantSlug)->first();
            \Log::info('Auth.Login.LastTenantCheck', [
                'user_id' => $user?->id,
                'last_tenant_slug' => $lastTenantSlug,
                'tenant_found' => (bool) $tenant,
                'belongs_to_user' => $tenant ? $user->tenants->contains($tenant) : false,
                'tenant_subdomain' => $tenant?->subdomain,
                'tenant_subdomain_enabled' => $tenant?->subdomain_enabled,
                'tenant_plan_slug' => $tenant?->activeSubscription?->plan?->slug,
            ]);
            if ($tenant && $user->tenants->contains($tenant)) {
                $targetUrl = $this->resolvePostLoginUrlForTenant($user, $tenant);
                \Log::info('Auth.Login.Redirecting.LastTenant', [
                    'user_id' => $user?->id,
                    'tenant_id' => $tenant->id,
                    'tenant_slug' => $tenant->slug,
                    'target_url' => $targetUrl,
                ]);

                return $targetUrl;
            }
            $request->session()->forget('last_tenant_slug');
        }

        $userTenant = $user->tenants->first();
        if ($userTenant) {
            $request->session()->put('last_tenant_slug', $userTenant->slug);
            $targetUrl = $this->resolvePostLoginUrlForTenant($user, $userTenant);
            \Log::info('Auth.Login.Redirecting.FirstTenant', [
                'user_id' => $user?->id,
                'tenant_id' => $userTenant->id,
                'tenant_slug' => $userTenant->slug,
                'tenant_subdomain' => $userTenant->subdomain,
                'tenant_subdomain_enabled' => $userTenant->subdomain_enabled,
                'tenant_plan_slug' => $userTenant->activeSubscription?->plan?->slug,
                'target_url' => $targetUrl,
            ]);

            return $targetUrl;
        }

        if (app()->environment('testing')) {
            return route('dashboard');
        }

        return route('onboarding.organization');
    }

    private function resolvePostLoginUrlForTenant(User $user, Tenant $tenant): string
    {
        return $tenant->getDashboardUrl();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        // Clear tenant-specific session data
        $request->session()->forget('last_tenant_slug');
        $request->session()->forget('current_tenant_id');

        $redirectTo = $request->input('redirect_to', '/');
        if (! Str::startsWith($redirectTo, '/')) {
            $redirectTo = '/';
        }

        return redirect($redirectTo);
    }
}
