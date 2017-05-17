<?php

namespace Laravel\ProductFields;

use Illuminate\Support\ServiceProvider;

class ProductFieldsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));
        $this->mergeConfigFrom(realpath(__DIR__.'/../config/product-fields.php'), 'product-fields');
    }
}
