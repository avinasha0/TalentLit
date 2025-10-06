<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Career\ApplyController;
use App\Http\Controllers\Career\CareerJobController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Tenant\CandidateController;
use App\Http\Controllers\Tenant\CandidateNoteController;
use App\Http\Controllers\Tenant\CandidateTagController;
use App\Http\Controllers\Tenant\CareersSettingsController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\InterviewController;
use App\Http\Controllers\Tenant\JobController;
use App\Http\Controllers\Tenant\JobQuestionsController;
use App\Http\Controllers\Tenant\JobStageController;
use App\Http\Controllers\Tenant\PipelineController;
use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Public route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Help Center routes
Route::get('/help-center.html', [HelpController::class, 'index'])->name('help.index');
Route::get('/help/{page}', [HelpController::class, 'page'])->name('help.page')
    ->where('page', 'register|login|onboarding|invite-team|dashboard|jobs|careers|candidates|applications|pipeline|interviews|notes-tags|analytics|settings|roles-permissions|integrations|troubleshooting|security|deploy|contact|faq');

// Features pages
Route::get('/features', [HomeController::class, 'features'])->name('features');

// Company pages
Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/careers', function () {
    return view('careers');
})->name('careers');

Route::get('/press', function () {
    return view('press');
})->name('press');

Route::get('/blog', function () {
    return view('blog');
})->name('blog');
Route::get('/features/candidate-sourcing.html', [HomeController::class, 'candidateSourcing'])->name('features.candidate-sourcing');
Route::get('/features/hiring-pipeline.html', [HomeController::class, 'hiringPipeline'])->name('features.hiring-pipeline');
Route::get('/features/career-site.html', [HomeController::class, 'careerSite'])->name('features.career-site');
Route::get('/features/job-advertising.html', [HomeController::class, 'jobAdvertising'])->name('features.job-advertising');
Route::get('/features/employee-referral.html', [HomeController::class, 'employeeReferral'])->name('features.employee-referral');
Route::get('/features/resume-management.html', [HomeController::class, 'resumeManagement'])->name('features.resume-management');
Route::get('/features/manage-submission.html', [HomeController::class, 'manageSubmission'])->name('features.manage-submission');
Route::get('/features/hiring-analytics.html', [HomeController::class, 'hiringAnalytics'])->name('features.hiring-analytics');

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Debug route for testing contact email (remove in production)
Route::get('/test-contact-email', function () {
    try {
        \Log::info('Test contact email route accessed', [
            'timestamp' => now(),
            'ip' => request()->ip()
        ]);
        
        $testData = [
            'name' => 'Debug Test User',
            'email' => 'debug@test.com',
            'company' => 'Debug Company',
            'phone' => '+1-555-999-0000',
            'subject' => 'Debug Test - Contact Form',
            'message' => 'This is a debug test message to verify email functionality.'
        ];
        
        $adminEmail = env('MAIL_TO_EMAIL', config('mail.from.address'));
        \Mail::to($adminEmail)->send(new \App\Mail\ContactMail($testData));
        
        return response()->json([
            'status' => 'success',
            'message' => 'Test email sent successfully',
            'config' => [
                'mail_mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name')
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('Test contact email route failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'status' => 'error',
            'message' => 'Test email failed: ' . $e->getMessage(),
            'config' => [
                'mail_mailer' => config('mail.default'),
                'mail_host' => config('mail.mailers.smtp.host'),
                'mail_port' => config('mail.mailers.smtp.port'),
                'mail_from_address' => config('mail.from.address'),
                'mail_from_name' => config('mail.from.name')
            ]
        ], 500);
    }
})->name('test.contact.email');

// Newsletter routes - using NewsletterController (legacy)
Route::post('/newsletter/subscribe-legacy', [App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe.legacy');
Route::post('/newsletter/verify-otp-legacy', [App\Http\Controllers\NewsletterController::class, 'verifyOtp'])->name('newsletter.verify-otp.legacy');
Route::post('/newsletter/resend-otp-legacy', [App\Http\Controllers\NewsletterController::class, 'resendOtp'])->name('newsletter.resend-otp.legacy');
Route::post('/newsletter/unsubscribe-legacy', [App\Http\Controllers\NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe.legacy');
Route::get('/newsletter/status-legacy', [App\Http\Controllers\NewsletterController::class, 'status'])->name('newsletter.status.legacy');

// HTML Pages Routes
Route::get('/features.html', function () {
    return view('features');
})->name('features.html');

Route::get('/pricing.html', function () {
    return view('pricing');
})->name('pricing.html');


Route::get('/documentation.html', function () {
    return view('documentation');
})->name('documentation.html');

Route::get('/community.html', function () {
    return view('community');
})->name('community.html');

Route::get('/status.html', function () {
    return view('status');
})->name('status.html');

Route::get('/security.html', function () {
    return view('security');
})->name('security.html');

Route::get('/cookie-policy.html', function () {
    return view('cookie-policy');
})->name('cookie-policy.html');

// Test Footer Component
Route::get('/test-footer', function () {
    return view('test-footer');
})->name('test-footer');

// Legal pages
Route::get('/privacy-policy', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy');

Route::get('/privacy-policy.html', function () {
    return view('legal.privacy-policy');
})->name('privacy-policy.html');

Route::get('/terms-of-service', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service');

Route::get('/terms-of-service.html', function () {
    return view('legal.terms-of-service');
})->name('terms-of-service.html');

// Additional HTML routes for company pages
Route::get('/about.html', function () {
    return view('about');
})->name('about.html');

Route::get('/contact.html', function () {
    return view('contact');
})->name('contact.html');

Route::get('/careers.html', function () {
    return view('careers');
})->name('careers.html');

Route::get('/blog.html', function () {
    return view('blog');
})->name('blog.html');

Route::get('/press.html', function () {
    return view('press');
})->name('press.html');

// Invitation routes
Route::get('/invitation/{token}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/invitation/{token}', [InvitationController::class, 'accept'])->name('invitation.accept');

// Subscription routes (public)
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');

// Waitlist routes (authenticated users only)
Route::middleware('auth')->group(function () {
    Route::post('/waitlist', [App\Http\Controllers\WaitlistController::class, 'store'])->name('waitlist.store');
    Route::get('/waitlist/check', [App\Http\Controllers\WaitlistController::class, 'check'])->name('waitlist.check');
});

// Payment routes (public)
Route::middleware('auth')->group(function () {
    Route::post('/payment/create-order', [PaymentController::class, 'createOrder'])->name('payment.create-order');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
    Route::get('/payment/status', [PaymentController::class, 'status'])->name('payment.status');
    Route::get('/payment/check-pro-plan', [PaymentController::class, 'checkProPlanAvailability'])->name('payment.check-pro-plan');
});

// Webhook routes (no auth required)
Route::post('/webhook/razorpay', [PaymentController::class, 'webhook'])->name('payment.webhook');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Test route
Route::get('/test', function () {
    return view('test');
})->name('test');

// reCAPTCHA Test routes
Route::get('/test-recaptcha', function () {
    return view('test-recaptcha');
})->name('test.recaptcha');

// Simple Newsletter Form
Route::get('/newsletter-simple', function () {
    return view('newsletter-simple');
})->name('newsletter.simple');

// Newsletter Subscription Routes - Main routes (clean names)
Route::get('/newsletter/subscribe', function () {
    $email = session('email');
    return view('newsletter.subscribe', ['old_email' => $email]);
})->name('newsletter.subscribe');

// Home page newsletter redirect (no OTP generation)
Route::post('/newsletter/redirect', function (\Illuminate\Http\Request $request) {
    $email = $request->input('email');
    return redirect()->route('newsletter.subscribe')->with('email', $email);
})->name('newsletter.redirect');

// Newsletter POST actions (distinct names)
Route::post('/newsletter/subscribe', [App\Http\Controllers\NewsletterSubscriptionController::class, 'subscribe'])
    ->name('newsletter.subscribe.post');
Route::post('/newsletter/verify-otp', [App\Http\Controllers\NewsletterSubscriptionController::class, 'verifyOtp'])
    ->name('newsletter.verify-otp.post');
Route::post('/newsletter/resend-otp', [App\Http\Controllers\NewsletterSubscriptionController::class, 'resendOtp'])
    ->name('newsletter.resend-otp.post');

Route::post('/test-recaptcha', function (\Illuminate\Http\Request $request) {
    // Simple reCAPTCHA test handler
    $token = $request->input('g-recaptcha-response');
    $secret = config('recaptcha.secret_key');
    
    // If reCAPTCHA is not configured, allow submission
    if (empty($secret)) {
        return response()->json([
            'success' => true,
            'message' => 'Form submitted successfully! (reCAPTCHA not configured)',
            'email' => $request->input('email'),
            'note' => 'Add RECAPTCHA_SECRET_KEY to .env to enable reCAPTCHA verification'
        ]);
    }
    
    // If reCAPTCHA is configured but token is missing
    if (empty($token)) {
        return response()->json([
            'success' => false,
            'message' => 'reCAPTCHA token is missing'
        ], 422);
    }
    
    // Verify with Google
    $response = \Illuminate\Support\Facades\Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $request->ip(),
    ]);
    
    $result = $response->json();
    
    if ($result['success'] ?? false) {
        return response()->json([
            'success' => true,
            'message' => 'reCAPTCHA verification successful!',
            'email' => $request->input('email'),
            'token_length' => strlen($token)
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'reCAPTCHA verification failed',
            'errors' => $result['error-codes'] ?? []
        ], 422);
    }
})->name('test.recaptcha.submit');


// Simple dashboard route
Route::get('/simple-dashboard', function () {
    return view('simple-dashboard');
})->name('simple-dashboard');

// Auth routes (global, not tenant-scoped)
require __DIR__.'/auth.php';

// Global (non-tenant) Breeze compatibility routes
Route::middleware('auth')->group(function () {
    // Minimal dashboard route used by Breeze tests/controllers
    Route::get('/dashboard', function () {
        // Redirect authenticated users to their tenant dashboard
        if (auth()->check()) {
            $user = auth()->user();
            
            // Check if user was trying to access a specific tenant
            $lastTenantSlug = session('last_tenant_slug');
            if ($lastTenantSlug) {
                $tenant = \App\Models\Tenant::where('slug', $lastTenantSlug)->first();
                if ($tenant && $user->tenants->contains($tenant)) {
                    return redirect()->route('tenant.dashboard', $tenant->slug);
                }
            }
            
            // Fallback to first available tenant
            if ($user->tenants->count() > 0) {
                $tenant = $user->tenants->first();
                return redirect()->route('tenant.dashboard', $tenant->slug);
            }
        }
        return view('simple-dashboard');
    })->name('dashboard');

    // Breeze profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public Career Site Routes
Route::prefix('{tenant}/careers')->middleware(['capture.tenant', 'tenant'])->group(function () {
    Route::get('/', [CareerJobController::class, 'index'])->name('careers.index');
    Route::get('/{job:slug}', [CareerJobController::class, 'show'])->name('careers.show');
    Route::get('/{job:slug}/apply', [ApplyController::class, 'create'])->name('careers.apply.create');
    Route::post('/{job:slug}/apply', [ApplyController::class, 'store'])->middleware('subscription.limit:max_applications_per_month')->name('careers.apply.store');
    Route::get('/{job:slug}/success', [ApplyController::class, 'success'])->name('careers.success');
});

// Internal Tenant Management Routes (require authentication)
Route::middleware(['capture.tenant', 'tenant', 'auth'])->group(function () {
    // Dashboard - accessible by all authenticated users with view dashboard permission
    Route::middleware('custom.permission:view_dashboard')->group(function () {
        Route::get('/{tenant}/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/{tenant}/dashboard.json', [DashboardController::class, 'json'])->name('tenant.dashboard.json');
    });

    // Job Management Routes - Owner, Admin, Recruiter
    Route::middleware('custom.permission:view_jobs')->group(function () {
        Route::get('/{tenant}/jobs', [JobController::class, 'index'])->name('tenant.jobs.index');
    });

    Route::middleware(['custom.permission:create_jobs', 'subscription.limit:max_job_openings'])->group(function () {
        Route::get('/{tenant}/jobs/create', [JobController::class, 'create'])->name('tenant.jobs.create');
        Route::post('/{tenant}/jobs', [JobController::class, 'store'])->name('tenant.jobs.store');
        Route::post('/{tenant}/jobs/ai-generate-description', [JobController::class, 'aiGenerateDescription'])->name('tenant.jobs.ai-generate-description');
    });

    Route::middleware('custom.permission:view_jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}', [JobController::class, 'show'])->name('tenant.jobs.show');
    });

    Route::middleware('custom.permission:edit_jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/edit', [JobController::class, 'edit'])->name('tenant.jobs.edit');
        Route::put('/{tenant}/jobs/{job}', [JobController::class, 'update'])->name('tenant.jobs.update');
    });

    Route::middleware('custom.permission:publish_jobs')->group(function () {
        Route::patch('/{tenant}/jobs/{job}/publish', [JobController::class, 'publish'])->name('tenant.jobs.publish');
    });

    Route::middleware('custom.permission:close_jobs')->group(function () {
        Route::patch('/{tenant}/jobs/{job}/close', [JobController::class, 'close'])->name('tenant.jobs.close');
    });

    Route::middleware('custom.permission:delete_jobs')->group(function () {
        Route::delete('/{tenant}/jobs/{job}', [JobController::class, 'destroy'])->name('tenant.jobs.destroy');
    });

    // Job Stage Management Routes - Owner, Admin, Recruiter
    Route::middleware('custom.permission:view_stages')->group(function () {
        Route::get('/{tenant}/jobs/{job}/stages', [JobStageController::class, 'index'])->name('tenant.jobs.stages.index');
    });

    Route::middleware('custom.permission:manage_stages')->group(function () {
        Route::post('/{tenant}/jobs/{job}/stages', [JobStageController::class, 'store'])->name('tenant.jobs.stages.store');
        Route::put('/{tenant}/jobs/{job}/stages/{stage}', [JobStageController::class, 'update'])->name('tenant.jobs.stages.update');
        Route::delete('/{tenant}/jobs/{job}/stages/{stage}', [JobStageController::class, 'destroy'])->name('tenant.jobs.stages.destroy');
        Route::patch('/{tenant}/jobs/{job}/stages/reorder', [JobStageController::class, 'reorder'])->name('tenant.jobs.stages.reorder');
    });

    // Pipeline Management Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware('custom.permission:view_jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/pipeline', [PipelineController::class, 'index'])->name('tenant.jobs.pipeline');
        Route::get('/{tenant}/jobs/{job}/pipeline.json', [PipelineController::class, 'json'])->name('tenant.jobs.pipeline.json');
    });

    Route::middleware('custom.permission:edit_jobs')->group(function () {
        Route::post('/{tenant}/jobs/{job}/pipeline/move', [PipelineController::class, 'move'])->name('tenant.jobs.pipeline.move');
    });

    // Candidate Management Routes - All authenticated users with view candidates permission
    Route::middleware('custom.permission:view_candidates')->group(function () {
        Route::get('/{tenant}/candidates', [CandidateController::class, 'index'])->name('tenant.candidates.index');
        Route::get('/{tenant}/candidates/job/{job}', [CandidateController::class, 'index'])->name('tenant.candidates.index.job');
        Route::get('/{tenant}/candidates/{candidate}', [CandidateController::class, 'show'])->name('tenant.candidates.show');
        Route::get('/{tenant}/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('tenant.candidates.edit');
        Route::put('/{tenant}/candidates/{candidate}', [CandidateController::class, 'update'])->name('tenant.candidates.update');
        
        // Candidate Notes Routes
        Route::post('/{tenant}/candidates/{candidate}/notes', [CandidateNoteController::class, 'store'])->name('tenant.candidates.notes.store');
        Route::delete('/{tenant}/candidates/{candidate}/notes/{note}', [CandidateNoteController::class, 'destroy'])->name('tenant.candidates.notes.destroy');
        
        // Candidate Tags Routes
        Route::get('/{tenant}/tags.json', [CandidateTagController::class, 'index'])->name('tenant.tags.index');
        Route::post('/{tenant}/candidates/{candidate}/tags', [CandidateTagController::class, 'store'])->name('tenant.candidates.tags.store');
        Route::delete('/{tenant}/candidates/{candidate}/tags/{tag}', [CandidateTagController::class, 'destroy'])->name('tenant.candidates.tags.destroy');
        
        // Candidate Resume Routes
        Route::post('/{tenant}/candidates/{candidate}/resumes', [CandidateController::class, 'storeResume'])->name('tenant.candidates.resumes.store');
        Route::delete('/{tenant}/candidates/{candidate}/resumes/{resume}', [CandidateController::class, 'destroyResume'])->name('tenant.candidates.resumes.destroy');
    });

    // Candidate Import Routes - Owner, Admin, Recruiter
    Route::middleware('custom.permission:import_candidates')->group(function () {
        Route::get('/{tenant}/candidates/import', [App\Http\Controllers\Tenant\CandidateImportController::class, 'index'])->name('tenant.candidates.import');
        Route::post('/{tenant}/candidates/import', [App\Http\Controllers\Tenant\CandidateImportController::class, 'store'])->middleware('subscription.limit:max_candidates')->name('tenant.candidates.import.store');
        Route::get('/{tenant}/candidates/import/template', [App\Http\Controllers\Tenant\CandidateImportController::class, 'downloadTemplate'])->name('tenant.candidates.import.template');
    });

    // Interview Management Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware('custom.permission:view_interviews')->group(function () {
        Route::get('/{tenant}/interviews', [InterviewController::class, 'index'])->name('tenant.interviews.index');
        Route::get('/{tenant}/interviews/{interview}', [InterviewController::class, 'show'])->name('tenant.interviews.show');
    });

    Route::middleware(['custom.permission:create_interviews', 'subscription.limit:max_interviews_per_month'])->group(function () {
        Route::get('/{tenant}/candidates/{candidate}/interviews/create', [InterviewController::class, 'create'])->name('tenant.interviews.create');
        Route::post('/{tenant}/candidates/{candidate}/interviews', [InterviewController::class, 'store'])->name('tenant.interviews.store');
        Route::post('/{tenant}/interviews/schedule', [InterviewController::class, 'storeDirect'])->name('tenant.interviews.store-direct');
    });

    Route::middleware('custom.permission:edit_interviews')->group(function () {
        Route::get('/{tenant}/interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('tenant.interviews.edit');
        Route::put('/{tenant}/interviews/{interview}', [InterviewController::class, 'update'])->name('tenant.interviews.update');
        Route::patch('/{tenant}/interviews/{interview}/cancel', [InterviewController::class, 'cancel'])->name('tenant.interviews.cancel');
        Route::patch('/{tenant}/interviews/{interview}/complete', [InterviewController::class, 'complete'])->name('tenant.interviews.complete');
    });

    Route::middleware('custom.permission:delete_interviews')->group(function () {
        Route::delete('/{tenant}/interviews/{interview}', [InterviewController::class, 'destroy'])->name('tenant.interviews.destroy');
    });

    // Analytics Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware(['capture.tenant', 'tenant', 'custom.permission:view_analytics'])->group(function () {
        Route::get('/{tenant}/analytics', [App\Http\Controllers\Tenant\AnalyticsController::class, 'index'])->name('tenant.analytics.index');
        Route::get('/{tenant}/analytics/data', [App\Http\Controllers\Tenant\AnalyticsController::class, 'data'])->name('tenant.analytics.data');
        Route::get('/{tenant}/analytics/export', [App\Http\Controllers\Tenant\AnalyticsController::class, 'export'])->name('tenant.analytics.export');
    });

    // Settings Routes - Users with manage_settings permission
    Route::middleware('custom.permission:manage_settings')->group(function () {
        // Careers Settings
        Route::get('/{tenant}/settings/careers', [CareersSettingsController::class, 'edit'])->name('tenant.settings.careers');
        Route::put('/{tenant}/settings/careers', [CareersSettingsController::class, 'update'])->name('tenant.settings.careers.update');
        
        // Team Management
        Route::get('/{tenant}/settings/team', [App\Http\Controllers\Tenant\UserManagementController::class, 'index'])->name('tenant.settings.team');
        
        // Roles & Permissions
        Route::get('/{tenant}/settings/roles', function() {
            return view('tenant.settings.roles');
        })->name('tenant.settings.roles');
        
        // General Settings
        Route::get('/{tenant}/settings/general', [App\Http\Controllers\Tenant\GeneralSettingsController::class, 'edit'])->name('tenant.settings.general');
        Route::put('/{tenant}/settings/general', [App\Http\Controllers\Tenant\GeneralSettingsController::class, 'update'])->name('tenant.settings.general.update');
        Route::put('/{tenant}/settings/general/smtp', [App\Http\Controllers\Tenant\GeneralSettingsController::class, 'updateSmtp'])->name('tenant.settings.general.smtp');
        Route::post('/{tenant}/settings/general/test-email', [App\Http\Controllers\Tenant\GeneralSettingsController::class, 'testEmail'])->name('tenant.settings.general.test-email');
        Route::post('/{tenant}/settings/general/get-password', [App\Http\Controllers\Tenant\GeneralSettingsController::class, 'getPassword'])->name('tenant.settings.general.get-password');
    });

    // User Management Routes - Users with manage_users permission
    Route::middleware('custom.permission:manage_users')->group(function () {
        Route::post('/{tenant}/users', [App\Http\Controllers\Tenant\UserManagementController::class, 'store'])->middleware('subscription.limit:max_users')->name('tenant.users.store');
        Route::put('/{tenant}/users/{user}', [App\Http\Controllers\Tenant\UserManagementController::class, 'update'])->name('tenant.users.update');
        Route::delete('/{tenant}/users/{user}', [App\Http\Controllers\Tenant\UserManagementController::class, 'destroy'])->name('tenant.users.destroy');
        Route::post('/{tenant}/users/{user}/resend-invitation', [App\Http\Controllers\Tenant\UserManagementController::class, 'resendInvitation'])->name('tenant.users.resend-invitation');
        Route::patch('/{tenant}/users/{user}/toggle-status', [App\Http\Controllers\Tenant\UserManagementController::class, 'toggleStatus'])->name('tenant.users.toggle-status');
    });

    // Subscription Management Routes - Users with manage_users permission
    Route::middleware('custom.permission:manage_users')->group(function () {
        Route::get('/{tenant}/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
        Route::post('/{tenant}/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('/{tenant}/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    });

    // Job Questions Routes - Owner, Admin, Recruiter
    Route::middleware('custom.permission:edit_jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/questions', [JobQuestionsController::class, 'edit'])->name('tenant.jobs.questions');
        Route::put('/{tenant}/jobs/{job}/questions', [JobQuestionsController::class, 'update'])->name('tenant.jobs.questions.update');
    });

    // Email Template Routes - Owner, Admin, Recruiter
    Route::middleware('custom.permission:manage_email_templates')->group(function () {
        Route::get('/{tenant}/email-templates', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'index'])->name('tenant.email-templates.index');
        Route::get('/{tenant}/email-templates/create', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'create'])->name('tenant.email-templates.create');
        Route::post('/{tenant}/email-templates', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'store'])->name('tenant.email-templates.store');
        Route::get('/{tenant}/email-templates/{template}', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'show'])->name('tenant.email-templates.show');
        Route::get('/{tenant}/email-templates/{template}/edit', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'edit'])->name('tenant.email-templates.edit');
        Route::put('/{tenant}/email-templates/{template}', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'update'])->name('tenant.email-templates.update');
        Route::delete('/{tenant}/email-templates/{template}', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'destroy'])->name('tenant.email-templates.destroy');
        Route::get('/{tenant}/email-templates/{template}/preview', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'preview'])->name('tenant.email-templates.preview');
        Route::post('/{tenant}/email-templates/{template}/duplicate', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'duplicate'])->name('tenant.email-templates.duplicate');
        Route::post('/{tenant}/email-templates/load-premade', [App\Http\Controllers\Tenant\EmailTemplateController::class, 'loadPremadeTemplate'])->name('tenant.email-templates.load-premade');
    });

    // Currency Demo Route (for testing)
    Route::get('/{tenant}/currency-demo', function ($tenant) {
        return view('currency-demo');
    })->name('tenant.currency-demo');

    // Account Routes
    Route::prefix('{tenant}/account')->group(function () {
        // Profile routes
        Route::get('/profile', [App\Http\Controllers\Tenant\ProfileController::class, 'index'])->name('account.profile');
        Route::put('/profile', [App\Http\Controllers\Tenant\ProfileController::class, 'update'])->name('account.profile.update');
        Route::put('/profile/password', [App\Http\Controllers\Tenant\ProfileController::class, 'updatePassword'])->name('account.profile.password');
        Route::put('/profile/email', [App\Http\Controllers\Tenant\ProfileController::class, 'updateEmail'])->name('account.profile.email');
        Route::put('/profile/notifications', [App\Http\Controllers\Tenant\ProfileController::class, 'updateNotifications'])->name('account.profile.notifications');
        
        // Settings routes (keeping for backward compatibility)
        Route::get('/settings', [App\Http\Controllers\Tenant\AccountSettingsController::class, 'index'])->name('account.settings');
        Route::put('/settings/notifications', [App\Http\Controllers\Tenant\AccountSettingsController::class, 'updateNotifications'])->name('account.settings.notifications');
        Route::put('/settings/password', [App\Http\Controllers\Tenant\AccountSettingsController::class, 'updatePassword'])->name('account.settings.password');
        Route::put('/settings/email', [App\Http\Controllers\Tenant\AccountSettingsController::class, 'updateEmail'])->name('account.settings.email');
    });
});

// API Routes
Route::prefix('api')->group(function () {
    Route::get('/applications', function (Request $request) {
        $applications = Application::with(['candidate', 'jobOpening'])
            ->when($request->job_id, function ($query, $jobId) {
                return $query->where('job_opening_id', $jobId);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($applications);
    });
});

// Add GET logout route for both testing and production
Route::get('/logout', function() {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    // Clear tenant-specific session data
    request()->session()->forget('last_tenant_slug');
    request()->session()->forget('current_tenant_id');
    return redirect('/');
})->name('logout.get');

// Onboarding routes (global, not tenant-scoped)
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/organization', [App\Http\Controllers\Onboarding\CustomOrganizationController::class, 'create'])->name('onboarding.organization');
    Route::post('/onboarding/organization', [App\Http\Controllers\Onboarding\CustomOrganizationController::class, 'store'])->name('onboarding.organization.store');
});

// Onboarding setup routes (tenant-scoped)
Route::middleware(['auth', 'capture.tenant', 'tenant'])->group(function () {
    Route::get('/{tenant}/onboarding/setup', [App\Http\Controllers\Onboarding\SetupController::class, 'index'])->name('onboarding.setup');
    Route::post('/{tenant}/onboarding/setup', [App\Http\Controllers\Onboarding\SetupController::class, 'store'])->name('onboarding.setup.store');
});

