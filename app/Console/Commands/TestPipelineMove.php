<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Console\Command;

class TestPipelineMove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:test-move {job_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test pipeline move functionality';

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

        $this->info("Job: {$job->title}");
        
        $application = $job->applications()->first();
        if (!$application) {
            $this->error("No applications found for this job.");
            return 1;
        }

        $this->info("Application ID: {$application->id}");
        $this->info("Current Stage ID: {$application->current_stage_id}");
        
        $stages = $job->jobStages()->orderBy('sort_order')->get();
        $this->info("Available Stages:");
        foreach ($stages as $stage) {
            $this->info("  - {$stage->name} (ID: {$stage->id})");
        }

        // Test moving to the second stage
        $secondStage = $stages->get(1);
        if ($secondStage) {
            $this->info("Testing move to: {$secondStage->name}");
            
            $application->update([
                'current_stage_id' => $secondStage->id,
                'stage_position' => 0
            ]);
            
            $this->info("Move completed successfully!");
            $this->info("Application is now in stage: {$secondStage->name}");
        }
        
        return 0;
    }
}
