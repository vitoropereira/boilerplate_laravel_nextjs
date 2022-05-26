<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Country;
use App\Models\CountryRegion;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TourDestination>
 */
class TourDestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country' =>$this->faker->country,
            'country_region' =>$this->faker->name,
            'state' =>$this->faker->name,
            'city' =>$this->faker->city,
            'name' => $this->faker->name,
            'slug' => $this->faker->slug,
            'description' => $this->faker->text(200),
        ];
    }
}
