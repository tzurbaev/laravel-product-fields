<?php

namespace Laravel\ProductFields\Facades;

use Illuminate\Support\Facades\Facade;
use Laravel\ProductFields\ModelsResolver as ModelsResolverAccessor;

class ModelsResolver extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return ModelsResolverAccessor::class;
    }
}
