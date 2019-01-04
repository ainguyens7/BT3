<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CacheFrontend\CacheFrontend;

class CacheFrontendProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CacheFrontend', 'App\Services\CacheFrontend\CacheFrontend');
    }
}
