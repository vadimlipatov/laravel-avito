@extends('layouts.app')

@section('content')
    @include('admin.regions._nav')

    <form action="{{ route('admin.regions.update', $region) }}" method="post">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="col-form-label">Name</label>
            <input type="text" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                name="name" value="{{ old('name', $region->name) }}" required>
            @if ($errors->has('name'))
                <span class="invalid-feedback"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="slug" class="col-form-label">Slug</label>
            <input type="text" id="slug" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}"
                name="slug" value="{{ old('slug', $region->slug) }}" required>
            @if ($errors->has('slug'))
                <span class="invalid-feedback"><strong>{{ $errors->first('slug') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <label for="parent" class="col-form-label">Parent</label>
            <select id="parent" class="form-control{{ $errors->has('parent') ? ' is-invalid' : '' }}" name="parent">
                <option value="" disabled>Choose parent</option>
                @foreach (collect() as $value => $label)
                    <option value="{{ $value }}"
                        {{ $value === old('parent', $region->parent->name) ? ' selected' : '' }}>
                        {{ $label }}</option>
                @endforeach
            </select>
            @if ($errors->has('parent'))
                <span class="invalid-feedback"><strong>{{ $errors->first('roparentle') }}</strong></span>
            @endif
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
