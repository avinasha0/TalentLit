<?php

/**
 * Production Data Seeder for Departments and Locations
 * 
 * This script can be run directly in production to seed departments and locations
 * with Indian data for all existing tenants.
 * 
 * Usage: php seed_production_data.php
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

echo "🌱 Production Data Seeder - Departments and Locations\n";
echo "====================================================\n\n";

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n\n";
    
    // Get all tenants
    $tenants = Tenant::all();
    
    if ($tenants->isEmpty()) {
        echo "❌ No tenants found. Please create tenants first.\n";
        exit(1);
    }

    echo "Found " . $tenants->count() . " tenant(s) to seed:\n";
    foreach ($tenants as $tenant) {
        echo "   - {$tenant->name} ({$tenant->slug})\n";
    }
    echo "\n";

    $totalDepartments = 0;
    $totalLocations = 0;

    foreach ($tenants as $tenant) {
        echo "🏢 Processing tenant: {$tenant->name} ({$tenant->slug})\n";
        
        // Departments data
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Marketing', 'code' => 'MKT'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Customer Support', 'code' => 'CS'],
            ['name' => 'Product Management', 'code' => 'PM'],
            ['name' => 'Quality Assurance', 'code' => 'QA'],
            ['name' => 'Business Development', 'code' => 'BD'],
            ['name' => 'Legal', 'code' => 'LEGAL'],
            ['name' => 'IT Support', 'code' => 'IT'],
            ['name' => 'Research & Development', 'code' => 'R&D'],
            ['name' => 'Supply Chain', 'code' => 'SC'],
            ['name' => 'Data Analytics', 'code' => 'DA'],
        ];

        // Seed departments
        $deptCount = 0;
        foreach ($departments as $dept) {
            try {
                Department::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'code' => $dept['code']
                    ],
                    [
                        'name' => $dept['name'],
                        'is_active' => true,
                    ]
                );
                $deptCount++;
            } catch (Exception $e) {
                echo "   ⚠️  Warning: Could not create department {$dept['name']}: " . $e->getMessage() . "\n";
            }
        }
        $totalDepartments += $deptCount;
        echo "   ✅ Seeded {$deptCount} departments\n";
        
        // Locations data with Indian cities
        $locations = [
            // Major Indian Cities
            ['name' => 'Mumbai', 'city' => 'Mumbai', 'country' => 'India'],
            ['name' => 'Delhi', 'city' => 'New Delhi', 'country' => 'India'],
            ['name' => 'Bangalore', 'city' => 'Bangalore', 'country' => 'India'],
            ['name' => 'Hyderabad', 'city' => 'Hyderabad', 'country' => 'India'],
            ['name' => 'Chennai', 'city' => 'Chennai', 'country' => 'India'],
            ['name' => 'Kolkata', 'city' => 'Kolkata', 'country' => 'India'],
            ['name' => 'Pune', 'city' => 'Pune', 'country' => 'India'],
            ['name' => 'Ahmedabad', 'city' => 'Ahmedabad', 'country' => 'India'],
            ['name' => 'Jaipur', 'city' => 'Jaipur', 'country' => 'India'],
            ['name' => 'Surat', 'city' => 'Surat', 'country' => 'India'],
            
            // IT Hubs
            ['name' => 'Gurgaon', 'city' => 'Gurgaon', 'country' => 'India'],
            ['name' => 'Noida', 'city' => 'Noida', 'country' => 'India'],
            ['name' => 'Kochi', 'city' => 'Kochi', 'country' => 'India'],
            ['name' => 'Coimbatore', 'city' => 'Coimbatore', 'country' => 'India'],
            ['name' => 'Chandigarh', 'city' => 'Chandigarh', 'country' => 'India'],
            ['name' => 'Indore', 'city' => 'Indore', 'country' => 'India'],
            ['name' => 'Bhubaneswar', 'city' => 'Bhubaneswar', 'country' => 'India'],
            ['name' => 'Vadodara', 'city' => 'Vadodara', 'country' => 'India'],
            ['name' => 'Nashik', 'city' => 'Nashik', 'country' => 'India'],
            ['name' => 'Vijayawada', 'city' => 'Vijayawada', 'country' => 'India'],
            
            // Remote/Work from Home options
            ['name' => 'Remote - India', 'city' => 'Remote', 'country' => 'India'],
            ['name' => 'Work from Home', 'city' => 'Remote', 'country' => 'India'],
            ['name' => 'Hybrid - India', 'city' => 'Hybrid', 'country' => 'India'],
            
            // Regional Centers
            ['name' => 'Goa', 'city' => 'Panaji', 'country' => 'India'],
            ['name' => 'Kerala', 'city' => 'Thiruvananthapuram', 'country' => 'India'],
            ['name' => 'Tamil Nadu', 'city' => 'Coimbatore', 'country' => 'India'],
            ['name' => 'Karnataka', 'city' => 'Mysore', 'country' => 'India'],
            ['name' => 'Maharashtra', 'city' => 'Nagpur', 'country' => 'India'],
            ['name' => 'Gujarat', 'city' => 'Rajkot', 'country' => 'India'],
        ];

        // Seed locations
        $locCount = 0;
        foreach ($locations as $location) {
            try {
                Location::updateOrCreate(
                    [
                        'tenant_id' => $tenant->id,
                        'name' => $location['name']
                    ],
                    [
                        'tenant_id' => $tenant->id,
                        'city' => $location['city'],
                        'country' => $location['country'],
                        'is_active' => true,
                    ]
                );
                $locCount++;
            } catch (Exception $e) {
                echo "   ⚠️  Warning: Could not create location {$location['name']}: " . $e->getMessage() . "\n";
            }
        }
        $totalLocations += $locCount;
        echo "   ✅ Seeded {$locCount} locations\n\n";
    }

    echo "🎉 Seeding Complete!\n";
    echo "===================\n";
    echo "Total departments seeded: {$totalDepartments}\n";
    echo "Total locations seeded: {$totalLocations}\n";
    echo "Tenants processed: " . $tenants->count() . "\n\n";
    
    echo "📋 Departments included:\n";
    echo "   Engineering, HR, Marketing, Sales, Finance, Operations,\n";
    echo "   Customer Support, Product Management, QA, Business Development,\n";
    echo "   Legal, IT Support, R&D, Supply Chain, Data Analytics\n\n";
    
    echo "🏙️ Locations included:\n";
    echo "   Major cities: Mumbai, Delhi, Bangalore, Hyderabad, Chennai, Kolkata, Pune\n";
    echo "   IT hubs: Gurgaon, Noida, Kochi, Coimbatore, Chandigarh\n";
    echo "   Remote options: Work from Home, Remote - India, Hybrid\n";
    echo "   Regional centers: Goa, Kerala, Tamil Nadu, Karnataka, Maharashtra, Gujarat\n\n";
    
    echo "✅ All data has been seeded successfully!\n";
    echo "You can now use these departments and locations when creating job openings.\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
