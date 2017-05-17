<?php

namespace Laravel\ProductFields\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\ProductFields\Traits\FieldTrait;

class Field extends Model
{
    use FieldTrait;

    /**
     * @var array
     */
    protected $fillable = ['name'];
}
