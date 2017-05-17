<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravel\ProductFields\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * @var Carbon
     */
    protected $now;

    /**
     * @var array
     */
    protected $products = [
        [
            'id' => 1,
            'name' => 'iPhone 6 32GB',
            'fields' => [
                1 => 1, // Operating System: iOS
                2 => 5, // RAM Size: 1GB
                3 => 8, // Storage Size: 32GB
                4 => 12, // Screen Resolution: 375x667
                5 => 21, // CPU Cores Count: 2
            ],
        ],
        [
            'id' => 2,
            'name' => 'iPhone 7 32GB',
            'fields' => [
                1 => 1, // Operating System: iOS
                2 => 6, // RAM Size: 1GB
                3 => 8, // Storage Size: 32GB
                4 => 18, // Screen Resolution: 375x667
                5 => 21, // CPU Cores Count: 2
            ],
        ],
        [
            'id' => 3,
            'name' => 'Samsung Galaxy S7',
            'fields' => [
                1 => 2, // Operating System: Android
                2 => 7, // RAM Size: 2GB
                3 => 8, // Storage Size: 32GB
                4 => 19, // Screen Resolution: 1920x1080
                5 => 23, // CPU Cores Count: 6
            ],
        ],
        [
            'id' => 4,
            'name' => 'Samsung Galaxy S8',
            'fields' => [
                1 => 2, // Operating System: Android
                2 => 7, // RAM Size: 2GB
                3 => 9, // Storage Size: 64GB
                4 => 19, // Screen Resolution: 1920x1080
                5 => 24, // CPU Cores Count: 8
            ],
        ],
    ];

    /**
     * Run the products seeder.
     */
    public function run()
    {
        $this->now = Carbon::now();

        foreach ($this->products as $product) {
            DB::table('products')->insert($this->createProduct($product));
            DB::table('fieldables')->insert($this->productFields($product['id'], $product['fields']));
        }
    }

    /**
     * @param array $product
     *
     * @return array
     */
    protected function createProduct(array $product)
    {
        return [
            'id' => $product['id'],
            'name' => $product['name'],
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];
    }

    /**
     * @param int $productId
     * @param array $fields
     *
     * @return array
     */
    protected function productFields(int $productId, array $fields)
    {
        $productFields = [];

        foreach ($fields as $fieldId => $valueId) {
            $productFields[] = [
                'field_id' => $fieldId,
                'field_value_id' => $valueId,
                'fieldable_type' => Product::class,
                'fieldable_id' => $productId,
            ];
        }

        return $productFields;
    }
}
