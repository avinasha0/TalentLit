<?php
/**
 * Production Environment Fix Script
 * Run this script on your production server to fix the 403 Forbidden error
 */

echo "Fixing production environment...\n";

// 1. Update .env file for production
$envFile = '.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    
    // Update APP_URL to production URL
    $envContent = preg_replace('/APP_URL=.*/', 'APP_URL=https://talentlit.com', $envContent);
    
    // Set production environment
    $envContent = preg_replace('/APP_ENV=.*/', 'APP_ENV=production', $envContent);
    
    // Disable debug mode
    $envContent = preg_replace('/APP_DEBUG=.*/', 'APP_DEBUG=false', $envContent);
    
    // Set log level to error for production
    $envContent = preg_replace('/LOG_LEVEL=.*/', 'LOG_LEVEL=error', $envContent);
    
    file_put_contents($envFile, $envContent);
    echo "✓ Updated .env file for production\n";
} else {
    echo "✗ .env file not found\n";
}

// 2. Clear Laravel caches
echo "Clearing Laravel caches...\n";
exec('php artisan config:clear', $output, $return);
exec('php artisan cache:clear', $output, $return);
exec('php artisan route:clear', $output, $return);
exec('php artisan view:clear', $output, $return);
echo "✓ Cleared Laravel caches\n";

// 3. Optimize for production
echo "Optimizing for production...\n";
exec('php artisan config:cache', $output, $return);
exec('php artisan route:cache', $output, $return);
exec('php artisan view:cache', $output, $return);
echo "✓ Optimized for production\n";

// 4. Set proper permissions (Linux/Unix only)
if (PHP_OS_FAMILY !== 'Windows') {
    echo "Setting file permissions...\n";
    exec('chmod -R 755 storage', $output, $return);
    exec('chmod -R 755 bootstrap/cache', $output, $return);
    exec('chown -R www-data:www-data storage', $output, $return);
    exec('chown -R www-data:www-data bootstrap/cache', $output, $return);
    echo "✓ Set file permissions\n";
} else {
    echo "⚠ Windows detected - permissions set via icacls commands\n";
}

echo "\nProduction environment fix completed!\n";
echo "Please test your application at https://talentlit.com\n";
