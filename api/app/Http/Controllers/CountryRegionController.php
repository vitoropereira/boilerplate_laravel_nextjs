<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryRegionResource;
use App\Models\CountryRegion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryRegionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $state = CountryRegion::query()
            ->orderBy('name', 'ASC')
            ->paginate(20);

        return CountryRegionResource::collection(
            $state
        );
    }
}
