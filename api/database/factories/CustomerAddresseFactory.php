<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerAddresse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerAddresse>
 */
class CustomerAddresseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'customer_id' => Customer::factory(),
            'type' => CustomerAddresse::BILLING_AND_DELIVERY_ADDRESS,
            'name' => $this->faker->name,
            'address1' => $this->faker->streetAddress,
            'address2' => $this->faker->streetAddress,
            'postcode' => $this->faker->randomNumber(8),
            'neighborhood' => $this->faker->address,
            'city' => $this->faker->city,
            'state' => $this->faker->name,
            'country' => $this->faker->country
        ];
    }
}
