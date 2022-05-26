<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country_Region>
 */
class CountryRegionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'ibge_country_regions_id' => $this->faker->numberBetween(12, 2334),
            'name' => $this->faker->name,
            'initials' => $this->faker->countryCode,
        ];
    }
}
