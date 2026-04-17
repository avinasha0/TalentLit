<?php

/**
 * Quick Test Script for Approval Workflow
 * 
 * Usage: php test_approval_workflow.php
 * 
 * This script will:
 * 1. Check if migrations are run
 * 2. Create test data (users, roles, requisition)
 * 3. Test workflow evaluation
 * 4. Provide testing instructions
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Requisition;
use App\Models\User;
use App\Models\Tenant;
use App\Services\ApprovalWorkflowService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "Approval Workflow Test Script\n";
echo "========================================\n\n";

// Check if tables exist
echo "1. Checking database tables...\n";
$tables = ['requisitions', 'requisition_approvals', 'tasks', 'custom_user_roles'];
$allExist = true;

foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        echo "   ✓ Table '{$table}' exists\n";
    } else {
        echo "   ✗ Table '{$table}' NOT found - Run migrations first!\n";
        $allExist = false;
    }
}

if (!$allExist) {
    echo "\n❌ Please run migrations first: php artisan migrate\n";
    exit(1);
}

// Check if approval columns exist
echo "\n2. Checking requisitions table columns...\n";
$columns = ['approval_status', 'approval_level', 'current_approver_id', 'approved_at', 'approval_workflow'];
$hasAllColumns = true;

foreach ($columns as $column) {
    if (Schema::hasColumn('requisitions', $column)) {
        echo "   ✓ Column '{$column}' exists\n";
    } else {
        echo "   ✗ Column '{$column}' NOT found - Run migrations first!\n";
        $hasAllColumns = false;
    }
}

if (!$hasAllColumns) {
    echo "\n❌ Please run migrations first: php artisan migrate\n";
    exit(1);
}

// Get or create tenant
echo "\n3. Setting up tenant...\n";
$tenant = Tenant::first();
if (!$tenant) {
    echo "   ⚠ No tenant found. Creating test tenant...\n";
    $tenant = Tenant::create([
        'name' => 'Test Company',
        'slug' => 'test-company'
    ]);
    echo "   ✓ Created tenant: {$tenant->name} (slug: {$tenant->slug})\n";
} else {
    echo "   ✓ Using existing tenant: {$tenant->name} (slug: {$tenant->slug})\n";
}

// Create test users
echo "\n4. Creating test users...\n";
$hiringManager = User::firstOrCreate(
    ['email' => 'hiring@test.com'],
    [
        'name' => 'Hiring Manager',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);
echo "   ✓ Hiring Manager: {$hiringManager->email} (ID: {$hiringManager->id})\n";

$approver1 = User::firstOrCreate(
    ['email' => 'approver1@test.com'],
    [
        'name' => 'Department Head',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);
echo "   ✓ Approver 1: {$approver1->email} (ID: {$approver1->id})\n";

$approver2 = User::firstOrCreate(
    ['email' => 'approver2@test.com'],
    [
        'name' => 'HR Manager',
        'password' => bcrypt('password'),
        'email_verified_at' => now()
    ]
);
echo "   ✓ Approver 2: {$approver2->email} (ID: {$approver2->id})\n";

// Assign users to tenant
echo "\n5. Assigning users to tenant...\n";
$hiringManager->tenants()->syncWithoutDetaching([$tenant->id]);
$approver1->tenants()->syncWithoutDetaching([$tenant->id]);
$approver2->tenants()->syncWithoutDetaching([$tenant->id]);
echo "   ✓ Users assigned to tenant\n";

// Assign roles
echo "\n6. Assigning roles...\n";
DB::table('custom_user_roles')->updateOrInsert(
    ['user_id' => $hiringManager->id, 'tenant_id' => $tenant->id],
    ['role_name' => 'Recruiter', 'created_at' => now(), 'updated_at' => now()]
);
echo "   ✓ Hiring Manager → Recruiter\n";

DB::table('custom_user_roles')->updateOrInsert(
    ['user_id' => $approver1->id, 'tenant_id' => $tenant->id],
    ['role_name' => 'DepartmentHead', 'created_at' => now(), 'updated_at' => now()]
);
echo "   ✓ Approver 1 → DepartmentHead\n";

DB::table('custom_user_roles')->updateOrInsert(
    ['user_id' => $approver2->id, 'tenant_id' => $tenant->id],
    ['role_name' => 'HRManager', 'created_at' => now(), 'updated_at' => now()]
);
echo "   ✓ Approver 2 → HRManager\n";

// Create test requisition
echo "\n7. Creating test requisition...\n";
$requisition = Requisition::create([
    'tenant_id' => $tenant->id,
    'department' => 'Engineering',
    'job_title' => 'Senior Software Engineer',
    'justification' => 'Need to expand the development team to handle increased workload.',
    'budget_min' => 100000,
    'budget_max' => 150000,
    'contract_type' => 'Full-time',
    'skills' => json_encode(['PHP', 'Laravel', 'MySQL', 'JavaScript']),
    'experience_min' => 5,
    'experience_max' => 10,
    'headcount' => 1,
    'priority' => 'Medium',
    'location' => 'Remote',
    'status' => 'Draft',
    'approval_status' => 'Draft',
    'approval_level' => 0,
    'created_by' => $hiringManager->id,
]);
echo "   ✓ Requisition created: ID {$requisition->id}\n";
echo "   ✓ Job Title: {$requisition->job_title}\n";
echo "   ✓ Status: {$requisition->status} / {$requisition->approval_status}\n";

// Test workflow service
echo "\n8. Testing workflow service...\n";
try {
    $workflowService = new ApprovalWorkflowService();
    $workflow = $workflowService->evaluateWorkflow($requisition);
    echo "   ✓ Workflow evaluated: " . count($workflow) . " level(s)\n";
    
    foreach ($workflow as $step) {
        echo "      - Level {$step['level']}: {$step['role']}\n";
    }
    
    $firstApprover = $workflowService->getFirstApprover($requisition);
    if ($firstApprover) {
        $approverUser = User::find($firstApprover);
        echo "   ✓ First approver: {$approverUser->name} (ID: {$firstApprover})\n";
    } else {
        echo "   ⚠ First approver not found - check role assignments\n";
    }
    
    $hasMore = $workflowService->hasMoreLevels($requisition);
    echo "   ✓ Has more levels: " . ($hasMore ? 'Yes' : 'No') . "\n";
    
} catch (\Exception $e) {
    echo "   ✗ Error testing workflow: " . $e->getMessage() . "\n";
}

// Summary
echo "\n========================================\n";
echo "Test Setup Complete!\n";
echo "========================================\n\n";

echo "Test Data Created:\n";
echo "  - Tenant: {$tenant->slug}\n";
echo "  - Requisition ID: {$requisition->id}\n";
echo "  - Hiring Manager: hiring@test.com / password\n";
echo "  - Approver 1: approver1@test.com / password\n";
echo "  - Approver 2: approver2@test.com / password\n\n";

echo "Next Steps:\n";
echo "1. Login as Hiring Manager: http://localhost/{$tenant->slug}/login\n";
echo "2. Go to requisitions: http://localhost/{$tenant->slug}/requisitions\n";
echo "3. View requisition: http://localhost/{$tenant->slug}/requisitions/{$requisition->id}\n";
echo "4. Submit for approval (via API or add button to UI)\n";
echo "5. Login as Approver 1: http://localhost/{$tenant->slug}/login\n";
echo "6. View pending approvals: http://localhost/{$tenant->slug}/requisitions/pending-approvals\n";
echo "7. Click on requisition to approve/reject/request changes\n\n";

echo "API Endpoints to Test:\n";
echo "  POST /{$tenant->slug}/api/requisitions/{$requisition->id}/submit\n";
echo "  GET  /{$tenant->slug}/api/approvals/pending\n";
echo "  POST /{$tenant->slug}/api/requisitions/{$requisition->id}/approve\n";
echo "  POST /{$tenant->slug}/api/requisitions/{$requisition->id}/reject\n";
echo "  POST /{$tenant->slug}/api/requisitions/{$requisition->id}/request-changes\n";
echo "  GET  /{$tenant->slug}/api/requisitions/{$requisition->id}/approvals\n\n";

echo "Database Queries to Verify:\n";
echo "  SELECT * FROM requisitions WHERE id = {$requisition->id};\n";
echo "  SELECT * FROM requisition_approvals WHERE requisition_id = {$requisition->id};\n";
echo "  SELECT * FROM tasks WHERE requisition_id = {$requisition->id};\n\n";

echo "Done! 🎉\n";

