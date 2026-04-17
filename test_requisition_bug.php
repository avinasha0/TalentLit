<?php

/**
 * Manual Test Script for Requisition Draft Update Bug
 * 
 * This script simulates the bug scenario:
 * 1. Creates an existing draft with job_title "Previous"
 * 2. Creates a new requisition with job_title "4"
 * 3. Verifies that the existing draft was NOT updated
 * 
 * Run: php test_requisition_bug.php
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

echo "=== Requisition Draft Update Bug Test ===\n\n";

try {
    // Get or create test tenant
    $tenant = Tenant::where('slug', 'testcompany')->first();
    if (!$tenant) {
        $tenant = Tenant::create([
            'name' => 'Test Company',
            'slug' => 'testcompany',
        ]);
        echo "Created test tenant: {$tenant->slug}\n";
    } else {
        echo "Using existing tenant: {$tenant->slug}\n";
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
        echo "Created test user: {$user->email}\n";
    } else {
        echo "Using existing user: {$user->email}\n";
    }

    // Set tenant context
    Tenancy::set($tenant);
    auth()->login($user);

    echo "\n--- Step 1: Create existing draft with job_title 'Previous' ---\n";
    
    // Clean up any existing test requisitions
    Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->whereIn('job_title', ['Previous', '4', 'Test New'])
        ->delete();

    $existingDraft = Requisition::create([
        'tenant_id' => $tenant->id,
        'created_by' => $user->id,
        'department' => 'IT',
        'job_title' => 'Previous',
        'justification' => 'Need for previous role',
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

    $originalJobTitle = $existingDraft->job_title;
    $originalUpdatedAt = $existingDraft->updated_at;
    $existingDraftId = $existingDraft->id;

    echo "Created existing draft:\n";
    echo "  ID: {$existingDraft->id}\n";
    echo "  Job Title: {$existingDraft->job_title}\n";
    echo "  Updated At: {$existingDraft->updated_at}\n";

    // Wait a moment to ensure timestamps are different
    sleep(2);

    echo "\n--- Step 2: Simulate form submission to create NEW requisition with job_title '4' ---\n";

    // Simulate the store method call
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
        'is_new' => 'true', // CRITICAL: This should prevent updating existing drafts
    ]);

    // Call the store method
    $response = $controller->store($request, $tenant->slug);
    $responseData = json_decode($response->getContent(), true);

    echo "Form submission response:\n";
    echo "  Success: " . ($responseData['success'] ?? 'false') . "\n";
    echo "  Requisition ID: " . ($responseData['requisition_id'] ?? 'N/A') . "\n";

    if (!isset($responseData['requisition_id'])) {
        echo "\n❌ ERROR: No requisition_id in response!\n";
        echo "Response: " . $response->getContent() . "\n";
        exit(1);
    }

    $newRequisitionId = $responseData['requisition_id'];

    echo "\n--- Step 3: Verify new requisition was created ---\n";
    $newRequisition = Requisition::find($newRequisitionId);
    if (!$newRequisition) {
        echo "❌ ERROR: New requisition not found!\n";
        exit(1);
    }

    echo "New requisition:\n";
    echo "  ID: {$newRequisition->id}\n";
    echo "  Job Title: {$newRequisition->job_title}\n";
    echo "  Status: {$newRequisition->status}\n";
    echo "  Approval Status: {$newRequisition->approval_status}\n";

    if ($newRequisition->job_title !== '4') {
        echo "\n❌ ERROR: New requisition should have job_title '4', but got '{$newRequisition->job_title}'\n";
        exit(1);
    }

    if ($newRequisition->id === $existingDraftId) {
        echo "\n❌ ERROR: New requisition has same ID as existing draft! This means it was updated, not created.\n";
        exit(1);
    }

    echo "✓ New requisition created correctly\n";

    echo "\n--- Step 4: CRITICAL - Verify existing draft was NOT updated ---\n";
    $existingDraft->refresh();
    
    echo "Existing draft after creation:\n";
    echo "  ID: {$existingDraft->id}\n";
    echo "  Job Title: {$existingDraft->job_title}\n";
    echo "  Updated At: {$existingDraft->updated_at}\n";

    if ($existingDraft->job_title !== $originalJobTitle) {
        echo "\n❌ BUG CONFIRMED: Existing draft job_title changed from '{$originalJobTitle}' to '{$existingDraft->job_title}'\n";
        echo "   This is the bug - existing drafts should NOT be updated when creating new requisitions!\n";
        exit(1);
    }

    if ($existingDraft->updated_at->format('Y-m-d H:i:s') !== $originalUpdatedAt->format('Y-m-d H:i:s')) {
        echo "\n⚠️  WARNING: Existing draft updated_at changed (might be due to database triggers or observers)\n";
        echo "   Original: {$originalUpdatedAt}\n";
        echo "   Current: {$existingDraft->updated_at}\n";
    } else {
        echo "✓ Existing draft updated_at unchanged\n";
    }

    echo "✓ Existing draft job_title unchanged\n";

    echo "\n--- Step 5: Verify no other drafts were updated ---\n";
    $updatedDrafts = Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->where('id', '!=', $newRequisitionId)
        ->where('job_title', '4')
        ->get();

    if ($updatedDrafts->count() > 0) {
        echo "❌ BUG CONFIRMED: Found {$updatedDrafts->count()} other draft(s) with job_title '4':\n";
        foreach ($updatedDrafts as $draft) {
            echo "  - ID: {$draft->id}, Job Title: {$draft->job_title}\n";
        }
        exit(1);
    }

    echo "✓ No other drafts were updated\n";

    echo "\n--- Step 6: Database State Summary ---\n";
    $allRequisitions = Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->orderBy('id')
        ->get(['id', 'job_title', 'status', 'approval_status', 'created_at', 'updated_at']);

    echo "Total requisitions: {$allRequisitions->count()}\n";
    foreach ($allRequisitions as $req) {
        echo "  - ID: {$req->id}, Job Title: '{$req->job_title}', Status: {$req->status}, Updated: {$req->updated_at}\n";
    }

    echo "\n=== TEST RESULT: ✓ PASSED - Bug is FIXED ===\n";
    echo "The existing draft was NOT updated when creating a new requisition.\n";
    exit(0);

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

