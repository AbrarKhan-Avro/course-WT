@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Song</h1>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('songs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Album --}}
        <div class="mb-3">
            <label for="album_id" class="form-label">Album</label>
            <select name="album_id" id="album_id" class="form-control" required>
                <option value="">-- Choose Album --</option>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}" {{ old('album_id') == $album->id ? 'selected' : '' }}>
                        {{ $album->title }} — {{ $album->artist->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Song Title</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title') }}"
                   class="form-control" required>
        </div>

        {{-- Duration --}}
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (e.g. 3:45)</label>
            <input type="text" name="duration" id="duration"
                   value="{{ old('duration') }}"
                   class="form-control">
        </div>

        {{-- Audio File --}}
        <div class="mb-3">
            <label for="audio_file" class="form-label">Audio File (MP3/WAV/OGG)</label>
            <input type="file" name="audio_file" id="audio_file" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Save Song</button>
        <a href="{{ route('songs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
