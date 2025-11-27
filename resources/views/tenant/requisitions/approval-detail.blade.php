@php
    $tenant = tenant();
    $tenantSlug = $tenant->slug;
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => tenantRoute('tenant.dashboard', $tenantSlug)],
        ['label' => 'Recruiting', 'url' => tenantRoute('tenant.recruiting.index', $tenantSlug)],
        ['label' => 'Requisitions', 'url' => tenantRoute('tenant.requisitions.index', $tenantSlug)],
        ['label' => 'Pending Approvals', 'url' => tenantRoute('tenant.requisitions.pending-approvals', $tenantSlug)],
        ['label' => 'Approval Detail', 'url' => null]
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
                        <h1 class="text-2xl font-bold text-black">{{ $requisition->job_title }}</h1>
                        <p class="mt-1 text-sm text-black">Requisition #{{ $requisition->id }} - Approval Level {{ $requisition->approval_level }}</p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($requisition->approval_status === 'Approved') 
                                bg-green-100 text-green-800
                            @elseif($requisition->approval_status === 'Pending') 
                                bg-yellow-100 text-yellow-800
                            @elseif($requisition->approval_status === 'Rejected') 
                                bg-red-100 text-red-800
                            @elseif($requisition->approval_status === 'ChangesRequested') 
                                bg-orange-100 text-orange-800
                            @else 
                                bg-gray-100 text-gray-800
                            @endif">
                            {{ $requisition->approval_status }}
                        </span>
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
                        <h2 class="text-lg font-semibold text-black">Requisition Information</h2>
                    </div>

                    <div class="px-6 py-6 space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Job Title</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->job_title }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Department</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->department ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Location</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->location ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Headcount</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->headcount }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Budget Range</dt>
                                <dd class="text-sm text-gray-900">
                                    ${{ number_format($requisition->budget_min) }} - ${{ number_format($requisition->budget_max) }}
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Priority</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($requisition->priority === 'High') bg-red-100 text-red-800
                                        @elseif($requisition->priority === 'Medium') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $requisition->priority }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Contract Type</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->contract_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Requested By</dt>
                                <dd class="text-sm text-gray-900">{{ $requisition->creator->name ?? 'N/A' }}</dd>
                            </div>
                        </div>

                        @if($requisition->justification)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Justification</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $requisition->justification }}</dd>
                            </div>
                        @endif

                        @if($requisition->additional_notes)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Additional Notes</dt>
                                <dd class="text-sm text-gray-900 whitespace-pre-wrap">{{ $requisition->additional_notes }}</dd>
                            </div>
                        @endif

                        @php
                            $skills = json_decode($requisition->skills, true);
                        @endphp
                        @if($skills && is_array($skills) && count($skills) > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Required Skills</dt>
                                <dd class="text-sm text-gray-900">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($skills as $skill)
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-md text-xs">{{ $skill }}</span>
                                        @endforeach
                                    </div>
                                </dd>
                            </div>
                        @endif

                        @if($requisition->attachments && $requisition->attachments->count() > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-600 mb-1">Attachments</dt>
                                <dd class="text-sm text-gray-900">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($requisition->attachments as $attachment)
                                            <li>
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($attachment->path) }}" 
                                                   target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800">
                                                    {{ $attachment->filename }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </dd>
                            </div>
                        @endif
                    </div>
                </x-card>

                <!-- Approval Actions -->
                @if($requisition->approval_status === 'Pending' && $requisition->current_approver_id === auth()->id())
                    <x-card>
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-black">Take Action</h2>
                        </div>

                        <div class="px-6 py-6 space-y-4">
                            <!-- Approve Button -->
                            <div>
                                <button onclick="showApproveModal()" 
                                        class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                    Approve
                                </button>
                            </div>

                            <!-- Request Changes Button -->
                            <div>
                                <button onclick="showRequestChangesModal()" 
                                        class="w-full bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                                    Request Changes
                                </button>
                            </div>

                            <!-- Reject Button -->
                            <div>
                                <button onclick="showRejectModal()" 
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    Reject
                                </button>
                            </div>

                            <!-- Delegate Button -->
                            <div>
                                <button onclick="showDelegateModal()" 
                                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    Delegate
                                </button>
                            </div>
                        </div>
                    </x-card>
                @endif

                <!-- Approval History Timeline -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-black">Approval History</h2>
                    </div>

                    <div class="px-6 py-6">
                        @if($approvals && $approvals->count() > 0)
                            <div class="space-y-4">
                                @foreach($approvals as $approval)
                                    <div class="flex items-start space-x-4 pb-4 border-b border-gray-200 last:border-0">
                                        <div class="flex-shrink-0">
                                            @if($approval->action === 'Approved')
                                                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @elseif($approval->action === 'Rejected')
                                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @elseif($approval->action === 'RequestedChanges')
                                                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @elseif($approval->action === 'Delegated')
                                                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                                                    </svg>
                                                </div>
                                            @else
                                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $approval->approver->name ?? 'N/A' }}
                                                @if($approval->action === 'Delegated' && $approval->delegate)
                                                    delegated to {{ $approval->delegate->name }}
                                                @endif
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                {{ ucfirst(str_replace('RequestedChanges', 'Requested Changes', $approval->action)) }} 
                                                (Level {{ $approval->approval_level }})
                                            </p>
                                            @if($approval->comments)
                                                <p class="text-sm text-gray-700 mt-1">{{ $approval->comments }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-1">
                                                {{ $approval->created_at->format('M j, Y g:i A') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">No approval history yet.</p>
                        @endif
                    </div>
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Summary Card -->
                <x-card>
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-black">Summary</h2>
                    </div>
                    <div class="px-6 py-6 space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Status</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $requisition->approval_status }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Current Approver</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $requisition->currentApprover->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Approval Level</dt>
                            <dd class="text-sm text-gray-900 mt-1">Level {{ $requisition->approval_level }}</dd>
                        </div>
                        @if($requisition->approved_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-600">Approved At</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $requisition->approved_at->format('M j, Y g:i A') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-600">Created</dt>
                            <dd class="text-sm text-gray-900 mt-1">{{ $requisition->created_at->format('M j, Y') }}</dd>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>

    <!-- Modals will be added via JavaScript -->
    @push('scripts')
    <script>
        const requisitionId = {{ $requisition->id }};
        const tenantSlug = '{{ $tenantSlug }}';

        function showApproveModal() {
            if (confirm('Are you sure you want to approve this requisition?')) {
                submitApproval('approve', '');
            }
        }

        function showRequestChangesModal() {
            const comments = prompt('Please provide comments on what changes are needed:');
            if (comments && comments.trim().length >= 10) {
                submitApproval('request-changes', comments.trim());
            } else if (comments !== null) {
                alert('Comments must be at least 10 characters long.');
            }
        }

        function showRejectModal() {
            const comments = prompt('Please provide a reason for rejection (required):');
            if (comments && comments.trim().length >= 10) {
                if (confirm('Are you sure you want to reject this requisition?')) {
                    submitApproval('reject', comments.trim());
                }
            } else if (comments !== null) {
                alert('Comments must be at least 10 characters long.');
            }
        }

        function showDelegateModal() {
            // In a real implementation, this would show a user selection modal
            const delegateId = prompt('Enter the user ID to delegate to:');
            if (delegateId) {
                submitDelegation(delegateId);
            }
        }

        function submitApproval(action, comments) {
            fetch(`/${tenantSlug}/api/requisitions/${requisitionId}/${action}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    comments: comments
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Action completed successfully.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to complete action.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }

        function submitDelegation(delegateId) {
            fetch(`/${tenantSlug}/api/requisitions/${requisitionId}/delegate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    delegate_to_user_id: delegateId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Delegation completed successfully.');
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to delegate.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        }
    </script>
    @endpush
</x-app-layout>

