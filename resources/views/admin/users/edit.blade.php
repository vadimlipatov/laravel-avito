@extends('layouts.app')

@section('content')
    @include('admin.users._nav')

    <form action="{{route('admin.users.store')}}" method="post">
        @csrf

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input type="text" id="name" class="form-control{{$errors->has('name') ? ' is-invalid' : ''}}" name="name" value="{{old('name', $user->name)}}" required>
            @if($errors->has('name'))
                <span class="invalid-feedback"><strong>{{$errors->first('name')}}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="email" class="col-form-label">Email</label>
            <input type="text" id="email" class="form-control{{$errors->has('email') ? ' is-invalid' : ''}}" name="email" value="{{old('email', $user->email)}}" required>
            @if($errors->has('email'))
                <span class="invalid-feedback"><strong>{{$errors->first('email')}}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="status" class="col-form-label">Status</label>
            <select name="status" id="status" type="email" class="form-control{{$errors->has('status') ? ' is-invalid' : ''}}">
                @foreach($statuses as $value => $label)
                    <option value="{{$value}}"{{$value === old('status', $user->status) ? ' selected' : ''}}>{{$label}}</option>
                @endforeach
                @if($errors->has('email'))
                    <span class="invalid-feedback"><strong>{{$errors->first('status')}}</strong></span>
                @endif
            </select>
        </div>

        <div class="form-group">
            <button type="button" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
