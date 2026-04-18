@php
    /** @var string $active 'applications'|'profile' */
    $isSubdomainApplicant = request()->routeIs('subdomain.applicant.*');
    $careersUrl = $isSubdomainApplicant
        ? route('subdomain.careers.index')
        : route('careers.index', ['tenant' => $tenant->slug]);
    $logoutAction = $isSubdomainApplicant
        ? route('subdomain.candidate.logout')
        : route('candidate.logout', ['tenant' => $tenant->slug]);
    $applicationsUrl = $isSubdomainApplicant
        ? route('subdomain.applicant.dashboard')
        : route('tenant.applicant.dashboard', ['tenant' => $tenant->slug]);
    $profileUrl = $isSubdomainApplicant
        ? route('subdomain.applicant.profile')
        : route('tenant.applicant.profile', ['tenant' => $tenant->slug]);
@endphp

<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-md shadow-sm shadow-slate-900/5">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 py-3 sm:py-0 sm:h-16 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
            <div class="flex items-center justify-between gap-3 sm:justify-start sm:min-w-0">
                <a href="{{ $careersUrl }}" class="flex items-center gap-3 min-w-0 group">
                    @if($tenant->branding?->logo_path)
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white ring-1 ring-slate-200 overflow-hidden">
                            <img src="{{ asset('storage/'.$tenant->branding->logo_path) }}" alt="" class="h-8 w-auto max-w-[2.5rem] object-contain">
                        </span>
                    @else
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white shadow-sm ring-1 ring-black/5" style="background-color: var(--applicant-brand);">
                            {{ strtoupper(substr($tenant->name, 0, 1)) }}
                        </span>
                    @endif
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Applicant portal</p>
                        <p class="font-semibold text-slate-900 truncate group-hover:text-slate-700 transition-colors">{{ $tenant->name }}</p>
                    </div>
                </a>
                <form method="POST" action="{{ $logoutAction }}" class="sm:hidden shrink-0">
                    @csrf
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white p-2 text-slate-600 hover:bg-slate-50" aria-label="Sign out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>

            <nav class="flex items-center gap-1 sm:gap-2 -mx-1 px-1 overflow-x-auto pb-1 sm:pb-0 scrollbar-thin" aria-label="Applicant portal">
                <a href="{{ $applicationsUrl }}"
                   class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $active === 'applications' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    My applications
                </a>
                <a href="{{ $profileUrl }}"
                   class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ $active === 'profile' ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    My profile
                </a>
            </nav>

            <div class="hidden sm:flex items-center gap-3 shrink-0">
                <span class="text-sm text-slate-500 max-w-[220px] truncate" title="{{ auth('candidate')->user()->email }}">{{ auth('candidate')->user()->email }}</span>
                <form method="POST" action="{{ $logoutAction }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-700 shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-colors">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="h-0.5 w-full opacity-95" style="background-image: linear-gradient(90deg, var(--applicant-brand), #6366f1, var(--applicant-brand));"></div>
</header>
