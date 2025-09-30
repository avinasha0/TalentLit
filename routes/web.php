<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Career\ApplyController;
use App\Http\Controllers\Career\CareerJobController;
use App\Http\Controllers\Tenant\CandidateController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\JobController;
use App\Http\Controllers\Tenant\JobStageController;
use App\Http\Controllers\Tenant\PipelineController;
use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

// Public route
Route::get('/', function () {
    return view('home');
})->name('home');

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
Route::middleware(['tenant', 'auth'])->group(function () {
    // Dashboard - accessible by all authenticated users with view dashboard permission
    Route::middleware('permission:view dashboard')->group(function () {
        Route::get('/{tenant}/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
        Route::get('/{tenant}/dashboard.json', [DashboardController::class, 'json'])->name('tenant.dashboard.json');
    });

    // Job Management Routes - Owner, Admin, Recruiter
    Route::middleware('permission:view jobs')->group(function () {
        Route::get('/{tenant}/jobs', [JobController::class, 'index'])->name('tenant.jobs.index');
        Route::get('/{tenant}/jobs/{job}', [JobController::class, 'show'])->name('tenant.jobs.show');
    });

    Route::middleware('permission:create jobs')->group(function () {
        Route::get('/{tenant}/jobs/create', [JobController::class, 'create'])->name('tenant.jobs.create');
        Route::post('/{tenant}/jobs', [JobController::class, 'store'])->name('tenant.jobs.store');
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
