<?php

namespace App\Http\Controllers\Adverts;

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Http\Controllers\Controller;

class AdvertController extends Controller
{
    public function index(Region $region = null, Category $category = null)
    {
        $query = Advert::with(['category', 'region'])->orderByDesc('id');

        $adverts = $query->paginate(20);

        return view('adverts.index', compact('adverts'));
    }
}
