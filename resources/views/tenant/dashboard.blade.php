<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        @php
            $breadcrumbs = [
                ['label' => 'Dashboard', 'url' => null]
            ];
        @endphp
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>

    <div class="space-y-4">
        <!-- Page header -->
        <div>
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <p class="mt-1 text-sm text-white">
                Welcome back! Here's what's happening with your hiring process.
            </p>
            
            <!-- User Role and Subscription Information -->
            <div class="mt-3 flex flex-wrap items-center gap-3">
                <!-- User Role -->
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if(auth()->user()->hasRole('Owner')) bg-purple-100 text-purple-800
                    @elseif(auth()->user()->hasRole('Admin')) bg-blue-100 text-blue-800
                    @elseif(auth()->user()->hasRole('Recruiter')) bg-green-100 text-green-800
                    @elseif(auth()->user()->hasRole('Hiring Manager')) bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800 @endif">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                    {{ auth()->user()->roles->first()->name ?? 'No Role' }}
                </div>

                <!-- Subscription Plan (for Owners only) -->
                @if(auth()->user()->hasRole('Owner') && $tenant->activeSubscription)
                    @php
                        $plan = $tenant->activeSubscription->plan;
                        $planColors = [
                            'free' => 'bg-gray-100 text-gray-800',
                            'pro' => 'bg-blue-100 text-blue-800',
                            'enterprise' => 'bg-purple-100 text-purple-800'
                        ];
                        $planColor = $planColors[$plan->slug] ?? 'bg-gray-100 text-gray-800';
                    @endphp
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $planColor }}">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $plan->name }} Plan
                        @if($plan->isFree())
                            <span class="ml-1 text-green-600 font-semibold">(Free)</span>
                        @else
                            <span class="ml-1 font-semibold">(@subscriptionPrice($plan->price, $plan->currency)/month)</span>
                        @endif
                    </div>
                @endif
            </div>
        </div>


        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
            <!-- Open Jobs -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Open Jobs</dt>
                            <dd class="text-lg font-medium text-black">{{ $openJobsCount }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Active Candidates -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Active Candidates</dt>
                            <dd class="text-lg font-medium text-black">{{ $activeCandidatesCount }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Interviews This Week -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">In Interview Stage</dt>
                            <dd class="text-lg font-medium text-black">{{ $interviewsThisWeek }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Hires -->
            <x-card class="hover:shadow-md transition-shadow">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-black truncate">Hired</dt>
                            <dd class="text-lg font-medium text-black">{{ $hiresThisMonth }}</dd>
                        </dl>
                    </div>
                </div>
            </x-card>

            <!-- Subscription Status (for Owners only) -->
            @if(auth()->user()->hasRole('Owner') && $tenant->activeSubscription)
                @php
                    $plan = $tenant->activeSubscription->plan;
                    $planColors = [
                        'free' => 'bg-gray-100 text-gray-600',
                        'pro' => 'bg-blue-100 text-blue-600',
                        'enterprise' => 'bg-purple-100 text-purple-600'
                    ];
                    $planColor = $planColors[$plan->slug] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <x-card class="hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 {{ $planColor }} rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-black truncate">Current Plan</dt>
                                <dd class="text-lg font-medium text-black">
                                    {{ $plan->name }}
                                    @if($plan->isFree())
                                        <span class="text-green-600">(Free)</span>
                                    @else
                                        <span class="text-gray-500">(@subscriptionPrice($plan->price, $plan->currency))</span>
                                    @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                </x-card>
            @endif
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Applications -->
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-black">Recent Applications</h3>
                        <a href="{{ route('tenant.candidates.index', $tenant->slug) }}" class="text-sm text-blue-600 hover:text-blue-700">
                            View all
                        </a>
                    </div>
                </x-slot>

                @if($recentApplications->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentApplications as $application)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 last:border-b-0">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-black truncate">
                                        {{ $application->candidate->full_name }}
                                    </p>
                                    <p class="text-sm text-black">
                                        {{ $application->jobOpening->title }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-600 text-white',
                                            'applied' => 'bg-green-600 text-white',
                                            'hired' => 'bg-blue-100 text-blue-800',
                                            'rejected' => 'bg-red-100 text-red-800',
                                            'withdrawn' => 'bg-gray-100 text-gray-800',
                                        ];
                                        $statusColor = $statusColors[$application->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    <span class="text-xs text-black">
                                        {{ $application->applied_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty 
                        icon="user-group"
                        title="No applications yet"
                        description="Applications will appear here once candidates start applying."
                        :action="route('tenant.candidates.index', $tenant->slug)"
                        actionText="View Candidates"
                    />
                @endif
            </x-card>

            <!-- Quick Actions -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-medium text-black">Quick Actions</h3>
                </x-slot>

                <div class="space-y-3">
                    <x-auth.for permission="create jobs">
                        <a href="{{ route('tenant.jobs.create', $tenant->slug) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-black">New Job</p>
                                <p class="text-sm text-black">Create a new job posting</p>
                            </div>
                        </a>
                    </x-auth.for>

                    <x-auth.for permission="import candidates">
                        <a href="{{ route('tenant.candidates.import', $tenant->slug) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-black">Import Candidates</p>
                                <p class="text-sm text-black">Upload candidate data</p>
                            </div>
                        </a>
                    </x-auth.for>

                    <x-auth.for permission="view analytics">
                        <a href="{{ route('tenant.analytics.index', $tenant->slug) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-black">Analytics Dashboard</p>
                                <p class="text-sm text-black">View hiring metrics and insights</p>
                            </div>
                        </a>
                    </x-auth.for>

                    <x-auth.for permission="manage email templates">
                        <a href="{{ route('tenant.email-templates.create', $tenant->slug) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-black">Create Email Template</p>
                                <p class="text-sm text-black">Design communication templates</p>
                            </div>
                        </a>
                    </x-auth.for>

                    <x-auth.for permission="manage users">
                        <a href="{{ route('tenant.settings.team', $tenant->slug) }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-black">Manage Team</p>
                                <p class="text-sm text-black">Add users and assign roles</p>
                            </div>
                        </a>
                    </x-auth.for>
                </div>
            </x-card>
        </div>
    </div>
</x-app-layout>