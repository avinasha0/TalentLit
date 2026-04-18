<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applicant portal — {{ $tenant->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'] },
                }
            }
        }
    </script>
    @php
        $brandHex = $tenant->branding?->primary_color ?: '#0f766e';
        $careersUrl = request()->routeIs('subdomain.applicant.*')
            ? route('subdomain.careers.index')
            : route('careers.index', ['tenant' => $tenant->slug]);
        $applicantName = trim($candidate->first_name.' '.$candidate->last_name);
        $statusStyles = [
            'active' => 'bg-emerald-50 text-emerald-800 ring-emerald-700/15',
            'applied' => 'bg-sky-50 text-sky-800 ring-sky-700/15',
            'pending' => 'bg-amber-50 text-amber-900 ring-amber-700/15',
            'rejected' => 'bg-red-50 text-red-800 ring-red-700/15',
            'hired' => 'bg-blue-50 text-blue-800 ring-blue-700/15',
            'closed' => 'bg-slate-100 text-slate-700 ring-slate-600/12',
            'withdrawn' => 'bg-slate-100 text-slate-600 ring-slate-600/12',
        ];
    @endphp
    <style>
        :root { --applicant-brand: {{ $brandHex }}; }
        .bg-app { background-color: #f1f5f9; background-image: radial-gradient(1200px 400px at 0% -10%, rgba(15, 118, 110, 0.06), transparent), radial-gradient(800px 320px at 100% 0%, rgb(226 232 240), transparent); }
    </style>
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-app">
    <div class="min-h-full flex flex-col">
        @include('applicant.partials.portal-nav', ['active' => 'applications'])

        <main class="flex-1 max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
            {{-- Page intro --}}
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6 mb-8">
                <div>
                    <nav class="text-sm text-slate-500 mb-2">
                        <span class="text-slate-400">Overview</span>
                        <span class="mx-2 text-slate-300">/</span>
                        <span class="font-medium text-slate-700">Applications</span>
                    </nav>
                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">My applications</h1>
                    <p class="mt-2 text-slate-600 max-w-2xl text-sm sm:text-base leading-relaxed">
                        Welcome{{ $applicantName ? ', '.$applicantName : '' }}. Track each submission, pipeline movement, and interview details from <span class="font-medium text-slate-800">{{ $tenant->name }}</span> in one place.
                    </p>
                </div>
                <a href="{{ $careersUrl }}" class="inline-flex items-center justify-center gap-2 self-start rounded-xl px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-slate-900/10 hover:opacity-95 transition-opacity" style="background: var(--applicant-brand);">
                    <svg class="w-4 h-4 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Browse open roles
                </a>
            </div>

            @php $appCount = $applications->count(); @endphp

            {{-- KPI strip --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-10">
                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200/80 shadow-sm shadow-slate-900/5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Active submissions</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900 tabular-nums">{{ $appCount }}</p>
                    <p class="mt-1 text-sm text-slate-500">Across this organization</p>
                </div>
                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200/80 shadow-sm shadow-slate-900/5">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Account</p>
                    <p class="mt-2 text-sm font-medium text-slate-900 truncate" title="{{ auth('candidate')->user()->email }}">{{ auth('candidate')->user()->email }}</p>
                    <p class="mt-1 text-sm text-slate-500">Applicant sign-in</p>
                </div>
                <div class="rounded-2xl bg-white p-5 ring-1 ring-slate-200/80 shadow-sm shadow-slate-900/5 sm:col-span-1">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Need help?</p>
                    <p class="mt-2 text-sm text-slate-600 leading-relaxed">For questions about a role or your status, contact the hiring team at {{ $tenant->name }} directly.</p>
                </div>
            </div>

            @forelse ($applications as $application)
                @php
                    $st = strtolower((string) $application->status);
                    $statusClass = $statusStyles[$st] ?? 'bg-slate-50 text-slate-700 ring-slate-600/12';
                @endphp
                <article class="mb-8 rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-md shadow-slate-900/5 overflow-hidden">
                    <div class="px-5 sm:px-7 py-5 sm:py-6 border-b border-slate-100 bg-gradient-to-br from-white to-slate-50/80">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                                        {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                                    </span>
                                    @if($application->currentStage)
                                        <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-700 ring-1 ring-inset ring-slate-600/10">
                                            Stage: {{ $application->currentStage->name }}
                                        </span>
                                    @endif
                                </div>
                                <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ $application->jobOpening->title }}</h2>
                                <p class="mt-2 flex flex-wrap items-center gap-x-3 gap-y-1 text-sm text-slate-600">
                                    @if($application->jobOpening->department)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            {{ $application->jobOpening->department->name }}
                                        </span>
                                    @endif
                                    @php $jobLoc = $application->jobOpening->location ?? $application->jobOpening->globalLocation ?? null; @endphp
                                    @if($jobLoc)
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $jobLoc->name }}
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <div class="shrink-0 text-left sm:text-right">
                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Submitted</p>
                                <p class="mt-1 text-sm font-semibold text-slate-900">
                                    {{ optional($application->applied_at)->timezone(config('app.timezone'))->format('M j, Y') ?? $application->created_at->timezone(config('app.timezone'))->format('M j, Y') }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ optional($application->applied_at)->timezone(config('app.timezone'))->format('g:i A') ?? $application->created_at->timezone(config('app.timezone'))->format('g:i A') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 sm:px-7 py-6 sm:py-8 grid grid-cols-1 lg:grid-cols-12 gap-8">
                        {{-- Timeline --}}
                        <div class="lg:col-span-7">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <span class="h-px flex-1 max-w-[2rem] bg-slate-200 lg:hidden"></span>
                                Pipeline activity
                                <span class="h-px flex-1 bg-slate-200"></span>
                            </h3>
                            <div class="relative pl-1">
                                <div class="absolute left-[15px] top-2 bottom-2 w-px bg-slate-200" aria-hidden="true"></div>
                                <ul class="space-y-6">
                                    <li class="relative flex gap-4">
                                        <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 ring-4 ring-white">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        </span>
                                        <div class="pt-0.5 min-w-0">
                                            <p class="font-semibold text-slate-900">Application received</p>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ optional($application->applied_at)->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') ?? $application->created_at->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </li>
                                    @foreach ($application->stageEvents->sortBy('created_at') as $event)
                                        <li class="relative flex gap-4">
                                            <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-indigo-700 ring-4 ring-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                                            </span>
                                            <div class="pt-0.5 min-w-0">
                                                <p class="font-semibold text-slate-900">
                                                    @if($event->fromStage && $event->toStage)
                                                        Advanced: {{ $event->fromStage->name }} → {{ $event->toStage->name }}
                                                    @elseif($event->toStage)
                                                        Stage: {{ $event->toStage->name }}
                                                    @else
                                                        Pipeline update
                                                    @endif
                                                </p>
                                                <p class="text-sm text-slate-500 mt-0.5">{{ $event->created_at->timezone(config('app.timezone'))->format('M j, Y \a\t g:i A') }}</p>
                                                @if($event->note)
                                                    <p class="mt-2 text-sm text-slate-600 rounded-lg bg-slate-50 border border-slate-100 px-3 py-2">{{ $event->note }}</p>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        {{-- Interviews --}}
                        <div class="lg:col-span-5">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-5 flex items-center gap-2">
                                <span class="h-px flex-1 bg-slate-200"></span>
                                Interviews
                                <span class="h-px flex-1 bg-slate-200"></span>
                            </h3>
                            @if($application->interviews->isNotEmpty())
                                <ul class="space-y-3">
                                    @foreach ($application->interviews->sortBy('scheduled_at') as $interview)
                                        <li class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 hover:border-slate-300 transition-colors">
                                            <div class="flex items-start justify-between gap-3">
                                                <div>
                                                    <p class="font-semibold text-slate-900">
                                                        {{ $interview->scheduled_at?->timezone(config('app.timezone'))->format('D, M j, Y') ?? 'Date TBD' }}
                                                    </p>
                                                    <p class="text-sm text-slate-600 mt-0.5">
                                                        {{ $interview->scheduled_at?->timezone(config('app.timezone'))->format('g:i A') ?? '' }}
                                                        @if($interview->duration_minutes)
                                                            <span class="text-slate-400">·</span> {{ $interview->duration_minutes }} min
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-slate-500 mt-2 capitalize">{{ str_replace('_', ' ', $interview->mode ?? 'Interview') }} · {{ ucfirst($interview->status ?? 'scheduled') }}</p>
                                                </div>
                                                @if($interview->meeting_link)
                                                    <a href="{{ $interview->meeting_link }}" target="_blank" rel="noopener" class="shrink-0 inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-2 text-xs font-semibold text-slate-800 ring-1 ring-slate-200 hover:bg-slate-50">
                                                        Join
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                    </a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="rounded-xl border border-dashed border-slate-200 bg-slate-50/50 px-4 py-8 text-center">
                                    <p class="text-sm text-slate-500">No interviews scheduled yet. When the team schedules one, it will appear here.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl bg-white ring-1 ring-slate-200/80 shadow-md shadow-slate-900/5 px-6 py-16 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-500 mb-5">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900">No applications yet</h2>
                    <p class="mt-2 text-slate-600 text-sm max-w-md mx-auto">Once you submit an application to {{ $tenant->name }}, your progress and updates will show here.</p>
                    <a href="{{ $careersUrl }}" class="mt-8 inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-semibold text-white shadow-md" style="background: var(--applicant-brand);">
                        View career opportunities
                    </a>
                </div>
            @endforelse
        </main>

        <footer class="mt-auto border-t border-slate-200/80 bg-white/80 py-6">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <p>© {{ date('Y') }} {{ $tenant->name }}. All rights reserved.</p>
                <p class="text-center sm:text-right">You are signed in to the applicant portal only for this organization.</p>
            </div>
        </footer>
    </div>
</body>
</html>
