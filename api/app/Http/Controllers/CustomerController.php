<?php

namespace App\Http\Controllers;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\CustomerValidator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;


class CustomerController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        //
        $customers = Customer::query()
            ->when(request('name'), fn ($builder) => $builder->whereName(request('name')))
            ->orderBy('name', 'DESC')
            ->paginate(20);

        return CustomerResource::collection(
            $customers
        );
    }

    public function show(Customer $customer): JsonResource
    {
        $customer->load(['user', 'customer_addresses']);

        return CustomerResource::make($customer);
    }

    public function create(): JsonResource
    {

        if (!auth()->user()->tokenCan('customer.create')) {
            Response::HTTP_FORBIDDEN;
        }

        $attributes = (new CustomerValidator())->validate(
            $customerInstance = new Customer(),
            request()->all()
        );

        $attributes['user_id'] = auth()->id();

        // $customer = auth()->user()->customer()->create(
        //     $attributes
        // );

        $customerInstance->fill(
            $attributes
        )->save();

        return CustomerResource::make(
            $customerInstance->load(['user', 'customer_addresses'])
        );
    }

    public function update(Customer $customer): JsonResource
    {
        if (!auth()->user()->tokenCan('customer.updade')) {
            Response::HTTP_FORBIDDEN;
        }

        $attributes = (new CustomerValidator())->validate($customer, request()->all());

        $attributes['user_id'] = auth()->id();

        // $customer = auth()->user()->customer()->create(
        //     $attributes
        // );

        $customer->update($attributes);

        return CustomerResource::make(
            $customer->load(['user'])
        );
    }
}
