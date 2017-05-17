<?php

namespace Laravel\ProductFields\Traits;

use Laravel\ProductFields\Facades\ModelsResolver;

trait FieldValueTrait
{
    /**
     * Parent Field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function field()
    {
        return $this->belongsTo(ModelsResolver::field());
    }
}
