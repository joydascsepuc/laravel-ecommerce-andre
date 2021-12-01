<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// add this to use bootstrap in the pagination
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Using this to add style in Paginator
        Paginator::useBootstrap();
    }

}
