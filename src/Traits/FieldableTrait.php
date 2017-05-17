<?php

namespace Laravel\ProductFields\Traits;

use Laravel\ProductFields\Facades\ModelsResolver;

trait FieldableTrait
{
    /**
     * Resource fields.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function fields()
    {
        return $this->morphToMany(ModelsResolver::field(), ModelsResolver::morphName());
    }
}
