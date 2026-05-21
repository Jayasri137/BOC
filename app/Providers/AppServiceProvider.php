<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        view()->composer('*', function ($view) {

            $storageLink = true;
            // check if storage folder exists
            if (file_exists(public_path('storage'))) {
                $storageLink = false;
            }

            $view->with('storageLink', $storageLink);
        });

        Paginator::useBootstrapFive();
    }
}
