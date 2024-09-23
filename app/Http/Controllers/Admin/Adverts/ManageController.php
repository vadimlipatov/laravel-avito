<?php

namespace App\Http\Controllers\Admin\Adverts;

use AdvertService;
use App\Entity\Adverts\Advert\Advert;
use App\Http\Controllers\Controller;
use App\Http\Middleware\FilledProfile;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use Gate;

class ManageController extends Controller
{
    private $service;

    public function __construct(AdvertService $service)
    {
        $this->middleware(FilledProfile::class);
        $this->service = $service;
    }

    public function attributes(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.edit.attributes', compact('advert'));
    }

    public function updateAttributes(AttributesRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->editAttributes($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show',  $advert);
    }

    public function photos(Advert $advert)
    {
        $this->checkAccess($advert);

        return view('adverts.edit.photos', compact('advert'));
    }

    public function updatePhotos(PhotosRequest $request, Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->addPhotos($advert->id, $request);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('adverts.show',  $advert);
    }

    public function moderate(Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->moderate($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function destroy(Advert $advert)
    {
        $this->checkAccess($advert);

        try {
            $this->service->remove($advert->id);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function checkAccess(Advert $advert)
    {
        if (!Gate::allows('manage-own-advert', $advert)) {
            abort(403);
        }
    }
}
