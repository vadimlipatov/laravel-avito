<?php

namespace App\Http\Controllers\Cabinet\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use Auth;

class AdvertController extends Controller
{
    public function __construct()
    {
        $this->middleware(FilledProfile::class);
        $this->middleware('can:manage-adverts');
    }

    public function index()
    {
        $adverts = Advert::forUser(Auth::user())->orderByDesc('id')->paginate(20);

        return view('cabinet.adverts.index', compact('adverts'));
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
