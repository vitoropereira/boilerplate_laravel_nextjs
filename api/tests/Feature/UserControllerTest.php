<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itOnlyListAllUserWhithAuthenticate()
    {
        User::factory()->count(2)->create();

        $response = $this->get('/api/users');

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itListAllUserInPaginateWay()
    {
        User::factory()->count(2)->create();

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function itOnlyListsUsersThatAreApproved()
    {
        User::factory()->count(2)->create();
        User::factory()->create(['approval_status' => 0]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/users');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * @test
     */
    public function itFiltersByName()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/users?name=Vitor');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertEquals($user->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itListOnlyTheDataOfTheLoggedUser()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response = $this->get('/api/user');

        $response->assertStatus(200);
        $this->assertEquals($user->name, $response->json('name'));
        $this->assertEquals($user->email, $response->json('email'));
    }
    /**
     * @test
     */
    public function itFiltersByNameViaPostMethod()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->post('/api/users', ['name' => 'Vitor']);
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertEquals($user->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itShouldNotUpdateUserDataIfUserNotLoggedIn()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $data = array(
            'name' => 'Vitor Onofre',
            'neighborhood' => 'Boa Viagem',
            'city' => 'Recife',
        );

        $response = $this->put('/api/users/' . $user->id, $data);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itShouldUpdateLoggedUserData()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = array(
            'name' => 'Vitor Onofre',
            'neighborhood' => 'Boa Viagem',
            'city' => 'Recife',
        );

        $response = $this->putJson('/api/users/' . $user->id, $data);
        $response->assertStatus(200);

        $this->assertEquals($response->json('data.name'), 'Vitor Onofre');
        $this->assertEquals($response->json('data.neighborhood'), 'Boa Viagem');
        $this->assertEquals($response->json('data.city'), 'Recife');
    }

    /**
     * @test
     */
    public function itShouldNotUpdateUserDataThatNotAreSend()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = array(
            'name' => 'Vitor Onofre',
            'neighborhood' => 'Boa Viagem',
            'city' => 'Recife',
        );

        $response = $this->putJson('/api/users/' . $user->id, $data);
        $response->assertStatus(200);

        $this->assertEquals($response->json('data.name'), 'Vitor Onofre');
        $this->assertEquals($response->json('data.cpf'), $user->cpf);
        $this->assertEquals($response->json('data.cell_phone'), $user->cell_phone);
        $this->assertEquals($response->json('data.address1'), $user->address1);
        $this->assertEquals($response->json('data.address2'), $user->address2);
        $this->assertEquals($response->json('data.postcode'), $user->postcode);
        $this->assertEquals($response->json('data.neighborhood'), 'Boa Viagem');
        $this->assertEquals($response->json('data.state'), $user->state);
        $this->assertEquals($response->json('data.city'), 'Recife');
        $this->assertEquals($response->json('data.country'), $user->country);
    }

    /**
     * @test
     */
    public function itSholdNotUpdateEmailOrPasswordOfLoggedUserData()
    {
        User::factory()->count(3)->create();

        $user = User::factory()->create(['name' => 'Vitor']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = array(
            'password' => '123456',
            'email' => 'jonh@doh.com',
        );

        $response = $this->put('/api/users/' . $user->id, $data);

        $this->assertEquals($response->json('data.neighborhood'), $user->neighborhood);
        $this->assertNotEquals($response->json('data.password'), '123456');
        $this->assertNotEquals($response->json('data.email'), 'jonh@doh.com');
        $this->assertEquals($response->json('data.email'), $user->email);

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function itShouldIncludesImages()
    {
        $user = User::factory()->create(['name' => 'Vitor']);
        $user->images()->create(['path' => 'image.png']);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();


        $response = $this->get('/api/users');
        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')[0]['images']);
        $this->assertCount(1, $response->json('data')[0]['images']);
    }
}
