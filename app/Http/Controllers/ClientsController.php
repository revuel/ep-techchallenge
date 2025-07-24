<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::all();

        foreach ($clients as $client) {
            $client->append('bookings_count');
        }

        return view('clients.index', ['clients' => $clients]);
    }

    public function create()
    {
        return view('clients.create');
    }

    public function show($client)
    {
        $client = Client::with(['bookings' => function ($query) {
            $query->orderBy('start', 'desc');
        }])->where('id', $client)->first();

        return view('clients.show', ['client' => $client]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create($request->only([
            'name', 'email', 'phone', 'address', 'city', 'postcode',
        ]));

        return $client;
    }

    public function destroy($client)
    {
        Client::where('id', $client)->delete();

        return 'Deleted';
    }
}
