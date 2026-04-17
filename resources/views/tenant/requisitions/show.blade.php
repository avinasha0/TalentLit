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
                @if($requisition->approval_status === 'Draft' || $requisition->approval_status === 'ChangesRequested')
                    @if($requisition->created_by === auth()->id() || auth()->user()->hasAnyRole(['Owner', 'Admin'], tenant_id()))
                        <x-card>
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-black">Actions</h3>
                            </div>
                            <div class="px-6 py-4 space-y-3">
                                <button type="button" 
                                        id="submit-for-approval-btn"
                                        class="w-full bg-purple-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 transition-colors hover:bg-purple-700">
                                    Submit for Approval
                                </button>
                                <a href="{{ tenantRoute('tenant.requisitions.edit', [$tenantSlug, $requisition->id]) }}"
                                   class="block w-full bg-gray-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors hover:bg-gray-700">
                                    Edit Requisition
                                </a>
                            </div>
                        </x-card>
                    @endif
                @elseif($requisition->approval_status === 'Pending' && $requisition->current_approver_id === auth()->id())
                    <x-card>
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-black">Actions</h3>
                        </div>
                        <div class="px-6 py-4">
                            <a href="{{ tenantRoute('tenant.requisitions.approval', [$tenantSlug, $requisition->id]) }}"
                               class="block w-full bg-blue-600 text-white text-center py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors hover:bg-blue-700">
                                Review & Approve
                            </a>
                        </div>
                    </x-card>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        (function() {
            // Prevent any beforeunload handlers from interfering
            let isSubmitting = false;
            
            // Override any existing beforeunload handlers for this action
            window.addEventListener('beforeunload', function(e) {
                if (isSubmitting) {
                    // Allow navigation if we're submitting
                    return;
                }
            }, { capture: true });
            
            document.addEventListener('DOMContentLoaded', function() {
                const submitBtn = document.getElementById('submit-for-approval-btn');
                if (submitBtn) {
                    submitBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();
                        
                        const requisitionId = {{ $requisition->id }};
                        
                        if (!confirm('Are you sure you want to submit this requisition for approval? Once submitted, you will not be able to edit it until changes are requested.')) {
                            return false;
                        }

                        // Set flag to prevent beforeunload warnings
                        isSubmitting = true;
                        
                        // Disable button to prevent double-clicks
                        submitBtn.disabled = true;
                        const originalText = submitBtn.textContent;
                        submitBtn.textContent = 'Submitting...';

                        // Determine if we're on subdomain or path-based routing
                        const hostname = window.location.hostname;
                        const isSubdomain = hostname.includes('.localhost') || (hostname.split('.').length > 2 && !hostname.startsWith('localhost'));
                        const submitUrl = isSubdomain 
                            ? `/api/requisitions/${requisitionId}/submit`
                            : `/{{ $tenantSlug }}/api/requisitions/${requisitionId}/submit`;
                        
                        console.log('Submitting requisition for approval:', {
                            requisitionId: requisitionId,
                            url: submitUrl,
                            isSubdomain: isSubdomain
                        });
                        
                        fetch(submitUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Failed to submit requisition');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Submit response:', data);
                            if (data.success) {
                                alert('Requisition submitted for approval successfully.');
                                // Reload after a short delay to ensure the alert is seen
                                setTimeout(() => {
                                    window.location.reload();
                                }, 100);
                            } else {
                                alert(data.message || 'Failed to submit requisition for approval.');
                                isSubmitting = false;
                                submitBtn.disabled = false;
                                submitBtn.textContent = originalText;
                            }
                        })
                        .catch(error => {
                            console.error('Error submitting requisition:', error);
                            alert('An error occurred: ' + error.message);
                            isSubmitting = false;
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        });
                        
                        return false;
                    }, { capture: true });
                }
            });
        })();
    </script>
    @endpush
</x-app-layout>

