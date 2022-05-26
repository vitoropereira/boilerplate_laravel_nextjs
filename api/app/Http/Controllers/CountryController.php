<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $country = Country::query()
            ->orderBy('name', 'ASC')
            ->paginate(20);

        return CountryResource::collection(
            $country
        );
    }
}
