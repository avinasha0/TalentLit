<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use App\Models\Tenant;
use Illuminate\Console\Command;

class CreateTestPipelineData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:create-test-data {job_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create test data for pipeline (stages and applications)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jobId = $this->argument('job_id');
        
        $job = JobOpening::find($jobId);
        if (!$job) {
            $this->error("Job with ID {$jobId} not found.");
            return 1;
        }

        $this->info("Creating test data for job: {$job->title}");

        // Create job stages if they don't exist
        if ($job->jobStages()->count() === 0) {
            $stages = [
                ['name' => 'Applied', 'sort_order' => 1],
                ['name' => 'Phone Screen', 'sort_order' => 2],
                ['name' => 'Interview', 'sort_order' => 3],
                ['name' => 'Offer', 'sort_order' => 4],
                ['name' => 'Hired', 'sort_order' => 5],
            ];

            foreach ($stages as $stageData) {
                JobStage::create([
                    'tenant_id' => $job->tenant_id,
                    'job_opening_id' => $job->id,
                    'name' => $stageData['name'],
                    'sort_order' => $stageData['sort_order'],
                    'is_terminal' => $stageData['name'] === 'Hired',
                ]);
            }
            $this->info("Created 5 job stages");
        } else {
            $this->info("Job already has " . $job->jobStages()->count() . " stages");
        }

        // Create test applications if they don't exist
        if ($job->applications()->count() === 0) {
            $firstStage = $job->jobStages()->orderBy('sort_order')->first();
            
            $candidates = [
                ['name' => 'John Doe', 'email' => 'john@example.com'],
                ['name' => 'Jane Smith', 'email' => 'jane@example.com'],
                ['name' => 'Bob Johnson', 'email' => 'bob@example.com'],
                ['name' => 'Alice Brown', 'email' => 'alice@example.com'],
                ['name' => 'Charlie Wilson', 'email' => 'charlie@example.com'],
            ];

            foreach ($candidates as $index => $candidateData) {
                $candidate = Candidate::create([
                    'tenant_id' => $job->tenant_id,
                    'first_name' => explode(' ', $candidateData['name'])[0],
                    'last_name' => explode(' ', $candidateData['name'])[1],
                    'primary_email' => $candidateData['email'],
                ]);

                Application::create([
                    'tenant_id' => $job->tenant_id,
                    'job_opening_id' => $job->id,
                    'candidate_id' => $candidate->id,
                    'current_stage_id' => $firstStage->id,
                    'stage_position' => $index,
                    'status' => 'active',
                    'applied_at' => now()->subDays(rand(1, 30)),
                ]);
            }
            $this->info("Created 5 test applications");
        } else {
            $this->info("Job already has " . $job->applications()->count() . " applications");
        }

        $this->info("Pipeline test data created successfully!");
        $this->info("You can now visit: /acme/jobs/{$job->id}/pipeline");
        
        return 0;
    }
}
