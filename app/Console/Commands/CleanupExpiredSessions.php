<?php

namespace App\Console\Commands;

use App\Models\EmailVerificationOtp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP codes and session data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Clean up expired OTP codes
        $deletedOtps = EmailVerificationOtp::cleanupExpired();
        $this->info("Cleaned up {$deletedOtps} expired OTP codes.");
        
        // Note: Session cleanup is handled automatically by Laravel
        // when sessions expire, so no manual cleanup needed for session data
        
        return Command::SUCCESS;
    }
}