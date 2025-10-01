<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Perfect for small teams getting started with recruitment',
                'price' => 0.00,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => 2,
                'max_job_openings' => 3,
                'max_candidates' => 50,
                'max_applications_per_month' => 25,
                'max_interviews_per_month' => 10,
                'max_storage_gb' => 1,
                'analytics_enabled' => false,
                'custom_branding' => false,
                'api_access' => false,
                'priority_support' => false,
                'advanced_reporting' => false,
                'integrations' => false,
                'white_label' => false,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for growing recruitment teams',
                'price' => 29.00,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => true,
                'max_users' => 10,
                'max_job_openings' => 25,
                'max_candidates' => 500,
                'max_applications_per_month' => 200,
                'max_interviews_per_month' => 100,
                'max_storage_gb' => 10,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => false,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Unlimited everything for large organizations',
                'price' => 99.00,
                'currency' => 'USD',
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'is_popular' => false,
                'max_users' => -1, // Unlimited
                'max_job_openings' => -1, // Unlimited
                'max_candidates' => -1, // Unlimited
                'max_applications_per_month' => -1, // Unlimited
                'max_interviews_per_month' => -1, // Unlimited
                'max_storage_gb' => 100,
                'analytics_enabled' => true,
                'custom_branding' => true,
                'api_access' => true,
                'priority_support' => true,
                'advanced_reporting' => true,
                'integrations' => true,
                'white_label' => true,
            ],
        ];

        foreach ($plans as $planData) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $planData['slug']],
                $planData
            );
        }
    }
}
