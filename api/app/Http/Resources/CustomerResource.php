<?php

namespace App\Http\Resources;

use App\Models\CustomerAddresse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class CustomerResource extends JsonResource
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

            $this->merge(Arr::except(parent::toArray($request), [
                'user_id', 'created_at', 'updated_at', 'deleted_at'
            ])),
            'user' => UserResource::make($this->user),
            'customer_addresses' => CustomerAddresseResource::make(CustomerAddresse::get())
        ];
    }
}
