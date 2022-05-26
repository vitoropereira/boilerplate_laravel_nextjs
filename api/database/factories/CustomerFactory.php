<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf' => $this->faker->numberBetween(11111111111, 99999999999),
            'cnpj' => $this->faker->numberBetween(11111111111111, 9999999999999),
            'rg' => $this->faker->numberBetween(11111, 99999),
            'passport' => $this->faker->numberBetween(11111, 99999),
            'birth_date' => $this->faker->date('Y-m-d'),
            'phone' => $this->faker->phoneNumber(),
        ];
    }
}
