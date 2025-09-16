@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Playlists</h1>

    <a href="{{ route('playlists.create') }}" class="btn btn-primary mb-3">Create Playlist</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($playlists->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Songs</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @foreach($playlists as $playlist)
                <tr>
                    <td><a href="{{ route('playlists.show',$playlist) }}">{{ $playlist->name }}</a></td>
                    <td>{{ $playlist->description }}</td>
                    <td>{{ $playlist->songs()->count() }}</td>
                    <td>
                        <a href="{{ route('playlists.edit',$playlist) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('playlists.destroy',$playlist) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this playlist?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <p>No playlists yet. Create one!</p>
    @endif
</div>
@endsection
