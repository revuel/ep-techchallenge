<?php
namespace Tests\Unit;

use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientFactoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_client_with_valid_address()
    {
        $client = factory(Client::class)->create();

        $this->assertDatabaseHas('clients', ['id' => $client->id,]);
        $this->assertNotNull($client->address, 'Address should not be null');
        $this->assertNotEmpty($client->address, 'Address should not be empty');
        $this->assertIsString($client->address, 'Address should be a string');
    }
}