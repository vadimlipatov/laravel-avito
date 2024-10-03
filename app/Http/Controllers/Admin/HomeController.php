<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Elasticsearch\Client;

class HomeController extends Controller
{
    public function index(Client $client)
    {
        $client->indices()->create([
            '...'
        ]);

        return view('admin.home');
    }
}
