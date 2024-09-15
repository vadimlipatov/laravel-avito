@extends('layouts.app')

@section('content')
  @include('admin.users._nav')

  <form action="{{ route('cabinet.profile.phone.verify') }}" method="post">
    @csrf
    @method('PUT')

    <div class="form-group">
      <label for="token" class="col-form-label">SMS Code</label>
      <input type="number" id="token" class="form-control{{ $errors->has('token') ? ' is-invalid' : '' }}" name="token"
        value="{{ old('token', $user->name) }}" required>
      @if ($errors->has('token'))
        <span class="invalid-feedback"><strong>{{ $errors->first('token') }}</strong></span>
      @endif
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-primary">Verify</button>
    </div>
  </form>
@endsection
