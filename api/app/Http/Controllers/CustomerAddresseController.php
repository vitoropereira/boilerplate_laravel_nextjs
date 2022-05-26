<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerAddresseResource;
use App\Models\CustomerAddresse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerAddresseController extends Controller
{
    public function index($customer_id): AnonymousResourceCollection
    {
        //
        $customerAddresse = CustomerAddresse::query()
            ->where('customer_id', $customer_id)
            ->when(request('name'), fn ($builder) => $builder->whereName(request('name')))
            ->orderBy('name', 'DESC')
            ->paginate(20);

        return CustomerAddresseResource::collection(
            $customerAddresse
        );
    }

    public function update(Request $request, $id)
    {
        $customerAddresse = CustomerAddresse::findOrFail($id);

        $name = $request->input('name') === NULL ?  $customerAddresse->name : $request->input('name');
        $type = $request->input('type') === NULL ?  $customerAddresse->type : $request->input('type');
        $address1 = $request->input('address1') === NULL ?  $customerAddresse->address1 : $request->input('address1');
        $address2 = $request->input('address2') === NULL ?  $customerAddresse->address2 : $request->input('address2');
        $postcode = $request->input('postcode') === NULL ?  $customerAddresse->postcode : $request->input('postcode');
        $neighborhood = $request->input('neighborhood') === NULL ?  $customerAddresse->neighborhood : $request->input('neighborhood');
        $city = $request->input('city') === NULL ?  $customerAddresse->city : $request->input('city');
        $state = $request->input('state') === NULL ?  $customerAddresse->state : $request->input('state');
        $country = $request->input('country') === NULL ?  $customerAddresse->country : $request->input('country');

        $customerAddresse->name = $name;
        $customerAddresse->type = $type;
        $customerAddresse->address1 = $address1;
        $customerAddresse->address2 = $address2;
        $customerAddresse->postcode = $postcode;
        $customerAddresse->neighborhood = $neighborhood;
        $customerAddresse->city = $city;
        $customerAddresse->state = $state;
        $customerAddresse->country = $country;

        if ($customerAddresse->save()) {
            return CustomerAddresse::findOrFail($id);
        }
    }

    public function create($customer_id): JsonResource
    {

        if (!auth()->user()->tokenCan('customerAddresse.create')) {
            abort(403);
        }

        $attributes = validator(
            request()->all(),
            [
                'type' => ['integer'],
                'name' => ['string', 'required'],
                'address1' => ['string', 'required'],
                'address2' => ['string'],
                'postcode' => ['string'],
                'neighborhood' => ['string'],
                'city' => ['string'],
                'state' => ['string'],
                'country' => ['string']
            ]
        )->validate();

        $attributes['customer_id'] = $customer_id;

        // $customer = auth()->user()->customer()->create(
        //     $attributes
        // );

        $customer = CustomerAddresse::create(
            $attributes
        );

        return CustomerAddresseResource::make($customer);
    }
}
