<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Application;
use App\Models\ApplicationNote;
use App\Models\ApplicationStageEvent;
use App\Models\Candidate;
use App\Models\CandidateTag;
use App\Models\Department;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Location;
use App\Models\Tag;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoreDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create Acme tenant
        $tenant = Tenant::where('slug', 'acme')->first();
        if (! $tenant) {
            $tenant = Tenant::create([
                'name' => 'Acme',
                'slug' => 'acme',
            ]);
        }

        // Create admin user for Acme
        $admin = User::where('email', 'admin@acme.com')->first();
        if (! $admin) {
            $admin = User::create([
                'name' => 'Acme Admin',
                'email' => 'admin@acme.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $admin->assignRole('Owner');
        }

        // Create departments
        $departments = [
            ['name' => 'Engineering', 'code' => 'ENG', 'is_active' => true],
            ['name' => 'Sales', 'code' => 'SAL', 'is_active' => true],
            ['name' => 'HR', 'code' => 'HR', 'is_active' => true],
        ];

        foreach ($departments as $deptData) {
            Department::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $deptData['name']],
                array_merge($deptData, ['tenant_id' => $tenant->id])
            );
        }

        // Create locations
        $locations = [
            ['name' => 'Chennai', 'city' => 'Chennai', 'country' => 'India', 'is_active' => true],
            ['name' => 'Bengaluru', 'city' => 'Bengaluru', 'country' => 'India', 'is_active' => true],
        ];

        foreach ($locations as $locData) {
            Location::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $locData['name']],
                array_merge($locData, ['tenant_id' => $tenant->id])
            );
        }

        $engineeringDept = Department::where('tenant_id', $tenant->id)->where('name', 'Engineering')->first();
        $chennaiLocation = Location::where('tenant_id', $tenant->id)->where('name', 'Chennai')->first();

        // Create job opening
        $jobOpening = JobOpening::firstOrCreate(
            ['tenant_id' => $tenant->id, 'slug' => 'senior-php-developer'],
            [
                'tenant_id' => $tenant->id,
                'title' => 'Senior PHP Developer',
                'slug' => 'senior-php-developer',
                'department_id' => $engineeringDept->id,
                'location_id' => $chennaiLocation->id,
                'employment_type' => 'full_time',
                'status' => 'published',
                'openings_count' => 2,
                'description' => 'We are looking for a Senior PHP Developer with Laravel experience to join our engineering team.',
                'published_at' => now(),
            ]
        );

        // Create job stages
        $stages = [
            ['name' => 'Sourced', 'sort_order' => 1, 'is_terminal' => false],
            ['name' => 'Screen', 'sort_order' => 2, 'is_terminal' => false],
            ['name' => 'Interview', 'sort_order' => 3, 'is_terminal' => false],
            ['name' => 'Offer', 'sort_order' => 4, 'is_terminal' => false],
            ['name' => 'Hired', 'sort_order' => 5, 'is_terminal' => true],
            ['name' => 'Rejected', 'sort_order' => 6, 'is_terminal' => true],
        ];

        foreach ($stages as $stageData) {
            JobStage::firstOrCreate(
                ['tenant_id' => $tenant->id, 'job_opening_id' => $jobOpening->id, 'name' => $stageData['name']],
                array_merge($stageData, ['tenant_id' => $tenant->id, 'job_opening_id' => $jobOpening->id])
            );
        }

        // Create tags
        $tags = [
            ['name' => 'PHP', 'color' => '#3B82F6'],
            ['name' => 'Laravel', 'color' => '#EF4444'],
            ['name' => 'Immediate Joiner', 'color' => '#10B981'],
            ['name' => 'Senior Level', 'color' => '#F59E0B'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(
                ['tenant_id' => $tenant->id, 'name' => $tagData['name']],
                array_merge($tagData, ['tenant_id' => $tenant->id])
            );
        }

        // Create candidates
        $candidates = [];
        $sources = ['Referral', 'Naukri', 'LinkedIn', 'Indeed', 'Company Website', 'Job Fair'];

        for ($i = 1; $i <= 12; $i++) {
            $candidate = Candidate::create([
                'tenant_id' => $tenant->id,
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'primary_email' => fake()->unique()->safeEmail(),
                'primary_phone' => fake()->optional(0.8)->phoneNumber(),
                'source' => fake()->randomElement($sources),
                'resume_raw_text' => fake()->optional(0.6)->paragraphs(10, true),
            ]);
            $candidates[] = $candidate;
        }

        // Attach tags to candidates
        $tagIds = Tag::where('tenant_id', $tenant->id)->pluck('id');
        foreach ($candidates as $candidate) {
            $randomTags = $tagIds->random(fake()->numberBetween(1, 3));
            foreach ($randomTags as $tagId) {
                CandidateTag::firstOrCreate([
                    'tenant_id' => $tenant->id,
                    'candidate_id' => $candidate->id,
                    'tag_id' => $tagId,
                ]);
            }
        }

        // Create applications
        $stageIds = JobStage::where('tenant_id', $tenant->id)
            ->where('job_opening_id', $jobOpening->id)
            ->orderBy('sort_order')
            ->pluck('id');

        $applications = [];
        for ($i = 0; $i < 20; $i++) {
            $candidate = $candidates[array_rand($candidates)];
            $stageId = $stageIds->random();

            $application = Application::firstOrCreate([
                'tenant_id' => $tenant->id,
                'job_opening_id' => $jobOpening->id,
                'candidate_id' => $candidate->id,
            ], [
                'tenant_id' => $tenant->id,
                'job_opening_id' => $jobOpening->id,
                'candidate_id' => $candidate->id,
                'status' => fake()->randomElement(['active', 'withdrawn', 'hired', 'rejected']),
                'current_stage_id' => $stageId,
                'applied_at' => fake()->dateTimeBetween('-30 days', 'now'),
            ]);
            $applications[] = $application;

            // Create stage event for the application
            ApplicationStageEvent::create([
                'tenant_id' => $tenant->id,
                'application_id' => $application->id,
                'from_stage_id' => null,
                'to_stage_id' => $stageId,
                'moved_by_user_id' => $admin->id,
                'note' => 'Initial application',
                'created_at' => $application->applied_at,
            ]);

            // Create some notes for applications
            if (fake()->boolean(60)) {
                ApplicationNote::create([
                    'tenant_id' => $tenant->id,
                    'application_id' => $application->id,
                    'user_id' => $admin->id,
                    'body' => fake()->sentence(),
                    'created_at' => fake()->dateTimeBetween($application->applied_at, 'now'),
                ]);
            }
        }

        // Create some activities
        foreach ($applications as $application) {
            if (fake()->boolean(40)) {
                Activity::create([
                    'tenant_id' => $tenant->id,
                    'application_id' => $application->id,
                    'candidate_id' => $application->candidate_id,
                    'type' => fake()->randomElement(['task', 'call', 'email', 'event']),
                    'due_at' => fake()->optional(0.7)->dateTimeBetween('now', '+7 days'),
                    'completed_at' => fake()->optional(0.3)->dateTimeBetween('-7 days', 'now'),
                    'title' => fake()->sentence(),
                    'body' => fake()->optional(0.8)->paragraphs(2, true),
                    'user_id' => $admin->id,
                ]);
            }
        }

        $this->command->info('Core data seeded successfully for Acme tenant!');
    }
}
