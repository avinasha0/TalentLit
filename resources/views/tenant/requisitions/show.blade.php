@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Requisition Details', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page Header -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-black">{{ $requisition->job_title ?? 'Requisition Details' }}</h1>
                        <p class="mt-1 text-sm text-black">View complete requisition information</p>
                    </div>
                    <div>
                        <a href="{{ tenantRoute('tenant.requisitions.index', $tenantSlug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            Back to Requisitions
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Requisition Details -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-semibold text-black">Requisition Information</h2>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                @if($requisition->status === 'Approved') 
                                    bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                @elseif($requisition->status === 'Pending') 
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                @elseif($requisition->status === 'Rejected') 
                                    bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                @elseif($requisition->status === 'Draft') 
                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @else 
                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                @endif">
                                {{ $requisition->status }}
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <!-- Job Title -->
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Job Title</dt>
                            <dd class="text-sm text-gray-900">{{ $requisition->job_title ?? 'N/A' }}</dd>
                        </div>

                        <!-- Department -->
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Department</dt>
                            <dd class="text-sm text-gray-900">{{ $requisition->department ?? 'N/A' }}</dd>
                        </div>

                        <!-- Headcount -->
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Headcount</dt>
                            <dd class="text-sm text-gray-900">{{ $requisition->headcount ?? 'N/A' }}</dd>
                        </div>

                        <!-- Justification -->
                        @if($requisition->justification)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Justification</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $requisition->justification }}</dd>
                            </div>
                        @endif

                        <!-- Budget Range -->
                        @if($requisition->budget_min || $requisition->budget_max)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Budget Range</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($requisition->budget_min && $requisition->budget_max)
                                        ${{ number_format($requisition->budget_min) }} - ${{ number_format($requisition->budget_max) }}
                                    @elseif($requisition->budget_min)
                                        ${{ number_format($requisition->budget_min) }} (minimum)
                                    @elseif($requisition->budget_max)
                                        ${{ number_format($requisition->budget_max) }} (maximum)
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        @endif

                        <!-- Contract Type -->
                        @if($requisition->contract_type)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Contract Type</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->contract_type }}</dd>
                            </div>
                        @endif

                        <!-- Skills -->
                        @if($requisition->skills)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Required Skills</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $requisition->skills }}</dd>
                            </div>
                        @endif

                        <!-- Experience Range -->
                        @if($requisition->experience_min || $requisition->experience_max)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Experience Required</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($requisition->experience_min && $requisition->experience_max)
                                        {{ $requisition->experience_min }} - {{ $requisition->experience_max }} years
                                    @elseif($requisition->experience_min)
                                        {{ $requisition->experience_min }}+ years (minimum)
                                    @elseif($requisition->experience_max)
                                        Up to {{ $requisition->experience_max }} years
                                    @else
                                        N/A
                                    @endif
                                </dd>
                            </div>
                        @endif
                    </div>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status Information -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-black">Status Information</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Current Status</dt>
                            <dd class="text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium
                                    @if($requisition->status === 'Approved') 
                                        bg-green-100 text-green-800
                                    @elseif($requisition->status === 'Pending') 
                                        bg-yellow-100 text-yellow-800
                                    @elseif($requisition->status === 'Rejected') 
                                        bg-red-100 text-red-800
                                    @elseif($requisition->status === 'Draft') 
                                        bg-gray-100 text-gray-800
                                    @else 
                                        bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $requisition->status }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600 mb-1">Created</dt>
                            <dd class="text-sm text-gray-900">{{ $requisition->created_at->format('M j, Y \a\t g:i A') }}</dd>
                        </div>
                        @if($requisition->updated_at && $requisition->updated_at->ne($requisition->created_at))
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Last Updated</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->updated_at->format('M j, Y \a\t g:i A') }}</dd>
                            </div>
                        @endif
                        @if($requisition->creator)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Created By</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->creator->name ?? 'N/A' }}</dd>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Actions -->
                @if($requisition->status === 'Pending')
                    <x-card>
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-black">Actions</h3>
                        </div>
                        <div class="px-6 py-4 space-y-3">
                            <form method="POST" action="{{ tenantRoute('tenant.requisitions.approve', [$tenantSlug, $requisition->id]) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-green-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors hover:bg-green-700"
                                        onclick="return confirm('Are you sure you want to approve this requisition?')">
                                    Approve Requisition
                                </button>
                            </form>
                            <form method="POST" action="{{ tenantRoute('tenant.requisitions.reject', [$tenantSlug, $requisition->id]) }}">
                                @csrf
                                <button type="submit" 
                                        class="w-full bg-red-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 transition-colors hover:bg-red-700"
                                        onclick="return confirm('Are you sure you want to reject this requisition?')">
                                    Reject Requisition
                                </button>
                            </form>
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

