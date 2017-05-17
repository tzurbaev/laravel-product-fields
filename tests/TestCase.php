<?php

namespace Tests;

use Laravel\ProductFields\Models\Product;
use Laravel\ProductFields\ProductFieldsServiceProvider;
use Laravel\ProductFields\ResourcesResolver;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        (new \CreateFieldsTable())->up();
        (new \CreateFieldValuesTable())->up();
        (new \CreateFieldablesTable())->up();
        (new \CreateProductsTable())->up();

        (new \FieldsSeeder())->run();
        (new \ProductsSeeder())->run();

        ResourcesResolver::registerResolver(Product::class, 'products', function (array $ids) {
            return Product::whereIn('id', $ids)->get();
        });
    }

    /**
     * {@inheritdoc}
     */
    protected function getPackageProviders($app)
    {
        return [ProductFieldsServiceProvider::class];
    }

    /**
     * {@inheritdoc}
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
