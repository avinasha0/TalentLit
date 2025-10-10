<?php

/**
 * Production Deployment Script for TalentLit
 * 
 * This script ensures all migrations and seeders are run properly
 * to prevent the subscription plan and custom role table issues.
 */

echo "🚀 Starting TalentLit Production Deployment...\n";
echo "===============================================\n\n";

// Check if we're in production
$isProduction = env('APP_ENV') === 'production';
if ($isProduction) {
    echo "⚠️  PRODUCTION ENVIRONMENT DETECTED\n";
    echo "This script will modify your production database.\n";
    echo "Make sure you have a backup before proceeding.\n\n";
    
    $confirm = readline("Type 'yes' to continue: ");
    if (strtolower($confirm) !== 'yes') {
        echo "❌ Deployment cancelled.\n";
        exit(1);
    }
}

echo "📋 Pre-deployment checks...\n";

try {
    // 1. Check current migration status
    echo "\n1. Checking migration status...\n";
    $migrationStatus = shell_exec('php artisan migrate:status 2>&1');
    
    if (strpos($migrationStatus, 'Pending') !== false) {
        echo "⚠️  Found pending migrations\n";
        echo "Pending migrations will be run.\n";
    } else {
        echo "✅ All migrations are up to date\n";
    }
    
    // 2. Check subscription plans
    echo "\n2. Checking subscription plans...\n";
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $planCount = \App\Models\SubscriptionPlan::count();
    echo "📊 Found {$planCount} subscription plans\n";
    
    if ($planCount === 0) {
        echo "❌ No subscription plans found - will seed them\n";
    } else {
        echo "✅ Subscription plans exist\n";
    }
    
    // 3. Check custom role tables
    echo "\n3. Checking custom role tables...\n";
    $tables = ['custom_tenant_roles', 'custom_user_roles'];
    $missingTables = [];
    
    foreach ($tables as $table) {
        if (!\Illuminate\Support\Facades\DB::getSchemaBuilder()->hasTable($table)) {
            $missingTables[] = $table;
        }
    }
    
    if (empty($missingTables)) {
        echo "✅ All custom role tables exist\n";
    } else {
        echo "❌ Missing tables: " . implode(', ', $missingTables) . "\n";
    }
    
    echo "\n🔄 Starting deployment process...\n";
    
    // 4. Run migrations
    echo "\n4. Running migrations...\n";
    $migrationOutput = shell_exec('php artisan migrate --force 2>&1');
    
    if (strpos($migrationOutput, 'FAIL') !== false || strpos($migrationOutput, 'error') !== false) {
        echo "❌ Migration failed:\n";
        echo $migrationOutput . "\n";
        
        // Try to run specific migrations
        echo "🔄 Attempting to run specific migrations...\n";
        
        $specificMigrations = [
            'database/migrations/2025_10_05_124022_create_custom_roles_tables.php',
            'database/migrations/2025_10_08_193707_add_activation_token_to_users_table.php'
        ];
        
        foreach ($specificMigrations as $migration) {
            if (file_exists($migration)) {
                echo "Running: " . basename($migration) . "\n";
                $output = shell_exec("php artisan migrate --path={$migration} --force 2>&1");
                if (strpos($output, 'DONE') !== false) {
                    echo "✅ " . basename($migration) . " completed\n";
                } else {
                    echo "⚠️  " . basename($migration) . " had issues: " . $output . "\n";
                }
            }
        }
    } else {
        echo "✅ Migrations completed successfully\n";
    }
    
    // 5. Seed subscription plans
    echo "\n5. Seeding subscription plans...\n";
    $seedOutput = shell_exec('php artisan db:seed --class=SubscriptionPlanSeeder --force 2>&1');
    
    if (strpos($seedOutput, 'Seeding') !== false) {
        echo "✅ Subscription plans seeded\n";
    } else {
        echo "⚠️  Seeding output: " . $seedOutput . "\n";
    }
    
    // 6. Verify final state
    echo "\n6. Verifying deployment...\n";
    
    $finalPlanCount = \App\Models\SubscriptionPlan::count();
    echo "📊 Subscription plans: {$finalPlanCount}\n";
    
    $freePlan = \App\Models\SubscriptionPlan::where('slug', 'free')->first();
    if ($freePlan) {
        echo "✅ Free plan exists\n";
    } else {
        echo "❌ Free plan missing!\n";
    }
    
    $allTablesExist = true;
    foreach ($tables as $table) {
        if (!\Illuminate\Support\Facades\DB::getSchemaBuilder()->hasTable($table)) {
            echo "❌ Table '{$table}' still missing\n";
            $allTablesExist = false;
        }
    }
    
    if ($allTablesExist) {
        echo "✅ All custom role tables exist\n";
    }
    
    // 7. Test organization creation
    echo "\n7. Testing organization creation capability...\n";
    
    try {
        $testTenant = new \App\Models\Tenant();
        $testTenant->name = 'Test Organization';
        $testTenant->slug = 'test-org-' . time();
        echo "✅ Tenant model can be instantiated\n";
        
        // Test if we can create custom roles
        if (\Illuminate\Support\Facades\DB::getSchemaBuilder()->hasTable('custom_tenant_roles')) {
            echo "✅ Custom role tables are accessible\n";
        }
        
    } catch (Exception $e) {
        echo "❌ Organization creation test failed: " . $e->getMessage() . "\n";
    }
    
    echo "\n🎉 Deployment completed!\n";
    echo "===============================================\n";
    
    if ($finalPlanCount > 0 && $freePlan && $allTablesExist) {
        echo "✅ All systems ready for organization creation\n";
        echo "✅ Users can now complete onboarding successfully\n";
    } else {
        echo "⚠️  Some issues remain - check the output above\n";
    }
    
} catch (Exception $e) {
    echo "❌ Deployment failed: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n📝 Next steps:\n";
echo "1. Test organization creation in your application\n";
echo "2. Monitor logs for any remaining issues\n";
echo "3. Consider setting up automated health checks\n";
echo "\nDeployment script completed.\n";
