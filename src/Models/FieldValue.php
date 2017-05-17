<?php

namespace Laravel\ProductFields\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\ProductFields\Traits\FieldValueTrait;

class FieldValue extends Model
{
    use FieldValueTrait;

    /**
     * @var array
     */
    protected $fillable = ['field_id', 'value'];
}
