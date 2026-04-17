<?php

namespace Tests\Feature;

use App\Models\Requisition;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class RequisitionDraftUpdateBugTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant
        $this->tenant = Tenant::factory()->create([
            'name' => 'Test Company',
            'slug' => 'testcompany',
        ]);

        // Create user
        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);
        $this->user->tenants()->attach($this->tenant->id);

        // Set tenant context
        Tenancy::set($this->tenant);
    }

    /**
     * Test that creating a new requisition does NOT update existing drafts
     * This is the bug fix test - when creating requisition with job_title "4",
     * previous requisitions should NOT be updated to "4"
     */
    public function test_creating_new_requisition_does_not_update_existing_drafts(): void
    {
        // Step 1: Create an existing draft requisition with job_title "Previous"
        $existingDraft = Requisition::create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
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

        // Wait a moment to ensure timestamps are different
        sleep(1);

        // Step 2: Simulate form submission to create a NEW requisition with job_title "4"
        $this->actingAs($this->user);

        $response = $this->postJson("/{$this->tenant->slug}/requisitions", [
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

        // Step 3: Verify response
        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success'] ?? false);

        // Step 4: Verify new requisition was created with job_title "4"
        $newRequisition = Requisition::where('id', $responseData['requisition_id'] ?? null)
            ->where('tenant_id', $this->tenant->id)
            ->where('created_by', $this->user->id)
            ->first();

        $this->assertNotNull($newRequisition, 'New requisition should be created');
        $this->assertEquals('4', $newRequisition->job_title, 'New requisition should have job_title "4"');
        $this->assertNotEquals($existingDraft->id, $newRequisition->id, 'New requisition should have different ID');

        // Step 5: CRITICAL - Verify existing draft was NOT updated
        $existingDraft->refresh();
        $this->assertEquals(
            $originalJobTitle,
            $existingDraft->job_title,
            'Existing draft job_title should remain "Previous", not changed to "4"'
        );
        $this->assertEquals(
            $originalUpdatedAt->format('Y-m-d H:i:s'),
            $existingDraft->updated_at->format('Y-m-d H:i:s'),
            'Existing draft updated_at should NOT change'
        );

        // Step 6: Verify total requisitions count
        $totalRequisitions = Requisition::where('tenant_id', $this->tenant->id)
            ->where('created_by', $this->user->id)
            ->count();
        $this->assertEquals(2, $totalRequisitions, 'Should have exactly 2 requisitions');

        // Step 7: Verify no other drafts were updated
        $updatedDrafts = Requisition::where('tenant_id', $this->tenant->id)
            ->where('created_by', $this->user->id)
            ->where('id', '!=', $newRequisition->id)
            ->where('job_title', '4')
            ->count();
        $this->assertEquals(0, $updatedDrafts, 'No other drafts should have job_title "4"');
    }

    /**
     * Test that creating multiple requisitions in sequence doesn't cause cross-updates
     */
    public function test_multiple_requisitions_do_not_update_each_other(): void
    {
        $this->actingAs($this->user);

        // Create first requisition
        $req1 = Requisition::create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'department' => 'IT',
            'job_title' => 'First',
            'justification' => 'First role',
            'budget_min' => 50000,
            'budget_max' => 70000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['PHP']),
            'experience_min' => 2,
            'headcount' => 1,
            'priority' => 'Medium',
            'location' => 'Remote',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
        ]);

        // Create second requisition
        $req2 = Requisition::create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'department' => 'HR',
            'job_title' => 'Second',
            'justification' => 'Second role',
            'budget_min' => 40000,
            'budget_max' => 60000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['HR']),
            'experience_min' => 1,
            'headcount' => 1,
            'priority' => 'Low',
            'location' => 'Office',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
        ]);

        // Store original values
        $req1OriginalTitle = $req1->job_title;
        $req2OriginalTitle = $req2->job_title;

        // Create third requisition via form submission
        $response = $this->postJson("/{$this->tenant->slug}/requisitions", [
            'department' => 'Sales',
            'job_title' => 'Third',
            'justification' => 'Third role',
            'budget_min' => 45000,
            'budget_max' => 65000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['Sales']),
            'experience_min' => 2,
            'headcount' => 1,
            'priority' => 'High',
            'location' => 'Hybrid',
            'submit_action' => 'draft',
            'is_new' => 'true',
        ]);

        $response->assertStatus(200);

        // Refresh all requisitions
        $req1->refresh();
        $req2->refresh();

        // Verify none were updated
        $this->assertEquals($req1OriginalTitle, $req1->job_title, 'First requisition should not be updated');
        $this->assertEquals($req2OriginalTitle, $req2->job_title, 'Second requisition should not be updated');

        // Verify third was created
        $req3 = Requisition::where('tenant_id', $this->tenant->id)
            ->where('created_by', $this->user->id)
            ->where('job_title', 'Third')
            ->first();
        $this->assertNotNull($req3, 'Third requisition should be created');
    }

    /**
     * Test that saveDraft endpoint doesn't update when is_new=true
     */
    public function test_save_draft_with_is_new_flag_creates_new_not_updates(): void
    {
        // Create existing draft
        $existingDraft = Requisition::create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'department' => 'IT',
            'job_title' => 'Existing',
            'justification' => 'Existing role',
            'budget_min' => 50000,
            'budget_max' => 70000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['PHP']),
            'experience_min' => 2,
            'headcount' => 1,
            'priority' => 'Medium',
            'location' => 'Remote',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
        ]);

        $originalTitle = $existingDraft->job_title;

        $this->actingAs($this->user);

        // Call saveDraft with is_new=true
        $response = $this->postJson("/{$this->tenant->slug}/api/requisitions/draft", [
            'department' => 'Operations',
            'job_title' => 'New Draft',
            'justification' => 'New role',
            'budget_min' => 60000,
            'budget_max' => 80000,
            'contract_type' => 'Full-time',
            'skills' => json_encode(['JavaScript']),
            'experience_min' => 3,
            'headcount' => 1,
            'priority' => 'Medium',
            'location' => 'Office',
            'is_new' => 'true', // Should create new, not update existing
        ]);

        $response->assertStatus(200);
        $responseData = $response->json();
        $this->assertTrue($responseData['success'] ?? false);

        // Verify existing draft was NOT updated
        $existingDraft->refresh();
        $this->assertEquals($originalTitle, $existingDraft->job_title, 'Existing draft should not be updated');

        // Verify new draft was created
        $newDraft = Requisition::where('id', $responseData['draft_id'] ?? null)
            ->where('tenant_id', $this->tenant->id)
            ->where('created_by', $this->user->id)
            ->first();
        $this->assertNotNull($newDraft, 'New draft should be created');
        $this->assertEquals('New Draft', $newDraft->job_title, 'New draft should have correct job_title');
        $this->assertNotEquals($existingDraft->id, $newDraft->id, 'New draft should have different ID');
    }
}

