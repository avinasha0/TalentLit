<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Requisition;
use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\User;
use App\Support\Tenancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DraftJobFromApprovedRequisitionTest extends TestCase
{
    use RefreshDatabase;

    private function seedTenantWithPlanAndDepartment(Tenant $tenant): Department
    {
        $plan = SubscriptionPlan::create([
            'name' => 'Test Plan',
            'slug' => 'test-plan-'.uniqid(),
            'description' => 'Test',
            'price' => 0,
            'currency' => 'USD',
            'billing_cycle' => 'monthly',
            'is_active' => true,
            'max_users' => 10,
            'max_job_openings' => -1,
            'max_candidates' => 100,
            'max_applications_per_month' => 50,
            'max_interviews_per_month' => 20,
            'max_storage_gb' => 1,
        ]);

        TenantSubscription::create([
            'tenant_id' => $tenant->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => null,
            'payment_method' => 'free',
        ]);

        return Department::withoutGlobalScope('tenant')->create([
            'tenant_id' => $tenant->id,
            'name' => 'Engineering',
            'code' => 'ENG',
            'is_active' => true,
        ]);
    }

    private function baseRequisitionAttributes(Tenant $tenant, User $user, Department $department): array
    {
        return [
            'tenant_id' => $tenant->id,
            'department' => $department->name,
            'job_title' => 'Staff Engineer',
            'justification' => 'Team growth',
            'budget_min' => 90000,
            'budget_max' => 120000,
            'contract_type' => 'Full-time',
            'duration' => null,
            'skills' => '["PHP","Laravel"]',
            'experience_min' => 4,
            'experience_max' => 8,
            'headcount' => 2,
            'priority' => 'High',
            'location' => null,
            'additional_notes' => 'Notes here',
            'created_by' => $user->id,
        ];
    }

    public function test_draft_job_created_when_requisition_fully_approved(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'approve-job-tenant']);
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        $department = $this->seedTenantWithPlanAndDepartment($tenant);
        Tenancy::set($tenant);

        $attrs = $this->baseRequisitionAttributes($tenant, $user, $department);
        $attrs['status'] = 'Approved';
        $attrs['approval_status'] = 'Approved';
        $attrs['approval_level'] = 0;
        $attrs['current_approver_id'] = null;
        $attrs['approved_at'] = now();

        $requisition = Requisition::create($attrs);

        $job = JobOpening::withoutGlobalScope('tenant')
            ->where('staffing_requisition_id', $requisition->id)
            ->first();

        $this->assertNotNull($job);
        $this->assertSame('draft', $job->status);
        $this->assertNull($job->published_at);
        $this->assertSame($requisition->job_title, $job->title);
        $this->assertSame(2, $job->openings_count);
        $this->assertSame('full_time', $job->employment_type);
        $this->assertSame($tenant->id, $job->tenant_id);
        $this->assertSame($department->id, $job->department_id);
        $this->assertStringContainsString('Team growth', $job->description);
        $this->assertStringContainsString('PHP', $job->description);
    }

    public function test_draft_job_created_when_department_name_differs_only_by_case(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'approve-job-tenant-case']);
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        $department = $this->seedTenantWithPlanAndDepartment($tenant);
        Tenancy::set($tenant);

        $attrs = $this->baseRequisitionAttributes($tenant, $user, $department);
        $attrs['department'] = strtolower($department->name);
        $attrs['status'] = 'Approved';
        $attrs['approval_status'] = 'Approved';
        $attrs['approval_level'] = 0;
        $attrs['approved_at'] = now();

        $requisition = Requisition::create($attrs);

        $job = JobOpening::withoutGlobalScope('tenant')
            ->where('staffing_requisition_id', $requisition->id)
            ->first();

        $this->assertNotNull($job);
        $this->assertSame($department->id, $job->department_id);
    }

    public function test_second_save_does_not_duplicate_job(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'approve-job-tenant-2']);
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        $department = $this->seedTenantWithPlanAndDepartment($tenant);
        Tenancy::set($tenant);

        $attrs = $this->baseRequisitionAttributes($tenant, $user, $department);
        $attrs['status'] = 'Approved';
        $attrs['approval_status'] = 'Approved';
        $attrs['approval_level'] = 0;

        $requisition = Requisition::create($attrs);

        $requisition->update(['job_title' => 'Staff Engineer II']);

        $count = JobOpening::withoutGlobalScope('tenant')
            ->where('staffing_requisition_id', $requisition->id)
            ->count();

        $this->assertSame(1, $count);
    }

    /**
     * Legacy web approve (RequisitionController::approve) sets the same fields as below;
     * observer runs on save — HTTP stack is covered indirectly by other tenant tests.
     */
    public function test_legacy_approve_field_updates_trigger_draft_job(): void
    {
        $tenant = Tenant::factory()->create(['slug' => 'legacy-approve-tenant']);
        $user = User::factory()->create();
        $user->tenants()->attach($tenant->id);
        $department = $this->seedTenantWithPlanAndDepartment($tenant);
        Tenancy::set($tenant);

        $attrs = $this->baseRequisitionAttributes($tenant, $user, $department);
        $attrs['status'] = 'Pending';
        $attrs['approval_status'] = 'Pending';
        $attrs['approval_level'] = 1;
        $attrs['current_approver_id'] = $user->id;

        $requisition = Requisition::create($attrs);

        $requisition->status = 'Approved';
        $requisition->approval_status = 'Approved';
        $requisition->current_approver_id = null;
        $requisition->approved_at = now();
        $requisition->save();

        $requisition->refresh();
        $this->assertSame('Approved', $requisition->approval_status);
        $this->assertSame('Approved', $requisition->status);
        $this->assertNotNull($requisition->approved_at);

        $this->assertDatabaseHas('job_openings', [
            'staffing_requisition_id' => $requisition->id,
            'status' => 'draft',
        ]);
    }
}
