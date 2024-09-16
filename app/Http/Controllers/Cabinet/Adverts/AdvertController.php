<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Http\Controllers\Controller;
use Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware('filled_profile')->only('index');;
    }

    public function index()
    {
        return view('cabinet.adverts.index');
    }

    public function create()
    {
        return view('cabinet.adverts.create');
    }

    public function edit()
    {
        return view('cabinet.adverts.index');
    }
}
