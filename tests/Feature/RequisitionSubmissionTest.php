<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Requisition;
use Illuminate\Support\Facades\Auth;

class RequisitionSubmissionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a requisition can be created and submitted for approval
     */
    public function test_requisition_can_be_submitted_for_approval()
    {
        // Create tenant and user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $tenant->users()->attach($user->id);
        
        // Assign Owner role to user
        \DB::table('custom_user_roles')->insert([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_name' => 'Owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Login as user
        $this->actingAs($user);

        // Create a requisition
        $requisition = Requisition::create([
            'tenant_id' => $tenant->id,
            'department' => 'Engineering',
            'job_title' => 'Senior Developer',
            'justification' => 'Need to expand team',
            'budget_min' => 50000,
            'budget_max' => 80000,
            'contract_type' => 'Full-time',
            'skills' => 'PHP, Laravel',
            'experience_min' => 5,
            'experience_max' => 10,
            'headcount' => 1,
            'priority' => 'High',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
            'created_by' => $user->id,
        ]);

        // Verify requisition is in Draft status
        $this->assertEquals('Draft', $requisition->approval_status);

        // Submit for approval via API
        $response = $this->postJson("/api/requisitions/{$requisition->id}/submit", []);

        // Check response
        if ($response->status() !== 200) {
            $this->fail('Submit failed: ' . $response->getContent());
        }

        $response->assertJson([
            'success' => true,
        ]);

        // Verify requisition status changed to Pending
        $requisition->refresh();
        $this->assertEquals('Pending', $requisition->approval_status);
        $this->assertNotNull($requisition->current_approver_id);
        $this->assertEquals(1, $requisition->approval_level);
    }

    /**
     * Test that draft deletion doesn't delete submitted requisitions
     */
    public function test_draft_deletion_does_not_delete_submitted_requisitions()
    {
        // Create tenant and user
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create();
        $tenant->users()->attach($user->id);
        
        // Assign Owner role to user
        \DB::table('custom_user_roles')->insert([
            'user_id' => $user->id,
            'tenant_id' => $tenant->id,
            'role_name' => 'Owner',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user);

        // Create a submitted requisition
        $submittedRequisition = Requisition::create([
            'tenant_id' => $tenant->id,
            'department' => 'Engineering',
            'job_title' => 'Senior Developer',
            'justification' => 'Need to expand team',
            'budget_min' => 50000,
            'budget_max' => 80000,
            'contract_type' => 'Full-time',
            'skills' => 'PHP, Laravel',
            'experience_min' => 5,
            'experience_max' => 10,
            'headcount' => 1,
            'priority' => 'High',
            'status' => 'Pending',
            'approval_status' => 'Pending',
            'approval_level' => 1,
            'created_by' => $user->id,
        ]);

        // Create a draft requisition
        $draftRequisition = Requisition::create([
            'tenant_id' => $tenant->id,
            'department' => 'Marketing',
            'job_title' => 'Marketing Manager',
            'justification' => 'Need marketing lead',
            'budget_min' => 40000,
            'budget_max' => 60000,
            'contract_type' => 'Full-time',
            'skills' => 'Marketing, SEO',
            'experience_min' => 3,
            'experience_max' => 7,
            'headcount' => 1,
            'priority' => 'Medium',
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'approval_level' => 0,
            'created_by' => $user->id,
        ]);

        // Delete all drafts
        $response = $this->deleteJson("/{$tenant->slug}/api/requisitions/draft");

        $response->assertJson(['success' => true]);

        // Verify submitted requisition still exists
        $this->assertDatabaseHas('requisitions', [
            'id' => $submittedRequisition->id,
            'approval_status' => 'Pending',
        ]);

        // Verify draft requisition was deleted
        $this->assertDatabaseMissing('requisitions', [
            'id' => $draftRequisition->id,
        ]);
    }
}

