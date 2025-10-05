<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Demo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Currency Management Demo</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Current Currency Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Current Currency Settings</h2>
                <div class="space-y-2">
                    <p><strong>Currency Code:</strong> <?php echo currentCurrency(); ?></p>
                    <p><strong>Currency Symbol:</strong> <?php echo currencySymbol(); ?></p>
                    <p><strong>Tenant Location:</strong> <?php echo e(tenant()->location ?? 'Not set'); ?></p>
                </div>
            </div>

            <!-- Currency Formatting Examples -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">Currency Formatting Examples</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Single Amount:</span>
                        <span class="font-mono"><?php echo currency(50000); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Salary Range:</span>
                        <span class="font-mono"><?php echo currencyRange(50000, 80000); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Large Amount:</span>
                        <span class="font-mono"><?php echo currency(1500000); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Small Amount:</span>
                        <span class="font-mono"><?php echo currency(1500); ?></span>
                    </div>
                </div>
            </div>

            <!-- USD Examples -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">USD Examples</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Single Amount:</span>
                        <span class="font-mono"><?php echo currency(50000, 'USD'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Salary Range:</span>
                        <span class="font-mono"><?php echo currencyRange(50000, 80000, 'USD'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Large Amount:</span>
                        <span class="font-mono"><?php echo currency(1500000, 'USD'); ?></span>
                    </div>
                </div>
            </div>

            <!-- INR Examples -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold mb-4">INR Examples</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Single Amount:</span>
                        <span class="font-mono"><?php echo currency(50000, 'INR'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Salary Range:</span>
                        <span class="font-mono"><?php echo currencyRange(50000, 80000, 'INR'); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Large Amount:</span>
                        <span class="font-mono"><?php echo currency(1500000, 'INR'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Instructions -->
        <div class="mt-8 bg-blue-50 rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Usage Instructions</h2>
            <div class="space-y-2 text-sm">
                <p><strong>Helper Functions:</strong></p>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li><code>currency($amount)</code> - Format amount with current tenant's currency</li>
                    <li><code>currency($amount, 'USD')</code> - Format amount with specific currency</li>
                    <li><code>currencyRange($min, $max)</code> - Format salary range</li>
                    <li><code>currencySymbol()</code> - Get current currency symbol</li>
                    <li><code>currentCurrency()</code> - Get current currency code</li>
                </ul>
                
                <p class="mt-4"><strong>Blade Directives:</strong></p>
                <ul class="list-disc list-inside space-y-1 ml-4">
                    <li><code><?php echo currency($amount); ?></code> - Format amount</li>
                    <li><code><?php echo currencySymbol(); ?></code> - Display currency symbol</li>
                    <li><code><?php echo currentCurrency(); ?></code> - Display currency code</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\hirehub2\resources\views\currency-demo.blade.php ENDPATH**/ ?>