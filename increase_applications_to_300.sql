-- SQL Script to manually increase application count to 300 for this month
-- Tenant ID: 0199e0f3-2da3-7157-874c-1c12f48f5cb2
-- This script will create dummy application records to reach 300 total for the current month

-- Step 1: Check current application count for this month
SELECT 
    COUNT(*) AS current_application_count,
    MONTH(NOW()) AS current_month,
    YEAR(NOW()) AS current_year
FROM applications
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
  AND MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW());

-- Step 2: Get available job openings and candidates for this tenant
-- (We need these to create valid application records)
SELECT 
    'Job Openings:' AS type,
    COUNT(*) AS count
FROM job_openings
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
UNION ALL
SELECT 
    'Candidates:' AS type,
    COUNT(*) AS count
FROM candidates
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2';

-- Step 3: Create dummy applications to reach 300 total
-- This uses a stored procedure approach with a loop
-- Note: This requires at least 1 job opening and 1 candidate to exist

DELIMITER $$

CREATE PROCEDURE IF NOT EXISTS IncreaseApplicationsTo300()
BEGIN
    DECLARE current_count INT DEFAULT 0;
    DECLARE target_count INT DEFAULT 300;
    DECLARE needed_count INT DEFAULT 0;
    DECLARE i INT DEFAULT 0;
    DECLARE v_job_id CHAR(36);
    DECLARE v_candidate_id CHAR(36);
    DECLARE v_tenant_id CHAR(36) DEFAULT '0199e0f3-2da3-7157-874c-1c12f48f5cb2';
    
    -- Get current count
    SELECT COUNT(*) INTO current_count
    FROM applications
    WHERE tenant_id = v_tenant_id
      AND MONTH(created_at) = MONTH(NOW())
      AND YEAR(created_at) = YEAR(NOW());
    
    -- Calculate how many we need
    SET needed_count = GREATEST(0, target_count - current_count);
    
    -- Get first available job and candidate
    SELECT id INTO v_job_id
    FROM job_openings
    WHERE tenant_id = v_tenant_id
    LIMIT 1;
    
    SELECT id INTO v_candidate_id
    FROM candidates
    WHERE tenant_id = v_tenant_id
    LIMIT 1;
    
    -- If no job or candidate exists, we can't create applications
    IF v_job_id IS NULL OR v_candidate_id IS NULL THEN
        SELECT 'ERROR: No job openings or candidates found for this tenant. Please create at least one of each first.' AS message;
    ELSE
        -- Create dummy applications
        WHILE i < needed_count DO
            INSERT INTO applications (
                id,
                tenant_id,
                job_opening_id,
                candidate_id,
                status,
                applied_at,
                created_at,
                updated_at
            ) VALUES (
                UUID(),
                v_tenant_id,
                v_job_id,
                v_candidate_id,
                'active',
                NOW(),
                NOW(),
                NOW()
            )
            ON DUPLICATE KEY UPDATE updated_at = NOW();
            
            SET i = i + 1;
        END WHILE;
        
        SELECT CONCAT('Successfully created ', needed_count, ' applications. Total applications this month: ', target_count) AS message;
    END IF;
END$$

DELIMITER ;

-- Step 4: Alternative approach using a simpler INSERT with subquery (MySQL 8.0+)
-- This creates applications in batches without stored procedure

-- First, let's create a temporary table to generate the needed count
-- Calculate how many applications are needed
SET @current_count = (
    SELECT COUNT(*) 
    FROM applications
    WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
      AND MONTH(created_at) = MONTH(NOW())
      AND YEAR(created_at) = YEAR(NOW())
);

SET @needed_count = GREATEST(0, 300 - @current_count);

-- Get first available job and candidate IDs
SET @job_id = (
    SELECT id 
    FROM job_openings 
    WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2' 
    LIMIT 1
);

SET @candidate_id = (
    SELECT id 
    FROM candidates 
    WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2' 
    LIMIT 1
);

-- Create applications using a recursive CTE (MySQL 8.0+) or simple loop
-- For MySQL 5.7 or earlier, use the stored procedure above
-- For MySQL 8.0+, use this:

-- Option A: Using recursive CTE (MySQL 8.0+)
/*
WITH RECURSIVE numbers AS (
    SELECT 1 AS n
    UNION ALL
    SELECT n + 1 FROM numbers WHERE n < @needed_count
)
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
    @job_id AS job_opening_id,
    @candidate_id AS candidate_id,
    'active' AS status,
    NOW() AS applied_at,
    NOW() AS created_at,
    NOW() AS updated_at
FROM numbers
WHERE @job_id IS NOT NULL 
  AND @candidate_id IS NOT NULL
  AND @needed_count > 0;
*/

-- Option B: Simple INSERT with multiple VALUES (works for smaller counts)
-- This creates 50 applications at a time - run multiple times if needed
-- Adjust the number of rows based on how many you need

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
    SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION
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
WHERE NOT EXISTS (
    -- Check if we've reached 300
    SELECT 1 
    FROM (
        SELECT COUNT(*) AS cnt
        FROM applications
        WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
          AND MONTH(created_at) = MONTH(NOW())
          AND YEAR(created_at) = YEAR(NOW())
    ) AS current
    WHERE current.cnt >= 300
)
LIMIT 50;

-- Step 5: Verify the final count
SELECT 
    COUNT(*) AS total_applications_this_month,
    'Target: 300' AS target,
    CASE 
        WHEN COUNT(*) >= 300 THEN '✅ Target reached'
        ELSE CONCAT('⚠️ Still need ', 300 - COUNT(*), ' more')
    END AS status
FROM applications
WHERE tenant_id = '0199e0f3-2da3-7157-874c-1c12f48f5cb2'
  AND MONTH(created_at) = MONTH(NOW())
  AND YEAR(created_at) = YEAR(NOW());

-- Step 6: Clean up stored procedure if created
-- DROP PROCEDURE IF EXISTS IncreaseApplicationsTo300;

