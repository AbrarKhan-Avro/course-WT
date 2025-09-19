@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Songs</h1>
    <a href="{{ route('songs.create') }}" class="btn btn-primary mb-3">Add Song</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Duration</th>
                <th>Play</th>
                <th>Actions</th>
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
                <td>{{ sprintf("%d:%02d", $minutes, $seconds) }}</td>
                <td>
                    @if($song->file_path)
                        <button class="btn btn-sm btn-primary play-btn"
                                data-src="{{ asset('storage/' . $song->file_path) }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name }}">
                            ▶ Play
                        </button>
                    @else
                        <span class="text-muted">No file</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('songs.edit',$song) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form action="{{ route('songs.destroy',$song) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Are you sure you want to delete this song?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
