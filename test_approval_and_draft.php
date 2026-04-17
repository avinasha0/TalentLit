<?php

/**
 * COMPREHENSIVE TEST - Approval and Draft Submission
 * 
 * Tests:
 * 1. Submit for Approval → Status should be "Pending"
 * 2. Save as Draft → Status should be "Draft"
 * 
 * Run: php test_approval_and_draft.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Requisition;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

echo "========================================\n";
echo "APPROVAL AND DRAFT SUBMISSION TEST\n";
echo "========================================\n\n";

$testResults = [
    'passed' => 0,
    'failed' => 0,
];

try {
    // Get or create test tenant
    $tenant = Tenant::where('slug', 'testcompany')->first();
    if (!$tenant) {
        $tenant = Tenant::create(['name' => 'Test Company', 'slug' => 'testcompany']);
    }

    // Get or create test user
    $user = User::where('email', 'test@example.com')->first();
    if (!$user) {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $user->tenants()->attach($tenant->id);
    }

    Tenancy::set($tenant);
    auth()->login($user);

    echo "TEST SETUP:\n";
    echo "  Tenant: {$tenant->slug}\n";
    echo "  User: {$user->email}\n\n";

    // Clean up previous test data
    echo "Cleaning up previous test data...\n";
    Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->whereIn('job_title', ['Test Approval', 'Test Draft'])
        ->delete();
    echo "✓ Cleanup complete\n\n";

    // ========================================
    // TEST 1: Submit for Approval
    // ========================================
    echo "========================================\n";
    echo "TEST 1: Submit for Approval\n";
    echo "========================================\n";
    
    $controller = new \App\Http\Controllers\Tenant\RequisitionController(
        app(\App\Services\ApprovalWorkflowService::class)
    );

    $request = new \Illuminate\Http\Request();
    $request->merge([
        'department' => 'IT',
        'job_title' => 'Test Approval',
        'justification' => 'Need for approval test',
        'budget_min' => 60000,
        'budget_max' => 80000,
        'contract_type' => 'Full-time',
        'skills' => json_encode(['PHP', 'Laravel']),
        'experience_min' => 3,
        'headcount' => 1,
        'priority' => 'Medium',
        'location' => 'Remote',
        'additional_notes' => 'Test approval submission',
        'submit_action' => 'approval', // CRITICAL: Submit for approval
        'is_new' => 'true',
    ]);

    echo "Submitting requisition for approval...\n";
    $response = $controller->store($request, $tenant->slug);
    
    // Extract requisition ID from response
    $responseContent = $response->getContent();
    $requisitionId = null;
    
    if (preg_match('/requisitions\/(\d+)\/success/', $responseContent, $matches)) {
        $requisitionId = (int)$matches[1];
    } elseif (preg_match('/"requisition_id":(\d+)/', $responseContent, $matches)) {
        $requisitionId = (int)$matches[1];
    }
    
    if (!$requisitionId) {
        // Try to find the most recent requisition with job_title "Test Approval"
        $requisition = Requisition::where('tenant_id', $tenant->id)
            ->where('created_by', $user->id)
            ->where('job_title', 'Test Approval')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($requisition) {
            $requisitionId = $requisition->id;
        }
    }
    
    if (!$requisitionId) {
        echo "❌ FAILED: Could not determine requisition ID from response\n";
        $testResults['failed']++;
    } else {
        echo "✓ Requisition created with ID: {$requisitionId}\n";
        
        // Check the status
        $requisition = Requisition::find($requisitionId);
        
        echo "\nChecking requisition status...\n";
        echo "  ID: {$requisition->id}\n";
        echo "  Job Title: {$requisition->job_title}\n";
        echo "  Status: {$requisition->status}\n";
        echo "  Approval Status: {$requisition->approval_status}\n";
        echo "  Approval Level: {$requisition->approval_level}\n";
        echo "  Current Approver ID: " . ($requisition->current_approver_id ?? 'null') . "\n";
        
        // Verify status is Pending
        if ($requisition->status === 'Pending' && $requisition->approval_status === 'Pending') {
            echo "\n✅ TEST 1 PASSED: Status is 'Pending' as expected\n";
            $testResults['passed']++;
        } else {
            echo "\n❌ TEST 1 FAILED: Status should be 'Pending', but got:\n";
            echo "   Status: {$requisition->status}\n";
            echo "   Approval Status: {$requisition->approval_status}\n";
            $testResults['failed']++;
        }
        
        // Verify approval workflow was set up
        if ($requisition->current_approver_id !== null) {
            echo "✓ Approval workflow set up (approver assigned)\n";
        } else {
            echo "⚠️  WARNING: No approver assigned (might be expected if no approvers configured)\n";
        }
    }

    echo "\n";

    // ========================================
    // TEST 2: Save as Draft
    // ========================================
    echo "========================================\n";
    echo "TEST 2: Save as Draft\n";
    echo "========================================\n";
    
    $request2 = new \Illuminate\Http\Request();
    $request2->merge([
        'department' => 'HR',
        'job_title' => 'Test Draft',
        'justification' => 'Need for draft test',
        'budget_min' => 50000,
        'budget_max' => 70000,
        'contract_type' => 'Full-time',
        'skills' => json_encode(['HR', 'Recruitment']),
        'experience_min' => 2,
        'headcount' => 1,
        'priority' => 'Low',
        'location' => 'Office',
        'additional_notes' => 'Test draft submission',
        'submit_action' => 'draft', // CRITICAL: Save as draft
        'is_new' => 'true',
    ]);

    echo "Saving requisition as draft...\n";
    $response2 = $controller->store($request2, $tenant->slug);
    
    // Extract requisition ID from response
    $responseContent2 = $response2->getContent();
    $requisitionId2 = null;
    
    if (preg_match('/requisitions\/(\d+)\/success/', $responseContent2, $matches)) {
        $requisitionId2 = (int)$matches[1];
    } elseif (preg_match('/"requisition_id":(\d+)/', $responseContent2, $matches)) {
        $requisitionId2 = (int)$matches[1];
    }
    
    if (!$requisitionId2) {
        // Try to find the most recent requisition with job_title "Test Draft"
        $requisition2 = Requisition::where('tenant_id', $tenant->id)
            ->where('created_by', $user->id)
            ->where('job_title', 'Test Draft')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($requisition2) {
            $requisitionId2 = $requisition2->id;
        }
    }
    
    if (!$requisitionId2) {
        echo "❌ FAILED: Could not determine requisition ID from response\n";
        $testResults['failed']++;
    } else {
        echo "✓ Requisition created with ID: {$requisitionId2}\n";
        
        // Check the status
        $requisition2 = Requisition::find($requisitionId2);
        
        echo "\nChecking requisition status...\n";
        echo "  ID: {$requisition2->id}\n";
        echo "  Job Title: {$requisition2->job_title}\n";
        echo "  Status: {$requisition2->status}\n";
        echo "  Approval Status: {$requisition2->approval_status}\n";
        echo "  Approval Level: {$requisition2->approval_level}\n";
        echo "  Current Approver ID: " . ($requisition2->current_approver_id ?? 'null') . "\n";
        
        // Verify status is Draft
        if ($requisition2->status === 'Draft' && $requisition2->approval_status === 'Draft') {
            echo "\n✅ TEST 2 PASSED: Status is 'Draft' as expected\n";
            $testResults['passed']++;
        } else {
            echo "\n❌ TEST 2 FAILED: Status should be 'Draft', but got:\n";
            echo "   Status: {$requisition2->status}\n";
            echo "   Approval Status: {$requisition2->approval_status}\n";
            $testResults['failed']++;
        }
        
        // Verify no approver assigned
        if ($requisition2->current_approver_id === null) {
            echo "✓ No approver assigned (correct for draft)\n";
        } else {
            echo "⚠️  WARNING: Approver assigned to draft (unexpected)\n";
        }
    }

    // ========================================
    // FINAL SUMMARY
    // ========================================
    echo "\n";
    echo "========================================\n";
    echo "FINAL TEST RESULTS\n";
    echo "========================================\n";
    echo "✅ Passed: {$testResults['passed']}\n";
    echo "❌ Failed: {$testResults['failed']}\n";
    echo "\n";
    
    if ($testResults['failed'] === 0) {
        echo "✅ ALL TESTS PASSED!\n";
        echo "   - Submit for Approval → Status: Pending ✓\n";
        echo "   - Save as Draft → Status: Draft ✓\n";
        exit(0);
    } else {
        echo "❌ SOME TESTS FAILED!\n";
        echo "   Please check the output above for details.\n";
        exit(1);
    }

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

