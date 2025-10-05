<?php $__env->startSection('title', 'Payment Failed - TalentLit'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-red-50 to-pink-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <!-- Failure Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-100 mb-6">
                <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <!-- Failure Message -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                Payment Failed
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                We're sorry, but your payment could not be processed at this time.
            </p>

            <?php if(session('error')): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium"><?php echo e(session('error')); ?></p>
                </div>
            <?php endif; ?>

            <?php if($error ?? null): ?>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-medium"><?php echo e($error); ?></p>
                </div>
            <?php endif; ?>

            <!-- Common Reasons -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Common Reasons for Payment Failure</h2>
                <ul class="text-left space-y-2 text-gray-600">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Insufficient funds in your account
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Incorrect card details or CVV
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Card expired or blocked by bank
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Network connectivity issues
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-4">
                <a href="<?php echo e(route('subscription.pricing')); ?>" 
                   class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg hover:shadow-xl text-center block">
                    Try Again
                </a>
                
                <a href="<?php echo e(route('home')); ?>" 
                   class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition-all duration-200 text-center block">
                    Back to Home
                </a>
            </div>

            <!-- Support Information -->
            <div class="mt-8 text-sm text-gray-500">
                <p>If you continue to experience issues, please contact our support team.</p>
                <p class="mt-2">
                    <a href="<?php echo e(route('contact')); ?>" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        Contact Support
                    </a>
                    or email us at 
                    <a href="mailto:support@talentlit.com" class="text-indigo-600 hover:text-indigo-700 font-medium">
                        support@talentlit.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\payment\failure.blade.php ENDPATH**/ ?>