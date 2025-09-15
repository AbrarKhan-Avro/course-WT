@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Artists</h1>
    <a href="{{ route('artists.create') }}" class="btn btn-primary mb-3">Add Artist</a>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <table class="table table-bordered">
        <tr><th>Name</th><th>Bio</th><th>Image</th><th>Actions</th></tr>
        @foreach($artists as $artist)
        <tr>
            <td>{{ $artist->name }}</td>
            <td>{{ $artist->bio }}</td>
            <td>
              @if($artist->image_path)
                 <img src="{{ asset('storage/'.$artist->image_path) }}" width="60">
              @endif
            </td>
            <td>
                <a href="{{ route('artists.edit',$artist) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('artists.destroy',$artist) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
