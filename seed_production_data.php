<?php

/**
 * Production Seeding Script for Departments and Locations
 * 
 * This script safely seeds global departments and locations in production
 * Run this script from your production server
 * 
 * Usage: php seed_production_data.php
 */

require_once 'vendor/autoload.php';

use App\Models\GlobalDepartment;
use App\Models\GlobalLocation;
use Illuminate\Support\Facades\DB;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🚀 Starting Production Seeding for Departments and Locations\n";
echo "============================================================\n\n";

// Check if we're in production
$environment = config('app.env');
if ($environment !== 'production') {
    echo "⚠️  WARNING: This script is designed for production. Current environment: {$environment}\n";
    echo "Continue anyway? (y/N): ";
    $handle = fopen("php://stdin", "r");
    $line = fgets($handle);
    if (trim($line) !== 'y' && trim($line) !== 'Y') {
        echo "❌ Aborted by user.\n";
        exit(1);
    }
    fclose($handle);
}

echo "✅ Environment: {$environment}\n\n";

// Create backup timestamp
$backupTime = date('Y-m-d_H-i-s');
echo "📅 Backup timestamp: {$backupTime}\n\n";

// Step 1: Create database backup
echo "1️⃣ Creating database backup...\n";
try {
    $database = config('database.connections.mysql.database');
    $username = config('database.connections.mysql.username');
    $password = config('database.connections.mysql.password');
    $host = config('database.connections.mysql.host');
    
    $backupFile = "backup_before_seeding_{$backupTime}.sql";
    $command = "mysqldump -h {$host} -u {$username} -p{$password} {$database} > {$backupFile}";
    
    exec($command, $output, $returnCode);
    
    if ($returnCode === 0) {
        echo "✅ Database backup created: {$backupFile}\n";
    } else {
        echo "⚠️  Database backup failed, but continuing...\n";
    }
} catch (Exception $e) {
    echo "⚠️  Database backup failed: " . $e->getMessage() . "\n";
    echo "Continuing without backup...\n";
}

echo "\n";

// Step 2: Seed Global Departments
echo "2️⃣ Seeding Global Departments...\n";
$departments = [
    ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Software development, technical architecture, and engineering solutions'],
    ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Talent acquisition, employee relations, and organizational development'],
    ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Brand management, digital marketing, and customer acquisition'],
    ['name' => 'Sales', 'code' => 'SALES', 'description' => 'Business development, client relations, and revenue generation'],
    ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Financial planning, accounting, and fiscal management'],
    ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Process optimization, supply chain, and operational excellence'],
    ['name' => 'Customer Support', 'code' => 'CS', 'description' => 'Customer service, technical support, and client success'],
    ['name' => 'Product Management', 'code' => 'PM', 'description' => 'Product strategy, roadmap planning, and feature development'],
    ['name' => 'Quality Assurance', 'code' => 'QA', 'description' => 'Testing, quality control, and process validation'],
    ['name' => 'Business Development', 'code' => 'BD', 'description' => 'Strategic partnerships, market expansion, and growth initiatives'],
    ['name' => 'Legal', 'code' => 'LEGAL', 'description' => 'Legal compliance, contract management, and risk assessment'],
    ['name' => 'IT Support', 'code' => 'IT', 'description' => 'Technical infrastructure, system administration, and IT services'],
    ['name' => 'Research & Development', 'code' => 'R&D', 'description' => 'Innovation, research, and product development'],
    ['name' => 'Supply Chain', 'code' => 'SC', 'description' => 'Procurement, logistics, and supply chain optimization'],
    ['name' => 'Data Analytics', 'code' => 'DA', 'description' => 'Data science, business intelligence, and analytics'],
    ['name' => 'Design', 'code' => 'DESIGN', 'description' => 'User experience, visual design, and creative direction'],
    ['name' => 'Content', 'code' => 'CONTENT', 'description' => 'Content creation, copywriting, and editorial'],
    ['name' => 'Security', 'code' => 'SEC', 'description' => 'Information security, compliance, and risk management'],
    ['name' => 'DevOps', 'code' => 'DEVOPS', 'description' => 'Infrastructure automation, deployment, and monitoring'],
    ['name' => 'Strategy', 'code' => 'STRAT', 'description' => 'Strategic planning, corporate development, and M&A'],
];

$deptCount = 0;
foreach ($departments as $dept) {
    try {
        GlobalDepartment::updateOrCreate(
            ['code' => $dept['code']],
            [
                'name' => $dept['name'],
                'description' => $dept['description'],
                'is_active' => true,
            ]
        );
        $deptCount++;
        echo "   ✅ {$dept['name']}\n";
    } catch (Exception $e) {
        echo "   ❌ Failed to seed {$dept['name']}: " . $e->getMessage() . "\n";
    }
}

echo "✅ Seeded {$deptCount} global departments\n\n";

// Step 3: Seed Global Locations
echo "3️⃣ Seeding Global Locations...\n";
$locations = [
    // Major Indian Cities
    ['name' => 'Mumbai', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Bangalore', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Hyderabad', 'city' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Chennai', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Kolkata', 'city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Pune', 'city' => 'Pune', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Ahmedabad', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Jaipur', 'city' => 'Jaipur', 'state' => 'Rajasthan', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Surat', 'city' => 'Surat', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    
    // IT Hubs
    ['name' => 'Gurgaon', 'city' => 'Gurgaon', 'state' => 'Haryana', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Noida', 'city' => 'Noida', 'state' => 'Uttar Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Kochi', 'city' => 'Kochi', 'state' => 'Kerala', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Coimbatore', 'city' => 'Coimbatore', 'state' => 'Tamil Nadu', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Chandigarh', 'city' => 'Chandigarh', 'state' => 'Chandigarh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Indore', 'city' => 'Indore', 'state' => 'Madhya Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Bhubaneswar', 'city' => 'Bhubaneswar', 'state' => 'Odisha', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Vadodara', 'city' => 'Vadodara', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Nashik', 'city' => 'Nashik', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Vijayawada', 'city' => 'Vijayawada', 'state' => 'Andhra Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    
    // Remote/Work from Home options
    ['name' => 'Remote - India', 'city' => 'Remote', 'state' => 'India', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    ['name' => 'Work from Home', 'city' => 'Remote', 'state' => 'Global', 'country' => 'Global', 'timezone' => 'UTC'],
    ['name' => 'Hybrid - India', 'city' => 'Hybrid', 'state' => 'India', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
    
    // International Locations
    ['name' => 'New York', 'city' => 'New York', 'state' => 'New York', 'country' => 'United States', 'timezone' => 'America/New_York'],
    ['name' => 'San Francisco', 'city' => 'San Francisco', 'state' => 'California', 'country' => 'United States', 'timezone' => 'America/Los_Angeles'],
    ['name' => 'London', 'city' => 'London', 'state' => 'England', 'country' => 'United Kingdom', 'timezone' => 'Europe/London'],
    ['name' => 'Singapore', 'city' => 'Singapore', 'state' => 'Singapore', 'country' => 'Singapore', 'timezone' => 'Asia/Singapore'],
    ['name' => 'Dubai', 'city' => 'Dubai', 'state' => 'Dubai', 'country' => 'UAE', 'timezone' => 'Asia/Dubai'],
    ['name' => 'Toronto', 'city' => 'Toronto', 'state' => 'Ontario', 'country' => 'Canada', 'timezone' => 'America/Toronto'],
    ['name' => 'Sydney', 'city' => 'Sydney', 'state' => 'NSW', 'country' => 'Australia', 'timezone' => 'Australia/Sydney'],
    ['name' => 'Berlin', 'city' => 'Berlin', 'state' => 'Berlin', 'country' => 'Germany', 'timezone' => 'Europe/Berlin'],
    ['name' => 'Amsterdam', 'city' => 'Amsterdam', 'state' => 'North Holland', 'country' => 'Netherlands', 'timezone' => 'Europe/Amsterdam'],
    ['name' => 'Tokyo', 'city' => 'Tokyo', 'state' => 'Tokyo', 'country' => 'Japan', 'timezone' => 'Asia/Tokyo'],
];

$locCount = 0;
foreach ($locations as $location) {
    try {
        GlobalLocation::updateOrCreate(
            ['name' => $location['name']],
            [
                'city' => $location['city'],
                'state' => $location['state'],
                'country' => $location['country'],
                'timezone' => $location['timezone'],
                'is_active' => true,
            ]
        );
        $locCount++;
        echo "   ✅ {$location['name']}\n";
    } catch (Exception $e) {
        echo "   ❌ Failed to seed {$location['name']}: " . $e->getMessage() . "\n";
    }
}

echo "✅ Seeded {$locCount} global locations\n\n";

// Step 4: Verification
echo "4️⃣ Verifying seeded data...\n";
try {
    $totalDepts = GlobalDepartment::where('is_active', true)->count();
    $totalLocs = GlobalLocation::where('is_active', true)->count();
    
    echo "✅ Total active departments: {$totalDepts}\n";
    echo "✅ Total active locations: {$totalLocs}\n";
    
    if ($totalDepts >= 20 && $totalLocs >= 30) {
        echo "✅ Seeding completed successfully!\n";
    } else {
        echo "⚠️  Seeding may not be complete. Please check the data.\n";
    }
} catch (Exception $e) {
    echo "❌ Verification failed: " . $e->getMessage() . "\n";
}

echo "\n🎉 Production seeding completed!\n";
echo "📊 Summary:\n";
echo "   - Departments: {$deptCount} seeded\n";
echo "   - Locations: {$locCount} seeded\n";
echo "   - Backup created: {$backupFile}\n";
echo "\n✅ Your HireHub application now has comprehensive department and location data!\n";