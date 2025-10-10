<?php

/**
 * Safe Production Database Setup
 * This script safely adds missing components without clearing existing data
 */

echo "🛡️  Safe Production Database Setup\n";
echo "==================================\n\n";

// Load Laravel
require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "🔍 Checking current database state...\n";
    
    // Check subscription plans
    $planCount = DB::table('subscription_plans')->count();
    echo "📊 Subscription plans: {$planCount}\n";
    
    // Check activation_token column
    $hasActivationToken = DB::getSchemaBuilder()->hasColumn('users', 'activation_token');
    echo "🔑 Activation token column: " . ($hasActivationToken ? 'exists' : 'missing') . "\n";
    
    // Check custom role tables
    $hasCustomTenantRoles = DB::getSchemaBuilder()->hasTable('custom_tenant_roles');
    $hasCustomUserRoles = DB::getSchemaBuilder()->hasTable('custom_user_roles');
    echo "🗂️  Custom role tables: " . ($hasCustomTenantRoles && $hasCustomUserRoles ? 'exist' : 'missing') . "\n";
    
    echo "\n🔄 Applying safe fixes...\n";
    
    // 1. Add subscription plans if missing
    if ($planCount === 0) {
        echo "📊 Adding subscription plans...\n";
        
        DB::table('subscription_plans')->insertOrIgnore([
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for small teams getting started with recruitment',
                'price' => 0.00,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => 2,
                'max_job_openings' => 3,
                'max_candidates' => 50,
                'max_applications_per_month' => 25,
                'max_interviews_per_month' => 10,
                'max_storage_gb' => 1,
                'analytics_enabled' => false,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => false,
                'advanced_reporting' => false,
                'integrations' => false,
                'white_label' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for growing recruitment teams',
                'price' => 999.00,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => true,
                'max_users' => 10,
                'max_job_openings' => 25,
                'max_candidates' => 500,
                'max_applications_per_month' => 200,
                'max_interviews_per_month' => 100,
                'max_storage_gb' => 10,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Unlimited everything for large organizations',
                'price' => -1,
                'currency' => 'INR',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => -1,
                'max_job_openings' => -1,
                'max_candidates' => -1,
                'max_applications_per_month' => -1,
                'max_interviews_per_month' => -1,
                'max_storage_gb' => 100,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
        
        echo "✅ Subscription plans added\n";
    } else {
        echo "✅ Subscription plans already exist\n";
    }
    
    // 2. Add activation_token column if missing
    if (!$hasActivationToken) {
        echo "🔑 Adding activation_token column...\n";
        DB::statement('ALTER TABLE users ADD COLUMN activation_token VARCHAR(60) NULL AFTER email_verified_at');
        echo "✅ activation_token column added\n";
    } else {
        echo "✅ activation_token column already exists\n";
    }
    
    // 3. Create custom role tables if missing
    if (!$hasCustomTenantRoles) {
        echo "🗂️  Creating custom_tenant_roles table...\n";
        DB::statement('
            CREATE TABLE custom_tenant_roles (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                tenant_id VARCHAR(36) NOT NULL,
                name VARCHAR(255) NOT NULL,
                permissions JSON,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE KEY unique_tenant_role (tenant_id, name),
                INDEX idx_tenant_id (tenant_id)
            )
        ');
        echo "✅ custom_tenant_roles table created\n";
    } else {
        echo "✅ custom_tenant_roles table already exists\n";
    }
    
    if (!$hasCustomUserRoles) {
        echo "🗂️  Creating custom_user_roles table...\n";
        DB::statement('
            CREATE TABLE custom_user_roles (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                user_id BIGINT UNSIGNED NOT NULL,
                tenant_id VARCHAR(36) NOT NULL,
                role_name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                UNIQUE KEY unique_user_tenant_role (user_id, tenant_id, role_name),
                INDEX idx_user_id (user_id),
                INDEX idx_tenant_id (tenant_id)
            )
        ');
        echo "✅ custom_user_roles table created\n";
    } else {
        echo "✅ custom_user_roles table already exists\n";
    }
    
    echo "\n🎉 Database setup completed successfully!\n";
    echo "✅ Organization creation should now work properly.\n";
    
    // Final verification
    echo "\n📋 Final verification:\n";
    $finalPlanCount = DB::table('subscription_plans')->count();
    $freePlanExists = DB::table('subscription_plans')->where('slug', 'free')->exists();
    
    echo "📊 Subscription plans: {$finalPlanCount}\n";
    echo "🆓 Free plan: " . ($freePlanExists ? 'exists' : 'missing') . "\n";
    
    if ($finalPlanCount > 0 && $freePlanExists) {
        echo "🎯 All systems ready for organization creation!\n";
    } else {
        echo "⚠️  Some issues may remain - check the output above.\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
