<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GlobalDepartment;
use App\Models\GlobalLocation;

class GlobalDepartmentLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding Global Departments and Locations...');

        // Seed global departments
        $this->seedGlobalDepartments();
        
        // Seed global locations
        $this->seedGlobalLocations();

        $this->command->info('✅ Global Departments and Locations seeded successfully!');
    }

    /**
     * Seed global departments
     */
    private function seedGlobalDepartments(): void
    {
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG', 'description' => 'Software development, technical architecture, and engineering solutions'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Talent acquisition, employee relations, and organizational development'],
            ['name' => 'Marketing', 'code' => 'MKT', 'description' => 'Brand management, digital marketing, and customer acquisition'],
            ['name' => 'Sales', 'code' => 'SALES', 'description' => 'Business development, client relations, and revenue generation'],
            ['name' => 'Finance', 'code' => 'FIN', 'description' => 'Financial planning, accounting, and fiscal management'],
            ['name' => 'Operations', 'code' => 'OPS', 'description' => 'Process optimization, supply chain, and operational excellence'],
            ['name' => 'Customer Support', 'code' => 'CS', 'description' => 'Customer service, technical support, and client success'],
            ['name' => 'Product Management', 'code' => 'PM', 'description' => 'Product strategy, roadmap planning, and feature development'],
            ['name' => 'Quality Assurance', 'code' => 'QA', 'description' => 'Testing, quality control, and process validation'],
            ['name' => 'Business Development', 'code' => 'BD', 'description' => 'Strategic partnerships, market expansion, and growth initiatives'],
            ['name' => 'Legal', 'code' => 'LEGAL', 'description' => 'Legal compliance, contract management, and risk assessment'],
            ['name' => 'IT Support', 'code' => 'IT', 'description' => 'Technical infrastructure, system administration, and IT services'],
            ['name' => 'Research & Development', 'code' => 'R&D', 'description' => 'Innovation, research, and product development'],
            ['name' => 'Supply Chain', 'code' => 'SC', 'description' => 'Procurement, logistics, and supply chain optimization'],
            ['name' => 'Data Analytics', 'code' => 'DA', 'description' => 'Data science, business intelligence, and analytics'],
            ['name' => 'Design', 'code' => 'DESIGN', 'description' => 'User experience, visual design, and creative direction'],
            ['name' => 'Content', 'code' => 'CONTENT', 'description' => 'Content creation, copywriting, and editorial'],
            ['name' => 'Security', 'code' => 'SEC', 'description' => 'Information security, compliance, and risk management'],
            ['name' => 'DevOps', 'code' => 'DEVOPS', 'description' => 'Infrastructure automation, deployment, and monitoring'],
            ['name' => 'Strategy', 'code' => 'STRAT', 'description' => 'Strategic planning, corporate development, and M&A'],
        ];

        foreach ($departments as $dept) {
            GlobalDepartment::updateOrCreate(
                ['code' => $dept['code']],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("   ✅ Seeded " . count($departments) . " global departments");
    }

    /**
     * Seed global locations with comprehensive Indian and international cities
     */
    private function seedGlobalLocations(): void
    {
        $locations = [
            // Major Indian Cities
            ['name' => 'Mumbai', 'city' => 'Mumbai', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Delhi', 'city' => 'New Delhi', 'state' => 'Delhi', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Bangalore', 'city' => 'Bangalore', 'state' => 'Karnataka', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Hyderabad', 'city' => 'Hyderabad', 'state' => 'Telangana', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Chennai', 'city' => 'Chennai', 'state' => 'Tamil Nadu', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Kolkata', 'city' => 'Kolkata', 'state' => 'West Bengal', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Pune', 'city' => 'Pune', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Ahmedabad', 'city' => 'Ahmedabad', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Jaipur', 'city' => 'Jaipur', 'state' => 'Rajasthan', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Surat', 'city' => 'Surat', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            
            // IT Hubs
            ['name' => 'Gurgaon', 'city' => 'Gurgaon', 'state' => 'Haryana', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Noida', 'city' => 'Noida', 'state' => 'Uttar Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Kochi', 'city' => 'Kochi', 'state' => 'Kerala', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Coimbatore', 'city' => 'Coimbatore', 'state' => 'Tamil Nadu', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Chandigarh', 'city' => 'Chandigarh', 'state' => 'Chandigarh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Indore', 'city' => 'Indore', 'state' => 'Madhya Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Bhubaneswar', 'city' => 'Bhubaneswar', 'state' => 'Odisha', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Vadodara', 'city' => 'Vadodara', 'state' => 'Gujarat', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Nashik', 'city' => 'Nashik', 'state' => 'Maharashtra', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Vijayawada', 'city' => 'Vijayawada', 'state' => 'Andhra Pradesh', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            
            // Remote/Work from Home options
            ['name' => 'Remote - India', 'city' => 'Remote', 'state' => 'India', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            ['name' => 'Work from Home', 'city' => 'Remote', 'state' => 'Global', 'country' => 'Global', 'timezone' => 'UTC'],
            ['name' => 'Hybrid - India', 'city' => 'Hybrid', 'state' => 'India', 'country' => 'India', 'timezone' => 'Asia/Kolkata'],
            
            // International Locations
            ['name' => 'New York', 'city' => 'New York', 'state' => 'New York', 'country' => 'United States', 'timezone' => 'America/New_York'],
            ['name' => 'San Francisco', 'city' => 'San Francisco', 'state' => 'California', 'country' => 'United States', 'timezone' => 'America/Los_Angeles'],
            ['name' => 'London', 'city' => 'London', 'state' => 'England', 'country' => 'United Kingdom', 'timezone' => 'Europe/London'],
            ['name' => 'Singapore', 'city' => 'Singapore', 'state' => 'Singapore', 'country' => 'Singapore', 'timezone' => 'Asia/Singapore'],
            ['name' => 'Dubai', 'city' => 'Dubai', 'state' => 'Dubai', 'country' => 'UAE', 'timezone' => 'Asia/Dubai'],
            ['name' => 'Toronto', 'city' => 'Toronto', 'state' => 'Ontario', 'country' => 'Canada', 'timezone' => 'America/Toronto'],
            ['name' => 'Sydney', 'city' => 'Sydney', 'state' => 'NSW', 'country' => 'Australia', 'timezone' => 'Australia/Sydney'],
            ['name' => 'Berlin', 'city' => 'Berlin', 'state' => 'Berlin', 'country' => 'Germany', 'timezone' => 'Europe/Berlin'],
            ['name' => 'Amsterdam', 'city' => 'Amsterdam', 'state' => 'North Holland', 'country' => 'Netherlands', 'timezone' => 'Europe/Amsterdam'],
            ['name' => 'Tokyo', 'city' => 'Tokyo', 'state' => 'Tokyo', 'country' => 'Japan', 'timezone' => 'Asia/Tokyo'],
        ];

        foreach ($locations as $location) {
            GlobalLocation::updateOrCreate(
                ['name' => $location['name']],
                [
                    'city' => $location['city'],
                    'state' => $location['state'],
                    'country' => $location['country'],
                    'timezone' => $location['timezone'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info("   ✅ Seeded " . count($locations) . " global locations");
    }
}