<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Show all playlists belonging to the logged-in user
        $playlists = Auth::user()->playlists;
        return view('playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Auth::user()->playlists()->create($validated);

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Playlist $playlist)
    {
        // Ensure the user owns this playlist
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $songs = $playlist->songs;
        return view('playlists.show', compact('playlist', 'songs'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        return view('playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $playlist->update($validated);

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->delete();

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist deleted successfully!');
    }

    /**
     * Add a song to the playlist.
     */
    public function addSong(Request $request, Playlist $playlist)
    {
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'song_id' => 'required|exists:songs,id',
        ]);

        $playlist->songs()->syncWithoutDetaching($request->song_id);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Song added to playlist!');
    }

    /**
     * Remove a song from the playlist.
     */
    public function removeSong(Playlist $playlist, Song $song)
    {
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->songs()->detach($song->id);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Song removed from playlist!');
    }
}
