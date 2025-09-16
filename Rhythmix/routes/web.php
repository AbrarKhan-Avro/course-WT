<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArtistController;
use App\Http\Controllers\AlbumController;
use App\Http\Controllers\SongController;
use App\Http\Controllers\PlaylistController; // ✅ Added
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ✅ Profile routes (from Breeze starter)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ Artist CRUD routes
    Route::resource('artists', ArtistController::class);

    // ✅ Album CRUD routes
    Route::resource('albums', AlbumController::class);

    // ✅ Song CRUD routes
    Route::resource('songs', SongController::class);

    // ✅ Playlist CRUD routes
    Route::resource('playlists', PlaylistController::class);

    // ✅ Add/Remove songs inside a playlist
    Route::post('playlists/{playlist}/add-song', [PlaylistController::class, 'addSong'])
        ->name('playlists.addSong');
    Route::delete('playlists/{playlist}/remove-song/{song}', [PlaylistController::class, 'removeSong'])
        ->name('playlists.removeSong');
});

require __DIR__ . '/auth.php';
