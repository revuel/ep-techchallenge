<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_expected_fillable_fields()
    {
        $client = new Client();

        $this->assertEquals([
            'name',
            'email',
            'phone',
            'address',
            'city',
            'postcode',
            'user_id'
        ], $client->getFillable());
    }
}