@extends('layouts.app')

@section('content')
@include('admin.adverts.categories._nav')

<a href="{{ route('admin.adverts.categories.create') }}" class="btn btn-success mb-3">Add Category</a>

<table class="table-bordered table-striped table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Slug</th>
    </tr>
  </thead>
  <tbody>

    @foreach ($categories as $category)
    <tr>
      <td>
        @for ($i = 0; $i < $category->depth; $i++)
          &mdash;
          @endfor

          <a href="{{ route('admin.adverts.categories.show', $category) }}">{{ $category->name }}</a>
      </td>
      <td>{{ $category->slug }}</td>
      <td>
        <div class="d-flex flex-row">
          <form class="mr-1" action="{{ route('admin.adverts.categories.first', $category) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </button>
          </form>
          <form class="mr-1" action="{{ route('admin.adverts.categories.up', $category) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="fa fa-angle-up" aria-hidden="true"></i>
            </button>
          </form>
          <form class="mr-1" action="{{ route('admin.adverts.categories.down', $category) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="fa fa-angle-down" aria-hidden="true"></i>
            </button>
          </form>
          <form class="mr-1" action="{{ route('admin.adverts.categories.last', $category) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-primary">
              <i class="fa fa-angle-double-down" aria-hidden="true"></i>
            </button>
          </form>
        </div>
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
@endsection
