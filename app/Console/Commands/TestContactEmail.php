<?php

namespace App\Console\Commands;

use App\Mail\ContactMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TestContactEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:contact-email {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test contact email functionality with debugging';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testEmail = $this->argument('email') ?? env('MAIL_TO_EMAIL', config('mail.from.address'));
        
        $this->info('Testing contact email functionality...');
        $this->info('Target email: ' . $testEmail);
        
        // Log mail configuration
        Log::info('Test contact email - Mail configuration', [
            'mail_mailer' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
            'mail_to_email' => env('MAIL_TO_EMAIL'),
            'app_env' => config('app.env'),
            'app_debug' => config('app.debug')
        ]);
        
        $this->info('Mail Configuration:');
        $this->line('  Mailer: ' . config('mail.default'));
        $this->line('  Host: ' . config('mail.mailers.smtp.host'));
        $this->line('  Port: ' . config('mail.mailers.smtp.port'));
        $this->line('  Username: ' . config('mail.mailers.smtp.username'));
        $this->line('  From Address: ' . config('mail.from.address'));
        $this->line('  From Name: ' . config('mail.from.name'));
        $this->line('  App Environment: ' . config('app.env'));
        $this->line('  App Debug: ' . (config('app.debug') ? 'true' : 'false'));
        
        // Test data
        $testData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'company' => 'Test Company',
            'phone' => '+1-555-123-4567',
            'subject' => 'Test Contact Form Submission',
            'message' => 'This is a test message from the contact form debugging command.'
        ];
        
        try {
            $this->info('Sending test email...');
            
            Log::info('Test contact email - Attempting to send', [
                'to_address' => $testEmail,
                'test_data' => $testData
            ]);
            
            Mail::to($testEmail)->send(new ContactMail($testData));
            
            Log::info('Test contact email - Sent successfully', [
                'to_address' => $testEmail,
                'timestamp' => now()
            ]);
            
            $this->info('✅ Test email sent successfully!');
            $this->info('Check the logs for detailed information.');
            
        } catch (\Exception $e) {
            Log::error('Test contact email - Failed to send', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'test_data' => $testData
            ]);
            
            $this->error('❌ Test email failed to send!');
            $this->error('Error: ' . $e->getMessage());
            $this->error('Check the logs for detailed error information.');
        }
        
        return 0;
    }
}
