<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rhythmix</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
    @auth
        <nav class="navbar navbar-expand-lg navbar-light bg-light mb-4">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">Rhythmix</a>
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a href="{{ route('artists.index') }}" class="nav-link">Artists</a></li>
                        <li class="nav-item"><a href="{{ route('albums.index') }}" class="nav-link">Albums</a></li>
                        <li class="nav-item"><a href="{{ route('songs.index') }}" class="nav-link">Songs</a></li>
                        <li class="nav-item"><a href="{{ route('playlists.index') }}" class="nav-link">Playlists</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

    <main>
        @yield('content')
    </main>
</body>
</html>
