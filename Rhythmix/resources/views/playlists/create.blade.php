@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Playlist</h1>

    <form method="POST" action="{{ route('playlists.store') }}">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <button class="btn btn-success">Save Playlist</button>
    </form>
</div>
@endsection
