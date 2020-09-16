<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Product Feature') }}</title> --}}
    <title>Gallega Spinner - Alpha</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/pannellum.css') }}" rel="stylesheet"> --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script> var tempHotspots = [];</script>
</head>

<body>
    <div id="app">
        <v-app>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            <builder-navigation :auth-user="{{ Auth::user() }}"></builder-navigation>
            @yield('content')
        </v-app>
    </div>
    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{-- <script src="https://unpkg.com/spritespin@4.0.11/release/spritespin.js"></script> --}}
    {{-- <script src="https://unpkg.com/spritespin@4.1.0/release/spritespin.js"></script> --}}
    {{-- <script src="https://unpkg.com/spritespin@x.x.x/release/spritespin.js"></script> --}}
    
    {{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script> --}}
    <script src="{{ asset('js/libpannellum.js') }}"></script>
    <script src="{{ asset('js/pannellum.js') }}"></script>
    <script src="{{ asset('js/sp.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</body>

</html>