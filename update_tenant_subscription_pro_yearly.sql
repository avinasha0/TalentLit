-- SQL Query to UPDATE subscription to Pro Yearly plan for tenant_id: 0199e0f3-2da3-7157-874c-1c12f48f5cb2
-- This will update the existing subscription to Pro Yearly plan

-- Option 1: Update subscription to Pro Yearly, extending expiration from current date
-- This will set expires_at to 366 days from now (regardless of previous expiration)
UPDATE tenant_subscriptions ts
INNER JOIN subscription_plans sp ON sp.slug = 'pro-yearly'
SET 
    ts.subscription_plan_id = sp.id,
    ts.status = 'active',
    ts.expires_at = DATE_ADD(NOW(), INTERVAL 366 DAY),  -- 366 days from now
    ts.payment_method = COALESCE(ts.payment_method, 'manual'),  -- Keep existing or set to 'manual'
    ts.updated_at = NOW()
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Option 2: Update subscription to Pro Yearly, extending expiration from current expires_at date
-- This will add 366 days to the existing expiration date (if it exists)
UPDATE tenant_subscriptions ts
INNER JOIN subscription_plans sp ON sp.slug = 'pro-yearly'
SET 
    ts.subscription_plan_id = sp.id,
    ts.status = 'active',
    ts.expires_at = CASE 
        WHEN ts.expires_at IS NULL THEN DATE_ADD(NOW(), INTERVAL 366 DAY)
        WHEN ts.expires_at < NOW() THEN DATE_ADD(NOW(), INTERVAL 366 DAY)
        ELSE DATE_ADD(ts.expires_at, INTERVAL 366 DAY)
    END,
    ts.payment_method = COALESCE(ts.payment_method, 'manual'),
    ts.updated_at = NOW()
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Option 3: Update subscription to Pro Yearly, extending expiration from starts_at date
-- This will set expires_at to 366 days from the original starts_at date
UPDATE tenant_subscriptions ts
INNER JOIN subscription_plans sp ON sp.slug = 'pro-yearly'
SET 
    ts.subscription_plan_id = sp.id,
    ts.status = 'active',
    ts.expires_at = DATE_ADD(ts.starts_at, INTERVAL 366 DAY),
    ts.payment_method = COALESCE(ts.payment_method, 'manual'),
    ts.updated_at = NOW()
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Option 4: Simple update if you know the subscription_plan_id
-- First, get the Pro Yearly plan ID:
-- SELECT id, name, slug FROM subscription_plans WHERE slug = 'pro-yearly';
--
-- Then update using the plan ID:
/*
UPDATE tenant_subscriptions
SET 
    subscription_plan_id = 3,  -- Replace with actual pro-yearly plan ID
    status = 'active',
    expires_at = DATE_ADD(NOW(), INTERVAL 366 DAY),
    payment_method = COALESCE(payment_method, 'manual'),
    updated_at = NOW()
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';
*/

-- Check current subscription before update:
SELECT 
    ts.id,
    ts.tenant_id,
    ts.subscription_plan_id,
    sp.name AS current_plan_name,
    sp.slug AS current_plan_slug,
    ts.status,
    ts.starts_at,
    ts.expires_at,
    ts.payment_method,
    DATEDIFF(ts.expires_at, NOW()) AS days_remaining
FROM tenant_subscriptions ts
INNER JOIN subscription_plans sp ON ts.subscription_plan_id = sp.id
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Verify the update was successful:
SELECT 
    ts.id,
    ts.tenant_id,
    t.name AS tenant_name,
    ts.subscription_plan_id,
    sp.name AS plan_name,
    sp.slug AS plan_slug,
    sp.billing_cycle,
    ts.status,
    ts.starts_at,
    ts.expires_at,
    ts.payment_method,
    DATEDIFF(ts.expires_at, NOW()) AS days_remaining,
    ts.updated_at
FROM tenant_subscriptions ts
INNER JOIN tenants t ON ts.tenant_id = t.id
INNER JOIN subscription_plans sp ON ts.subscription_plan_id = sp.id
WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

