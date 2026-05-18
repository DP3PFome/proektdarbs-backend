<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

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
        // Ensure API responses always include JSON content type
        Request::macro('expectsJson', function () {
            return true;
        });

        // Set JSON response headers globally
        if (request()->is('api/*')) {
            header('Content-Type: application/json');
            header('Accept: application/json');
        }
    }
}

