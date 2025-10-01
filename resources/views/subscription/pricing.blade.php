@extends('layouts.app')

@section('title', 'Pricing Plans - HireHub')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold text-gray-900 sm:text-5xl">
                Choose Your Plan
            </h1>
            <p class="mt-4 text-xl text-gray-600 max-w-3xl mx-auto">
                Start with our free plan and upgrade as your team grows. All plans include our core recruitment features.
            </p>
        </div>

        <!-- Pricing Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            @foreach($plans as $plan)
            <div class="relative bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden {{ $plan->is_popular ? 'ring-2 ring-indigo-500 scale-105' : '' }}">
                @if($plan->is_popular)
                <div class="absolute top-0 left-0 right-0 bg-indigo-500 text-white text-center py-2 text-sm font-semibold">
                    Most Popular
                </div>
                @endif

                <div class="p-8 {{ $plan->is_popular ? 'pt-12' : '' }}">
                    <!-- Plan Header -->
                    <div class="text-center mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <p class="mt-2 text-gray-600">{{ $plan->description }}</p>
                        
                        <div class="mt-6">
                            <span class="text-5xl font-bold text-gray-900">
                                ${{ number_format($plan->price, 0) }}
                            </span>
                            <span class="text-gray-600">/{{ $plan->billing_cycle }}</span>
                        </div>
                    </div>

                    <!-- Features -->
                    <div class="space-y-4 mb-8">
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

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_candidates == -1 ? 'Unlimited' : number_format($plan->max_candidates) }} Candidates
                            </span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_applications_per_month == -1 ? 'Unlimited' : number_format($plan->max_applications_per_month) }} Applications/Month
                            </span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_interviews_per_month == -1 ? 'Unlimited' : number_format($plan->max_interviews_per_month) }} Interviews/Month
                            </span>
                        </div>

                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">
                                {{ $plan->max_storage_gb }}GB Storage
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

                        @if($plan->api_access)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">API Access</span>
                        </div>
                        @endif

                        @if($plan->priority_support)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">Priority Support</span>
                        </div>
                        @endif

                        @if($plan->white_label)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-700">White Label</span>
                        </div>
                        @endif
                    </div>

                    <!-- CTA Button -->
                    <form method="POST" action="{{ route('subscription.subscribe') }}" class="w-full">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit" 
                                class="w-full {{ $plan->is_popular ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-900 hover:bg-gray-800' }} text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                            @if($plan->isFree())
                                Get Started Free
                            @else
                                Choose {{ $plan->name }}
                            @endif
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- FAQ Section -->
        <div class="mt-20">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
                Frequently Asked Questions
            </h2>
            
            <div class="max-w-3xl mx-auto space-y-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Can I change my plan later?
                    </h3>
                    <p class="text-gray-600">
                        Yes! You can upgrade or downgrade your plan at any time. Changes take effect immediately, and we'll prorate any billing differences.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        What happens if I exceed my limits?
                    </h3>
                    <p class="text-gray-600">
                        We'll notify you when you're approaching your limits. You can upgrade your plan or contact us to discuss custom solutions for your needs.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Is there a free trial?
                    </h3>
                    <p class="text-gray-600">
                        Our Free plan is always free with no time limits. You can use it indefinitely with the included features and limits.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Do you offer custom enterprise plans?
                    </h3>
                    <p class="text-gray-600">
                        Yes! Contact our sales team to discuss custom enterprise solutions with unlimited features and dedicated support.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
