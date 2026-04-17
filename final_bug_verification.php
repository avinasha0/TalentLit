<?php

/**
 * FINAL VERIFICATION TEST - Requisition Draft Update Bug
 * 
 * This test simulates the EXACT user scenario:
 * 1. User has existing requisitions (some with job_title "Previous")
 * 2. User creates a NEW requisition with job_title "4"
 * 3. Verifies that existing requisitions are NOT updated to "4"
 * 
 * Run: php final_bug_verification.php
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
echo "FINAL BUG VERIFICATION TEST\n";
echo "========================================\n\n";

$testResults = [
    'passed' => 0,
    'failed' => 0,
    'warnings' => 0,
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
        ->whereIn('job_title', ['Previous', '4', 'Test1', 'Test2', 'Test3'])
        ->delete();
    echo "✓ Cleanup complete\n\n";

    // STEP 1: Create multiple existing drafts (simulating user's existing requisitions)
    echo "STEP 1: Creating existing drafts (simulating user's previous requisitions)...\n";
    
    $existingRequisitions = [];
    $jobTitles = ['Previous', 'Test1', 'Test2'];
    
    foreach ($jobTitles as $title) {
        $req = Requisition::create([
            'tenant_id' => $tenant->id,
            'created_by' => $user->id,
            'department' => 'IT',
            'job_title' => $title,
            'justification' => "Need for {$title} role",
            'budget_min' => 50000,
            'budget_max' => 70000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['PHP', 'Laravel']),
            'experience_min' => 2,
            'headcount' => 1,
            'priority' => 'Medium',
            'location' => 'Remote',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
        ]);
        
        $existingRequisitions[$req->id] = [
            'id' => $req->id,
            'job_title' => $req->job_title,
            'updated_at' => $req->updated_at->format('Y-m-d H:i:s'),
        ];
        
        echo "  Created: ID {$req->id}, Job Title: '{$req->job_title}'\n";
    }
    
    echo "✓ Created " . count($existingRequisitions) . " existing drafts\n\n";
    $testResults['passed']++;
    
    // Wait to ensure timestamps are different
    sleep(2);

    // STEP 2: Simulate form submission to create NEW requisition with job_title "4"
    echo "STEP 2: Simulating form submission to create NEW requisition with job_title '4'...\n";
    
    $controller = new \App\Http\Controllers\Tenant\RequisitionController(
        app(\App\Services\ApprovalWorkflowService::class)
    );

    $request = new \Illuminate\Http\Request();
    $request->merge([
        'department' => 'Operations',
        'job_title' => '4',
        'justification' => 'Need for new role',
        'budget_min' => 60000,
        'budget_max' => 80000,
        'contract_type' => 'Full-time',
        'skills' => json_encode(['JavaScript', 'React']),
        'experience_min' => 3,
        'headcount' => 1,
        'priority' => 'Medium',
        'location' => 'Office',
        'additional_notes' => 'Test notes',
        'submit_action' => 'draft',
        'is_new' => 'true', // CRITICAL FLAG
    ]);

    // Call store method
    $response = $controller->store($request, $tenant->slug);
    
    // Extract requisition ID from response (could be redirect or JSON)
    $responseContent = $response->getContent();
    $newRequisitionId = null;
    
    if (preg_match('/requisitions\/(\d+)\/success/', $responseContent, $matches)) {
        $newRequisitionId = (int)$matches[1];
    } elseif (preg_match('/"requisition_id":(\d+)/', $responseContent, $matches)) {
        $newRequisitionId = (int)$matches[1];
    }
    
    if (!$newRequisitionId) {
        // Try to find the most recent requisition with job_title "4"
        $newRequisition = Requisition::where('tenant_id', $tenant->id)
            ->where('created_by', $user->id)
            ->where('job_title', '4')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($newRequisition) {
            $newRequisitionId = $newRequisition->id;
        }
    }
    
    if (!$newRequisitionId) {
        echo "❌ ERROR: Could not determine new requisition ID from response\n";
        echo "Response: " . substr($responseContent, 0, 500) . "...\n";
        $testResults['failed']++;
        exit(1);
    }
    
    echo "✓ Form submission completed, new requisition ID: {$newRequisitionId}\n\n";
    $testResults['passed']++;

    // STEP 3: Verify new requisition was created correctly
    echo "STEP 3: Verifying new requisition was created correctly...\n";
    
    $newRequisition = Requisition::find($newRequisitionId);
    if (!$newRequisition) {
        echo "❌ FAILED: New requisition not found in database!\n";
        $testResults['failed']++;
        exit(1);
    }
    
    if ($newRequisition->job_title !== '4') {
        echo "❌ FAILED: New requisition should have job_title '4', but got '{$newRequisition->job_title}'\n";
        $testResults['failed']++;
        exit(1);
    }
    
    if (in_array($newRequisition->id, array_keys($existingRequisitions))) {
        echo "❌ FAILED: New requisition has same ID as existing draft! This means it was updated, not created.\n";
        $testResults['failed']++;
        exit(1);
    }
    
    echo "✓ New requisition created correctly:\n";
    echo "  ID: {$newRequisition->id}\n";
    echo "  Job Title: '{$newRequisition->job_title}'\n";
    echo "  Status: {$newRequisition->status}\n\n";
    $testResults['passed']++;

    // STEP 4: CRITICAL - Verify existing requisitions were NOT updated
    echo "STEP 4: CRITICAL - Verifying existing requisitions were NOT updated...\n";
    
    $allUpdated = true;
    $anyUpdated = false;
    
    foreach ($existingRequisitions as $id => $original) {
        $current = Requisition::find($id);
        
        if (!$current) {
            echo "⚠️  WARNING: Existing requisition ID {$id} was deleted!\n";
            $testResults['warnings']++;
            continue;
        }
        
        if ($current->job_title !== $original['job_title']) {
            echo "❌ FAILED: Requisition ID {$id} was updated!\n";
            echo "  Original job_title: '{$original['job_title']}'\n";
            echo "  Current job_title: '{$current->job_title}'\n";
            $allUpdated = false;
            $anyUpdated = true;
            $testResults['failed']++;
        } else {
            echo "✓ Requisition ID {$id} unchanged: '{$current->job_title}'\n";
        }
    }
    
    if ($anyUpdated) {
        echo "\n❌ BUG CONFIRMED: Existing requisitions were updated when creating new one!\n";
        echo "   This is the bug that should be fixed.\n";
        exit(1);
    }
    
    echo "\n✓ All existing requisitions remained unchanged\n";
    $testResults['passed']++;

    // STEP 5: Verify no other requisitions were updated to "4"
    echo "STEP 5: Verifying no other requisitions were updated to '4'...\n";
    
    $allRequisitions = Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->get();
    
    $requisitionsWithTitle4 = $allRequisitions->filter(function($req) {
        return $req->job_title === '4';
    });
    
    if ($requisitionsWithTitle4->count() > 1) {
        echo "❌ FAILED: Found {$requisitionsWithTitle4->count()} requisitions with job_title '4':\n";
        foreach ($requisitionsWithTitle4 as $req) {
            echo "  - ID: {$req->id}, Created: {$req->created_at}, Updated: {$req->updated_at}\n";
        }
        $testResults['failed']++;
        exit(1);
    }
    
    if ($requisitionsWithTitle4->count() === 1 && $requisitionsWithTitle4->first()->id === $newRequisitionId) {
        echo "✓ Only the new requisition has job_title '4'\n";
        $testResults['passed']++;
    } else {
        echo "⚠️  WARNING: Unexpected requisitions with job_title '4'\n";
        $testResults['warnings']++;
    }

    // STEP 6: Final database state summary
    echo "\nSTEP 6: Final database state summary...\n";
    echo "Total requisitions: {$allRequisitions->count()}\n";
    foreach ($allRequisitions as $req) {
        $isNew = $req->id === $newRequisitionId ? ' [NEW]' : '';
        echo "  ID: {$req->id}, Job Title: '{$req->job_title}'{$isNew}\n";
    }

    // FINAL RESULT
    echo "\n";
    echo "========================================\n";
    echo "FINAL TEST RESULTS\n";
    echo "========================================\n";
    echo "✓ Passed: {$testResults['passed']}\n";
    echo "❌ Failed: {$testResults['failed']}\n";
    echo "⚠️  Warnings: {$testResults['warnings']}\n";
    echo "\n";
    
    if ($testResults['failed'] === 0) {
        echo "✅ TEST PASSED - BUG IS FIXED!\n";
        echo "   The existing requisitions were NOT updated when creating a new one.\n";
        echo "   The fix is working correctly.\n";
        exit(0);
    } else {
        echo "❌ TEST FAILED - BUG STILL EXISTS!\n";
        echo "   The existing requisitions were updated when creating a new one.\n";
        echo "   The fix needs to be reviewed.\n";
        exit(1);
    }

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

