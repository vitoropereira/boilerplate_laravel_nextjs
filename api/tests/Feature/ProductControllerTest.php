<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\TourDestination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use App\Notifications\ProductChange;
use Illuminate\Support\Facades\Storage;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itListAllProductsInPaginateWay()
    {
        $product =  Product::factory()->count(5)->create();

        $response = $this->get('/api/products');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function itShouldFiltersProductsByName()
    {
        Product::factory()->count(3)->create();

        $product = Product::factory()->create(['name' => 'Vitor']);

        $response = $this->get('/api/products?name=Vitor');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertEquals($product->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itShouldFiltersProductsBySlug()
    {
        Product::factory()->count(3)->create();

        $product = Product::factory()->create();

        $response = $this->get('/api/products?slug=' . $product->slug);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertEquals($product->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itShouldFiltersProductsByTourDestination()
    {
        Product::factory()->count(3)->create();
        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $response = $this->get('/api/products?tour_destination_uuid=' . $tour_destination->id);
        $response->assertStatus(200);
        // $response->assertJsonCount(4, 'data');
        dd($tour_destination->id);

        $this->assertEquals($product->id, $response->json('data')[0]['id']);
    }

    /**
     * @test
     */
    public function itShouldIncludesImages()
    {
        Product::factory()->count(3)->create();

        $product = Product::factory()->create();

        $product->images()->create(['path' => 'image.png']);

        $response = $this->get('/api/products');
        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')[0]['images']);
    }

    /**
     * @test
     */
    public function itShouldShowsTheProductWhithProductPrices()
    {
        $product = Product::factory()->create();

        ProductPrice::factory()->for($product)->create();
        $product->images()->create(['path' => 'image.png']);

        $response = $this->get('/api/product/' . $product->id);
        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')['product_prices']);
    }

    /**
     * @test
     */
    public function itShouldCreateNewProduct()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        // Notification::fake();

        $tour_destination = TourDestination::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = [
            'tour_destination_uuid' => $tour_destination->id,
            'name' => 'Vitor Pereira',
            'slug' => 'vitor-pereira',
            'description' => 'Big discription',
            'available' => true,
            'meta_title' => 'Big meta title',
            'meta_description' => 'Meta discription'
        ];
        $response =  $this->postJson('/api/products', $data);

        $response->assertCreated();

        $this->assertDatabaseHas('products', [
            'tour_destination_uuid' => $tour_destination->id,
            'name' => 'Vitor Pereira',
            'slug' => 'vitor-pereira',
            'description' => 'Big discription',
            'available' => true,
            'meta_title' => 'Big meta title',
            'meta_description' => 'Meta discription'
        ]);

        // Notification::assertSentTo($user, ProductChange::class);
    }

    /**
     * @test
     */
    public function itShouldNotCreateNewProductWithoutToken()
    {
        $user = User::factory()->createQuietly();

        $token = $user->createToken('test', []);

        $response =  $this->postJson('/api/products', [], [
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function itShouldUpdateAnProduct()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->putJson('/api/products/' . $product->id, [
            'name' => 'Vitor Pereira',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Vitor Pereira');
    }

    /**
     * @test
     */
    public function itShouldUpdateTheFeaturedImageOfAnProduct()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $login = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $image = $product->images()->create(['path' => 'image.png']);
        $this->assertAuthenticated();
        $login->assertNoContent();

        $response = $this->putJson('/api/products/' . $product->id, [
            'featured_image_id' =>  $image->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.featured_image_id', $image->id);
    }

    /**
     * @test
     */
    public function itShouldNotUpdateTheFeaturedImageThatBelongsToAnotherProduct()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();
        $product2 = Product::factory()->for($tour_destination)->create();

        $login = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $image = $product2->images()->create(['path' => 'image.png']);
        $this->assertAuthenticated();
        $login->assertNoContent();

        $response = $this->putJson('/api/products/' . $product->id, [
            'featured_image_id' =>  $image->id,
        ]);

        $response->assertUnprocessable();
    }

    // /**
    //  * @test
    //  */
    // public function itShouldSendAMensageIfUpdateAreDirty()
    // {

    //     $user = User::factory()->create([
    //         'name' => 'Vitor',
    //         'access_level' => User::ADMIN_USER
    //     ]);

    //     Notification::fake();

    //     $tour_destination = TourDestination::factory()->create();
    //     $product = Product::factory()->for($tour_destination)->create();

    //     $response = $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);

    //     $this->assertAuthenticated();
    //     $response->assertNoContent();

    //     $response =  $this->putJson('/api/products/' . $product->id, [
    //         'name' => 'Vitor Pereira',
    //     ]);

    //     $response->assertOk()->assertJsonPath('data.name', 'Vitor Pereira');

    //     Notification::assertSentTo($user, ProductChange::class);
    // }

    /**
     * @test
     */
    public function itShouldNotUpdateProductIfNotLoggedIn()
    {

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $response =  $this->putJson('/api/products/' . $product->id, [
            'name' => 'Vitor Pereira',
        ]);

        $response->assertStatus(401);
    }


    /**
     * @test
     */
    public function itShouldNotUpdateIfUserIsNotAdmin()
    {
        $user = User::factory()->create();

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->putJson('/api/products/' . $product->id, [
            'name' => 'Vitor Pereira',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function itShouldDeleteAnProduct()
    {
        Storage::put('/product_image.jpeg', 'empty');

        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        $product = Product::factory()->for($tour_destination)->create();

        $image = $product->images()->create([
            'path' => 'product_image.jpeg',
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->deleteJson('/api/products/' . $product->id);

        $response->assertOk();

        $this->assertSoftDeleted($product);

        $this->assertModelMissing($image);

        Storage::assertMissing('product_image.jpeg');
    }
}
