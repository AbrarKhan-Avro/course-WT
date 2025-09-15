@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Song</h1>

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

    <form action="{{ route('songs.update', $song) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Album --}}
        <div class="mb-3">
            <label for="album_id" class="form-label">Album</label>
            <select name="album_id" id="album_id" class="form-control" required>
                @foreach($albums as $album)
                    <option value="{{ $album->id }}"
                        {{ old('album_id', $song->album_id) == $album->id ? 'selected' : '' }}>
                        {{ $album->title }} — {{ $album->artist->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Song Title</label>
            <input type="text" name="title" id="title"
                   value="{{ old('title', $song->title) }}"
                   class="form-control" required>
        </div>

        {{-- Duration --}}
        <div class="mb-3">
            <label for="duration" class="form-label">Duration (e.g. 3:45)</label>
            <input type="text" name="duration" id="duration"
                   value="{{ old('duration', $song->duration) }}"
                   class="form-control">
        </div>

        {{-- Audio File --}}
        <div class="mb-3">
            <label for="audio_file" class="form-label">Replace Audio (leave empty to keep current)</label>
            <input type="file" name="audio_file" id="audio_file" class="form-control">
            @if($song->audio_file)
                <p class="mt-2">Current:
                    <audio controls style="max-width:200px;">
                        <source src="{{ asset('storage/'.$song->audio_file) }}" type="audio/mpeg">
                    </audio>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update Song</button>
        <a href="{{ route('songs.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
