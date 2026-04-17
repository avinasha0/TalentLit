<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class TaskSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $tenant;
    protected $user;
    protected $approver;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create tenant
        $this->tenant = Tenant::factory()->create();
        
        // Create users
        $this->user = User::factory()->create();
        $this->approver = User::factory()->create();
        
        // Attach users to tenant
        $this->tenant->users()->attach($this->user->id);
        $this->tenant->users()->attach($this->approver->id);
    }

    /**
     * Test: Task is created when requisition is submitted for approval
     */
    public function test_task_created_on_requisition_submission(): void
    {
        // Create requisition
        $requisition = Requisition::factory()->create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'status' => 'Draft',
            'approval_status' => 'Draft',
            'current_approver_id' => null,
        ]);

        // Act as requester
        $this->actingAs($this->user);

        // Submit requisition for approval
        $response = $this->postJson("/{$this->tenant->slug}/api/requisitions/{$requisition->id}/submit");

        // Assert task was created
        $this->assertDatabaseHas('tasks', [
            'requisition_id' => $requisition->id,
            'user_id' => $this->approver->id, // Assuming workflow assigns to approver
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Approver can see tasks in My Tasks
     */
    public function test_approver_can_see_tasks(): void
    {
        // Create task
        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as approver
        $this->actingAs($this->approver);

        // Fetch tasks
        $response = $this->getJson("/{$this->tenant->slug}/api/tasks/my");

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonPath('data.0.id', $task->id);
    }

    /**
     * Test: Task is marked completed when approver approves
     */
    public function test_task_completed_on_approval(): void
    {
        // Create requisition and task
        $requisition = Requisition::factory()->create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'status' => 'Pending',
            'approval_status' => 'Pending',
            'current_approver_id' => $this->approver->id,
        ]);

        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'requisition_id' => $requisition->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as approver
        $this->actingAs($this->approver);

        // Approve requisition
        $response = $this->postJson("/{$this->tenant->slug}/api/requisitions/{$requisition->id}/approve", [
            'comments' => 'Approved',
        ]);

        // Assert task is completed
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'Completed',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Edit task created when changes are requested
     */
    public function test_edit_task_created_on_request_changes(): void
    {
        // Create requisition and task
        $requisition = Requisition::factory()->create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'status' => 'Pending',
            'approval_status' => 'Pending',
            'current_approver_id' => $this->approver->id,
        ]);

        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'requisition_id' => $requisition->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as approver
        $this->actingAs($this->approver);

        // Request changes
        $response = $this->postJson("/{$this->tenant->slug}/api/requisitions/{$requisition->id}/request-changes", [
            'comments' => 'Please update the budget range',
        ]);

        // Assert edit task was created for requester
        $this->assertDatabaseHas('tasks', [
            'requisition_id' => $requisition->id,
            'user_id' => $this->user->id,
            'task_type' => 'Requisition Edit Needed',
            'status' => 'Pending',
        ]);

        // Assert original task is completed
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'Completed',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Task reassigned on delegate action
     */
    public function test_task_reassigned_on_delegate(): void
    {
        $delegate = User::factory()->create();
        $this->tenant->users()->attach($delegate->id);

        // Create requisition and task
        $requisition = Requisition::factory()->create([
            'tenant_id' => $this->tenant->id,
            'created_by' => $this->user->id,
            'status' => 'Pending',
            'approval_status' => 'Pending',
            'current_approver_id' => $this->approver->id,
        ]);

        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'requisition_id' => $requisition->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as approver
        $this->actingAs($this->approver);

        // Delegate
        $response = $this->postJson("/{$this->tenant->slug}/api/requisitions/{$requisition->id}/delegate", [
            'delegate_to_user_id' => $delegate->id,
            'comments' => 'Delegated for approval',
        ]);

        // Assert original task is completed
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'Completed',
        ]);

        // Assert new task created for delegate
        $this->assertDatabaseHas('tasks', [
            'requisition_id' => $requisition->id,
            'user_id' => $delegate->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Unauthorized user cannot view task
     */
    public function test_unauthorized_user_cannot_view_task(): void
    {
        $unauthorizedUser = User::factory()->create();

        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as unauthorized user
        $this->actingAs($unauthorizedUser);

        // Try to view task
        $response = $this->getJson("/{$this->tenant->slug}/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    /**
     * Test: Task can be completed via API
     */
    public function test_task_can_be_completed_via_api(): void
    {
        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as assignee
        $this->actingAs($this->approver);

        // Complete task
        $response = $this->postJson("/{$this->tenant->slug}/api/tasks/{$task->id}/complete");

        // Assert task is completed
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'Completed',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Task can be reassigned via API
     */
    public function test_task_can_be_reassigned_via_api(): void
    {
        $newAssignee = User::factory()->create();
        $this->tenant->users()->attach($newAssignee->id);

        $task = Task::factory()->create([
            'user_id' => $this->approver->id,
            'task_type' => 'Requisition Approval',
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        // Act as current assignee
        $this->actingAs($this->approver);

        // Reassign task
        $response = $this->postJson("/{$this->tenant->slug}/api/tasks/{$task->id}/reassign", [
            'user_id' => $newAssignee->id,
        ]);

        // Assert task is reassigned
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'user_id' => $newAssignee->id,
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    /**
     * Test: Task filters work correctly
     */
    public function test_task_filters_work(): void
    {
        // Create tasks with different statuses
        Task::factory()->create([
            'user_id' => $this->approver->id,
            'status' => 'Pending',
            'created_by' => $this->user->id,
        ]);

        Task::factory()->create([
            'user_id' => $this->approver->id,
            'status' => 'Completed',
            'created_by' => $this->user->id,
        ]);

        // Act as approver
        $this->actingAs($this->approver);

        // Filter by status
        $response = $this->getJson("/{$this->tenant->slug}/api/tasks/my?status=Pending");

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonCount(1, 'data');
    }
}

