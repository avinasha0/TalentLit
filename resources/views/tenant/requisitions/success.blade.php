@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Success', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full space-y-8">
            <div class="rounded-2xl bg-slate-900 px-6 sm:px-10 py-10 text-center text-white shadow-xl border border-slate-700/80">
                <!-- Success Icon -->
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-emerald-500/20 mb-6">
                    <svg class="h-12 w-12 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <!-- Success Message (Tasks 82-84) -->
                <h1 class="text-3xl font-bold text-white mb-4">
                    Requisition Created Successfully!
                </h1>
                <p class="text-lg text-white/90 mb-2">
                    @if($requisition->approval_status === 'Pending')
                        Your requisition has been submitted for approval.
                    @else
                        Your requisition has been created and saved as draft.
                    @endif
                </p>
                <p class="text-sm text-white/75 mb-8">
                    Requisition ID: <span class="font-semibold text-white">{{ $requisition->id }}</span>
                </p>

                <!-- Requisition Summary -->
                <div class="rounded-xl bg-black/30 border border-white/10 p-6 text-left">
                    <h2 class="text-lg font-semibold text-white mb-4">Requisition Summary</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-white/60">Job Title</p>
                            <p class="text-sm font-medium text-white">{{ $requisition->job_title }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">Department</p>
                            <p class="text-sm font-medium text-white">{{ $requisition->department }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">Headcount</p>
                            <p class="text-sm font-medium text-white">{{ $requisition->headcount }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">Priority</p>
                            <p class="text-sm font-medium text-white">{{ $requisition->priority }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-white/60">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($requisition->approval_status === 'Pending') bg-amber-500/25 text-amber-100
                                @elseif($requisition->approval_status === 'Approved') bg-emerald-500/25 text-emerald-100
                                @elseif($requisition->approval_status === 'Rejected') bg-red-500/25 text-red-100
                                @else bg-white/15 text-white
                                @endif">
                                {{ $requisition->approval_status ?? $requisition->status }}
                            </span>
                        </div>
                        @if($requisition->approval_status === 'Draft')
                        <div class="md:col-span-2">
                            <p class="text-sm text-white/60 mb-2">Next Steps</p>
                            <p class="text-sm text-white/85">
                                To submit this requisition for approval, go to the requisition details page and click "Submit for Approval".
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons (Task 85) -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if($requisition->approval_status === 'Draft')
                    <a href="{{ tenantRoute('tenant.requisitions.show', [$tenantSlug, $requisition->id]) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        View Requisition & Submit for Approval
                    </a>
                    @endif
                    <a href="{{ tenantRoute('tenant.requisitions.index', $tenantSlug) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        Go to All Requisitions
                    </a>
                    <a href="{{ tenantRoute('tenant.requisitions.create', $tenantSlug) }}"
                       class="inline-flex items-center justify-center px-6 py-3 border border-slate-600 text-base font-medium rounded-md text-white bg-slate-800 hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 dark:focus:ring-offset-gray-900 focus:ring-purple-500">
                        Create Another Requisition
                    </a>
            </div>
        </div>
    </div>
</x-app-layout>

