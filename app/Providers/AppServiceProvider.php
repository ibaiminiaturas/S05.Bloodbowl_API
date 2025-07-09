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
              if (app()->environment('production')) {
        $privateKey = storage_path('oauth-private.key');
        $publicKey = storage_path('oauth-public.key');

        if (File::exists($privateKey)) {
            @chmod($privateKey, 0660);
        }

        if (File::exists($publicKey)) {
            @chmod($publicKey, 0660);
        }
    }
    }
}
