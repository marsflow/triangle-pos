<?php

namespace Modules\Product\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'product_name' => $this->faker->word(),
            'product_code' => $this->faker->unique()->ean8(),
            'product_barcode_symbology' => 'Code128',
            'product_quantity' => $this->faker->numberBetween(1, 1000),
            'product_cost' => $this->faker->numberBetween(1000, 50000), // In cents
            'product_price' => $this->faker->numberBetween(1000, 100000), // In cents
            'product_unit' => $this->faker->randomElement(['piece', 'kg', 'liter', 'meter']),
            'product_stock_alert' => $this->faker->numberBetween(5, 50),
            'product_order_tax' => $this->faker->numberBetween(0, 20),
            'product_tax_type' => $this->faker->randomElement([0, 1, 2]),
            'product_note' => $this->faker->optional()->sentence(),
        ];
    }
}
