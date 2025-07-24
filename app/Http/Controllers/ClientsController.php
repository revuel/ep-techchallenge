<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    public function index()
    {
        $clients = Client::where('user_id', Auth::id())->get();

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
        }])
        ->where('id', $client)
        ->where('user_id', Auth::id())
        ->firstOrFail();

        return view('clients.show', ['client' => $client]);
    }

    public function store(StoreClientRequest $request)
    {
        $client = Client::create(array_merge(
            $request->only([
                'name', 'email', 'phone', 'address', 'city', 'postcode',
            ]),
            ['user_id' => Auth::id()] // Ensure ownership
        ));

        return $client;
    }

    public function destroy($client)
    {
        $client = Client::where('id', $client)
                        ->where('user_id', Auth::id())
                        ->firstOrFail();

        $client->delete();

        return 'Deleted';
    }
}
