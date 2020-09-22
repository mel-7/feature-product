<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>Gallega Demo</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    {{-- <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
      <style>
      #panorama {
        width: 100%;
        height: 400px;
      }
      .custom-hotspot{
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fbad18;
      }
      div.custom-tooltip span {
        visibility: hidden;
        position: absolute;
        border-radius: 3px;
        background-color: #fff;
        color: #000;
        text-align: center;
        max-width: 300px;
        min-width: 200px;
        padding: 5px 10px;
        margin-left: -220px;
        cursor: default;
        bottom: -50px;
        border: 1px solid #eee;
      }
      div.custom-tooltip:hover span {
        visibility: visible;
      }
      /* div.custom-tooltip:hover span:after {
        content: "";
        position: absolute;
        width: 0;
        height: 0;
        border-width: 10px;
        border-style: solid;
        border-color: #fff transparent transparent transparent;
        bottom: -20px;
        left: -10px;
        margin: 0 50%;
      } */
    </style>
</head>

<body>
    <div id="app">
        <v-app>
            @yield('content')
        </v-app>
    </div>
    @include('product.scripts')
</body>

</html>
