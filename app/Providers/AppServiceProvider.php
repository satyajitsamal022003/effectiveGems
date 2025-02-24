<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        //
        Paginator::useBootstrap();
        view()->composer('*', function ($view) {
            $annoument_text = Setting::first(); // Fetch the setting
            $view->with('annoument_text', $annoument_text); // Pass it to all views
        });
    }
}
