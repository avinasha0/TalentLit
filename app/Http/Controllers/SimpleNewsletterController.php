<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\RecaptchaService;

class SimpleNewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        // Basic validation
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return view('newsletter-simple', [
                'errors' => $validator->errors(),
                'old_email' => $request->old('email')
            ]);
        }

        // Verify reCAPTCHA with Google
        $recaptchaService = app(RecaptchaService::class);
        $hostname = $request->getHost();
        
        if (!$recaptchaService->verify($request->input('g-recaptcha-response'), $request->ip(), $hostname)) {
            return view('newsletter-simple', [
                'errors' => collect(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']),
                'old_email' => $request->input('email')
            ]);
        }

        // ✅ Passed reCAPTCHA → Handle newsletter signup
        // Example: Save email to DB or send to Mailchimp/Sendgrid
        // NewsletterSubscriber::create(['email' => $request->email]);

        return view('newsletter-simple', [
            'success' => 'Thank you for subscribing! Your email has been added to our newsletter.'
        ]);
    }
}
