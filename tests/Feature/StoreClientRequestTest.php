<?php

namespace Tests\Feature;

use App\Client;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreClientRequestTest extends TestCase
{
    use RefreshDatabase;

    protected $validPayload;

    protected function setUp(): void
    {
        parent::setUp();

        // Simulate an authenticated user
        $user = factory(User::class)->create();
        $this->actingAs($user);

        // Define reusable valid payload
        $this->validPayload = [
            'name' => 'Bruce Wayne',
            'email' => 'bruce.wayne@gmail.com',
            'phone' => '+333 444 555',
            'address' => 'Main Street 1',
            'city' => 'Gotham',
            'postcode' => '99999',
        ];
    }

    // Helper
    private function postJsonClient(array $payload)
    {
        return $this->postJson('/clients', $payload);
    }

    /** @test */
    public function it_allows_valid_data()
    {
        $response = $this->postJsonClient($this->validPayload);
        $response->assertStatus(201);
    }

    /** @test */
    public function it_requires_name()
    {
        $payload = $this->validPayload;
        unset($payload['name']);

        $response = $this->postJsonClient($payload);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_limits_name_to_190_chars()
    {
        $payload = $this->validPayload;
        $payload['name'] = str_repeat('a', 191);

        $response = $this->postJsonClient($payload);
        $response->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_requires_valid_email_format_if_provided()
    {
        $payload = $this->validPayload;
        $payload['email'] = 'invalid-email';

        $response = $this->postJsonClient($payload);
        $response->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function it_allows_null_email_and_phone_but_not_both()
    {
        $payload = $this->validPayload;
        $payload['email'] = null;
        $payload['phone'] = null;

        $response = $this->postJsonClient($payload);
        $response->assertJsonValidationErrors(['email', 'phone']);
    }

    /** @test */
    public function it_rejects_phone_with_invalid_characters()
    {
        $payload = $this->validPayload;
        $payload['phone'] = '123-abc-456';

        $response = $this->postJsonClient($payload);
        $response->assertJsonValidationErrors(['phone']);
    }
}
