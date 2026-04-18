<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Models\CandidateAccount;
use App\Models\Tenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        $tenant = tenant();
        if (!$tenant) {
            abort(404);
        }

        $tenant->load('branding');

        $loginAction = request()->routeIs('subdomain.candidate.login')
            ? route('subdomain.candidate.login.store')
            : route('candidate.login.store', ['tenant' => $tenant->slug]);

        return view('auth.candidate-login', [
            'tenant' => $tenant,
            'loginAction' => $loginAction,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $tenant = tenant();
        if (!$tenant) {
            abort(404);
        }

        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = strtolower(trim($validated['email']));

        $account = CandidateAccount::query()
            ->where('tenant_id', $tenant->id)
            ->where('email', $email)
            ->first();

        if (!$account || ! Hash::check($validated['password'], $account->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __('auth.failed')]);
        }

        Auth::guard('candidate')->login($account, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()->intended($tenant->getApplicantPortalUrl());
    }

    public function destroy(Request $request): RedirectResponse
    {
        $tenant = tenant() ?? Tenant::where('slug', $request->route('tenant'))->first();

        Auth::guard('candidate')->logout();

        $request->session()->regenerateToken();

        if ($tenant) {
            return redirect()->to($tenant->getCandidateLoginUrl());
        }

        return redirect('/');
    }
}
