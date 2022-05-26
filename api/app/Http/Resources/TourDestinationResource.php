<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class TourDestinationResource extends JsonResource
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
            $this->merge(Arr::except(parent::toArray($request), [
                'created_at', 'updated_at', 'deleted_at'
            ]))
        ];
    }
}
