<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlbumController extends Controller
{
    /**
     * Display a listing of the albums.
     */
    public function index()
    {
        // eager-load artist to avoid N+1 query
        $albums = Album::with('artist')->get();
        return view('albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new album.
     */
    public function create()
    {
        $artists = Artist::all();
        return view('albums.create', compact('artists'));
    }

    /**
     * Store a newly created album in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'artist_id'    => 'required|exists:artists,id',
            'title'        => 'required|string|max:255',
            'release_year' => 'nullable|digits:4|integer',
            'cover_image'  => 'nullable|image|max:2048',
        ]);

        $path = $request->file('cover_image')
            ? $request->file('cover_image')->store('albums', 'public')
            : null;

        Album::create([
            'artist_id'    => $validated['artist_id'],
            'title'        => $validated['title'],
            'release_year' => $validated['release_year'],
            'cover_image'  => $path,
        ]);

        return redirect()->route('albums.index')->with('success', 'Album added!');
    }

    /**
     * Show the form for editing the specified album.
     */
    public function edit(Album $album)
    {
        $artists = Artist::all();
        return view('albums.edit', compact('album', 'artists'));
    }

    /**
     * Update the specified album in storage.
     */
    public function update(Request $request, Album $album)
    {
        $validated = $request->validate([
            'artist_id'    => 'required|exists:artists,id',
            'title'        => 'required|string|max:255',
            'release_year' => 'nullable|digits:4|integer',
            'cover_image'  => 'nullable|image|max:2048',
        ]);

        // handle cover replacement
        if ($request->hasFile('cover_image')) {
            if ($album->cover_image) {
                Storage::disk('public')->delete($album->cover_image);
            }
            $album->cover_image = $request->file('cover_image')->store('albums', 'public');
        }

        $album->artist_id    = $validated['artist_id'];
        $album->title        = $validated['title'];
        $album->release_year = $validated['release_year'];
        $album->save();

        return redirect()->route('albums.index')->with('success', 'Album updated!');
    }

    /**
     * Remove the specified album from storage.
     */
    public function destroy(Album $album)
    {
        if ($album->cover_image) {
            Storage::disk('public')->delete($album->cover_image);
        }
        $album->delete();
        return redirect()->route('albums.index')->with('success', 'Album deleted!');
    }
}
