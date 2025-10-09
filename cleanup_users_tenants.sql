-- =====================================================
-- MySQL Cleanup Script for Users and Tenants
-- =====================================================
-- 
-- WARNING: This script will permanently delete data!
-- Make sure to backup your database before running this script.
-- 
-- This script cleans up users and tenants along with all related data
-- in the correct order to respect foreign key constraints.
-- =====================================================

-- Disable foreign key checks temporarily for safer execution
SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================
-- 1. CLEANUP USER-RELATED DATA
-- =====================================================

-- Delete user sessions
DELETE FROM sessions WHERE user_id IS NOT NULL;

-- Delete password reset tokens
DELETE FROM password_reset_tokens;

-- Delete email verification OTPs
DELETE FROM email_verification_otps;

-- Delete custom user roles
DELETE FROM custom_user_roles;

-- Delete tenant-user relationships
DELETE FROM tenant_user;

-- Delete interview-user relationships
DELETE FROM interview_user;

-- Delete application events (user-related)
DELETE FROM application_events WHERE user_id IS NOT NULL;

-- Delete candidate notes (user-related)
DELETE FROM candidate_notes WHERE user_id IS NOT NULL;

-- =====================================================
-- 2. CLEANUP TENANT-RELATED DATA
-- =====================================================

-- Delete tenant subscriptions
DELETE FROM tenant_subscriptions;

-- Delete tenant branding
DELETE FROM tenant_branding;

-- Delete custom tenant roles
DELETE FROM custom_tenant_roles;

-- Delete application answers
DELETE FROM application_answers;

-- Delete job application questions (junction table)
DELETE FROM job_application_question;

-- Delete application questions
DELETE FROM application_questions;

-- Delete email templates
DELETE FROM email_templates;

-- Delete application events (tenant-related)
DELETE FROM application_events WHERE tenant_id IS NOT NULL;

-- Delete candidate notes (tenant-related)
DELETE FROM candidate_notes WHERE tenant_id IS NOT NULL;

-- Delete interview feedback
DELETE FROM interview_feedback;

-- Delete interviews
DELETE FROM interviews;

-- Delete application stage events
DELETE FROM application_stage_events;

-- Delete application notes
DELETE FROM application_notes;

-- Delete applications
DELETE FROM applications;

-- Delete activities
DELETE FROM activities;

-- Delete privacy events
DELETE FROM privacy_events;

-- Delete consents
DELETE FROM consents;

-- Delete candidate tags
DELETE FROM candidate_tags;

-- Delete candidate contacts
DELETE FROM candidate_contacts;

-- Delete resumes
DELETE FROM resumes;

-- Delete candidates
DELETE FROM candidates;

-- Delete job stages
DELETE FROM job_stages;

-- Delete job openings
DELETE FROM job_openings;

-- Delete job requisitions
DELETE FROM job_requisitions;

-- Delete departments
DELETE FROM departments;

-- Delete locations
DELETE FROM locations;

-- Delete tags
DELETE FROM tags;

-- Delete attachments
DELETE FROM attachments;

-- =====================================================
-- 3. CLEANUP CORE TABLES
-- =====================================================

-- Delete all users
DELETE FROM users;

-- Delete all tenants
DELETE FROM tenants;

-- =====================================================
-- 4. CLEANUP CACHE AND TEMPORARY DATA
-- =====================================================

-- Clear cache table
DELETE FROM cache;

-- Clear cache locks
DELETE FROM cache_locks;

-- =====================================================
-- 5. RESET AUTO-INCREMENT COUNTERS (Optional)
-- =====================================================

-- Reset auto-increment counters for tables that have them
-- Uncomment the following lines if you want to reset counters:

-- ALTER TABLE users AUTO_INCREMENT = 1;
-- ALTER TABLE custom_tenant_roles AUTO_INCREMENT = 1;
-- ALTER TABLE custom_user_roles AUTO_INCREMENT = 1;
-- ALTER TABLE email_verification_otps AUTO_INCREMENT = 1;
-- ALTER TABLE tenant_branding AUTO_INCREMENT = 1;
-- ALTER TABLE application_questions AUTO_INCREMENT = 1;
-- ALTER TABLE application_answers AUTO_INCREMENT = 1;
-- ALTER TABLE tenant_subscriptions AUTO_INCREMENT = 1;
-- ALTER TABLE email_templates AUTO_INCREMENT = 1;
-- ALTER TABLE candidate_notes AUTO_INCREMENT = 1;
-- ALTER TABLE application_events AUTO_INCREMENT = 1;
-- ALTER TABLE interview_feedback AUTO_INCREMENT = 1;
-- ALTER TABLE application_stage_events AUTO_INCREMENT = 1;
-- ALTER TABLE application_notes AUTO_INCREMENT = 1;
-- ALTER TABLE activities AUTO_INCREMENT = 1;
-- ALTER TABLE privacy_events AUTO_INCREMENT = 1;
-- ALTER TABLE consents AUTO_INCREMENT = 1;
-- ALTER TABLE tags AUTO_INCREMENT = 1;
-- ALTER TABLE attachments AUTO_INCREMENT = 1;
-- ALTER TABLE resumes AUTO_INCREMENT = 1;
-- ALTER TABLE candidate_contacts AUTO_INCREMENT = 1;
-- ALTER TABLE candidate_tags AUTO_INCREMENT = 1;
-- ALTER TABLE applications AUTO_INCREMENT = 1;
-- ALTER TABLE interviews AUTO_INCREMENT = 1;
-- ALTER TABLE job_stages AUTO_INCREMENT = 1;
-- ALTER TABLE job_openings AUTO_INCREMENT = 1;
-- ALTER TABLE job_requisitions AUTO_INCREMENT = 1;
-- ALTER TABLE departments AUTO_INCREMENT = 1;
-- ALTER TABLE locations AUTO_INCREMENT = 1;

-- =====================================================
-- 6. RE-ENABLE FOREIGN KEY CHECKS
-- =====================================================

-- Re-enable foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Run these queries to verify cleanup was successful:

-- Check if users table is empty
-- SELECT COUNT(*) as user_count FROM users;

-- Check if tenants table is empty
-- SELECT COUNT(*) as tenant_count FROM tenants;

-- Check if tenant_user table is empty
-- SELECT COUNT(*) as tenant_user_count FROM tenant_user;

-- Check if any remaining data in related tables
-- SELECT 
--     (SELECT COUNT(*) FROM applications) as applications_count,
--     (SELECT COUNT(*) FROM candidates) as candidates_count,
--     (SELECT COUNT(*) FROM interviews) as interviews_count,
--     (SELECT COUNT(*) FROM job_openings) as job_openings_count;

-- =====================================================
-- SCRIPT COMPLETED
-- =====================================================

-- The cleanup script has been executed successfully.
-- All users, tenants, and related data have been removed.
-- 
-- Next steps:
-- 1. Verify the cleanup using the verification queries above
-- 2. Run your application's seeders to restore default data if needed
-- 3. Test your application to ensure everything works correctly
