<?php

namespace Laravel\ProductFields\Traits;

use Laravel\ProductFields\Facades\ModelsResolver;

trait FieldTrait
{
    /**
     * Field values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function fieldValues()
    {
        return $this->hasMany(ModelsResolver::fieldValue());
    }
}
