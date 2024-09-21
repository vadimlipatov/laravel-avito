<?php

use App\Entity\Adverts\Advert\Advert;
use App\Entity\Adverts\Category;
use App\Entity\Region;
use App\Entity\User;
use App\Http\Requests\Adverts\AttributesRequest;
use App\Http\Requests\Adverts\PhotosRequest;
use App\Http\Requests\Adverts\RejectRequest;
use App\Http\Requests\Cabinet\Adverts\CreateRequest;
use Carbon\Carbon;

class AdvertService
{
    public function create($userId, $categoryId, $regionId, CreateRequest $request)
    {
        $user = User::findOrFail($userId);
        $category = Category::findOrFail($categoryId);
        $region = $regionId ? Region::findOrFail($regionId) : null;

        return DB::transaction(function () use ($user, $category, $region, $request) {
            $advert = Advert::make([
                'title' => $request['title'],
                'content' => $request['content'],
                'price' => $request['price'],
                'address' => $request['address'],
                'status' => Advert::STATUS_DRAFT,
            ]);

            $advert->user()->associate($user);
            $advert->category()->associate($category);
            $advert->region()->associate($region);

            $advert->saveOrFail();

            foreach ($category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }

            return $advert;
        });
    }

    public function addPhotos($id, PhotosRequest $request)
    {
        $advert =  $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            foreach ($request['files'] as $file) {
                $advert->photos()->create([
                    'file' => $file->store('adverts')
                ]);
            }
        });
    }

    public function getAdvert($id): Advert
    {
        return Advert::findOrFail($id);
    }

    public function sendToModeration($id)
    {
        $advert = $this->getAdvert($id);
        $advert->sendToModeration();
    }

    public function moderate($id)
    {
        $advert = $this->getAdvert($id);
        $advert->moderate(Carbon::now());
    }

    public function reject($id, RejectRequest $request)
    {
        $advert = $this->getAdvert($id);
        $advert->reject($request['reason']);
    }

    public function editAttributes($id, AttributesRequest $request)
    {
        $advert = $this->getAdvert($id);

        DB::transaction(function () use ($advert, $request) {
            $advert->values()->delete();

            foreach ($request->category->allAttributes() as $attribute) {
                $value = $request['attributes'][$attribute->id] ?? null;
                if (!empty($value)) {
                    $advert->values()->create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
        });
    }

    public function remove($id)
    {
        $advert = $this->getAdvert($id);
        $advert->delete();
    }
}
