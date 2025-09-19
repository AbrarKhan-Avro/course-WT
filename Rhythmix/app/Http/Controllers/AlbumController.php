<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    public function index()
    {
        // eager-load artist to avoid N+1 query
        $albums = Album::with('artist')->get();
        return view('albums.index', compact('albums'));
    }

    public function create()
    {
        $artists = Artist::all();
        return view('albums.create', compact('artists'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'artist_id'    => 'required|exists:artists,id',
            'title'        => 'required|string|max:255',
            'release_year' => 'nullable|digits:4|integer',
            'cover'        => 'nullable|image|max:2048',
        ]);

        $path = $request->file('cover')
            ? $request->file('cover')->store('albums', 'public')
            : null;

        Album::create([
            'artist_id'    => $validated['artist_id'],
            'title'        => $validated['title'],
            'release_year' => $validated['release_year'],
            'cover_path'   => $path,
        ]);

        return redirect()->route('albums.index')->with('success', 'Album added!');
    }

    public function edit(Album $album)
    {
        $artists = Artist::all();
        return view('albums.edit', compact('album', 'artists'));
    }

    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'artist_id'    => 'required|exists:artists,id',
            'title'        => 'required|string|max:255',
            'release_year' => 'nullable|digits:4|integer',
            'cover'        => 'nullable|image|max:2048',
        ]);

        // handle cover replacement
        if ($request->hasFile('cover')) {
            if ($album->cover_path) {
                Storage::disk('public')->delete($album->cover_path);
            }
            $album->cover_path = $request->file('cover')->store('albums', 'public');
        }

        $album->artist_id    = $validated['artist_id'];
        $album->title        = $validated['title'];
        $album->release_year = $validated['release_year'];
        $album->save();

        return redirect()->route('albums.index')->with('success', 'Album updated!');
    }

    public function destroy(Album $album)
    {
        if ($album->cover_path) {
            Storage::disk('public')->delete($album->cover_path);
        }
        $album->delete();
        return redirect()->route('albums.index')->with('success', 'Album deleted!');
    }
}
