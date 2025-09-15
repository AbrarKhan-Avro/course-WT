@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Songs</h1>
    <a href="{{ route('songs.create') }}" class="btn btn-primary mb-3">Add Song</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <tr>
            <th>Title</th>
            <th>Artist</th>
            <th>Album</th>
            <th>Duration</th>
            <th>Audio</th>
            <th>Actions</th>
        </tr>
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
                    <audio controls>
                        <source src="{{ asset('storage/' . $song->file_path) }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                @endif
            </td>
            <td>
                <a href="{{ route('songs.edit',$song) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('songs.destroy',$song) }}" method="POST" style="display:inline">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection
