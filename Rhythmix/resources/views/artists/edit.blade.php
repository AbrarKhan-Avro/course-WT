@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Edit Artist</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('artists.update', $artist) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name">Name</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name', $artist->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control"
                      rows="4">{{ old('bio', $artist->bio) }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image">Image (leave blank to keep current)</label>
            <input id="image" type="file" name="image" class="form-control">
            @if($artist->image_path)
                <p class="mt-2">Current:
                   <img src="{{ asset('storage/'.$artist->image_path) }}" width="100">
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('artists.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
