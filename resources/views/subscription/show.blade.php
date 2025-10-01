@extends('layouts.app')

@section('title', 'Subscription - HireHub')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Subscription Management</h1>
            <p class="mt-2 text-gray-600">Manage your subscription and view usage statistics.</p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <svg class="w-5 h-5 text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Current Plan -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Current Plan</h2>
                    
                    @if($subscription && $plan)
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                            <p class="text-gray-600">{{ $plan->description }}</p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-gray-900">
                                ${{ number_format($plan->price, 0) }}
                                <span class="text-lg text-gray-600">/{{ $plan->billing_cycle }}</span>
                            </div>
                            <div class="text-sm text-gray-500">
                                @if($subscription->expires_at)
                                    Expires {{ $subscription->expires_at->format('M j, Y') }}
                                @else
                                    No expiration
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Plan Features -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_users == -1 ? 'Unlimited' : $plan->max_users }} Users
                            </span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_job_openings == -1 ? 'Unlimited' : $plan->max_job_openings }} Job Openings
                            </span>
                        </div>

                        @if($plan->analytics_enabled)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Advanced Analytics</span>
                        </div>
                        @endif

                        @if($plan->custom_branding)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Custom Branding</span>
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-4">
                        <a href="{{ route('subscription.pricing') }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                            Change Plan
                        </a>
                        
                        @if(!$plan->isFree())
                        <form method="POST" action="{{ route('subscription.cancel') }}" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
                                    onclick="return confirm('Are you sure you want to cancel your subscription?')">
                                Cancel Subscription
                            </button>
                        </form>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Active Subscription</h3>
                        <p class="text-gray-600 mb-6">You don't have an active subscription. Choose a plan to get started.</p>
                        <a href="{{ route('subscription.pricing') }}" 
                           class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200">
                            View Plans
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Usage Statistics -->
            @if($usage)
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Usage Statistics</h2>
                    
                    <div class="space-y-6">
                        @foreach($usage as $key => $stat)
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-700 capitalize">
                                    {{ str_replace('_', ' ', $key) }}
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $stat['current'] }} / {{ $stat['limit'] == -1 ? '∞' : $stat['limit'] }}
                                </span>
                            </div>
                            
                            @if($stat['limit'] != -1)
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                @php
                                    $percentage = min(100, ($stat['current'] / $stat['limit']) * 100);
                                @endphp
                                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                            
                            @if($stat['remaining'] <= 0)
                            <p class="text-xs text-red-600 mt-1">Limit reached</p>
                            @elseif($stat['remaining'] <= 2)
                            <p class="text-xs text-yellow-600 mt-1">{{ $stat['remaining'] }} remaining</p>
                            @endif
                            @else
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 100%"></div>
                            </div>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
