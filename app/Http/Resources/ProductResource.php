<?php

namespace App\Http\Resources;

use App\Models\TourDestination;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'images' => ImageResource::collection($this->images),
            'product_prices' => ProductPriceResource::make($this->productPrices),
            'tour_destination' => TourDestinationResource::make($this->tourDestination),
            $this->merge(Arr::except(parent::toArray($request), [
                'created_at', 'updated_at', 'deleted_at'
            ])),

        ];
    }
}
