<?php

/**
 * Production Fix Script for location_id Foreign Key Constraint
 * 
 * This script fixes the issue where the location_id column cannot be dropped
 * due to foreign key constraints in production.
 * 
 * Run this script in production before running migrations:
 * php fix_production_location_id.php
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Starting location_id foreign key constraint fix...\n";

try {
    // Get all foreign key constraints for the job_openings table
    $foreignKeys = DB::select("
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'job_openings' 
        AND COLUMN_NAME = 'location_id' 
        AND CONSTRAINT_NAME != 'PRIMARY'
        AND REFERENCED_TABLE_NAME IS NOT NULL
    ");
    
    echo "Found " . count($foreignKeys) . " foreign key constraints to drop.\n";
    
    // Drop each foreign key constraint found
    foreach ($foreignKeys as $fk) {
        $constraintName = $fk->CONSTRAINT_NAME;
        echo "Dropping foreign key constraint: {$constraintName}\n";
        
        try {
            DB::statement("ALTER TABLE job_openings DROP FOREIGN KEY `{$constraintName}`");
            echo "✓ Successfully dropped constraint: {$constraintName}\n";
        } catch (Exception $e) {
            echo "✗ Failed to drop constraint {$constraintName}: " . $e->getMessage() . "\n";
        }
    }
    
    // Check if location_id column exists
    $columnExists = DB::select("
        SELECT COLUMN_NAME 
        FROM information_schema.COLUMNS 
        WHERE TABLE_NAME = 'job_openings' 
        AND COLUMN_NAME = 'location_id'
    ");
    
    if (count($columnExists) > 0) {
        echo "Dropping location_id column...\n";
        DB::statement("ALTER TABLE job_openings DROP COLUMN location_id");
        echo "✓ Successfully dropped location_id column\n";
    } else {
        echo "✓ location_id column already removed\n";
    }
    
    echo "\n✅ Production fix completed successfully!\n";
    echo "You can now run: php artisan migrate --force\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Please check the error and try again.\n";
    exit(1);
}
