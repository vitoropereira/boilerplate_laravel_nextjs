<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddresse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CustomerAddresseControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function itOnlyShowCustomerAddressForLoggedInUser()
    {
        CustomerAddresse::factory()->count(1)->create();
        $customerAddresse = CustomerAddresse::factory()->create();

        $response = $this->get('api/customers-addresse/' . $customerAddresse->customer_id);

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itListOnlyTheAddressOfASpecificCustomers()
    {
        CustomerAddresse::factory()->count(5)->create();

        CustomerAddresse::factory()->create(['customer_id' => 1234]);
        $customerAddresse = CustomerAddresse::factory()->create(['customer_id' => 1234]);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('api/customers-addresse/' . $customerAddresse->customer_id);

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertEquals($customerAddresse->customer_id, $response->json('data')[0]['customer_id']);
    }

    /**
     * @test
     */
    public function itShouldNotUpdateCustomerAddresseDataIfNotLoggedIn()
    {
        CustomerAddresse::factory()->count(3)->create();

        $customerAddresse = CustomerAddresse::factory()->create(['customer_id' => 1234]);

        $data = array(
            'name' => 'Vitor Onofre',
            'address1' => 'Av. Armindo Moura',
            'city' => 'Recife',
            'type' => 2
        );

        $response = $this->put('api/customers-addresse/' . $customerAddresse->id, $data);
        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itShouldUpdatesCustomerAddresseDataOnlyIfLoggedIn()
    {
        CustomerAddresse::factory()->count(3)->create();

        $customerAddresse = CustomerAddresse::factory()->create(['customer_id' => 1234]);

        $user = User::factory()->create();

        $responseLogin = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $responseLogin->assertStatus(204);

        $data = array(
            'name' => 'Casa da Praia',
            'address1' => 'Av. Armindo Moura',
            'city' => 'Recife',
            'type' => 2
        );

        $response = $this->put('api/customers-addresse/' . $customerAddresse->id, $data);
        $response->assertStatus(200);

        $this->assertEquals($response->json('name'), 'Casa da Praia');
        $this->assertEquals($response->json('address1'), 'Av. Armindo Moura');
        $this->assertEquals($response->json('city'), 'Recife');
        $this->assertEquals($response->json('type'), 2);
    }

    /**
     * @test
     */
    public function itShouldNotUpdatesCustomerAddresseDataIfTheyNotAreSend()
    {
        CustomerAddresse::factory()->count(3)->create();

        $customerAddresse = CustomerAddresse::factory()->create(['customer_id' => 1234]);
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = array(
            'name' => 'Casa da Praia',
            'address1' => 'Av. Armindo Moura',
            'city' => 'Recife',
            'type' => 2
        );

        $response = $this->put('api/customers-addresse/' . $customerAddresse->id, $data);
        $response->assertStatus(200);

        $this->assertEquals($response->json('name'), 'Casa da Praia');
        $this->assertEquals($response->json('type'), 2);
        $this->assertEquals($response->json('address1'), 'Av. Armindo Moura');
        $this->assertEquals($response->json('address2'), $customerAddresse->address2);
        $this->assertEquals($response->json('postcode'), $customerAddresse->postcode);
        $this->assertEquals($response->json('city'), 'Recife');
        $this->assertEquals($response->json('neighborhood'), $customerAddresse->neighborhood);
        $this->assertEquals($response->json('state'), $customerAddresse->state);
        $this->assertEquals($response->json('country'), $customerAddresse->country);
    }

    /**
     * @test
     */
    public function itShouldCreateNewCustomerAddresse()
    {
        $user = User::factory()->create();
        $customer = Customer::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->postJson('/api/customers-addresse/' . $customer->id, [
            'type' => 2,
            'name' => 'Minha Casa',
            'address1' => 'Av. Alguma Coisa',
            'address2' => 'Fica perto da padaria.',
            'postcode' => '63382982',
            'neighborhood' => 'Bom de Mais',
            'city' => 'Kingdown',
            'state' => 'Fortlander',
            'country' => 'Brasil'
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('customer_addresses', [
            'type' => 2,
            'name' => 'Minha Casa',
            'address1' => 'Av. Alguma Coisa',
            'customer_id' => $customer->id
        ]);
    }

    /**
     * @test
     */
    public function itShouldNotCreateNewCustomerWithoutToken()
    {
        $user = User::factory()->createQuietly();
        $customer = Customer::factory()->create();

        $token = $user->createToken('test', []);

        $response =  $this->postJson('/api/customers-addresse/' . $customer, [], [
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ]);

        $response->assertStatus(403);
    }
}
