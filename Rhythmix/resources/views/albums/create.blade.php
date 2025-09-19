@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Add New Album</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                   <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm p-4">
        <form action="{{ route('albums.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="artist_id" class="form-label">Artist</label>
                <select name="artist_id" id="artist_id" class="form-select" required>
                    @foreach($artists as $artist)
                        <option value="{{ $artist->id }}" {{ old('artist_id') == $artist->id ? 'selected' : '' }}>
                            {{ $artist->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Album Title</label>
                <input type="text" name="title" id="title"
                       value="{{ old('title') }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="release_year" class="form-label">Release Year</label>
                <input type="text" name="release_year" id="release_year"
                       value="{{ old('release_year') }}" class="form-control" placeholder="e.g. 2025">
            </div>

            <div class="mb-3">
                <label for="cover" class="form-label">Album Cover</label>
                <input type="file" name="cover" id="cover" class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Create Album</button>
            <a href="{{ route('albums.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
