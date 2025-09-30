<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\JobOpening;
use App\Models\Location;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class JobOpeningSeeder extends Seeder
{
    public function run(): void
    {
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->warn('No tenant found. Please run TenantSeeder first.');
            return;
        }

        // Get or create departments
        $engineering = Department::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Engineering',
        ]);

        $marketing = Department::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Marketing',
        ]);

        $sales = Department::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Sales',
        ]);

        // Get or create locations
        $remote = Location::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'Remote',
        ]);

        $sf = Location::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'San Francisco, CA',
        ]);

        $ny = Location::firstOrCreate([
            'tenant_id' => $tenant->id,
            'name' => 'New York, NY',
        ]);

        // Create job openings
        $jobs = [
            [
                'title' => 'Senior Software Engineer',
                'slug' => 'senior-software-engineer',
                'department_id' => $engineering->id,
                'location_id' => $remote->id,
                'employment_type' => 'full_time',
                'status' => 'published',
                'openings_count' => 2,
                'description' => 'We are looking for a Senior Software Engineer to join our growing team. You will be responsible for designing, developing, and maintaining our core platform.

## Responsibilities:
- Design and implement scalable software solutions
- Collaborate with cross-functional teams
- Write clean, maintainable code
- Mentor junior developers
- Participate in code reviews

## Requirements:
- 5+ years of software development experience
- Strong knowledge of PHP/Laravel or similar frameworks
- Experience with modern frontend technologies
- Excellent problem-solving skills
- Strong communication skills

## Benefits:
- Competitive salary
- Health insurance
- 401k matching
- Flexible work arrangements
- Professional development opportunities',
                'published_at' => now(),
            ],
            [
                'title' => 'Product Marketing Manager',
                'slug' => 'product-marketing-manager',
                'department_id' => $marketing->id,
                'location_id' => $sf->id,
                'employment_type' => 'full_time',
                'status' => 'published',
                'openings_count' => 1,
                'description' => 'Join our marketing team as a Product Marketing Manager and help us tell our story to the world.

## Responsibilities:
- Develop and execute product marketing strategies
- Create compelling marketing content
- Collaborate with product and sales teams
- Analyze market trends and competitor activities
- Manage marketing campaigns

## Requirements:
- 3+ years of product marketing experience
- Strong analytical skills
- Excellent written and verbal communication
- Experience with marketing tools and platforms
- Bachelor\'s degree in Marketing or related field

## Benefits:
- Competitive salary and equity
- Comprehensive health benefits
- Flexible PTO
- Professional development budget
- Modern office in San Francisco',
                'published_at' => now(),
            ],
            [
                'title' => 'Sales Development Representative',
                'slug' => 'sales-development-representative',
                'department_id' => $sales->id,
                'location_id' => $ny->id,
                'employment_type' => 'full_time',
                'status' => 'published',
                'openings_count' => 3,
                'description' => 'We are seeking motivated Sales Development Representatives to join our sales team and help drive growth.

## Responsibilities:
- Prospect and qualify new leads
- Conduct initial sales calls and demos
- Maintain accurate records in CRM
- Collaborate with account executives
- Meet and exceed sales targets

## Requirements:
- 1-2 years of sales experience (preferred)
- Strong communication and interpersonal skills
- Self-motivated and results-driven
- Experience with CRM systems
- Bachelor\'s degree or equivalent experience

## Benefits:
- Base salary + commission
- Health and dental insurance
- 401k with company matching
- Sales training and development
- Career advancement opportunities',
                'published_at' => now(),
            ],
            [
                'title' => 'Frontend Developer',
                'slug' => 'frontend-developer',
                'department_id' => $engineering->id,
                'location_id' => $remote->id,
                'employment_type' => 'contract',
                'status' => 'published',
                'openings_count' => 1,
                'description' => 'We need a talented Frontend Developer to help us build beautiful, responsive user interfaces.

## Responsibilities:
- Develop responsive web applications
- Collaborate with designers and backend developers
- Optimize applications for maximum speed and scalability
- Write clean, maintainable code
- Participate in agile development process

## Requirements:
- 3+ years of frontend development experience
- Proficiency in HTML, CSS, and JavaScript
- Experience with React, Vue.js, or similar frameworks
- Knowledge of modern build tools
- Strong attention to detail

## Benefits:
- Competitive hourly rate
- Flexible schedule
- Remote work opportunity
- Opportunity for full-time conversion
- Work on cutting-edge projects',
                'published_at' => now(),
            ],
            [
                'title' => 'Customer Success Manager',
                'slug' => 'customer-success-manager',
                'department_id' => $sales->id,
                'location_id' => $remote->id,
                'employment_type' => 'full_time',
                'status' => 'published',
                'openings_count' => 2,
                'description' => 'Help our customers succeed by joining our Customer Success team.

## Responsibilities:
- Manage and grow existing customer relationships
- Onboard new customers
- Identify upsell and expansion opportunities
- Gather customer feedback and insights
- Work closely with product and engineering teams

## Requirements:
- 2+ years of customer success experience
- Strong relationship-building skills
- Excellent communication skills
- Experience with SaaS products
- Problem-solving mindset

## Benefits:
- Competitive salary
- Health insurance
- 401k matching
- Remote work flexibility
- Career growth opportunities',
                'published_at' => now(),
            ],
        ];

        foreach ($jobs as $jobData) {
            JobOpening::create(array_merge($jobData, [
                'tenant_id' => $tenant->id,
            ]));
        }

        $this->command->info('Created ' . count($jobs) . ' job openings for ' . $tenant->name);
    }
}