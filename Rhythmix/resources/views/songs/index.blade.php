@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">🎶 Songs Library</h1>
    <a href="{{ route('songs.create') }}" class="btn btn-primary">
        + Add New Song
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
@endif

<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Title</th>
                <th>Artist</th>
                <th>Album</th>
                <th>Duration</th>
                <th>Play</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($songs as $song)
            @php
                $minutes = floor($song->duration / 60);
                $seconds = $song->duration % 60;
            @endphp
            <tr>
                <td class="fw-semibold">{{ $song->title }}</td>
                <td>{{ $song->artist->name }}</td>
                <td>{{ $song->album->title }}</td>
                <td>{{ sprintf("%d:%02d", $minutes, $seconds) }}</td>
                <td>
                    @if($song->file_path)
                        <button class="btn btn-sm btn-outline-primary play-btn"
                                data-src="{{ asset('storage/' . $song->file_path) }}"
                                data-title="{{ $song->title }}"
                                data-artist="{{ $song->artist->name }}">
                            ▶ Play
                        </button>
                    @else
                        <span class="text-muted">No file</span>
                    @endif
                </td>
                <td class="text-end">
                    <a href="{{ route('songs.edit',$song) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                    <form action="{{ route('songs.destroy',$song) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"
                                onclick="return confirm('Delete this song?')">
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
