<?php

/**
 * Database Check Script for Requisition Draft Update Bug
 * 
 * This script checks the database directly to see if:
 * 1. New requisition was created with job_title "4"
 * 2. Existing draft with job_title "Previous" was NOT updated
 * 
 * Run: php check_database_bug.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Requisition;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== Database Check for Requisition Draft Update Bug ===\n\n";

try {
    $tenant = Tenant::where('slug', 'testcompany')->first();
    if (!$tenant) {
        echo "❌ Test tenant not found. Run test_requisition_bug.php first.\n";
        exit(1);
    }

    $user = User::where('email', 'test@example.com')->first();
    if (!$user) {
        echo "❌ Test user not found. Run test_requisition_bug.php first.\n";
        exit(1);
    }

    echo "Checking requisitions for tenant: {$tenant->slug}, user: {$user->email}\n\n";

    // Get all requisitions
    $requisitions = Requisition::where('tenant_id', $tenant->id)
        ->where('created_by', $user->id)
        ->orderBy('id')
        ->get();

    echo "Total requisitions found: {$requisitions->count()}\n\n";

    if ($requisitions->count() === 0) {
        echo "No requisitions found. Run test_requisition_bug.php first.\n";
        exit(0);
    }

    // Check for the existing draft
    $existingDraft = $requisitions->where('job_title', 'Previous')->first();
    $newRequisition = $requisitions->where('job_title', '4')->first();

    echo "--- Requisition Details ---\n";
    foreach ($requisitions as $req) {
        echo "ID: {$req->id}\n";
        echo "  Job Title: '{$req->job_title}'\n";
        echo "  Status: {$req->status}\n";
        echo "  Approval Status: {$req->approval_status}\n";
        echo "  Created: {$req->created_at}\n";
        echo "  Updated: {$req->updated_at}\n";
        echo "\n";
    }

    echo "--- Bug Check Results ---\n";

    if ($existingDraft) {
        echo "✓ Found existing draft with job_title 'Previous' (ID: {$existingDraft->id})\n";
        
        // Check if it was updated recently (within last 5 minutes)
        $recentUpdate = $existingDraft->updated_at->gt(now()->subMinutes(5));
        if ($recentUpdate) {
            echo "⚠️  WARNING: Existing draft was updated recently ({$existingDraft->updated_at})\n";
            echo "   This might indicate the bug - check if job_title changed\n";
        } else {
            echo "✓ Existing draft was not updated recently\n";
        }
    } else {
        echo "⚠️  Existing draft with job_title 'Previous' not found\n";
    }

    if ($newRequisition) {
        echo "✓ Found new requisition with job_title '4' (ID: {$newRequisition->id})\n";
        
        if ($existingDraft && $newRequisition->id === $existingDraft->id) {
            echo "❌ BUG CONFIRMED: New requisition has same ID as existing draft!\n";
            echo "   This means the existing draft was updated instead of creating a new one.\n";
            exit(1);
        } else {
            echo "✓ New requisition has different ID - correctly created as new record\n";
        }
    } else {
        echo "⚠️  New requisition with job_title '4' not found\n";
    }

    // Check for any requisitions with job_title "4" that shouldn't exist
    $allWithTitle4 = $requisitions->where('job_title', '4');
    if ($allWithTitle4->count() > 1) {
        echo "\n❌ BUG CONFIRMED: Found {$allWithTitle4->count()} requisitions with job_title '4':\n";
        foreach ($allWithTitle4 as $req) {
            echo "  - ID: {$req->id}, Created: {$req->created_at}, Updated: {$req->updated_at}\n";
        }
        echo "   This suggests multiple drafts were updated to '4'.\n";
        exit(1);
    }

    // Check if any other requisitions were updated to "4"
    $updatedTo4 = $requisitions->filter(function($req) use ($newRequisition) {
        return $req->job_title === '4' 
            && $req->id !== ($newRequisition->id ?? null)
            && $req->created_at->lt(now()->subMinutes(2)); // Created more than 2 minutes ago
    });

    if ($updatedTo4->count() > 0) {
        echo "\n❌ BUG CONFIRMED: Found {$updatedTo4->count()} old requisition(s) that were updated to '4':\n";
        foreach ($updatedTo4 as $req) {
            echo "  - ID: {$req->id}, Created: {$req->created_at}, Updated: {$req->updated_at}\n";
        }
        exit(1);
    }

    echo "\n=== RESULT: ✓ No bug detected ===\n";
    echo "The existing draft was NOT updated when creating a new requisition.\n";
    exit(0);

} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

