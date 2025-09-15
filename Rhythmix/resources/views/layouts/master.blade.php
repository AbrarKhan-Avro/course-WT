<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rhythmix</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <nav class="p-4 bg-gray-800 text-white">
        <a href="{{ url('/') }}">Home</a> |
        <a href="{{ route('artists.index') }}">Artists</a>
        @auth
            | <a href="{{ route('logout') }}"
                 onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                 Logout
               </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
                @csrf
            </form>
        @endauth
    </nav>

    <main class="p-6">
        @yield('content')
    </main>
</body>
</html>
