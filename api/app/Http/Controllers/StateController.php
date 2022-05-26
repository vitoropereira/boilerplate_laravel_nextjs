<?php

namespace App\Http\Controllers;

use App\Http\Resources\StateResource;
use Illuminate\Http\Request;
use App\Models\State;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StateController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $state = State::query()
            ->orderBy('name', 'ASC')
            ->paginate(20);

        return StateResource::collection(
            $state
        );
    }
}
