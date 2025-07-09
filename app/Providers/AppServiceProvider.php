<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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

        $keyPath = storage_path('oauth-private.key');

        if (file_exists($keyPath)) {
            @chmod($keyPath, 0600);
        }

        $pubKeyPath = storage_path('oauth-public.key');

        if (file_exists($pubKeyPath)) {
            @chmod($pubKeyPath, 0600);
        }

    }
}
