@php
    $tenant = tenant();
    $breadcrumbs = [
        ['label' => 'Dashboard', 'url' => route('tenant.dashboard', $tenant->slug)],
        ['label' => 'Subscription', 'url' => null]
    ];
@endphp

<x-app-layout :tenant="$tenant">
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="$breadcrumbs" />
    </x-slot>
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

        <!-- All Plans Overview -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Available Plans</h2>
            <p class="text-gray-600">Choose the plan that best fits your needs. You can upgrade or downgrade at any time.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            @php
                $allPlans = \App\Models\SubscriptionPlan::where('is_active', true)->orderBy('price', 'asc')->get();
                $currentPlanSlug = $subscription ? $subscription->plan->slug : null;
            @endphp
            
            @foreach($allPlans as $planItem)
            <div class="bg-white rounded-lg shadow-sm border-2 {{ $currentPlanSlug === $planItem->slug ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200' }} p-6 relative">
                @if($currentPlanSlug === $planItem->slug)
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2">
                    <span class="bg-indigo-600 text-white px-4 py-1 rounded-full text-sm font-semibold">Current Plan</span>
                </div>
                @endif
                
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold {{ $currentPlanSlug === $planItem->slug ? 'text-indigo-900' : 'text-gray-900' }} mb-2">{{ $planItem->name }}</h3>
                    <p class="text-gray-600 mb-4">{{ $planItem->description }}</p>
                    
                    <div class="mb-4">
                        @if($planItem->requiresContactForPricing())
                            <span class="text-2xl font-bold text-gray-900">Contact for Pricing</span>
                        @else
                            <span class="text-3xl font-bold {{ $currentPlanSlug === $planItem->slug ? 'text-indigo-900' : 'text-gray-900' }}">
                                @subscriptionPrice($planItem->price, $planItem->currency)
                            </span>
                            <span class="text-gray-600">/{{ $planItem->billing_cycle }}</span>
                        @endif
                    </div>
                </div>

                <!-- Features -->
                <div class="space-y-3 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ $planItem->max_users == -1 ? 'Unlimited' : $planItem->max_users }} Users
                        </span>
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">
                            {{ $planItem->max_job_openings == -1 ? 'Unlimited' : $planItem->max_job_openings }} Job Openings
                        </span>
                    </div>
                    
                    @if($planItem->analytics_enabled)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">Advanced Analytics</span>
                    </div>
                    @endif
                    
                    @if($planItem->custom_branding)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-sm text-gray-700">Custom Branding</span>
                    </div>
                    @endif
                </div>

                <!-- Action Button -->
                <div class="mt-auto">
                    @if($currentPlanSlug === $planItem->slug)
                        <div class="w-full bg-gray-100 text-gray-600 font-semibold py-3 px-6 rounded-lg text-center">
                            ✓ Current Plan
                        </div>
                    @elseif($planItem->slug === 'free')
                        <form method="POST" action="{{ route('subscription.subscribe', $tenant->slug) }}" class="w-full">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $planItem->id }}">
                            <button type="submit" 
                                    class="w-full bg-gray-900 hover:bg-gray-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Switch to Free
                            </button>
                        </form>
                    @elseif($planItem->slug === 'pro')
                        @php
                            $razorpayConfigured = config('razorpay.key_id') && config('razorpay.key_secret');
                            $proPlanActive = config('razorpay.pro_plan_mode') === 'active' || $razorpayConfigured;
                        @endphp
                        @if($proPlanActive && $razorpayConfigured)
                            @if($tenant->hasFreePlan())
                                <button onclick="initiatePayment('{{ $planItem->id }}', '{{ $planItem->name }}', {{ $planItem->price }}, '{{ $planItem->currency }}')" 
                                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                    Upgrade To Pro - ₹{{ number_format($planItem->price, 0) }}/month
                                </button>
                            @else
                                <div class="w-full bg-gray-100 text-gray-600 font-semibold py-3 px-6 rounded-lg text-center">
                                    Start with Free Plan First
                                </div>
                            @endif
                        @else
                            <button onclick="openWaitlistModal('pro')" 
                                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200">
                                Upgrade To Pro
                            </button>
                        @endif
                    @elseif($planItem->slug === 'enterprise')
                        <a href="{{ route('contact') }}" 
                           class="w-full bg-gradient-to-r from-gray-900 to-gray-800 hover:from-gray-800 hover:to-gray-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 text-center block">
                            Contact for Pricing
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Usage Statistics -->
        @if($usage)
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

<!-- Waitlist Modal -->
<div id="waitlistModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 transform transition-all duration-300 scale-95 opacity-0" id="waitlistModalContent">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Join the Waitlist</h3>
                            <p class="text-indigo-100 text-sm">Be the first to know when Pro plan launches</p>
                        </div>
                    </div>
                    <button onclick="closeWaitlistModal()" class="text-white hover:text-indigo-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- User Info Display -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="text-sm text-gray-600">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>

                <!-- Waitlist Form -->
                <form id="waitlistForm" class="space-y-4" action="{{ route('waitlist.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="plan_slug" id="plan_slug_input" value="">
                    
                    <div>
                        <label for="company" class="block text-sm font-semibold text-gray-700 mb-2">
                            Company (Optional)
                        </label>
                        <input type="text" id="company" name="company"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                               placeholder="Enter your company name">
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                            Message (Optional)
                        </label>
                        <textarea id="message" name="message" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                                  placeholder="Tell us about your hiring needs..."></textarea>
                    </div>

                    <button type="submit" id="waitlistSubmitBtn"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                        <span id="waitlistBtnText">Upgrade To Pro</span>
                        <span id="waitlistBtnLoading" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Joining...
                        </span>
                    </button>
                </form>

                <!-- Success Message -->
                <div id="waitlistSuccess" class="hidden text-center">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">You're on the Waitlist!</h3>
                    <p class="text-gray-600 mb-6">Thank you for joining. We'll notify you as soon as the Pro plan is available.</p>
                    <button onclick="closeWaitlistModal()"
                            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg transition-all duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Waitlist functionality
let currentPlanSlug = '';

function openWaitlistModal(planSlug) {
    currentPlanSlug = planSlug;
    
    // Set the plan_slug in the hidden input
    const planSlugInput = document.getElementById('plan_slug_input');
    if (planSlugInput) {
        planSlugInput.value = planSlug;
    }
    
    const modal = document.getElementById('waitlistModal');
    const modalContent = document.getElementById('waitlistModalContent');
    
    modal.classList.remove('hidden');
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeWaitlistModal() {
    const modal = document.getElementById('waitlistModal');
    const modalContent = document.getElementById('waitlistModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        resetWaitlistModal();
    }, 300);
}

function resetWaitlistModal() {
    document.getElementById('waitlistForm').classList.remove('hidden');
    document.getElementById('waitlistSuccess').classList.add('hidden');
    document.getElementById('waitlistForm').reset();
}

// Waitlist form submission
document.getElementById('waitlistForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    data.plan_slug = currentPlanSlug;
    
    const submitBtn = document.getElementById('waitlistSubmitBtn');
    const btnText = document.getElementById('waitlistBtnText');
    const btnLoading = document.getElementById('waitlistBtnLoading');
    
    // Show loading state
    submitBtn.disabled = true;
    btnText.classList.add('hidden');
    btnLoading.classList.remove('hidden');
    
    console.log('Submitting waitlist data:', data);
    console.log('Route URL:', '{{ route("waitlist.store") }}');
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            throw new Error('CSRF token not found');
        }
        
        const response = await fetch('{{ route("waitlist.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Response data:', result);
        
        if (result.success) {
            // Show success message
            document.getElementById('waitlistForm').classList.add('hidden');
            document.getElementById('waitlistSuccess').classList.remove('hidden');
        } else {
            showNotification(result.message || 'Something went wrong. Please try again.', 'error');
        }
    } catch (error) {
        console.error('Error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    } finally {
        // Reset button state
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    }
});

// Notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-blue-500 text-white'
    };
    
    notification.className += ` ${colors[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Payment functionality
async function initiatePayment(planId, planName, amount, currency) {
    try {
        // Show loading state
        showNotification('Creating payment order...', 'info');
        
        // Create payment order
        const response = await fetch('{{ route("payment.create-order") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                plan_id: planId
            })
        });
        
        const data = await response.json();
        
        if (!data.success) {
            console.error('Order creation failed:', data);
            showNotification(data.message || 'Failed to create payment order', 'error');
            return;
        }
        
        // Validate order data structure
        if (!data.order || !data.order.id) {
            console.error('Invalid order data structure:', data);
            showNotification('Invalid order data. Please try again.', 'error');
            return;
        }
        
        if (!data.key_id) {
            console.error('Razorpay key_id missing:', data);
            showNotification('Payment gateway configuration error. Please contact support.', 'error');
            return;
        }
        
        // Store order_id for fallback
        const orderIdFromOrder = data.order.id;
        
        // Validate order data before proceeding
        if (!orderIdFromOrder) {
            console.error('Order ID is missing from order data:', data);
            showNotification('Failed to create payment order. Please try again.', 'error');
            return;
        }
        
        // Configure RazorPay options
        const options = {
            key: data.key_id,
            amount: data.amount * 100, // Convert to paise
            currency: data.currency,
            name: data.name,
            description: data.description,
            order_id: orderIdFromOrder,
            prefill: data.prefill || {},
            theme: {
                color: '#4f46e5'
            },
            handler: function(response) {
                // Log full response for debugging
                console.log('Razorpay payment handler called with response:', response);
                
                // Payment successful - validate response
                if (!response || typeof response !== 'object') {
                    console.error('Invalid Razorpay response:', response);
                    showNotification('Invalid payment response. Please try again.', 'error');
                    return;
                }
                
                // Use response order_id or fallback to original order_id
                const orderId = response.razorpay_order_id || orderIdFromOrder;
                
                // Validate critical fields with detailed error messages
                if (!response.razorpay_payment_id) {
                    console.error('Missing payment_id in Razorpay response:', response);
                    showNotification('Payment ID is missing. Please contact support with Order ID: ' + orderIdFromOrder, 'error');
                    return;
                }
                
                if (!response.razorpay_signature) {
                    console.error('Missing signature in Razorpay response:', response);
                    showNotification('Payment signature is missing. Please contact support with Payment ID: ' + response.razorpay_payment_id, 'error');
                    return;
                }
                
                if (!orderId) {
                    console.error('Order ID is missing:', { response, orderIdFromOrder });
                    showNotification('Order ID is missing. Please contact support with Payment ID: ' + response.razorpay_payment_id, 'error');
                    return;
                }
                
                // All validations passed, proceed with form submission
                const form = document.createElement('form');
                form.method = 'GET';
                form.action = '{{ route("payment.success") }}';
                
                const paymentId = document.createElement('input');
                paymentId.type = 'hidden';
                paymentId.name = 'razorpay_payment_id';
                paymentId.value = response.razorpay_payment_id;
                form.appendChild(paymentId);
                
                const orderIdInput = document.createElement('input');
                orderIdInput.type = 'hidden';
                orderIdInput.name = 'razorpay_order_id';
                orderIdInput.value = orderId;
                form.appendChild(orderIdInput);
                
                const signature = document.createElement('input');
                signature.type = 'hidden';
                signature.name = 'razorpay_signature';
                signature.value = response.razorpay_signature;
                form.appendChild(signature);
                
                document.body.appendChild(form);
                form.submit();
            },
            modal: {
                ondismiss: function() {
                    showNotification('Payment cancelled', 'info');
                }
            }
        };
        
        // Open RazorPay checkout
        try {
            const rzp = new Razorpay(options);
            
            // Add error handler for payment failures
            rzp.on('payment.failed', function(response) {
                console.error('Razorpay payment failed:', response);
                const errorMessage = response.error?.description || response.error?.reason || 'Payment failed. Please try again.';
                showNotification('Payment failed: ' + errorMessage, 'error');
            });
            
            rzp.open();
        } catch (error) {
            console.error('Error opening Razorpay checkout:', error);
            showNotification('Failed to open payment gateway. Please try again.', 'error');
        }
        
    } catch (error) {
        console.error('Payment error:', error);
        showNotification('Something went wrong. Please try again.', 'error');
    }
}

// Load RazorPay script dynamically
function loadRazorPayScript() {
    return new Promise((resolve, reject) => {
        if (window.Razorpay) {
            resolve();
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://checkout.razorpay.com/v1/checkout.js';
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

// Initialize RazorPay when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadRazorPayScript().catch(error => {
        console.error('Failed to load RazorPay script:', error);
        showNotification('Payment system unavailable', 'error');
    });
});
</script>
</x-app-layout>
