-- Update subscription to Enterprise plan for tenant: leadformhub
UPDATE tenant_subscriptions ts
INNER JOIN tenants t ON ts.tenant_id = t.id
INNER JOIN subscription_plans sp ON sp.slug = 'enterprise'
SET 
    ts.subscription_plan_id = sp.id,
    ts.status = 'active',
    ts.updated_at = NOW()
WHERE t.slug = 'leadformhub';

