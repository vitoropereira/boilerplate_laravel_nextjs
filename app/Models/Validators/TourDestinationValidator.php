<?php

namespace App\Models\Validators;

use App\Models\TourDestination;
use Illuminate\Validation\Rule;

class TourDestinationValidator
{

  public function validate(TourDestination $tour_destination, array $attributes): array
  {
    return validator(
      $attributes,
      [
        'name' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],
        'country' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],
        'country_region' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],
        'state' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],
        'city' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],

        'featured_image_id' => [Rule::exists('images', 'id')->where('resource_type', 'tourdestination')->where('resource_id', $tour_destination->id)],
        'slug' => [Rule::when($tour_destination->exists, 'sometimes'), 'string', 'required'],

        'description' => ['string'],
      ]
    )->validate();
  }
}
