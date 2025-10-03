<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Existing seeders
            TenantSeeder::class,
            UserSeeder::class,
            SubscriptionPlanSeeder::class,
            TenantSubscriptionSeeder::class,
            TenantRoleSeeder::class,
            TenantBrandingSeeder::class,
            DepartmentSeeder::class,
            LocationSeeder::class,
            JobStageSeeder::class,
            JobRequisitionSeeder::class,
            JobOpeningSeeder::class,
            CandidateSeeder::class,
            ApplicationSeeder::class,
            InterviewSeeder::class,
            EmailTemplateSeeder::class,
        ]);
    }
}