<?php

namespace App\Console\Commands;

use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Console\Command;

class CreateStagesForAllJobs extends Command
{
    protected $signature = 'pipeline:create-stages-all';
    protected $description = 'Create default stages for all jobs that don\'t have stages';

    public function handle()
    {
        $jobs = JobOpening::with('jobStages')->get();
        
        foreach ($jobs as $job) {
            if ($job->jobStages->count() > 0) {
                $this->info("Job '{$job->title}' already has stages, skipping...");
                continue;
            }
            
            $this->info("Creating stages for job: {$job->title}");
            
            // Create default stages
            $stages = [
                ['name' => 'Applied', 'sort_order' => 1, 'is_terminal' => false],
                ['name' => 'Phone Screen', 'sort_order' => 2, 'is_terminal' => false],
                ['name' => 'Interview', 'sort_order' => 3, 'is_terminal' => false],
                ['name' => 'Final Review', 'sort_order' => 4, 'is_terminal' => false],
                ['name' => 'Hired', 'sort_order' => 5, 'is_terminal' => true],
            ];
            
            foreach ($stages as $stageData) {
                JobStage::create([
                    'tenant_id' => $job->tenant_id,
                    'job_opening_id' => $job->id,
                    'name' => $stageData['name'],
                    'sort_order' => $stageData['sort_order'],
                    'is_terminal' => $stageData['is_terminal'],
                ]);
            }
            
            $this->info("Created 5 stages for job: {$job->title}");
        }
        
        $this->info("All jobs now have stages!");
        return 0;
    }
}
