<?php

namespace App\Models\Validators;

use App\Models\User;
use Illuminate\Validation\Rule;

class UpdateUserValidator
{
  public function validate(User $user, array $attributes): array
  {
    return validator(
      $attributes,
      [
        'name' => [Rule::when($user->exists, 'sometimes'), 'string', 'required'],
        'cpf' => [Rule::when($user->exists, 'sometimes'), 'string', 'required'],
        'cell_phone' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'address1' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'address2' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'postcode' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'neighborhood' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'city' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'state' => [Rule::when($user->exists, 'sometimes'), 'string'],
        'country' => [Rule::when($user->exists, 'sometimes'), 'string']
      ]
    )->validate();
  }
}
