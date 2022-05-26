<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Http\Resources\TourDestinationResource;
use App\Models\TourDestination;
use App\Models\Validators\TourDestinationValidator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TourDestinationController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $tour_destination = TourDestination::query()
            ->when(request('name'), fn ($builder) => $builder->whereName(request('name')))
            ->when(request('slug'), fn ($builder) => $builder->whereSlug(request('slug')))
            ->when(request('country'), fn ($builder) => $builder->whereCountry(request('country')))
            ->when(request('country_region'), fn ($builder) => $builder->whereCountryRegion(request('country_region')))
            ->when(request('state'), fn ($builder) => $builder->whereState(request('state')))
            ->when(request('city'), fn ($builder) => $builder->whereCity(request('city')))
            ->with(['images', 'product'])
            ->orderBy('name', 'DESC')
            ->paginate(20);

        return TourDestinationResource::collection(
            $tour_destination
        );
    }

    public function show(TourDestination $tour_destination)
    {
        // Log::info("TourDestinationController::create() - attributes: ", $tour_destination);
        $tour_destination->load(['images', 'product']);
        return TourDestinationResource::collection($tour_destination);
    }

    public function create(): JsonResource
    {
        // $this->authorize('create', $tour_destination);

        $attributes =  (new TourDestinationValidator())->validate(
            $tour_destination = new TourDestination(),
            request()->all()
        );

        Log::info("TourDestinationController::create() - attributes: ", $attributes);

        $tour_destination->fill(
            $attributes
        )->save();

        return TourDestinationResource::make($tour_destination);
    }

    public function update(TourDestination $tour_destination): JsonResource
    {
        $this->authorize('update', $tour_destination);

        $attributes = (new TourDestinationValidator())->validate($tour_destination, request()->all());

        $tour_destination->fill(
            $attributes
        );

        $tour_destination->save();

        return TourDestinationResource::make($tour_destination);
    }

    public function delete(TourDestination $tour_destination)
    {
        $this->authorize('delete', $tour_destination);

        if ($tour_destination->product()->where('available', true)->exists()) {
            throw ValidationException::withMessages([
                'message' => 'Não é possivel deletar esta localidade, pois ela tem um produto ativo.'
            ]);
        }

        $tour_destination->images->each(function ($image) {
            Storage::delete($image->path);
            $image->delete();
        });

        $tour_destination->delete();
    }
}
