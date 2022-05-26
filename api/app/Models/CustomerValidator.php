<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class CustomerValidator extends Model
{
    use HasFactory;

    public function validate(Customer $customer, array $attributes): array
    {
        return validator(
            request()->all(),
            [
                'name' => [Rule::when($customer->exists, 'sometimes'), 'string', 'required'],
                'email' => [Rule::when($customer->exists, 'sometimes'), 'string', 'required'],

                'cpf' => ['string'],
                'cnpj' => ['string'],
                'rg' => ['string'],
                'passport' => ['string'],
                'birth_date' => ['date'],
                'phone' => ['string']
            ]
        )->validate();
    }
}
