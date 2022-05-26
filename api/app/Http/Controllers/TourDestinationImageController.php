<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\TourDestination;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TourDestinationImageController extends Controller
{
    public function store(TourDestination $tour_destination): JsonResource
    {
        $this->authorize('update', $tour_destination);

        request()->validate([
            'image' => ['file', 'max:5000', 'mimes:png,jpg'],
        ]);

        $path = request()->file('image')->storePublicly('/');

        $image = $tour_destination->images()->create([
            'path' => $path,
        ]);

        return ImageResource::make($image);
    }

    public function delete(TourDestination $tour_destination, Image $image)
    {
        $this->authorize('delete', $tour_destination);

        throw_if(
            $tour_destination->images()->count() == 1,
            ValidationException::withMessages(['image' => 'Obrigatório ter pelo menos uma imagem.'])
        );

        throw_if(
            $tour_destination->featured_image_id == $image->id,
            ValidationException::withMessages(['image' => 'Não é possível deletar a imagem principal.'])
        );

        Storage::delete($image->path);
        $image->delete();
    }
}
