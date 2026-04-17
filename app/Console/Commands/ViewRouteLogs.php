<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewRouteLogs extends Command
{
    protected $signature = 'logs:routes {--lines=50 : Number of lines to show} {--filter= : Filter by keyword}';
    protected $description = 'View route matching logs for debugging';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return 1;
        }
        
        $lines = (int) $this->option('lines');
        $filter = $this->option('filter');
        
        // Read last N lines from log file
        $content = File::get($logPath);
        $allLines = explode("\n", $content);
        $recentLines = array_slice($allLines, -$lines);
        
        $this->info("Showing last {$lines} log lines" . ($filter ? " filtered by: {$filter}" : ""));
        $this->line(str_repeat('-', 80));
        
        $found = false;
        foreach ($recentLines as $line) {
            if (empty(trim($line))) {
                continue;
            }
            
            // Filter if specified
            if ($filter && !str_contains(strtolower($line), strtolower($filter))) {
                continue;
            }
            
            // Highlight important log entries
            if (str_contains($line, 'Route.Match')) {
                $this->line($line);
                $found = true;
            } elseif (str_contains($line, 'ResolveTenantFromSubdomain')) {
                $this->line($line);
                $found = true;
            } elseif (str_contains($line, 'ResolveTenantFromPath')) {
                $this->line($line);
                $found = true;
            } elseif ($filter && str_contains(strtolower($line), strtolower($filter))) {
                $this->line($line);
                $found = true;
            } elseif (!$filter) {
                // Show all lines if no filter
                $this->line($line);
                $found = true;
            }
        }
        
        if (!$found) {
            $this->warn('No matching log entries found.');
        }
        
        $this->line(str_repeat('-', 80));
        $this->info('Use --filter="keyword" to filter logs');
        $this->info('Use --lines=N to show more lines (default: 50)');
        
        return 0;
    }
}

