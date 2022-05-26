<?php

namespace Database\Factories;

use App\Models\TourDestination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'tour_destination_uuid' => TourDestination::factory(),
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text(200),
            'available' => true,
            'meta_title' => $this->faker->text(200),
            'meta_description' => $this->faker->text(200),
        ];
    }
}
