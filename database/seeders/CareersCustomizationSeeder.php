<?php

namespace Database\Seeders;

use App\Models\ApplicationQuestion;
use App\Models\JobApplicationQuestion;
use App\Models\JobOpening;
use App\Models\Tenant;
use App\Models\TenantBranding;
use Illuminate\Database\Seeder;

class CareersCustomizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first tenant
        $tenant = Tenant::first();
        
        if (!$tenant) {
            $this->command->warn('No tenant found. Please run TenantSeeder first.');
            return;
        }

        // Create tenant branding
        $branding = TenantBranding::firstOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'primary_color' => '#4f46e5',
                'intro_headline' => 'Join Our Amazing Team',
                'intro_subtitle' => 'We\'re looking for talented individuals to join our growing team and help us build the future.',
            ]
        );

        $this->command->info("Created branding for tenant: {$tenant->name}");

        // Create sample application questions
        $questions = [
            [
                'label' => 'LinkedIn Profile URL',
                'name' => 'linkedin_url',
                'type' => 'short_text',
                'required' => false,
            ],
            [
                'label' => 'Years of Experience',
                'name' => 'years_experience',
                'type' => 'select',
                'required' => true,
                'options' => ['0-1 years', '2-3 years', '4-5 years', '6-10 years', '10+ years'],
            ],
            [
                'label' => 'Current Salary (CTC)',
                'name' => 'current_ctc',
                'type' => 'short_text',
                'required' => false,
            ],
            [
                'label' => 'Expected Salary (CTC)',
                'name' => 'expected_ctc',
                'type' => 'short_text',
                'required' => true,
            ],
            [
                'label' => 'Notice Period',
                'name' => 'notice_period',
                'type' => 'select',
                'required' => true,
                'options' => ['Immediate', '15 days', '30 days', '60 days', '90 days', 'More than 90 days'],
            ],
            [
                'label' => 'Why do you want to join our company?',
                'name' => 'why_join_us',
                'type' => 'long_text',
                'required' => false,
            ],
            [
                'label' => 'Do you have any references from our company?',
                'name' => 'has_references',
                'type' => 'checkbox',
                'required' => false,
            ],
            [
                'label' => 'Portfolio/Work Samples',
                'name' => 'portfolio',
                'type' => 'file',
                'required' => false,
            ],
        ];

        foreach ($questions as $questionData) {
            ApplicationQuestion::firstOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $questionData['name'],
                ],
                array_merge($questionData, ['tenant_id' => $tenant->id])
            );
        }

        $this->command->info('Created ' . count($questions) . ' application questions');

        // Attach some questions to a sample job
        $job = JobOpening::where('tenant_id', $tenant->id)->first();
        
        if ($job) {
            $sampleQuestions = ApplicationQuestion::where('tenant_id', $tenant->id)
                ->whereIn('name', ['linkedin_url', 'years_experience', 'expected_ctc'])
                ->get();

            foreach ($sampleQuestions as $index => $question) {
                JobApplicationQuestion::create([
                    'tenant_id' => $tenant->id,
                    'job_id' => $job->id,
                    'question_id' => $question->id,
                    'sort_order' => $index + 1,
                    'required_override' => $index > 0, // Make years_experience and expected_ctc required
                ]);
            }

            $this->command->info("Attached {$sampleQuestions->count()} questions to job: {$job->title}");
        }

        $this->command->info('Careers customization seeding completed!');
    }
}