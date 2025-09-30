<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Console\Command;

class FixApplicationStages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pipeline:fix-stages {job_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix application stage assignments for pipeline';

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

        $firstStage = $job->jobStages()->orderBy('sort_order')->first();
        if (!$firstStage) {
            $this->error("No stages found for this job.");
            return 1;
        }

        // Update all applications to be in the first stage
        $updated = $job->applications()->update([
            'current_stage_id' => $firstStage->id,
            'stage_position' => 0
        ]);

        $this->info("Updated {$updated} applications to stage: {$firstStage->name}");
        $this->info("Pipeline should now be visible at: /acme/jobs/{$jobId}/pipeline");
        
        return 0;
    }
}
