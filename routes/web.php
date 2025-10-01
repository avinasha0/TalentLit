<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Career\ApplyController;
use App\Http\Controllers\Career\CareerJobController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SubscriptionController;
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

// Contact routes
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Subscription routes (public)
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('subscription.pricing');

// SEO Routes
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Test route
Route::get('/test', function () {
    return view('test');
})->name('test');

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
    Route::post('/{job:slug}/apply', [ApplyController::class, 'store'])->name('careers.apply.store');
    Route::get('/{job:slug}/success', [ApplyController::class, 'success'])->name('careers.success');
});

// Internal Tenant Management Routes (require authentication)
Route::middleware(['capture.tenant', 'tenant', 'auth'])->group(function () {
    // Dashboard - accessible by all authenticated users with view dashboard permission
    Route::middleware('permission:view dashboard')->group(function () {
        Route::get('/{tenant}/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/{tenant}/dashboard.json', [DashboardController::class, 'json'])->name('tenant.dashboard.json');
    });

    // Job Management Routes - Owner, Admin, Recruiter
    Route::middleware('permission:view jobs')->group(function () {
        Route::get('/{tenant}/jobs', [JobController::class, 'index'])->name('tenant.jobs.index');
    });

    Route::middleware(['permission:create jobs', 'subscription.limit:max_job_openings'])->group(function () {
        Route::get('/{tenant}/jobs/create', [JobController::class, 'create'])->name('tenant.jobs.create');
        Route::post('/{tenant}/jobs', [JobController::class, 'store'])->name('tenant.jobs.store');
    });

    Route::middleware('permission:view jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}', [JobController::class, 'show'])->name('tenant.jobs.show');
    });

    Route::middleware('permission:edit jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/edit', [JobController::class, 'edit'])->name('tenant.jobs.edit');
        Route::put('/{tenant}/jobs/{job}', [JobController::class, 'update'])->name('tenant.jobs.update');
    });

    Route::middleware('permission:publish jobs')->group(function () {
        Route::patch('/{tenant}/jobs/{job}/publish', [JobController::class, 'publish'])->name('tenant.jobs.publish');
    });

    Route::middleware('permission:close jobs')->group(function () {
        Route::patch('/{tenant}/jobs/{job}/close', [JobController::class, 'close'])->name('tenant.jobs.close');
    });

    Route::middleware('permission:delete jobs')->group(function () {
        Route::delete('/{tenant}/jobs/{job}', [JobController::class, 'destroy'])->name('tenant.jobs.destroy');
    });

    // Job Stage Management Routes - Owner, Admin, Recruiter
    Route::middleware('permission:view stages')->group(function () {
        Route::get('/{tenant}/jobs/{job}/stages', [JobStageController::class, 'index'])->name('tenant.jobs.stages.index');
    });

    Route::middleware('permission:manage stages')->group(function () {
        Route::post('/{tenant}/jobs/{job}/stages', [JobStageController::class, 'store'])->name('tenant.jobs.stages.store');
        Route::put('/{tenant}/jobs/{job}/stages/{stage}', [JobStageController::class, 'update'])->name('tenant.jobs.stages.update');
        Route::delete('/{tenant}/jobs/{job}/stages/{stage}', [JobStageController::class, 'destroy'])->name('tenant.jobs.stages.destroy');
        Route::patch('/{tenant}/jobs/{job}/stages/reorder', [JobStageController::class, 'reorder'])->name('tenant.jobs.stages.reorder');
    });

    // Pipeline Management Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware('permission:view jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/pipeline', [PipelineController::class, 'index'])->name('tenant.jobs.pipeline');
        Route::get('/{tenant}/jobs/{job}/pipeline.json', [PipelineController::class, 'json'])->name('tenant.jobs.pipeline.json');
    });

    Route::middleware('permission:edit jobs')->group(function () {
        Route::post('/{tenant}/jobs/{job}/pipeline/move', [PipelineController::class, 'move'])->name('tenant.jobs.pipeline.move');
    });

    // Candidate Management Routes - All authenticated users with view candidates permission
    Route::middleware('permission:view candidates')->group(function () {
        Route::get('/{tenant}/candidates', [CandidateController::class, 'index'])->name('tenant.candidates.index');
        Route::get('/{tenant}/candidates/job/{job}', [CandidateController::class, 'index'])->name('tenant.candidates.index.job');
        Route::get('/{tenant}/candidates/{candidate}', [CandidateController::class, 'show'])->name('tenant.candidates.show');
        
        // Candidate Notes Routes
        Route::post('/{tenant}/candidates/{candidate}/notes', [CandidateNoteController::class, 'store'])->name('tenant.candidates.notes.store');
        Route::delete('/{tenant}/candidates/{candidate}/notes/{note}', [CandidateNoteController::class, 'destroy'])->name('tenant.candidates.notes.destroy');
        
        // Candidate Tags Routes
        Route::get('/{tenant}/tags.json', [CandidateTagController::class, 'index'])->name('tenant.tags.index');
        Route::post('/{tenant}/candidates/{candidate}/tags', [CandidateTagController::class, 'store'])->name('tenant.candidates.tags.store');
        Route::delete('/{tenant}/candidates/{candidate}/tags/{tag}', [CandidateTagController::class, 'destroy'])->name('tenant.candidates.tags.destroy');
    });

    // Interview Management Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware('permission:view interviews')->group(function () {
        Route::get('/{tenant}/interviews', [InterviewController::class, 'index'])->name('tenant.interviews.index');
        Route::get('/{tenant}/interviews/{interview}', [InterviewController::class, 'show'])->name('tenant.interviews.show');
    });

    Route::middleware(['permission:create interviews', 'subscription.limit:max_interviews_per_month'])->group(function () {
        Route::get('/{tenant}/candidates/{candidate}/interviews/create', [InterviewController::class, 'create'])->name('tenant.interviews.create');
        Route::post('/{tenant}/candidates/{candidate}/interviews', [InterviewController::class, 'store'])->name('tenant.interviews.store');
        Route::post('/{tenant}/interviews/schedule', [InterviewController::class, 'storeDirect'])->name('tenant.interviews.store-direct');
    });

    Route::middleware('permission:edit interviews')->group(function () {
        Route::get('/{tenant}/interviews/{interview}/edit', [InterviewController::class, 'edit'])->name('tenant.interviews.edit');
        Route::put('/{tenant}/interviews/{interview}', [InterviewController::class, 'update'])->name('tenant.interviews.update');
        Route::patch('/{tenant}/interviews/{interview}/cancel', [InterviewController::class, 'cancel'])->name('tenant.interviews.cancel');
        Route::patch('/{tenant}/interviews/{interview}/complete', [InterviewController::class, 'complete'])->name('tenant.interviews.complete');
    });

    Route::middleware('permission:delete interviews')->group(function () {
        Route::delete('/{tenant}/interviews/{interview}', [InterviewController::class, 'destroy'])->name('tenant.interviews.destroy');
    });

    // Analytics Routes - Owner, Admin, Recruiter, Hiring Manager
    Route::middleware('permission:view analytics')->group(function () {
        Route::get('/{tenant}/analytics', [App\Http\Controllers\Tenant\AnalyticsController::class, 'index'])->name('tenant.analytics.index');
        Route::get('/{tenant}/analytics/data', [App\Http\Controllers\Tenant\AnalyticsController::class, 'data'])->name('tenant.analytics.data');
        Route::get('/{tenant}/analytics/export', [App\Http\Controllers\Tenant\AnalyticsController::class, 'export'])->name('tenant.analytics.export');
    });

    // Settings Routes - Owner, Admin only
    Route::middleware('role:Owner|Admin')->group(function () {
        // Careers Settings
        Route::get('/{tenant}/settings/careers', [CareersSettingsController::class, 'edit'])->name('tenant.settings.careers');
        Route::put('/{tenant}/settings/careers', [CareersSettingsController::class, 'update'])->name('tenant.settings.careers.update');
        
        // Team Management
        Route::get('/{tenant}/settings/team', function() {
            return view('tenant.settings.team');
        })->name('tenant.settings.team');
        
        // Roles & Permissions
        Route::get('/{tenant}/settings/roles', function() {
            return view('tenant.settings.roles');
        })->name('tenant.settings.roles');
        
        // General Settings
        Route::get('/{tenant}/settings/general', function() {
            return view('tenant.settings.general');
        })->name('tenant.settings.general');
    });

    // Subscription Management Routes - Owner only
    Route::middleware('role:Owner')->group(function () {
        Route::get('/{tenant}/subscription', [SubscriptionController::class, 'show'])->name('subscription.show');
        Route::post('/{tenant}/subscription/subscribe', [SubscriptionController::class, 'subscribe'])->name('subscription.subscribe');
        Route::post('/{tenant}/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    });

    // Job Questions Routes - Owner, Admin, Recruiter
    Route::middleware('permission:edit jobs')->group(function () {
        Route::get('/{tenant}/jobs/{job}/questions', [JobQuestionsController::class, 'edit'])->name('tenant.jobs.questions');
        Route::put('/{tenant}/jobs/{job}/questions', [JobQuestionsController::class, 'update'])->name('tenant.jobs.questions.update');
    });

    // Account Routes
    Route::prefix('{tenant}/account')->group(function () {
        Route::view('/profile', 'tenant.account.profile')->name('account.profile');
        Route::view('/settings', 'tenant.account.settings')->name('account.settings');
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
    return redirect('/');
})->name('logout.get');

// Onboarding routes (global, not tenant-scoped)
Route::middleware(['auth'])->group(function () {
    Route::get('/onboarding/organization', [App\Http\Controllers\Onboarding\OrganizationController::class, 'create'])->name('onboarding.organization');
    Route::post('/onboarding/organization', [App\Http\Controllers\Onboarding\OrganizationController::class, 'store'])->name('onboarding.organization.store');
    Route::get('/{tenant}/onboarding/setup', [App\Http\Controllers\Onboarding\SetupController::class, 'index'])->name('onboarding.setup');
    Route::post('/{tenant}/onboarding/setup', [App\Http\Controllers\Onboarding\SetupController::class, 'store'])->name('onboarding.setup.store');
});
