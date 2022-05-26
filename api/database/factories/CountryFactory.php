<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'code' => $this->faker->citySuffix,
            'phone_code' => $this->faker->phoneNumber,
            'currency_code' => $this->faker->currencyCode,
            'currency_symbol' => '$',
            'lang_code' => $this->faker->languageCode
        ];
    }
}
