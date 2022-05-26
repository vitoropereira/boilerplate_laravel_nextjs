<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\CountryRegion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\State>
 */
class StateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'country_region_id' => CountryRegion::factory(),
            'ibge_state_id' => $this->faker->numberBetween(12, 2334),
            'name' => $this->faker->name,
            'initials' => $this->faker->countryCode,
        ];
    }
}
