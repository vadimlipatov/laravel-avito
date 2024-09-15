@extends('layouts.app')

@section('content')
  @include('admin.users._nav')

  <form action="{{ route('cabinet.profile.update') }}" method="post">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="name" class="col-form-label">Name</label>
      <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name"
        value="{{ old('name', $user->name) }}" required>
      @if ($errors->has('name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
      @endif
    </div>

    <div class="form-group">
      <label for="last_name" class="col-form-label">Last Name</label>
      <input type="text" id="last_name" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
        name="last_name" value="{{ old('last_name', $user->last_name) }}" required>
      @if ($errors->has('last_name'))
        <span class="invalid-feedback"><strong>{{ $errors->first('last_name') }}</strong></span>
      @endif
    </div>

    <div class="form-group">
      <label for="phone" class="col-form-label">Phone</label>
      <input type="tel" id="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
        name="phone" value="{{ old('phone', $user->phone) }}" required>
      @if ($errors->has('phone'))
        <span class="invalid-feedback"><strong>{{ $errors->first('phone') }}</strong></span>
      @endif
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form>
@endsection
