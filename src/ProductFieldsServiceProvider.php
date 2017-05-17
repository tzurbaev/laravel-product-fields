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
        $this->publishes([
            realpath(__DIR__.'/../config/product-fields.php') => config_path('product-fields.php'),
        ], 'config');

        $this->publishes([
            realpath(__DIR__.'/../database/migrations') => database_path('migrations'),
        ], 'migrations');

        $this->mergeConfigFrom(realpath(__DIR__.'/../config/product-fields.php'), 'product-fields');
    }
}
