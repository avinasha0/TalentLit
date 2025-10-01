<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssignFreePlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the free plan
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();
        
        if (!$freePlan) {
            $this->command->error('Free plan not found. Please run SubscriptionPlanSeeder first.');
            return;
        }

        // Get all tenants without active subscriptions
        $tenantsWithoutSubscriptions = Tenant::whereDoesntHave('subscription')->get();

        $this->command->info("Found {$tenantsWithoutSubscriptions->count()} tenants without subscriptions.");

        foreach ($tenantsWithoutSubscriptions as $tenant) {
            TenantSubscription::create([
                'tenant_id' => $tenant->id,
                'subscription_plan_id' => $freePlan->id,
                'status' => 'active',
                'starts_at' => now(),
                'expires_at' => null, // Free plans don't expire
                'payment_method' => 'free',
            ]);

            $this->command->info("Assigned free plan to tenant: {$tenant->name}");
        }

        $this->command->info('Successfully assigned free plans to all tenants without subscriptions.');
    }
}
