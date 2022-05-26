<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itShoudUploadProductImages()
    {
        Storage::fake();

        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $product = Product::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->post("/api/products/{$product->id}/images", [
            'image' => UploadedFile::fake()->create('image.jpeg'),
        ]);

        Storage::assertExists(
            $response->json('data.path')
        );
    }

    /**
     * @test
     */
    public function itShouldNotUpdateImageIfNotAuthenticated()
    {
        $product = Product::factory()->create();

        $response = $this->post("/api/products/{$product->id}/images", [
            'image' => UploadedFile::fake()->create('image.jpeg'),
        ]);

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itShouldDeleteImages()
    {
        Storage::put('/product_image.jpeg', 'empty');

        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $product = Product::factory()->create();

        $product->images()->create([
            'path' => 'image1.jpeg',
        ]);

        $image = $product->images()->create([
            'path' => 'product_image.jpeg',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}/images/{$image->id}");

        $response->assertOk();

        $this->assertModelMissing($image);

        Storage::assertMissing('product_image.jpeg');
    }

    /**
     * @test
     */
    public function itShouldNotDeleteTheOnlyImages()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $product = Product::factory()->create();

        $image = $product->images()->create([
            'path' => 'product_image.jpeg',
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}/images/{$image->id}");

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['image' => 'Obrigatório ter pelo menos uma imagem.']);
    }

    /**
     * @test
     */
    public function itShouldNotDeleteImageThatBelongsToAnotherResource()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $product = Product::factory()->create(['name' => 'Product 1']);
        $product2 = Product::factory()->create(['name' => 'Product 2']);

        $image = $product->images()->create([
            'path' => 'product_image.jpeg',
        ]);


        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/products/{$product2->id}/images/{$image->id}");

        $response->assertNotFound();
    }

    /**
     * @test
     */
    public function itShouldNotDeleteTheFeaturedImages()
    {
        $user = User::factory()->create(['access_level' => User::ADMIN_USER]);
        $product = Product::factory()->create();

        $product->images()->create([
            'path' => 'image1.jpeg',
        ]);

        $image = $product->images()->create([
            'path' => 'product_image.jpeg',
        ]);

        $product->update(['featured_image_id' => $image->id]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->deleteJson("/api/products/{$product->id}/images/{$image->id}");

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['image' => 'Não é possível deletar a imagem principal.']);
    }
}
