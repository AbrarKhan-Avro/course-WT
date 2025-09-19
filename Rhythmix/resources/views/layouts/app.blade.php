<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Rhythmix') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font (Nunito) -->
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Nunito', sans-serif; }
        .navbar-brand { font-weight: 700; letter-spacing: .5px; }
        main.container { background:#fff; padding:2rem; border-radius: .75rem; box-shadow:0 0 15px rgba(0,0,0,0.05); }
        #player-bar { box-shadow:0 -2px 6px rgba(0,0,0,.15); }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<div id="app">
    <!-- ================= NAVBAR ================= -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                🎵 {{ config('app.name', 'Rhythmix') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Left Side -->
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('artists.index') }}">Artists</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('albums.index') }}">Albums</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('songs.index') }}">Songs</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('playlists.index') }}">Playlists</a></li>
                    @endauth
                </ul>

                <!-- Right Side -->
                <ul class="navbar-nav ms-auto">
                    @guest
                        @if (Route::has('login'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                        @endif
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="container mb-5">
        @yield('content')
    </main>

    <!-- ================= GLOBAL MUSIC PLAYER ================= -->
    <div id="player-bar" class="bg-dark text-light p-3 fixed-bottom">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Song Info -->
            <div>
                <strong id="player-title">No song playing</strong><br>
                <small id="player-artist"></small>
            </div>

            <!-- Controls -->
            <div>
                <button class="btn btn-sm btn-light me-2" id="prev-btn">⏮ Prev</button>
                <button class="btn btn-sm btn-light me-2" id="play-pause-btn">▶ Play</button>
                <button class="btn btn-sm btn-light" id="next-btn">⏭ Next</button>
            </div>

            <!-- Hidden Audio -->
            <audio id="audio-player">
                <source id="audio-source" src="" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Global Player Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const audioPlayer  = document.getElementById('audio-player');
    const audioSource  = document.getElementById('audio-source');
    const titleEl      = document.getElementById('player-title');
    const artistEl     = document.getElementById('player-artist');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const nextBtn      = document.getElementById('next-btn');
    const prevBtn      = document.getElementById('prev-btn');

    let playlist = [];      // {src,title,artist}
    let currentIndex = -1;
    let isPlaying = false;

    function playSong(index) {
        if (index < 0 || index >= playlist.length) return;
        currentIndex = index;
        const song = playlist[index];
        audioSource.src = song.src;
        titleEl.textContent  = song.title;
        artistEl.textContent = song.artist;
        audioPlayer.load();
        audioPlayer.play();
        isPlaying = true;
        playPauseBtn.textContent = '⏸ Pause';
    }

    playPauseBtn.addEventListener('click', () => {
        if (!playlist.length) return;
        if (isPlaying) {
            audioPlayer.pause();
            isPlaying = false;
            playPauseBtn.textContent = '▶ Play';
        } else {
            audioPlayer.play();
            isPlaying = true;
            playPauseBtn.textContent = '⏸ Pause';
        }
    });

    nextBtn.addEventListener('click', () => {
        if (playlist.length) playSong((currentIndex + 1) % playlist.length);
    });

    prevBtn.addEventListener('click', () => {
        if (playlist.length) playSong((currentIndex - 1 + playlist.length) % playlist.length);
    });

    audioPlayer.addEventListener('ended', () => {
        if (playlist.length > 1) nextBtn.click();
    });

    // Add song to queue
    document.body.addEventListener('click', e => {
        if (e.target.classList.contains('play-btn')) {
            e.preventDefault();
            const songData = {
                src:    e.target.dataset.src,
                title:  e.target.dataset.title,
                artist: e.target.dataset.artist
            };
            let idx = playlist.findIndex(s => s.src === songData.src);
            if (idx === -1) {
                playlist.push(songData);
                idx = playlist.length - 1;
            }
            playSong(idx);
        }
    });
});
</script>
</body>
</html>
