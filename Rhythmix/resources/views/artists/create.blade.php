@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Add Artist</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('artists.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name">Name</label>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-control"
                      rows="4">{{ old('bio') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image">Image</label>
            <input id="image" type="file" name="image" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('artists.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
