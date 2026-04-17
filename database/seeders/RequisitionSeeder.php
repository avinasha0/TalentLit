<?php

namespace Database\Seeders;

use App\Models\Requisition;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class RequisitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Requisitions for all tenants...');

        // Get all tenants
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Please create tenants first.');
            return;
        }

        foreach ($tenants as $tenant) {
            $this->command->info("Seeding requisitions for tenant: {$tenant->name} ({$tenant->slug})");
            
            // Get a user for this tenant to use as created_by
            $user = User::whereHas('tenants', function ($query) use ($tenant) {
                $query->where('tenants.id', $tenant->id);
            })->first();

            if (!$user) {
                $this->command->warn("   ⚠️  No user found for tenant {$tenant->name}, skipping...");
                continue;
            }

            // Create 2 sample requisitions
            $this->createRequisitions($tenant, $user);
        }

        $this->command->info('✅ Requisitions seeded successfully!');
    }

    /**
     * Create sample requisitions for a tenant
     */
    private function createRequisitions(Tenant $tenant, User $user): void
    {
        $requisitions = [
            [
                'tenant_id' => $tenant->id,
                'department' => 'Engineering',
                'job_title' => 'Senior Full Stack Developer',
                'justification' => 'We need an experienced full-stack developer to lead our new product development initiative. The candidate should have strong expertise in Laravel, React, and modern web technologies. This role is critical for our Q1 2025 product launch.',
                'budget_min' => 800000,
                'budget_max' => 1200000,
                'contract_type' => 'full-time',
                'skills' => 'Laravel, PHP, React, JavaScript, MySQL, RESTful APIs, Git, Docker, AWS',
                'experience_min' => 5,
                'experience_max' => 8,
                'headcount' => 1,
                'status' => 'Pending',
                'created_by' => $user->id,
            ],
            [
                'tenant_id' => $tenant->id,
                'department' => 'Sales',
                'job_title' => 'Business Development Manager',
                'justification' => 'Expanding our sales team to target new markets in the enterprise segment. The ideal candidate will have proven experience in B2B sales, client relationship management, and a track record of exceeding sales targets. This position will help us achieve our revenue goals for the upcoming fiscal year.',
                'budget_min' => 600000,
                'budget_max' => 900000,
                'contract_type' => 'full-time',
                'skills' => 'B2B Sales, CRM, Lead Generation, Negotiation, Client Relationship Management, Market Research, Presentation Skills',
                'experience_min' => 3,
                'experience_max' => 6,
                'headcount' => 1,
                'status' => 'Draft',
                'created_by' => $user->id,
            ],
        ];

        foreach ($requisitions as $requisitionData) {
            Requisition::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'job_title' => $requisitionData['job_title'],
                    'department' => $requisitionData['department'],
                ],
                $requisitionData
            );
        }

        $this->command->info("   ✅ Created 2 requisitions for {$tenant->name}");
    }
}
