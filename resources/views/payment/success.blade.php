@extends('layouts.public')

@section('title', 'Payment Successful - TalentLit')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Success Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-6">
                <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <!-- Success Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Payment Successful!
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Thank you for your payment. Your subscription has been activated successfully.
            </p>

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <p class="text-green-800 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if($subscription)
                <!-- Subscription Details -->
                <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Subscription Details</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Plan:</span>
                            <span class="font-semibold text-gray-900">{{ $subscription->subscriptionPlan->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount Paid:</span>
                            <span class="font-semibold text-gray-900">
                                ₹{{ number_format($subscription->amount_paid, 2) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment Method:</span>
                            <span class="font-semibold text-gray-900">
                                {{ ucfirst($subscription->payment_method) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Expires:</span>
                            <span class="font-semibold text-gray-900">
                                {{ $subscription->expires_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="{{ route('tenant.dashboard', $subscription->tenant->slug ?? '') }}" 
                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl text-center block">
                    Go to Dashboard
                </a>
                
                <a href="{{ route('subscription.pricing') }}" 
                   class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 text-center block">
                    View All Plans
                </a>
            </div>

            <!-- Additional Info -->
            <div class="mt-8 text-sm text-gray-500">
                <p>You will receive a confirmation email shortly.</p>
                <p class="mt-2">
                    Need help? 
                    <a href="{{ route('contact') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        Contact Support
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
