<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Models\Validators\ProductValidator;
use App\Notifications\ProductChange;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $products = Product::query()
            ->orderBy('name', 'DESC')
            ->when(request('available'), fn ($builder) => $builder->whereName(request('available')))
            ->when(request('name'), fn ($builder) => $builder->whereName(request('name')))
            ->when(request('slug'), fn ($builder) => $builder->whereSlug(request('slug')))
            ->when(request('tour_destination_uuid'), fn ($builder) => $builder->whereTourDestinationId(request('tour_destination_id')))
            ->with(['images', 'tourDestination'])
            ->paginate(20);

        return ProductResource::collection(
            $products
        );
    }

    public function show(Product $product)
    {
        $product->load(['productPrices', 'images']);
        return ProductResource::make($product);
    }

    public function create(): JsonResource
    {
        // $this->authorize('create', $product);

        $attributes =  (new ProductValidator())->validate(
            $product = new Product(),
            request()->all()
        );

        Log::info("ProductController::create() - attributes: ", $attributes);

        $product->fill(
            $attributes
        )->save();

        // Notification::send(User::where('access_level', User::ADMIN_USER)->get(), new ProductChange($product));

        return ProductResource::make($product);
    }

    public function update(Product $product): JsonResource
    {
        $this->authorize('update', $product);

        $attributes = (new ProductValidator())->validate($product, request()->all());

        $product->fill(
            $attributes
        );

        $requeresNotification = $product->isDirty(['name', 'slug', 'available', 'tour_destination_id']);

        $product->save();

        if ($requeresNotification) {
            // Notification::send(User::where('access_level', User::ADMIN_USER)->get(), new ProductChange($product));
        }

        return ProductResource::make($product);
    }

    public function delete(Product $product)
    {
        $this->authorize('delete', $product);

        $product->images->each(function ($image) {
            Storage::delete($image->path);
            $image->delete();
        });

        $product->delete();
    }
}
