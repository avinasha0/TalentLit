<?php

namespace App\Console\Commands;

use App\Models\JobOpening;
use Illuminate\Console\Command;

class CheckJobApplications extends Command
{
    protected $signature = 'jobs:check-applications';
    protected $description = 'Check application counts for all jobs';

    public function handle()
    {
        $jobs = JobOpening::with('applications')->get();
        
        foreach ($jobs as $job) {
            $this->info("Job: {$job->title} (ID: {$job->id})");
            $this->info("  Applications: {$job->applications->count()}");
            $this->info("  Applications with candidates: " . $job->applications->where('candidate_id', '!=', null)->count());
            $this->info("  Applications in stages: " . $job->applications->where('current_stage_id', '!=', null)->count());
            $this->info("");
        }
        
        return 0;
    }
}
