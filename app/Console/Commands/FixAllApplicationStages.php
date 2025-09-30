<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Console\Command;

class FixAllApplicationStages extends Command
{
    protected $signature = 'pipeline:fix-all-stages';
    protected $description = 'Fix all application stage assignments for all jobs';

    public function handle()
    {
        $jobs = JobOpening::with('applications')->get();
        
        foreach ($jobs as $job) {
            $this->info("Processing job: {$job->title} (ID: {$job->id})");
            
            // Get the first stage for this job
            $firstStage = JobStage::where('job_opening_id', $job->id)
                ->orderBy('sort_order')
                ->first();
                
            if (!$firstStage) {
                $this->warn("No stages found for job: {$job->title}");
                continue;
            }
            
            // Update all applications for this job to have current_stage_id and stage_position
            $updated = Application::where('job_opening_id', $job->id)
                ->whereNull('current_stage_id')
                ->update([
                    'current_stage_id' => $firstStage->id,
                    'stage_position' => 0
                ]);
                
            $this->info("Updated {$updated} applications for job: {$job->title}");
        }
        
        $this->info("All application stages have been fixed!");
        return 0;
    }
}
