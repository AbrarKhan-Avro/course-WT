@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Albums</h1>
    <a href="{{ route('albums.create') }}" class="btn btn-primary mb-3">Add Album</a>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <table class="table table-bordered">
        <tr><th>Title</th><th>Artist</th><th>Year</th><th>Cover</th><th>Actions</th></tr>
        @foreach($albums as $album)
        <tr>
            <td>{{ $album->title }}</td>
            <td>{{ $album->artist->name }}</td>
            <td>{{ $album->release_year }}</td>
            <td>
              @if($album->cover_image)
                <img src="{{ asset('storage/'.$album->cover_image) }}" width="60">
              @endif
            </td>
            <td>
                <a href="{{ route('albums.edit',$album) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('albums.destroy',$album) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
