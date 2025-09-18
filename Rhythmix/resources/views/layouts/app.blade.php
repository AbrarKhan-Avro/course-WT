<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Rhythmix') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- App CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
    <div id="app">
        <!-- ================= NAVBAR ================= -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Rhythmix') }}
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
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/dashboard') }}">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('artists.index') }}">Artists</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('albums.index') }}">Albums</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('songs.index') }}">Songs</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('playlists.index') }}">Playlists</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">Login</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">Register</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
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
            <div class="container d-flex align-items-center">
                <span id="current-song-title" class="me-3 fw-bold">No song playing</span>
                <audio id="audio-player" controls style="width: 100%;">
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
        document.addEventListener('DOMContentLoaded', function () {
            const audioPlayer = document.getElementById('audio-player');
            const audioSource = document.getElementById('audio-source');
            const currentSongTitle = document.getElementById('current-song-title');

            // Play any song when a play button is clicked
            document.body.addEventListener('click', function (e) {
                if (e.target.classList.contains('play-btn')) {
                    e.preventDefault();
                    const fileUrl = e.target.getAttribute('data-src');
                    const title   = e.target.getAttribute('data-title') || 'Playing...';

                    audioSource.src = fileUrl;
                    currentSongTitle.textContent = title;
                    audioPlayer.load();
                    audioPlayer.play();
                }
            });
        });
    </script>
</body>
</html>
