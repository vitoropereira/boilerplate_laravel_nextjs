<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductPrice>
 */
class ProductPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'description' => $this->faker->text(200),
            'max_people_quantity' => $this->faker->numberBetween(1, 10),
            'cost_price' => $this->faker->numberBetween(10_00, 200_00),
            'sale_price' => $this->faker->numberBetween(15_00, 350_00),
            'available' => true
        ];
    }
}
