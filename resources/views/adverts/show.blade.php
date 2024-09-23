@extends('layouts.app')

@section('content')
  @if ($advert->isDraft())
    <div class="alert alert-danger">
      It is a draft.
    </div>
    @if ($advert->reject_reason)
      <div class="alert alert-danger">
        {{ $advert->reject_reason }}
      </div>
    @endif
  @endif

  @can('edit-own-advert', $advert)
    <div class="d-flex flex-row mb-3">
      <a href="{{ route('admin.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
      <a href="{{ route('admin.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
      <form method="POST" action="{{ route('admin.adverts.moderate', $advert) }}" class="mr-1">
        @csrf
        <button type="submit" class="btn btn-success">Publish</button>
      </form>
      <a href="{{ route('admin.adverts.reject', $advert) }}" class="btn btn-danger mr-1">Reject</a>
      <form method="POST" action="{{ route('admin.adverts.destroy', $advert) }}" class="mr-1">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
      </form>
    </div>
  @endcan

  @can('moderate-advert', $advert)
    <div class="d-flex flex-row mb-3">
      <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
      <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>
      <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="mr-1">
        @csrf
        <button type="submit" class="btn btn-success">Publish</button>
      </form>
      <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-1">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
      </form>
    </div>
  @endcan

  @can('manage-own-advert', $advert)
    <div class="d-flex flex-row mb-3">
      <a href="{{ route('cabinet.adverts.edit', $advert) }}" class="btn btn-primary mr-1">Edit</a>
      <a href="{{ route('cabinet.adverts.photos', $advert) }}" class="btn btn-primary mr-1">Photos</a>

      @if ($advert->isDraft())
        <form method="POST" action="{{ route('cabinet.adverts.send', $advert) }}" class="mr-1">
          @csrf
          <button type="submit" class="btn btn-success">Publish</button>
        </form>
      @endif

      <form method="POST" action="{{ route('cabinet.adverts.destroy', $advert) }}" class="mr-1">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete</button>
      </form>
    </div>
  @endcan

  <div class="row">
    <div class="col-md-9">

      <p class="float-right" style="font-size: 36px;">{{ $advert->price }}</p>
      <h1 style="margin-bottom: 10px;">{{ $advert->title }}</h1>
      <p>
        Date: {{ $advert->created_at }}
        @if ($dvert->expires_at)
          Expires: {{ $advert->expires_at }}
        @endif
      </p>

      <div>
        <div class="row">
          <div class="col-10">
            <div style="height: 400px; background: #f6f6f6; border: 1px solid #ddd;"></div>
          </div>
          <div class="col-2">
            <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd;"></div>
            <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd;"></div>
            <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd;"></div>
            <div style="height: 100px; background: #f6f6f6; border: 1px solid #ddd;"></div>
          </div>
        </div>
      </div>

      <p>{!! nl2br(e($advert->content)) !!}</p>

      <hr>

      <p>Address: {{ $advert->address }}</p>

      <div style="height: 200px; background: #f6f6f6; border: 1px solid #ddd;">
        <div id="map"></div>

        <script src="//api-maps.yandex.ru/2.1/?load=package.standart&lang=ru-RU""></script>
        <script>
          ymaps.ready(function init() {
            const geocoder = new ymaps.geocode('{{ $advert->getFullAddress() }}', {
              results: 1
            });
            geocoder.then(function(res) {
              function(res) {
                if (!res.geoObjects.get(0)) {
                  console.error('Undefined function')
                  return;
                }
                const coords = res.geoObjects.get(0).geometry.getCoordinates();
                const map = new ymaps.Map('map', {
                  center: coords,
                  zoom: 7,
                  behaviors: ['default', 'scrollZoom'],
                  controls: ['mapTools']
                });
                map.geoObjects.add(res.geoObjects.get(0));
                map.zoomRange.get(coords).then(function(range) {
                  map.setCenter(coords, range[1] - 1);
                })
                map.controls.add('mapTools')
                  .add('zoomControl')
                  .add('typeSelector')
              }
            })
          })
        </script>
      </div>

      <p style="height: 200px; background: #f6f6f6; border: 1px solid #ddd;">
        Seller {{ $advert->user->name }}
      </p>

      <p>
        <span class="btn btn-primary phone-button" data-source="{{ route('adverts.phone', $advert) }}">
          <span class="fa fa-phone"> <span class="number">Show Phone Number</span></span>
        </span>
      </p>

      <hr>

      <div class="h3">Similar adverts</div>

      <div class="row">
        <div class="col-sm-6 col-md-4">
          <div class="card">
            <img
              src="https://images.pixels.com/photos/297933/pexels-photo-296933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb"
              alt="" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title h4 mt-0"><a href="/catalog/show.html">The first Thing</a></h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-4">
          <div class="card">
            <img
              src="https://images.pixels.com/photos/297933/pexels-photo-296933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb"
              alt="" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title h4 mt-0"><a href="/catalog/show.html">The first Thing</a></h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6 col-md-4">
          <div class="card">
            <img
              src="https://images.pixels.com/photos/297933/pexels-photo-296933.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb"
              alt="" class="card-img-top">
            <div class="card-body">
              <h5 class="card-title h4 mt-0"><a href="/catalog/show.html">The first Thing</a></h5>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
