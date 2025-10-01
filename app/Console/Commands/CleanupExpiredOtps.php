<?php

namespace App\Console\Commands;

use App\Models\EmailVerificationOtp;
use Illuminate\Console\Command;

class CleanupExpiredOtps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP verification codes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = EmailVerificationOtp::cleanupExpired();
        
        $this->info("Cleaned up {$deletedCount} expired OTP codes.");
        
        return Command::SUCCESS;
    }
}
