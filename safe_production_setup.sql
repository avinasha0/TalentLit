-- Safe Production Database Script
-- This script ONLY adds missing data - will NOT clear existing data
-- Run this after syncing your repo to production

-- ============================================
-- 1. ADD SUBSCRIPTION PLANS (if missing)
-- ============================================

-- Check if subscription plans exist, if not add them
-- Note: If actual_price and discount_price columns don't exist, they will be ignored
INSERT IGNORE INTO subscription_plans (
    name, slug, description, price, actual_price, discount_price, currency, billing_cycle, 
    is_active, is_popular, max_users, max_job_openings, 
    max_candidates, max_applications_per_month, max_interviews_per_month,
    max_storage_gb, analytics_enabled, custom_branding, api_access,
    priority_support, advanced_reporting, integrations, white_label,
    created_at, updated_at
) VALUES 
-- Free Plan (CRITICAL - needed for organization creation)
('Free', 'free', 'Perfect for small teams getting started with recruitment', 
 0.00, NULL, NULL, 'INR', 'monthly', 1, 0, 2, 3, 50, 25, 10, 1, 0, 0, 0, 0, 0, 0, 0, NOW(), NOW()),

-- Pro Plan
('Pro', 'pro', 'Advanced features for growing recruitment teams', 
 997.00, 3999.00, 997.00, 'INR', 'monthly', 1, 1, 4, 10, 200, 80, 40, 10, 1, 1, 1, 1, 1, 1, 0, NOW(), NOW()),

-- Pro Yearly Plan
('Pro Yearly', 'pro-yearly', 'Advanced features for growing recruitment teams with enhanced limits', 
 9997.00, 47974.00, 9997.00, 'INR', 'yearly', 1, 0, 6, 15, 300, 125, 60, 10, 1, 1, 1, 1, 1, 1, 0, NOW(), NOW()),

-- Enterprise Plan
('Enterprise', 'enterprise', 'Unlimited everything for large organizations', 
 -1, NULL, NULL, 'INR', 'monthly', 1, 0, -1, -1, -1, -1, -1, 100, 1, 1, 1, 1, 1, 1, 1, NOW(), NOW());

-- ============================================
-- 2. ADD ACTIVATION_TOKEN COLUMN (if missing)
-- ============================================

-- Check if activation_token column exists, if not add it
SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'users' 
    AND COLUMN_NAME = 'activation_token'
);

SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE users ADD COLUMN activation_token VARCHAR(60) NULL AFTER email_verified_at',
    'SELECT "activation_token column already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================
-- 3. CREATE CUSTOM ROLE TABLES (if missing)
-- ============================================

-- Create custom_tenant_roles table if it doesn't exist
CREATE TABLE IF NOT EXISTS custom_tenant_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tenant_id VARCHAR(36) NOT NULL,
    name VARCHAR(255) NOT NULL,
    permissions JSON,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_tenant_role (tenant_id, name),
    INDEX idx_tenant_id (tenant_id)
);

-- Create custom_user_roles table if it doesn't exist
CREATE TABLE IF NOT EXISTS custom_user_roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    tenant_id VARCHAR(36) NOT NULL,
    role_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    UNIQUE KEY unique_user_tenant_role (user_id, tenant_id, role_name),
    INDEX idx_user_id (user_id),
    INDEX idx_tenant_id (tenant_id)
);

-- ============================================
-- 4. VERIFICATION QUERIES
-- ============================================

-- Check subscription plans
SELECT 'Subscription Plans Check' as check_type;
SELECT COUNT(*) as plan_count FROM subscription_plans;
SELECT name, slug FROM subscription_plans WHERE slug = 'free';

-- Check activation_token column
SELECT 'Activation Token Column Check' as check_type;
SELECT COUNT(*) as column_exists 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'users' 
AND COLUMN_NAME = 'activation_token';

-- Check custom role tables
SELECT 'Custom Role Tables Check' as check_type;
SELECT COUNT(*) as custom_tenant_roles_exists 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'custom_tenant_roles';

SELECT COUNT(*) as custom_user_roles_exists 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'custom_user_roles';

-- ============================================
-- SUCCESS MESSAGE
-- ============================================
SELECT 'Database setup completed successfully!' as status;
SELECT 'Organization creation should now work properly.' as message;
