<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Jobs', 'url' => route('tenant.jobs.index', $tenant->slug)],
                ['label' => $job->title, 'url' => route('tenant.jobs.show', [$tenant->slug, $job->id])],
                ['label' => 'Pipeline', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-6">
        <!-- Page header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">{{ $job->title }} - Pipeline</h1>
                <p class="mt-1 text-sm text-white">
                    Drag and drop applications between stages to manage your hiring pipeline.
                </p>
            </div>
            
            <div class="flex items-center space-x-3">
                <button id="refresh-btn" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Refresh
                </button>
                
                <a href="{{ route('tenant.jobs.show', [$tenant->slug, $job->id]) }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Job
                </a>
            </div>
        </div>

        <!-- Pipeline Board -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6">
                
                <div id="pipeline-board" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5 gap-6 min-h-96">
                    @foreach($stages as $stage)
                        @php
                            $stageApplications = $applicationsByStage->get($stage->id, collect());
                            $stageCount = $stageApplications->count();
                        @endphp
                        
                        
                        <div class="bg-gray-50 rounded-lg border-2 border-gray-200 min-h-96" 
                             data-stage-id="{{ $stage->id }}"
                             ondrop="drop(event)" 
                             ondragover="allowDrop(event)">
                            
                            <!-- Stage Header -->
                            <div class="p-4 border-b border-gray-200 bg-white rounded-t-lg">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold text-gray-900">{{ $stage->name }}</h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $stageCount }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Applications Container -->
                            <div class="p-4 space-y-3 min-h-80" id="stage-{{ $stage->id }}-applications">
                                @forelse($stageApplications as $application)
                                    @if($application->candidate)
                                    <div class="bg-white rounded-lg border border-gray-200 p-4 cursor-move hover:shadow-md transition-shadow"
                                         draggable="true"
                                         data-application-id="{{ $application->id }}"
                                         data-stage-id="{{ $stage->id }}"
                                         ondragstart="drag(event)"
                                         ondragend="dragEnd(event)">
                                        
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1 min-w-0">
                                                <a href="{{ route('tenant.candidates.show', ['tenant' => $tenant->slug, 'candidate' => $application->candidate->id]) }}" 
                                                   class="block hover:bg-gray-50 rounded-md p-1 -m-1 transition-colors">
                                                    <h4 class="text-sm font-medium text-gray-900 truncate hover:text-blue-600">
                                                        {{ $application->candidate->full_name }}
                                                    </h4>
                                                    <p class="text-sm text-gray-500 truncate">
                                                        {{ $application->candidate->email }}
                                                    </p>
                                                    <p class="text-xs text-gray-400 mt-1">
                                                        Applied {{ $application->applied_at->diffForHumans() }}
                                                    </p>
                                                </a>
                                            </div>
                                            
                                            @php
                                                $statusColors = [
                                                    'active' => 'bg-green-100 text-green-800',
                                                    'hired' => 'bg-blue-100 text-blue-800',
                                                    'rejected' => 'bg-red-100 text-red-800',
                                                    'withdrawn' => 'bg-gray-100 text-gray-800',
                                                ];
                                                $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                            @endphp
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                        
                                        <!-- Loading indicator (hidden by default) -->
                                        <div class="hidden loading-indicator mt-2">
                                            <div class="flex items-center justify-center">
                                                <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span class="ml-2 text-xs text-gray-500">Moving...</span>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <!-- Application without candidate -->
                                    <div class="bg-red-50 rounded-lg border border-red-200 p-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <p class="text-sm font-medium text-red-800">Application #{{ $application->id }}</p>
                                                <p class="text-xs text-red-600">No candidate data available</p>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                @empty
                                    <div class="text-center py-8 text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                        </svg>
                                        <p class="mt-2 text-sm">No applications in this stage</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Events Sidebar -->
        @if($recentEvents->count() > 0)
            <div class="bg-white rounded-lg shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Activity</h3>
                    <div class="space-y-3">
                        @foreach($recentEvents as $event)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $event->application->candidate->full_name }}</span>
                                        @if($event->fromStage && $event->toStage)
                                            moved from <span class="font-medium">{{ $event->fromStage->name }}</span> to <span class="font-medium">{{ $event->toStage->name }}</span>
                                        @elseif($event->toStage)
                                            moved to <span class="font-medium">{{ $event->toStage->name }}</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        by {{ $event->user->name }} • {{ $event->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- CSRF Token for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}'
        };
    </script>
</x-app-layout>

<script>
// Drag and Drop functionality
let draggedElement = null;

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    console.log('=== DRAG START ===');
    console.log('Drag started on:', ev.target);
    console.log('Target dataset:', ev.target.dataset);
    draggedElement = ev.target;
    ev.target.style.opacity = '0.5';
    ev.dataTransfer.effectAllowed = 'move';
    ev.dataTransfer.setData('text/html', ev.target.outerHTML);
    console.log('Dragged element set:', draggedElement);
}

function dragEnd(ev) {
    console.log('=== DRAG END ===');
    console.log('Drag ended on:', ev.target);
    ev.target.style.opacity = '1';
    if (draggedElement) {
        console.log('Clearing dragged element');
        draggedElement = null;
    }
}

function drop(ev) {
    console.log('=== DROP EVENT ===');
    console.log('Drop event triggered on:', ev.currentTarget);
    console.log('Event target:', ev.target);
    console.log('Dragged element:', draggedElement);
    
    ev.preventDefault();
    ev.stopPropagation();
    
    if (!draggedElement) {
        console.error('ERROR: No dragged element found!');
        return;
    }
    
    const targetStage = ev.currentTarget;
    const targetStageId = targetStage.dataset.stageId;
    const applicationId = draggedElement.dataset.applicationId;
    const fromStageId = draggedElement.dataset.stageId;
    
    console.log('Drop data:', {
        targetStageId,
        applicationId,
        fromStageId,
        targetStage: targetStage,
        draggedElement: draggedElement
    });
    
    // Don't do anything if dropped in the same stage
    if (fromStageId === targetStageId) {
        console.log('Same stage, ignoring');
        draggedElement.style.opacity = '1';
        draggedElement = null;
        return;
    }
    
    console.log('Moving to different stage...');
    
    // Show loading indicator
    const loadingIndicator = draggedElement.querySelector('.loading-indicator');
    if (loadingIndicator) {
        console.log('Showing loading indicator');
        loadingIndicator.classList.remove('hidden');
    } else {
        console.log('No loading indicator found');
    }
    
    // Move the element visually
    const targetContainer = targetStage.querySelector(`[id^="stage-${targetStageId}-applications"]`);
    if (!targetContainer) {
        console.error('ERROR: Target container not found for stage:', targetStageId);
        console.log('Available containers:', document.querySelectorAll('[id^="stage-"]'));
        return;
    }
    
    console.log('Moving element to target container:', targetContainer);
    targetContainer.appendChild(draggedElement);
    
    // Update the stage ID
    draggedElement.dataset.stageId = targetStageId;
    console.log('Updated stage ID to:', targetStageId);
    
    // Send AJAX request
    const requestData = {
        application_id: applicationId,
        from_stage_id: fromStageId,
        to_stage_id: targetStageId,
        to_position: 0
    };
    
    console.log('=== SENDING AJAX REQUEST ===');
    console.log('Request data:', requestData);
    console.log('CSRF Token:', window.Laravel.csrfToken);
    console.log('URL:', `/{{ $tenant->slug }}/jobs/{{ $job->id }}/pipeline/move`);
    
    fetch(`/{{ $tenant->slug }}/jobs/{{ $job->id }}/pipeline/move`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.Laravel.csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify(requestData)
    })
    .then(response => {
        console.log('=== AJAX RESPONSE ===');
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        if (!response.ok) {
            console.error('HTTP error! status:', response.status);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('=== RESPONSE DATA ===');
        console.log('Response data:', data);
        if (data.ok) {
            console.log('Move successful, updating UI...');
            // Update stage counts
            updateStageCounts(data.counts);
            
            // Hide loading indicator
            if (loadingIndicator) {
                loadingIndicator.classList.add('hidden');
            }
            
            // Show success message
            showNotification('Application moved successfully', 'success');
        } else {
            console.error('Move failed:', data.message);
            // Revert the move on error
            revertMove(draggedElement, fromStageId);
            showNotification('Failed to move application: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('=== AJAX ERROR ===');
        console.error('Error details:', error);
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        // Revert the move on error
        revertMove(draggedElement, fromStageId);
        showNotification('Failed to move application: ' + error.message, 'error');
    })
    .finally(() => {
        draggedElement.style.opacity = '1';
        draggedElement = null;
    });
}

function revertMove(element, originalStageId) {
    const originalStage = document.querySelector(`[data-stage-id="${originalStageId}"]`);
    const originalContainer = originalStage.querySelector(`[id^="stage-${originalStageId}-applications"]`);
    originalContainer.appendChild(element);
    element.dataset.stageId = originalStageId;
}

function updateStageCounts(counts) {
    Object.keys(counts).forEach(stageId => {
        const countElement = document.querySelector(`[data-stage-id="${stageId}"] .bg-blue-100`);
        if (countElement) {
            countElement.textContent = counts[stageId];
        }
    });
}

function showNotification(message, type) {
    // Simple notification - you can enhance this with a proper notification system
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-4 py-2 rounded-md text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Refresh functionality
document.getElementById('refresh-btn').addEventListener('click', function() {
    fetch(`/{{ $tenant->slug }}/jobs/{{ $job->id }}/pipeline.json`)
        .then(response => response.json())
        .then(data => {
            // Reload the page with fresh data
            window.location.reload();
        })
        .catch(error => {
            console.error('Error refreshing pipeline:', error);
            showNotification('Failed to refresh pipeline', 'error');
        });
});

// Prevent default drag behavior on images and other elements
document.addEventListener('dragstart', function(e) {
    if (e.target.tagName === 'IMG' || e.target.tagName === 'A') {
        e.preventDefault();
    }
});
</script>
