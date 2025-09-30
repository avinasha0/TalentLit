<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;

class CheckInterviewStage extends Command
{
    protected $signature = 'dashboard:check-interview-stage';
    protected $description = 'Check applications in Interview stage';

    public function handle()
    {
        $count = Application::whereHas('currentStage', function ($query) {
            $query->where('name', 'like', '%Interview%');
        })->count();
        
        $this->info("Applications in Interview stage: {$count}");
        
        // Also show which applications are in interview stage
        $applications = Application::with(['candidate', 'currentStage'])
            ->whereHas('currentStage', function ($query) {
                $query->where('name', 'like', '%Interview%');
            })
            ->get();
            
        foreach ($applications as $app) {
            $this->info("- Application {$app->id}: {$app->candidate->full_name} in stage '{$app->currentStage->name}'");
        }
        
        return 0;
    }
}
