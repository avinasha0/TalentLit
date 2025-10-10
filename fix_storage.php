<?php
/**
 * Storage Fix Script for Production
 * 
 * This script fixes common storage issues in production:
 * - Creates storage symlink
 * - Sets proper file permissions
 * - Clears Laravel caches
 * 
 * Usage: php fix_storage.php
 */

echo "🔧 Starting Storage Fix Process...\n\n";

// Check if we're in a Laravel project
if (!file_exists('artisan')) {
    echo "❌ Error: This script must be run from the Laravel project root directory.\n";
    exit(1);
}

echo "1️⃣ Creating storage symlink...\n";
$output = [];
$returnCode = 0;
exec('php artisan storage:link 2>&1', $output, $returnCode);

if ($returnCode === 0) {
    echo "✅ Storage symlink created successfully!\n";
} else {
    echo "⚠️  Storage symlink creation returned code: $returnCode\n";
    echo "Output: " . implode("\n", $output) . "\n";
}

echo "\n2️⃣ Setting file permissions...\n";

// Set permissions for storage directory
if (is_dir('storage')) {
    if (PHP_OS_FAMILY === 'Windows') {
        // Windows permissions
        exec('icacls "storage" /grant "Everyone:(OI)(CI)F" 2>&1', $output, $returnCode);
        echo "✅ Windows permissions set for storage directory\n";
    } else {
        // Unix/Linux permissions
        exec('chmod -R 755 storage/ 2>&1', $output, $returnCode);
        echo "✅ Unix permissions set for storage directory\n";
    }
} else {
    echo "❌ Storage directory not found!\n";
}

// Set permissions for public/storage if it exists
if (is_dir('public/storage')) {
    if (PHP_OS_FAMILY === 'Windows') {
        exec('icacls "public/storage" /grant "Everyone:(OI)(CI)F" 2>&1', $output, $returnCode);
        echo "✅ Windows permissions set for public/storage directory\n";
    } else {
        exec('chmod -R 755 public/storage/ 2>&1', $output, $returnCode);
        echo "✅ Unix permissions set for public/storage directory\n";
    }
} else {
    echo "⚠️  public/storage directory not found (symlink may not exist)\n";
}

echo "\n3️⃣ Clearing Laravel caches...\n";

$cacheCommands = [
    'config:clear' => 'Configuration cache',
    'cache:clear' => 'Application cache',
    'route:clear' => 'Route cache',
    'view:clear' => 'View cache',
];

foreach ($cacheCommands as $command => $description) {
    exec("php artisan $command 2>&1", $output, $returnCode);
    if ($returnCode === 0) {
        echo "✅ $description cleared\n";
    } else {
        echo "⚠️  Failed to clear $description\n";
    }
}

echo "\n4️⃣ Verifying storage setup...\n";

// Check if storage symlink exists
if (is_link('public/storage') || is_dir('public/storage')) {
    echo "✅ Storage symlink exists\n";
} else {
    echo "❌ Storage symlink does not exist!\n";
}

// Check if storage directory is writable
if (is_writable('storage')) {
    echo "✅ Storage directory is writable\n";
} else {
    echo "❌ Storage directory is not writable!\n";
}

// Check if public/storage is accessible
if (is_readable('public/storage')) {
    echo "✅ public/storage directory is readable\n";
} else {
    echo "❌ public/storage directory is not readable!\n";
}

echo "\n5️⃣ Testing file upload path...\n";

// Create a test directory to verify uploads work
$testPath = 'storage/app/public/test';
if (!is_dir($testPath)) {
    mkdir($testPath, 0755, true);
}

if (is_dir($testPath)) {
    echo "✅ Test upload directory created successfully\n";
    // Clean up test directory
    rmdir($testPath);
} else {
    echo "❌ Failed to create test upload directory\n";
}

echo "\n🎉 Storage fix process completed!\n\n";

echo "📋 Next steps:\n";
echo "1. Check your .env file - ensure APP_URL is correct\n";
echo "2. Verify web server can access public/storage directory\n";
echo "3. Test image uploads in your application\n";
echo "4. Check Laravel logs if issues persist: storage/logs/laravel.log\n\n";

echo "🔗 Storage URL should be: " . (getenv('APP_URL') ?: 'http://localhost') . "/storage\n";
echo "📁 Storage path: storage/app/public/\n";
echo "🔗 Public path: public/storage/\n";
