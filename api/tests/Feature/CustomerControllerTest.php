<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerAddresse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test
     */
    public function itOnlyListAllCustomersWhithAuthenticate()
    {
        Customer::factory()->count(1)->create();

        $response = $this->get('/api/customers');

        $response->assertStatus(302);
    }

    /**
     * @test
     */
    public function itListAllCustomersInPaginateWay()
    {
        Customer::factory()->count(5)->create();

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/customers');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');

        $this->assertNotNull($response->json('data')[0]['id']);
        $this->assertNotNull($response->json('meta'));
        $this->assertNotNull($response->json('links'));
    }

    /**
     * @test
     */
    public function itFiltersCustomersByName()
    {
        Customer::factory()->count(3)->create();

        $customer = Customer::factory()->create(['name' => 'Vitor']);

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/customers?name=Vitor');
        $response->assertStatus(200);
        $response->assertJsonCount(1, 'data');

        $this->assertEquals($customer->name, $response->json('data')[0]['name']);
    }

    /**
     * @test
     */
    public function itShouldShowsTheCustomerWhithAdresse()
    {
        $customer = Customer::factory()->create();
        CustomerAddresse::factory()->for($customer)->create();

        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response = $this->get('/api/customers/' . $customer->id);
        $response->assertStatus(200);

        $this->assertIsArray($response->json('data')['customer_addresses']);
    }

    /**
     * @test
     */
    public function itShouldCreateNewCustomer()
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $response =  $this->postJson('/api/customers', [
            'name' => 'VITOR',
            'email' => 'vitor@vitor.com',
            'cpf' => '128849387280',
            'birth_date' => '2022-03-07',
            'phone' => '8399343211'
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.user.id', $user->id);
        $this->assertDatabaseHas('customers', [
            'name' => 'VITOR',
            'email' => 'vitor@vitor.com',
            'cpf' => '128849387280',
            'birth_date' => '2022-03-07',
            'phone' => '8399343211'
        ]);
    }

    /**
     * @test
     */
    public function itShouldNotCreateNewCustomerWithoutToken()
    {
        $user = User::factory()->createQuietly();

        $token = $user->createToken('test', []);

        $response =  $this->postJson('/api/customers', [], [
            'Authorization' => 'Bearer ' . $token->plainTextToken
        ]);

        $response->assertStatus(422);
    }

    /**
     * @test
     */
    public function itShouldUpdatesCustomer()
    {
        $user = User::factory()->create(['name' => 'Vitor']);
        $customer = Customer::factory()->for($user)->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertNoContent();

        $data = array(
            'name' => 'Vitor Onofre',
            'cpf' => '06559467481',
            'birth_date' => '1986-02-19',
        );

        $response = $this->putJson('/api/customers/' . $customer->id, $data);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Vitor Onofre')
            ->assertJsonPath('data.cpf', '06559467481')
            ->assertJsonPath('data.birth_date', '1986-02-19T00:00:00.000000Z');
    }

    // /**
    //  * @test
    //  */
    // public function itShouldNotUpdatesCustomerDataIfTheyNotAreSend()
    // {
    //     Customer::factory()->count(3)->create();
    //     $customer = Customer::factory()->create(['name' => 'Vitor']);

    //     $user = User::factory()->create();

    //     $response = $this->post('/login', [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);

    //     $this->assertAuthenticated();
    //     $response->assertNoContent();

    //     $data = array(
    //         'name' => 'Vitor Onofre',
    //         'cpf' => '06559467481',
    //         'birth_date' => '1986-02-19',
    //     );

    //     $response = $this->put('/api/customers/' . $customer->id, $data);
    //     $response->assertStatus(200);

    //     $this->assertEquals($response->json('name'), 'Vitor Onofre');
    //     $this->assertEquals($response->json('cpf'), '06559467481');
    //     $this->assertEquals($response->json('email'), $customer->email);
    //     $this->assertEquals($response->json('cnpj'), $customer->cnpj);
    //     $this->assertEquals($response->json('rg'), $customer->rg);
    //     $this->assertEquals($response->json('passport'), $customer->passport);
    //     $this->assertEquals($response->json('birth_date'), '1986-02-19T00:00:00.000000Z');
    //     $this->assertEquals($response->json('phone'), $customer->phone);
    // }
}
