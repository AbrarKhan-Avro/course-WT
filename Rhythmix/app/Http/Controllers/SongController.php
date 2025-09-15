<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SongController extends Controller
{
    /**
     * Display a listing of the songs.
     */
    public function index()
    {
        // Load songs with related album and artist
        $songs = Song::with('album.artist')->get();
        return view('songs.index', compact('songs'));
    }

    /**
     * Show the form for creating a new song.
     */
    public function create()
    {
        // Get albums (with artists) for dropdown
        $albums = Album::with('artist')->get();
        return view('songs.create', compact('albums'));
    }

    /**
     * Store a newly created song in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'album_id'   => 'required|exists:albums,id',
            'title'      => 'required|string|max:255',
            'duration'   => 'nullable|string|max:10', // input as mm:ss
            'audio_file' => 'required|mimes:mp3,wav,ogg|max:20480', // up to 20 MB
        ]);

        $album = Album::findOrFail($validated['album_id']);

        // Convert duration mm:ss → total seconds
        $durationInSeconds = null;
        if (!empty($validated['duration'])) {
            $parts = explode(':', $validated['duration']);
            if (count($parts) == 2) {
                $durationInSeconds = intval($parts[0]) * 60 + intval($parts[1]);
            } elseif (count($parts) == 1) {
                $durationInSeconds = intval($parts[0]);
            }
        }

        // Upload audio file
        $path = $request->file('audio_file')->store('songs', 'public');

        // Create the song
        Song::create([
            'album_id'  => $album->id,
            'artist_id' => $album->artist_id,  // Automatically set from album
            'title'     => $validated['title'],
            'duration'  => $durationInSeconds,
            'file_path' => $path,
        ]);

        return redirect()->route('songs.index')->with('success', 'Song added!');
    }

    /**
     * Show the form for editing the specified song.
     */
    public function edit(Song $song)
    {
        $albums = Album::with('artist')->get();
        return view('songs.edit', compact('song', 'albums'));
    }

    /**
     * Update the specified song in storage.
     */
    public function update(Request $request, Song $song)
    {
        $validated = $request->validate([
            'album_id'   => 'required|exists:albums,id',
            'title'      => 'required|string|max:255',
            'duration'   => 'nullable|string|max:10',
            'audio_file' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        ]);

        $album = Album::findOrFail($validated['album_id']);

        // Convert duration mm:ss → total seconds
        $durationInSeconds = null;
        if (!empty($validated['duration'])) {
            $parts = explode(':', $validated['duration']);
            if (count($parts) == 2) {
                $durationInSeconds = intval($parts[0]) * 60 + intval($parts[1]);
            } elseif (count($parts) == 1) {
                $durationInSeconds = intval($parts[0]);
            }
        }

        // If a new audio file was uploaded, replace the old one
        if ($request->hasFile('audio_file')) {
            if ($song->file_path) {
                Storage::disk('public')->delete($song->file_path);
            }
            $song->file_path = $request->file('audio_file')->store('songs', 'public');
        }

        // Update song details
        $song->album_id  = $album->id;
        $song->artist_id = $album->artist_id;
        $song->title     = $validated['title'];
        $song->duration  = $durationInSeconds;
        $song->save();

        return redirect()->route('songs.index')->with('success', 'Song updated!');
    }

    /**
     * Remove the specified song from storage.
     */
    public function destroy(Song $song)
    {
        // Delete the stored audio file if it exists
        if ($song->file_path) {
            Storage::disk('public')->delete($song->file_path);
        }

        $song->delete();
        return redirect()->route('songs.index')->with('success', 'Song deleted!');
    }
}
