<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureCandidateBelongsToTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::guard('candidate')->user();
        if (!$account) {
            abort(403, 'Unauthorized');
        }

        $tenant = null;
        if (function_exists('tenant')) {
            $tenant = tenant();
        }
        if (!$tenant && class_exists(\App\Support\Tenancy::class)) {
            $tenant = \App\Support\Tenancy::get();
        }
        if (!$tenant && $request->route('tenant')) {
            $tenant = Tenant::where('slug', $request->route('tenant'))->first();
        }
        if (!$tenant && session('current_tenant_id')) {
            $tenant = Tenant::find(session('current_tenant_id'));
        }

        if (!$tenant) {
            abort(403, 'Tenant not found');
        }

        if ((string) $account->tenant_id !== (string) $tenant->id) {
            abort(403, 'Invalid organization for this applicant session.');
        }

        return $next($request);
    }
}
