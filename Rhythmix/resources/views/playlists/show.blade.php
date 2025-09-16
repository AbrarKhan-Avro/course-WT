@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $playlist->name }}</h1>
    <p>{{ $playlist->description }}</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Songs inside playlist --}}
    <h3>Songs in this Playlist</h3>
    @if($songs->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Artist</th>
                    <th>Album</th>
                    <th>Duration</th>
                    <th>Audio</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            @foreach($songs as $song)
                @php
                    $minutes = floor($song->duration / 60);
                    $seconds = $song->duration % 60;
                @endphp
                <tr>
                    <td>{{ $song->title }}</td>
                    <td>{{ $song->artist->name }}</td>
                    <td>{{ $song->album->title }}</td>
                    <td>{{ sprintf('%d:%02d', $minutes, $seconds) }}</td>
                    <td>
                        @if($song->file_path)
                        <audio controls>
                            <source src="{{ asset('storage/'.$song->file_path) }}" type="audio/mpeg">
                            Your browser does not support the audio element.
                        </audio>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('playlists.removeSong',[$playlist,$song]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Remove this song?')">Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No songs yet in this playlist.</p>
    @endif

    {{-- Add new song --}}
    <h3 class="mt-4">Add a Song</h3>
    <form method="POST" action="{{ route('playlists.addSong',$playlist) }}">
        @csrf
        <div class="row">
            <div class="col-md-8 mb-2">
                <select name="song_id" class="form-control" required>
                    <option value="">-- Select a song --</option>
                    @foreach(\App\Models\Song::all() as $song)
                        <option value="{{ $song->id }}">{{ $song->title }} ({{ $song->artist->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-primary">Add Song</button>
            </div>
        </div>
    </form>
</div>
@endsection
