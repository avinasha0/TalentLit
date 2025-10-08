<?php

/**
 * Mark Migration as Complete Script
 * 
 * This script marks the fix_location_id_foreign_key_constraint migration as completed
 * since the location_id column was already dropped by the previous migration.
 * 
 * Run this script in production:
 * php mark_migration_complete.php
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Marking migration as complete...\n";

try {
    // Check if the migration is already in the migrations table
    $migrationExists = DB::table('migrations')
        ->where('migration', '2025_10_08_070209_fix_location_id_foreign_key_constraint')
        ->exists();
    
    if (!$migrationExists) {
        // Insert the migration record to mark it as completed
        DB::table('migrations')->insert([
            'migration' => '2025_10_08_070209_fix_location_id_foreign_key_constraint',
            'batch' => DB::table('migrations')->max('batch') + 1
        ]);
        
        echo "✅ Migration marked as completed successfully!\n";
    } else {
        echo "✅ Migration already marked as completed.\n";
    }
    
    echo "\nYou can now run: php artisan migrate --force\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
