<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\TourDestination;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class TourDestinationControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itListAllTourDestinationInPaginateWay()
    {
        TourDestination::factory()->count(3)->create();


        $response = $this->get('/api/tour_destinations');

        $response->assertStatus(200);

        $response->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationByName()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['name' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['name' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['name' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?name=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationBySlug()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['slug' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['slug' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['slug' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?slug=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->slug, $response->json('data')[0]['slug']);
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationByCity()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['city' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['city' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['city' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?city=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->city, $response->json('data')[0]['city']);
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationByState()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['state' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['state' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['state' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?state=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->state, $response->json('data')[0]['state']);
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationByCountryRegion()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['country_region' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['country_region' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['country_region' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?country_region=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->country_region, $response->json('data')[0]['country_region']);
    }

    /**
     * @test
     */
    public function itShouldFiltersTourDestinationByCountry()
    {
        TourDestination::factory(5)->count(3)->create();
        TourDestination::factory()->create(['country' => 'Porto de Galinhas']);
        TourDestination::factory()->create(['country' => 'Japaratinga']);

        $tour_destination = TourDestination::factory()->create(['country' => 'Milagres']);

        $response = $this->get('/api/tour_destinations?country=Milagres');
        $response->assertStatus(200);

        $this->assertEquals($tour_destination->country, $response->json('data')[0]['country']);
    }

    /**
     * @test
     */
    public function itShouldIncludesImages()
    {
        TourDestination::factory()->count(3)->create();

        $tour_destinations = TourDestination::factory()->create();

        $tour_destinations->images()->create(['path' => 'image.png']);

        $response = $this->get('/api/tour_destinations');
        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')[0]['images']);
    }

    /**
     * @test
     */
    public function itShouldIncludesProducts()
    {
        TourDestination::factory()->count(3)->create();

        $tour_destination = TourDestination::factory()->create();

        Product::factory()->for($tour_destination)->create();
        Product::factory()->for($tour_destination)->create();
        Product::factory()->for($tour_destination)->create();

        $response = $this->get('/api/tour_destinations');

        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')[0]['product']);
    }

    // /**
    //  * @test
    //  */
    // public function itShouldShowsTheTourDestinationWhithImages()
    // {
    //     TourDestination::factory()->count(3)->create();
    //     $tour_destinations = TourDestination::factory()->create();

    //     $tour_destinations->images()->create(['path' => 'image.png']);
    //     // dd($tour_destinations);
    //     $response = $this->get('/api/tour_destination/' . $tour_destinations->id);

    //     $response->assertStatus(200);


    //     $this->assertIsArray($response->json('data')['images']);
    // }


    /**
     * @test
     */
    public function itShouldCreateNewTourDestination()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = [
            'name' => 'Praia de Porto de Galinhas',
            'slug' => 'praia-de-porto-de-galinhas',
            'country' => 'Brasil',
            'country_region' => 'Nordeste',
            'state' => 'Pernambuco',
            'city' => 'Ipojuca',

            'description' => 'Big discription',
        ];

        $response =  $this->postJson('/api/tour_destinations', $data);

        $response->assertCreated();

        $this->assertDatabaseHas('tour_destinations', [
            'name' => 'Praia de Porto de Galinhas',
            'slug' => 'praia-de-porto-de-galinhas',
            'country' => 'Brasil',
            'country_region' => 'Nordeste',
            'state' => 'Pernambuco',
            'city' => 'Ipojuca',

            'description' => 'Big discription',
        ]);
    }

    /**
     * @test
     */
    public function itShouldNotCreateNewTourDestinationWithoutToken()
    {
        $user = User::factory()->createQuietly();

        $token = $user->createToken('test', []);

        $response =  $this->postJson('/api/tour_destinations', [], [
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     */
    public function itShouldUpdateAnTourDestination()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->putJson('/api/tour_destinations/' . $tour_destination->id, [
            'name' => 'Carneiros',
        ]);

        $response->assertOk()->assertJsonPath('data.name', 'Carneiros');
    }

    /**
     * @test
     */
    public function itShouldUpdateTheFeaturedImageOfAnTourDestination()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();

        $login = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $image = $tour_destination->images()->create(['path' => 'image.png']);

        $this->assertAuthenticated();
        $login->assertNoContent();

        $response = $this->putJson('/api/tour_destinations/' . $tour_destination->id, [
            'featured_image_id' =>  $image->id,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.featured_image_id', $image->id);
    }

    /**
     * @test
     */
    public function itShouldNotUpdateTheFeaturedImageThatBelongsToAnotherTourDestination()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        $tour_destination2 = TourDestination::factory()->create();

        $login = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $image = $tour_destination2->images()->create(['path' => 'image.png']);

        $this->assertAuthenticated();
        $login->assertNoContent();

        $response = $this->putJson('/api/tour_destinations/' . $tour_destination->id, [
            'featured_image_id' =>  $image->id,
        ]);

        $response->assertUnprocessable();
    }

    /**
     * @test
     */
    public function itShouldNotUpdateTourDestinationIfNotLoggedIn()
    {
        $tour_destination = TourDestination::factory()->create();

        $response =  $this->putJson('/api/tour_destinations/' . $tour_destination->id, [
            'name' => 'Carneiros',
        ]);

        $response->assertStatus(401);
    }

    /**
     * @test
     */
    public function itShouldNotUpdateATourDestinationIfUserIsNotAdmin()
    {
        $user = User::factory()->create();

        $tour_destination = TourDestination::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->putJson('/api/tour_destinations/' . $tour_destination->id, [
            'name' => 'Carneiros',
        ]);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function itShouldDeleteAnTourDestination()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->deleteJson('/api/tour_destinations/' . $tour_destination->id);

        $response->assertOk();

        $this->assertSoftDeleted($tour_destination);
    }

    /**
     * @test
     */
    public function itShouldNotDeleteAnTourDestinationThatHasProducts()
    {
        $user = User::factory()->create([
            'name' => 'Vitor',
            'access_level' => User::ADMIN_USER
        ]);

        $tour_destination = TourDestination::factory()->create();
        Product::factory()->for($tour_destination)->create();
        Product::factory()->for($tour_destination)->create();
        Product::factory()->for($tour_destination)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->deleteJson('/api/tour_destinations/' . $tour_destination->id);

        $response->assertStatus(422);

        $this->assertDatabaseHas('tour_destinations', [
            'id' => $tour_destination->id,
            'deleted_at' => null
        ]);
    }
}
