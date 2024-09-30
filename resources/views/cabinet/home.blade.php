@extends('layouts.app')

@section('content')
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a href="{{ route('cabinet.home') }}" class="nav-link active">Dashboard</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="{{ route('cabinet.adverts.index') }}">Adverts</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('cabinet.favorites.index') }}" class="nav-link">Favorites</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('cabinet.profile.home') }}" class="nav-link">Profile</a>
    </li>
  </ul>
@endsection
