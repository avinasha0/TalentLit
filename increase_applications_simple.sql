-- Simple SQL Script to increase application count to 300 for this month
-- Tenant ID: 0199e0f3-2da3-7157-874c-1c12f48f5cb2

-- Step 1: Check current application count
SELECT 
    COUNT(*) AS current_count,
    CONCAT('Need to create ', GREATEST(0, 300 - COUNT(*)), ' more applications') AS action_needed
FROM applications
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
  AND MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW());

-- Step 2: Verify tenant has at least one job opening and one candidate
SELECT 
    (SELECT COUNT(*) FROM job_openings WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2') AS job_count,
    (SELECT COUNT(*) FROM candidates WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2') AS candidate_count;

-- Step 3: Create applications in batches of 50
-- Run this query multiple times until you reach 300 applications
-- Each execution creates up to 50 new applications

INSERT INTO applications (
    id,
    tenant_id,
    job_opening_id,
    candidate_id,
    status,
    applied_at,
    created_at,
    updated_at
)
SELECT 
    UUID() AS id,
    '0199e0f3-2da3-7157-874c-1c12f48f5cb2' AS tenant_id,
    (SELECT id FROM job_openings WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2' LIMIT 1) AS job_opening_id,
    (SELECT id FROM candidates WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2' LIMIT 1) AS candidate_id,
    'active' AS status,
    NOW() AS applied_at,
    NOW() AS created_at,
    NOW() AS updated_at
FROM (
    SELECT 1 AS n UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
    SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION
    SELECT 11 UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15 UNION
    SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20 UNION
    SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION
    SELECT 26 UNION SELECT 27 UNION SELECT 28 UNION SELECT 29 UNION SELECT 30 UNION
    SELECT 31 UNION SELECT 32 UNION SELECT 33 UNION SELECT 34 UNION SELECT 35 UNION
    SELECT 36 UNION SELECT 37 UNION SELECT 38 UNION SELECT 39 UNION SELECT 40 UNION
    SELECT 41 UNION SELECT 42 UNION SELECT 43 UNION SELECT 44 UNION SELECT 45 UNION
    SELECT 46 UNION SELECT 47 UNION SELECT 48 UNION SELECT 49 UNION SELECT 50
) AS numbers
WHERE (
    SELECT COUNT(*) 
    FROM applications
    WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
      AND MONTH(created_at) = MONTH(NOW())
      AND YEAR(created_at) = YEAR(NOW())
) < 300
LIMIT 50;

-- Step 4: Verify final count after running the INSERT above
SELECT 
    COUNT(*) AS total_applications_this_month,
    CASE 
        WHEN COUNT(*) >= 300 THEN '✅ Target reached (300+)'
        ELSE CONCAT('⚠️ Current: ', COUNT(*), ' / Target: 300')
    END AS status
FROM applications
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
  AND MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW());

-- INSTRUCTIONS:
-- 1. Run Step 1 to check current count
-- 2. Run Step 2 to verify you have at least 1 job and 1 candidate
-- 3. Run Step 3 multiple times (each time creates up to 50 applications)
--    - If you have 0 applications, run it 6 times (6 x 50 = 300)
--    - If you have 100 applications, run it 4 times (4 x 50 = 200, total = 300)
-- 4. Run Step 4 to verify you've reached 300

