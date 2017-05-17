<?php

return [
    'models' => [
        'field' => Laravel\ProductFields\Models\Field::class,
        'field_value' => Laravel\ProductFields\Models\FieldValue::class,
    ],

    'morph_name' => 'fieldable',
];
