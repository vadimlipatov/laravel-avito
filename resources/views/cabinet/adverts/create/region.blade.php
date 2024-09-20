@extends('layouts.app')

@section('content')
  @if ($region)
    <p>
      <a class="btn btn-success" href="{{ route('cabinet.adverts.create.advert', [$category, $region]) }}">
        Add advert for {{ region->name }}
      </a>
    </p>
  @else
    <p>
      <a class="btn btn-success" href="{{ route('cabinet.adverts.create.advert', [$category]) }}">
        Add advert for all regions
      </a>
    </p>
  @endif

  <p>Or choose nested region:</p>

  <ul>
    @foreach ($regions as $region)
      <li>
        <a href="{{ route('cabinet.adverts.create.region', [$category, $region]) }}">{{ $region->name }}</a>
      </li>
    @endforeach
  </ul>
@endsection
