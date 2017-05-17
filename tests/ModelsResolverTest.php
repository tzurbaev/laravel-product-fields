<?php

namespace Tests;

use Laravel\ProductFields\ModelsResolver;

class ModelsResolverTest extends TestCase
{
    public function testFieldModel()
    {
        $model = 'App\Field';
        config(['product-fields.models.field' => $model]);

        $resolver = app(ModelsResolver::class);
        $this->assertSame($model, $resolver->field());
    }

    public function testFieldValueModel()
    {
        $model = 'App\FieldValue';
        config(['product-fields.models.field_value' => $model]);

        $resolver = app(ModelsResolver::class);
        $this->assertSame($model, $resolver->fieldValue());
    }
}
