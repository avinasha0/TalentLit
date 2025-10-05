<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Support\Str;

class DepartmentLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Departments and Locations with Indian data...');

        // Get all tenants
        $tenants = Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Creating sample data for all tenants.');
            return;
        }

        foreach ($tenants as $tenant) {
            $this->command->info("Seeding data for tenant: {$tenant->name} ({$tenant->slug})");
            
            // Seed departments
            $this->seedDepartments($tenant);
            
            // Seed locations
            $this->seedLocations($tenant);
        }

        $this->command->info('✅ Departments and Locations seeded successfully!');
    }

    /**
     * Seed departments for a tenant
     */
    private function seedDepartments(Tenant $tenant): void
    {
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG'],
            ['name' => 'Human Resources', 'code' => 'HR'],
            ['name' => 'Marketing', 'code' => 'MKT'],
            ['name' => 'Sales', 'code' => 'SALES'],
            ['name' => 'Finance', 'code' => 'FIN'],
            ['name' => 'Operations', 'code' => 'OPS'],
            ['name' => 'Customer Support', 'code' => 'CS'],
            ['name' => 'Product Management', 'code' => 'PM'],
            ['name' => 'Quality Assurance', 'code' => 'QA'],
            ['name' => 'Business Development', 'code' => 'BD'],
            ['name' => 'Legal', 'code' => 'LEGAL'],
            ['name' => 'IT Support', 'code' => 'IT'],
            ['name' => 'Research & Development', 'code' => 'R&D'],
            ['name' => 'Supply Chain', 'code' => 'SC'],
            ['name' => 'Data Analytics', 'code' => 'DA'],
        ];

        foreach ($departments as $dept) {
            Department::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'code' => $dept['code']
                ],
                [
                    'name' => $dept['name'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("   ✅ Seeded " . count($departments) . " departments");
    }

    /**
     * Seed locations for a tenant with Indian cities
     */
    private function seedLocations(Tenant $tenant): void
    {
        $locations = [
            // Major Indian Cities
            ['name' => 'Mumbai', 'city' => 'Mumbai', 'country' => 'India'],
            ['name' => 'Delhi', 'city' => 'New Delhi', 'country' => 'India'],
            ['name' => 'Bangalore', 'city' => 'Bangalore', 'country' => 'India'],
            ['name' => 'Hyderabad', 'city' => 'Hyderabad', 'country' => 'India'],
            ['name' => 'Chennai', 'city' => 'Chennai', 'country' => 'India'],
            ['name' => 'Kolkata', 'city' => 'Kolkata', 'country' => 'India'],
            ['name' => 'Pune', 'city' => 'Pune', 'country' => 'India'],
            ['name' => 'Ahmedabad', 'city' => 'Ahmedabad', 'country' => 'India'],
            ['name' => 'Jaipur', 'city' => 'Jaipur', 'country' => 'India'],
            ['name' => 'Surat', 'city' => 'Surat', 'country' => 'India'],
            
            // IT Hubs
            ['name' => 'Gurgaon', 'city' => 'Gurgaon', 'country' => 'India'],
            ['name' => 'Noida', 'city' => 'Noida', 'country' => 'India'],
            ['name' => 'Kochi', 'city' => 'Kochi', 'country' => 'India'],
            ['name' => 'Coimbatore', 'city' => 'Coimbatore', 'country' => 'India'],
            ['name' => 'Chandigarh', 'city' => 'Chandigarh', 'country' => 'India'],
            ['name' => 'Indore', 'city' => 'Indore', 'country' => 'India'],
            ['name' => 'Bhubaneswar', 'city' => 'Bhubaneswar', 'country' => 'India'],
            ['name' => 'Vadodara', 'city' => 'Vadodara', 'country' => 'India'],
            ['name' => 'Nashik', 'city' => 'Nashik', 'country' => 'India'],
            ['name' => 'Vijayawada', 'city' => 'Vijayawada', 'country' => 'India'],
            
            // Remote/Work from Home options
            ['name' => 'Remote - India', 'city' => 'Remote', 'country' => 'India'],
            ['name' => 'Work from Home', 'city' => 'Remote', 'country' => 'India'],
            ['name' => 'Hybrid - India', 'city' => 'Hybrid', 'country' => 'India'],
            
            // Regional Centers
            ['name' => 'Goa', 'city' => 'Panaji', 'country' => 'India'],
            ['name' => 'Kerala', 'city' => 'Thiruvananthapuram', 'country' => 'India'],
            ['name' => 'Tamil Nadu', 'city' => 'Coimbatore', 'country' => 'India'],
            ['name' => 'Karnataka', 'city' => 'Mysore', 'country' => 'India'],
            ['name' => 'Maharashtra', 'city' => 'Nagpur', 'country' => 'India'],
            ['name' => 'Gujarat', 'city' => 'Rajkot', 'country' => 'India'],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $location['name']
                ],
                [
                    'tenant_id' => $tenant->id,
                    'city' => $location['city'],
                    'country' => $location['country'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("   ✅ Seeded " . count($locations) . " locations");
    }
}
