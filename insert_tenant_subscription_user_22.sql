-- SQL Query to insert Pro Yearly subscription for tenant_id: 0199e0f3-2da3-7157-874c-1c12f48f5cb2
-- This query will insert a subscription record using the Pro Yearly plan

-- Option 1: Direct INSERT with tenant_id (Recommended - Simple and Fast)
-- This will automatically get the Pro Yearly plan ID and set expiration to 366 days from now
INSERT INTO tenant_subscriptions (
    tenant_id,
    subscription_plan_id,
    status,
    starts_at,
    expires_at,
    payment_method,
    created_at,
    updated_at
)
SELECT 
    '0199e0f3-2da3-7157-874c-1c12f48f5cb2' AS tenant_id,
    sp.id AS subscription_plan_id,
    'active' AS status,
    NOW() AS starts_at,
    DATE_ADD(NOW(), INTERVAL 366 DAY) AS expires_at,  -- Yearly plans expire after 366 days
    'manual' AS payment_method,  -- or 'razorpay' if paid through payment gateway
    NOW() AS created_at,
    NOW() AS updated_at
FROM subscription_plans sp
WHERE sp.slug = 'pro-yearly'  -- Using Pro Yearly plan
  AND NOT EXISTS (
      -- Check if tenant already has a subscription
      SELECT 1 
      FROM tenant_subscriptions ts 
      WHERE ts.tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
  )
LIMIT 1;

-- Option 2: If you know the exact subscription_plan_id, use this simpler version:
-- First, check what subscription plans exist:
-- SELECT id, name, slug, billing_cycle FROM subscription_plans WHERE slug = 'pro-yearly';
--
-- Then use the plan ID:
/*
INSERT INTO tenant_subscriptions (
    tenant_id,
    subscription_plan_id,
    status,
    starts_at,
    expires_at,
    payment_method,
    created_at,
    updated_at
) VALUES (
    '0199e0f3-2da3-7157-874c-1c12f48f5cb2',
    3,  -- Replace with actual subscription_plan_id for pro-yearly from subscription_plans table
    'active',
    NOW(),
    DATE_ADD(NOW(), INTERVAL 366 DAY),  -- Yearly plans expire after 366 days
    'manual',  -- or 'razorpay' if paid through payment gateway
    NOW(),
    NOW()
);
*/

-- To check if the insert was successful:
SELECT 
    ts.*,
    t.name AS tenant_name,
    sp.name AS plan_name,
    sp.slug AS plan_slug
FROM tenant_subscriptions ts
INNER JOIN tenants t ON ts.tenant_id = t.id
INNER JOIN subscription_plans sp ON ts.subscription_plan_id = sp.id
INNER JOIN tenant_user tu ON t.id = tu.tenant_id
WHERE tu.user_id = 22;

