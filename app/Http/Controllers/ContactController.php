<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Rules\RecaptchaRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display the contact page
     */
    public function index()
    {
        return view('contact');
    }

    /**
     * Handle contact form submission
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request
        \Log::info('Contact form submission started', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
            'form_data' => $request->except(['_token'])
        ]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'g-recaptcha-response' => ['required', new RecaptchaRule(app(\App\Services\RecaptchaService::class), $request)],
        ], [
            'name.required' => 'Please provide your name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'subject.required' => 'Please provide a subject.',
            'message.required' => 'Please provide your message.',
            'message.max' => 'Your message is too long. Please keep it under 2000 characters.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ]);

        if ($validator->fails()) {
            \Log::warning('Contact form validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->except(['_token'])
            ]);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Debug: Log mail configuration
            \Log::info('Mail configuration check', [
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

            // Get admin email from environment or fallback to from address
            $adminEmail = env('MAIL_TO_EMAIL', config('mail.from.address'));
            
            // Debug: Log before sending email
            \Log::info('Attempting to send contact email', [
                'to_address' => $adminEmail,
                'admin_email_env' => env('MAIL_TO_EMAIL'),
                'fallback_address' => config('mail.from.address'),
                'contact_data' => $request->except(['_token'])
            ]);

            // Send email to admin
            Mail::to($adminEmail)
                ->send(new ContactMail($request->all()));

            // Debug: Log successful email send
            \Log::info('Contact email sent successfully', [
                'to_address' => $adminEmail,
                'timestamp' => now()
            ]);

            return redirect()->back()
                ->with('success', 'Thank you for your message! We\'ll get back to you soon.');
        } catch (\Exception $e) {
            // Debug: Log email sending error
            \Log::error('Contact email sending failed', [
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
                'contact_data' => $request->except(['_token'])
            ]);

            return redirect()->back()
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.')
                ->withInput();
        }
    }
}
