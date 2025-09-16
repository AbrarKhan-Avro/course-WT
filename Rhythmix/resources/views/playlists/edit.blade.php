@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Playlist</h1>

    <form method="POST" action="{{ route('playlists.update',$playlist) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name',$playlist->name) }}" class="form-control" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description',$playlist->description) }}</textarea>
        </div>

        <button class="btn btn-success">Update Playlist</button>
    </form>
</div>
@endsection
