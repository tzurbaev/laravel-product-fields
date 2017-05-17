<?php

namespace Laravel\ProductFields\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\ProductFields\Traits\FieldableTrait;

class Product extends Model
{
    use FieldableTrait;

    /**
     * @var array
     */
    protected $fillable = ['name'];
}
