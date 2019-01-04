<?php

namespace App\Providers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('shopify_domain', function ($attribute, $value, $parameters, $validator) {
            $pattern = '/^([A-Z]|[a-z]|[0-9]|\-)+.myshopify.com$/';
            return preg_match($pattern, $value) === 1;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
