<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ProductImageControlLer extends Controller
{

    public function store(Product $product): JsonResource
    {
        $this->authorize('update', $product);

        request()->validate([
            'image' => ['file', 'max:5000', 'mimes:png,jpg'],
        ]);

        $path = request()->file('image')->storePublicly('/');
        $image = $product->images()->create([
            'path' => $path,
        ]);

        return ImageResource::make($image);
    }

    public function delete(Product $product, Image $image)
    {
        $this->authorize('delete', $product);

        throw_if(
            $product->images()->count() == 1,
            ValidationException::withMessages(['image' => 'Obrigatório ter pelo menos uma imagem.'])
        );

        throw_if(
            $product->featured_image_id == $image->id,
            ValidationException::withMessages(['image' => 'Não é possível deletar a imagem principal.'])
        );

        Storage::delete($image->path);
        $image->delete();
    }
}
