<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Career\ApplyController;
use App\Http\Controllers\Career\CareerJobController;
use App\Http\Controllers\Tenant\CandidateController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\JobController;
use App\Models\Application;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public route
Route::get('/', function () {
    return view('home');
})->name('home');

// Test route
Route::get('/test', function () {
    return view('test');
})->name('test');

// Simple dashboard test
Route::get('/simple-dashboard', function () {
    return view('simple-dashboard');
})->name('simple-dashboard');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Career Site Routes
Route::prefix('{tenant}/careers')->middleware(['tenant'])->group(function () {
    Route::get('/', [CareerJobController::class, 'index'])->name('careers.index');
    Route::get('/{job:slug}', [CareerJobController::class, 'show'])->name('careers.show');
    Route::get('/{job:slug}/apply', [ApplyController::class, 'create'])->name('careers.apply.create');
    Route::post('/{job:slug}/apply', [ApplyController::class, 'store'])->name('careers.apply.store');
    Route::get('/{job:slug}/success', [ApplyController::class, 'success'])->name('careers.success');
});

// Internal Tenant Management Routes
Route::middleware(['tenant', 'auth'])->group(function () {
    Route::get('/{tenant}/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');

    // Job Management Routes
    Route::get('/{tenant}/jobs', [JobController::class, 'index'])->name('tenant.jobs.index');
    Route::get('/{tenant}/jobs/{job}', [JobController::class, 'show'])->name('tenant.jobs.show');

    // Candidate Management Routes
    Route::get('/{tenant}/candidates', [CandidateController::class, 'index'])->name('tenant.candidates.index');
    Route::get('/{tenant}/candidates/{candidate}', [CandidateController::class, 'show'])->name('tenant.candidates.show');

    // Account Routes
    Route::prefix('{tenant}/account')->group(function () {
        Route::view('/profile', 'tenant.account.profile')->name('account.profile');
        Route::view('/settings', 'tenant.account.settings')->name('account.settings');
    });

    // Legacy Application API Route (for backward compatibility)
    Route::post('/{tenant}/applications', function (Request $request) {
        $request->validate([
            'job_opening_id' => 'required|exists:job_openings,id',
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $application = Application::create([
            'job_opening_id' => $request->job_opening_id,
            'candidate_id' => $request->candidate_id,
            'status' => 'active',
            'applied_at' => now(),
        ]);

        return response()->json([
            'message' => 'Application created successfully',
            'application' => $application,
        ], 201);
    });
});
