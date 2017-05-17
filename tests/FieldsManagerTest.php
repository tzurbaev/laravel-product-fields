<?php

namespace Tests;

use Laravel\ProductFields\FieldsManager;
use Laravel\ProductFields\Models\Field;
use Laravel\ProductFields\Models\FieldValue;
use Laravel\ProductFields\Models\Product;

class FieldsManagerTest extends TestCase
{
    public function testItLoadsModelsByFieldValuesCollection()
    {
        $field = Field::where('name', 'Operating System')->first();
        $this->assertInstanceOf(Field::class, $field);

        $value = FieldValue::where('value', 'iOS')->where('field_id', $field->id)->first();
        $this->assertInstanceOf(FieldValue::class, $value);

        $manager = app(FieldsManager::class);
        $results = $manager->getResourcesByFieldValues(collect([$value]));

        $this->assertSame(1, count($results));
        $this->assertTrue($results->has('products'));
        $this->assertSame(2, count($results->get('products')));
    }

    public function testItLoadsModelsByFieldIdFieldValueIdMaps()
    {
        $field = Field::where('name', 'Operating System')->first();
        $this->assertInstanceOf(Field::class, $field);

        $ios = FieldValue::where('value', 'iOS')->where('field_id', $field->id)->first();
        $this->assertInstanceOf(FieldValue::class, $ios);

        $android = FieldValue::where('value', 'Android')->where('field_id', $field->id)->first();
        $this->assertInstanceOf(FieldValue::class, $android);

        $filters = [
            [
                'filter' => [$field->id => $ios->id],
                'expected' => 2,
            ],
            [
                'filter' => [$field->id => $android->id],
                'expected' => 2,
            ],
            [
                'filter' => [$field->id => [$ios->id]],
                'expected' => 2,
            ],
            [
                'filter' => [$field->id => [$android->id]],
                'expected' => 2,
            ],
            [
                'filter' => [$field->id => [$ios->id, $android->id]],
                'expected' => 4,
            ],
        ];

        $manager = app(FieldsManager::class);

        collect($filters)->each(function (array $filter) use ($manager) {
            $results = $manager->filterResources(collect($filter['filter']));

            $this->assertTrue($results->has('products'));
            $this->assertSame($filter['expected'], count($results->get('products')));
        });
    }

    public function testFieldValuesCanBeDetachedAndSynced()
    {
        $field = Field::where('name', 'Operating System')->first();
        $this->assertInstanceOf(Field::class, $field);

        $ios = FieldValue::where('value', 'iOS')->where('field_id', $field->id)->first();
        $this->assertInstanceOf(FieldValue::class, $ios);

        $android = FieldValue::where('value', 'Android')->where('field_id', $field->id)->first();
        $this->assertInstanceOf(FieldValue::class, $android);

        $product = Product::first();
        $this->assertInstanceOf(Product::class, $product);

        $manager = app(FieldsManager::class);

        $this->assertTrue($product->fields()->count() > 0);
        $fieldIds = $product->fields()->get()->pluck('id')->toArray();

        $manager->detachFields($product, $fieldIds);
        $this->assertSame(0, $product->fields()->count());

        $manager->syncFields($product, [$field->id => $ios->id]);
        $this->assertSame(1, $product->fields()->count());
    }
}
