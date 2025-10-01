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
        // Get all tenants
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            // Create branding for each tenant
            $this->createBranding($tenant);
            
            // Create sample questions for each tenant
            $this->createSampleQuestions($tenant);
            
            // Attach questions to sample jobs
            $this->attachQuestionsToJobs($tenant);
        }
    }

    private function createBranding(Tenant $tenant): void
    {
        TenantBranding::updateOrCreate(
            ['tenant_id' => $tenant->id],
            [
                'primary_color' => '#4f46e5',
                'intro_headline' => 'Join Our Amazing Team',
                'intro_subtitle' => 'We\'re looking for talented individuals to join our growing team and help us build something amazing together.',
            ]
        );
    }

    private function createSampleQuestions(Tenant $tenant): void
    {
        $questions = [
            [
                'label' => 'LinkedIn Profile URL',
                'name' => 'linkedin_url',
                'type' => 'short_text',
                'required' => false,
                'options' => null,
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
                'options' => null,
            ],
            [
                'label' => 'Expected Salary (CTC)',
                'name' => 'expected_ctc',
                'type' => 'short_text',
                'required' => false,
                'options' => null,
            ],
            [
                'label' => 'Notice Period',
                'name' => 'notice_period',
                'type' => 'select',
                'required' => true,
                'options' => ['Immediate', '1 month', '2 months', '3 months', 'More than 3 months'],
            ],
            [
                'label' => 'Why do you want to join our company?',
                'name' => 'why_join_us',
                'type' => 'long_text',
                'required' => true,
                'options' => null,
            ],
            [
                'label' => 'Do you have any certifications?',
                'name' => 'has_certifications',
                'type' => 'checkbox',
                'required' => false,
                'options' => null,
            ],
            [
                'label' => 'Preferred Work Arrangement',
                'name' => 'work_arrangement',
                'type' => 'multi_select',
                'required' => false,
                'options' => ['Remote', 'Hybrid', 'On-site', 'Flexible hours'],
            ],
            [
                'label' => 'Portfolio/Work Samples',
                'name' => 'portfolio',
                'type' => 'file',
                'required' => false,
                'options' => null,
            ],
            [
                'label' => 'Additional Comments',
                'name' => 'additional_comments',
                'type' => 'long_text',
                'required' => false,
                'options' => null,
            ],
        ];

        foreach ($questions as $questionData) {
            ApplicationQuestion::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'name' => $questionData['name'],
                ],
                array_merge($questionData, ['tenant_id' => $tenant->id])
            );
        }
    }

    private function attachQuestionsToJobs(Tenant $tenant): void
    {
        $jobs = JobOpening::where('tenant_id', $tenant->id)->take(3)->get();
        $questions = ApplicationQuestion::where('tenant_id', $tenant->id)->take(5)->get();

        foreach ($jobs as $index => $job) {
            // Clear existing questions first
            JobApplicationQuestion::where('tenant_id', $tenant->id)
                ->where('job_id', $job->id)
                ->delete();
            
            // Attach first 3 questions to each job
            $questionsToAttach = $questions->take(3);
            
            foreach ($questionsToAttach as $sortOrder => $question) {
                JobApplicationQuestion::create([
                    'tenant_id' => $tenant->id,
                    'job_id' => $job->id,
                    'question_id' => $question->id,
                    'sort_order' => $sortOrder + 1,
                    'required_override' => $question->required, // Use question's default required status
                ]);
            }
        }
    }
}