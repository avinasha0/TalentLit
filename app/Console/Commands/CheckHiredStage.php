<?php

namespace App\Console\Commands;

use App\Models\Application;
use Illuminate\Console\Command;

class CheckHiredStage extends Command
{
    protected $signature = 'dashboard:check-hired-stage';
    protected $description = 'Check applications in Hired stage';

    public function handle()
    {
        $count = Application::whereHas('currentStage', function ($query) {
            $query->where('name', 'like', '%Hired%');
        })->count();
        
        $this->info("Applications in Hired stage: {$count}");
        
        // Also show which applications are in hired stage
        $applications = Application::with(['candidate', 'currentStage'])
            ->whereHas('currentStage', function ($query) {
                $query->where('name', 'like', '%Hired%');
            })
            ->get();
            
        foreach ($applications as $app) {
            $this->info("- Application {$app->id}: {$app->candidate->full_name} in stage '{$app->currentStage->name}'");
        }
        
        return 0;
    }
}
