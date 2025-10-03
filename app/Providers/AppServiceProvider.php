<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use App\Models\Candidate;
use App\Models\CandidateNote;
use App\Models\Interview;
use App\Models\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register layout components
        $this->loadViewComponentsAs('', [
            'app-layout' => \App\View\Components\AppLayout::class,
            'guest-layout' => \App\View\Components\GuestLayout::class,
            'public-layout' => \App\View\Components\PublicLayout::class,
        ]);

        // Route model binding for candidates (without tenant scoping - handled in controller)
        Route::bind('candidate', function ($value) {
            return Candidate::findOrFail($value);
        });

        // Route model binding for notes (tenant scoping handled in controller)
        Route::bind('note', function ($value) {
            return CandidateNote::findOrFail($value);
        });

        // Route model binding for tags (tenant scoping handled in controller)
        Route::bind('tag', function ($value) {
            return Tag::findOrFail($value);
        });

        // Route model binding for interviews (tenant scoping handled in controller)
        Route::bind('interview', function ($value) {
            return Interview::findOrFail($value);
        });


        // Hard-disable @vite in testing environment (belt-and-suspenders safety)
        if (app()->environment('testing')) {
            Blade::directive('vite', fn () => ''); // hard-disable @vite in tests
        }
    }
}
