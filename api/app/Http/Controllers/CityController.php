<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $cities = City::query()
            ->orderBy('name', 'ASC')
            ->paginate(20);

        return CityResource::collection(
            $cities
        );
    }
}
