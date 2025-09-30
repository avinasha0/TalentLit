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

// Simple dashboard route
Route::get('/simple-dashboard', function () {
    return view('simple-dashboard');
})->name('simple-dashboard');

// Auth routes (global, not tenant-scoped)
require __DIR__.'/auth.php';

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
    Route::get('/{tenant}/dashboard', [DashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/{tenant}/dashboard.json', [DashboardController::class, 'json'])->name('tenant.dashboard.json');

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
