<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Public route
Route::get('/', function () {
    return 'HireHub is running';
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tenant routes
Route::middleware(['tenant', 'auth'])->group(function () {
    Route::get('/{tenant}/dashboard', function () {
        $tenant = currentTenant();
        $user = auth()->user();

        return view('tenant.dashboard', compact('tenant', 'user'));
    });
});
