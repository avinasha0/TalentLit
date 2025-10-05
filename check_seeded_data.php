<?php

/**
 * Check Seeded Data - Departments and Locations
 * 
 * This script checks what departments and locations have been seeded
 * for each tenant in production.
 * 
 * Usage: php check_seeded_data.php
 */

// Set up error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if we're in the right directory
if (!file_exists('vendor/autoload.php')) {
    echo "❌ Error: Please run this script from the Laravel project root directory.\n";
    echo "Current directory: " . getcwd() . "\n";
    exit(1);
}

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Department;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

echo "🔍 Checking Seeded Data - Departments and Locations\n";
echo "==================================================\n\n";

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // Get all tenants
    $tenants = Tenant::all();
    
    if ($tenants->isEmpty()) {
        echo "❌ No tenants found.\n";
        exit(1);
    }

    $totalDepartments = 0;
    $totalLocations = 0;

    foreach ($tenants as $tenant) {
        echo "🏢 Tenant: {$tenant->name} ({$tenant->slug})\n";
        echo str_repeat("=", 50) . "\n";
        
        // Get departments for this tenant
        $departments = Department::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        echo "📋 Departments ({$departments->count()}):\n";
        foreach ($departments as $dept) {
            echo "   • {$dept->name} ({$dept->code})\n";
        }
        
        // Get locations for this tenant
        $locations = Location::where('tenant_id', $tenant->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        
        echo "\n🏙️ Locations ({$locations->count()}):\n";
        foreach ($locations as $location) {
            $locationInfo = $location->name;
            if ($location->city && $location->city !== $location->name) {
                $locationInfo .= " ({$location->city})";
            }
            if ($location->country) {
                $locationInfo .= ", {$location->country}";
            }
            echo "   • {$locationInfo}\n";
        }
        
        $totalDepartments += $departments->count();
        $totalLocations += $locations->count();
        
        echo "\n" . str_repeat("-", 50) . "\n\n";
    }

    // Summary
    echo "📊 Summary\n";
    echo "==========\n";
    echo "Total tenants: " . $tenants->count() . "\n";
    echo "Total departments: {$totalDepartments}\n";
    echo "Total locations: {$totalLocations}\n";
    echo "Average departments per tenant: " . round($totalDepartments / $tenants->count(), 1) . "\n";
    echo "Average locations per tenant: " . round($totalLocations / $tenants->count(), 1) . "\n\n";
    
    if ($totalDepartments > 0 && $totalLocations > 0) {
        echo "✅ Data verification complete!\n";
        echo "All tenants have departments and locations data.\n";
    } else {
        echo "⚠️  No data found. You may need to run the seeder first.\n";
        echo "Run: php seed_production_data.php\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
