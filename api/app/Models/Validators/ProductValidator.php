<?php

namespace App\Models\Validators;

use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductValidator
{

  public function validate(Product $product, array $attributes): array
  {

    return validator(
      $attributes,
      [
        'tour_destination_uuid' => ['integer', Rule::exists('tour_destinations', 'id')],
        'featured_image_id' => [Rule::exists('images', 'id')->where('resource_type', 'product')->where('resource_id', $product->id)],

        'name' => [Rule::when($product->exists, 'sometimes'), 'string', 'required'],
        'slug' => [Rule::when($product->exists, 'sometimes'), 'string', 'required'],

        'description' => ['string'],
        'available' => ['bool'],
        'meta_title' => ['string'],
        'meta_description' => ['string']
      ]
    )->validate();
  }
}
