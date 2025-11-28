<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;

class ProductTest extends TestCase
{
    /**
     * Test that product cost attribute multiplies by 100 on set
     */
    public function test_product_cost_attribute_multiplies_by_100_on_set()
    {
        $product = new Product();
        $product->product_cost = 10.50;

        $this->assertEquals(1050, $product->attributes['product_cost']);
    }

    /**
     * Test that product cost attribute divides by 100 on get
     */
    public function test_product_cost_attribute_divides_by_100_on_get()
    {
        $product = new Product();
        $product->attributes['product_cost'] = 1050;

        $this->assertEquals(10.50, $product->product_cost);
    }

    /**
     * Test that product price attribute multiplies by 100 on set
     */
    public function test_product_price_attribute_multiplies_by_100_on_set()
    {
        $product = new Product();
        $product->product_price = 25.99;

        $this->assertEquals(2599, $product->attributes['product_price']);
    }

    /**
     * Test that product price attribute divides by 100 on get
     */
    public function test_product_price_attribute_divides_by_100_on_get()
    {
        $product = new Product();
        $product->attributes['product_price'] = 2599;

        $this->assertEquals(25.99, $product->product_price);
    }

    /**
     * Test product cost conversion with zero value
     */
    public function test_product_cost_with_zero_value()
    {
        $product = new Product();
        $product->product_cost = 0;

        $this->assertEquals(0, $product->attributes['product_cost']);
        $this->assertEquals(0, $product->product_cost);
    }

    /**
     * Test product price conversion with decimal precision
     */
    public function test_product_price_with_decimal_precision()
    {
        $product = new Product();
        $product->product_price = 99.99;

        $this->assertEquals(9999, $product->attributes['product_price']);
        $this->assertEquals(99.99, $product->product_price);
    }

    /**
     * Test product cost with large values
     */
    public function test_product_cost_with_large_values()
    {
        $product = new Product();
        $product->product_cost = 1000.50;

        $this->assertEquals(100050, $product->attributes['product_cost']);
        $this->assertEquals(1000.50, $product->product_cost);
    }

    /**
     * Test product price with large values
     */
    public function test_product_price_with_large_values()
    {
        $product = new Product();
        $product->product_price = 5000.75;

        $this->assertEquals(500075, $product->attributes['product_price']);
        $this->assertEquals(5000.75, $product->product_price);
    }
}
