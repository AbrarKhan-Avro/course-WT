<?php

namespace App\Http\Controllers;

use App\Models\Artist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all artists for the list page
        $artists = Artist::all();
        return view('artists.index', compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Show the form to add a new artist
        return view('artists.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'bio'   => 'nullable|string',
            'image' => 'nullable|image|max:2048', // 2 MB max
        ]);

        // Handle optional image upload
        $path = $request->hasFile('image')
            ? $request->file('image')->store('artists', 'public')
            : null;

        // Create the artist record
        Artist::create([
            'name'       => $validated['name'],
            'bio'        => $validated['bio'] ?? null,
            'image_path' => $path,
        ]);

        return redirect()->route('artists.index')->with('success', 'Artist added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Artist $artist)
    {
        // (Optional) Show a single artist's details
        return view('artists.show', compact('artist'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Artist $artist)
    {
        // Show the edit form with existing data
        return view('artists.edit', compact('artist'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Artist $artist)
    {
        // Validate request
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'bio'   => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // If a new image is uploaded, replace the old one
        if ($request->hasFile('image')) {
            if ($artist->image_path) {
                Storage::disk('public')->delete($artist->image_path);
            }
            $artist->image_path = $request->file('image')->store('artists', 'public');
        }

        // Update other fields
        $artist->name = $validated['name'];
        $artist->bio  = $validated['bio'] ?? null;
        $artist->save();

        return redirect()->route('artists.index')->with('success', 'Artist updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Artist $artist)
    {
        // Delete image if it exists
        if ($artist->image_path) {
            Storage::disk('public')->delete($artist->image_path);
        }

        // Delete artist record
        $artist->delete();

        return redirect()->route('artists.index')->with('success', 'Artist deleted successfully!');
    }
}
