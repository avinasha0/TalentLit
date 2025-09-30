<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\JobOpening;
use App\Models\JobStage;
use Illuminate\Console\Command;

class CreateMoreTestApplications extends Command
{
    protected $signature = 'pipeline:create-more-apps {job_id}';
    protected $description = 'Create more test applications for a job';

    public function handle()
    {
        $jobId = $this->argument('job_id');
        $job = JobOpening::find($jobId);
        
        if (!$job) {
            $this->error("Job not found: {$jobId}");
            return 1;
        }
        
        // Get the first stage
        $firstStage = JobStage::where('job_opening_id', $job->id)
            ->orderBy('sort_order')
            ->first();
            
        if (!$firstStage) {
            $this->error("No stages found for job: {$job->title}");
            return 1;
        }
        
        // Create 4 more test applications
        $timestamp = time();
        $candidateNames = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => "john.doe.{$timestamp}@example.com"],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => "jane.smith.{$timestamp}@example.com"],
            ['first_name' => 'Mike', 'last_name' => 'Johnson', 'email' => "mike.johnson.{$timestamp}@example.com"],
            ['first_name' => 'Sarah', 'last_name' => 'Wilson', 'email' => "sarah.wilson.{$timestamp}@example.com"],
        ];
        
        foreach ($candidateNames as $candidateData) {
            // Create candidate
            $candidate = Candidate::create([
                'tenant_id' => $job->tenant_id,
                'first_name' => $candidateData['first_name'],
                'last_name' => $candidateData['last_name'],
                'primary_email' => $candidateData['email'],
                'primary_phone' => '+1-555-' . rand(100, 999) . '-' . rand(1000, 9999),
            ]);
            
            // Create application
            Application::create([
                'tenant_id' => $job->tenant_id,
                'job_opening_id' => $job->id,
                'candidate_id' => $candidate->id,
                'status' => 'active',
                'current_stage_id' => $firstStage->id,
                'stage_position' => 0,
                'applied_at' => now()->subDays(rand(1, 30)),
            ]);
        }
        
        $this->info("Created 4 more test applications for job: {$job->title}");
        $this->info("Pipeline should now show 5 applications at: /acme/jobs/{$jobId}/pipeline");
        
        return 0;
    }
}
