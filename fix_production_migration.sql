-- Fix Production Migration Issue
-- Run this SQL directly in your production database

-- Mark the problematic migration as completed
INSERT INTO migrations (migration, batch) 
VALUES ('2025_10_08_070209_fix_location_id_foreign_key_constraint', 
        (SELECT MAX(batch) + 1 FROM migrations));

-- Verify the migration was added
SELECT * FROM migrations WHERE migration LIKE '%fix_location_id%';
