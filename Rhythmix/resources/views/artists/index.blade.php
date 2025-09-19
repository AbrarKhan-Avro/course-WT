@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="mb-0">Artists</h1>
        <a href="{{ route('artists.create') }}" class="btn btn-primary">+ Add Artist</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($artists as $artist)
                    <tr>
                        <td>{{ $artist->id }}</td>
                        <td>
                            @if($artist->photo)
                                <img src="{{ asset('storage/'.$artist->photo) }}"
                                     alt="Artist photo"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                            @else
                                <img src="{{ asset('images/placeholder.jpg') }}"
                                     alt="No photo"
                                     style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                            @endif
                        </td>
                        <td>{{ $artist->name }}</td>
                        <td>
                            <a href="{{ route('artists.edit', $artist) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('artists.destroy', $artist) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this artist?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center">No artists yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
