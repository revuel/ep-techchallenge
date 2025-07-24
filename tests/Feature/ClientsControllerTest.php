<?php

namespace Tests\Feature;

use App\Client;
use App\Booking;
use App\User; // or App\Models\User if using Laravel 8+ with model namespaced
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_client_with_bookings()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $client = factory(Client::class)->create();
        $booking = factory(Booking::class)->create([
            'client_id' => $client->id,
        ]);

        $response = $this->get('/clients/' . $client->id);

        $response->assertStatus(200);
        $response->assertViewHas('client', function ($viewClient) use ($client, $booking) {
            return $viewClient->id === $client->id &&
                   $viewClient->relationLoaded('bookings') &&
                   $viewClient->bookings->contains($booking);
        });
    }
}