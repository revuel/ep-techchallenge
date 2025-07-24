<?php

namespace Tests\Feature;

use App\Client;
use App\Booking;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_client_with_bookings_ordered_by_date()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $client = factory(Client::class)->create();
        $olderBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDays(2),
        ]);
        $newerBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start' => now()->subDay(),
        ]);

        $response = $this->get('/clients/' . $client->id);

        $response->assertStatus(200);
        $response->assertViewHas('client', function ($viewClient) use ($client, $newerBooking, $olderBooking) {
            return $viewClient->id === $client->id &&
                $viewClient->relationLoaded('bookings') &&
                $viewClient->bookings->pluck('id')->values()->all() === [
                    $newerBooking->id,
                    $olderBooking->id
                ];
        });
    }
}