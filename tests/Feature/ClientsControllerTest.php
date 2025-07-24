<?php
namespace Tests\Feature;

use App\Booking;
use App\Client;
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
        $client       = factory(Client::class)->create(['user_id' => $user->id]);
        $olderBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start'     => now()->subDays(2),
        ]);
        $newerBooking = factory(Booking::class)->create([
            'client_id' => $client->id,
            'start'     => now()->subDay(),
        ]);

        $response = $this->get('/clients/' . $client->id);

        $response->assertStatus(200);
        $response->assertViewHas('client', function ($viewClient) use ($client, $newerBooking, $olderBooking) {
            return $viewClient->id === $client->id &&
            $viewClient->relationLoaded('bookings') &&
            $viewClient->bookings->pluck('id')->values()->all() === [
                $newerBooking->id,
                $olderBooking->id,
            ];
        });
    }

    /** @test */
    public function user_can_only_see_their_own_clients()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $ownClient   = factory(Client::class)->create(['user_id' => $user->id]);
        $otherClient = factory(Client::class)->create(); // belongs to a different user

        $response = $this->get('/clients');

        $response->assertStatus(200);
        $response->assertViewHas('clients', function ($clients) use ($ownClient, $otherClient) {
            return $clients->contains($ownClient) && ! $clients->contains($otherClient);
        });
    }

    /** @test */
    public function user_cannot_view_another_users_client()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $otherClient = factory(Client::class)->create(); // belongs to another user

        $response = $this->get('/clients/' . $otherClient->id);

        $response->assertStatus(404);
    }

    /** @test */
    public function created_client_is_assigned_to_authenticated_user()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $data = [
            'name' => 'Santa Claus',
            'email' => 'santa@christmas.com',
            'phone' => '123456789',
            'address' => 'North Pole Village',
            'city' => 'Rovaniemi',
            'postcode' => '12345',
        ];

        $response = $this->post('/clients', $data);

        $this->assertDatabaseHas('clients', [
            'email' => 'santa@christmas.com',
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function user_cannot_delete_another_users_client()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $otherClient = factory(Client::class)->create(); // belongs to another user

        $response = $this->delete('/clients/' . $otherClient->id);

        $response->assertStatus(404); // or 404
        $this->assertDatabaseHas('clients', ['id' => $otherClient->id]);
    }

    /** @test */
    public function user_can_delete_their_own_client()
    {
        $user = factory(User::class)->create();
        $this->actingAs($user);
        $client = factory(Client::class)->create(['user_id' => $user->id]);

        $response = $this->delete('/clients/' . $client->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
