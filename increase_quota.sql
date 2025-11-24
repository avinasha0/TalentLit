-- SQL Script to Increase Quota for Tenant ID: 0199e0f3-2da3-7157-874c-1c12f48f5cb2
-- Modify the values below as needed

-- First, let's see the current subscription plan for this tenant
SELECT 
    ts.tenant_id,
    ts.subscription_plan_id,
    sp.name AS plan_name,
    sp.max_users,
    sp.max_job_openings,
    sp.max_candidates,
    sp.max_applications_per_month,
    sp.max_interviews_per_month,
    sp.max_storage_gb
FROM tenant_subscriptions ts
JOIN subscription_plans sp ON ts.subscription_plan_id = sp.id
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Update quota limits for the subscription plan associated with this tenant
-- Adjust the values below according to your needs
UPDATE subscription_plans sp
JOIN tenant_subscriptions ts ON sp.id = ts.subscription_plan_id
SET 
    sp.max_users = 100,                    -- Change as needed
    sp.max_job_openings = 500,             -- Change as needed
    sp.max_candidates = 10000,             -- Change as needed
    sp.max_applications_per_month = 5000,  -- Change as needed
    sp.max_interviews_per_month = 1000,    -- Change as needed
    sp.max_storage_gb = 100                -- Change as needed
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Verify the update
SELECT 
    ts.tenant_id,
    ts.subscription_plan_id,
    sp.name AS plan_name,
    sp.max_users,
    sp.max_job_openings,
    sp.max_candidates,
    sp.max_applications_per_month,
    sp.max_interviews_per_month,
    sp.max_storage_gb
FROM tenant_subscriptions ts
JOIN subscription_plans sp ON ts.subscription_plan_id = sp.id
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

