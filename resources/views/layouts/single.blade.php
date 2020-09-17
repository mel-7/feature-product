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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- slick slider -->
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//kenwheeler.github.io/slick/slick/slick-theme.css"/>
    
  

    <script src="https://unpkg.com/spritespin@4.0.11/release/spritespin.js"></script>

    <link rel="stylesheet" href="{{ asset('css/pannellum.css') }}" />
    <script type="text/javascript" src="{{ asset('js/libpannellum.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pannellum.js') }}"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
      .spritespin-wrapper {
          max-width: 1366;
          margin: 0 auto;
          position: relative;
      }
        #panorama {
        width: 100%;
         
        min-height:450px;
      }
      .custom-hotspot{
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #fbad18;
        cursor:pointer;
      }
      .cd-single-point {
            position: absolute;
            border-radius: 50%;
            display:none;
        }
      .cd-single-point > a {
            position: relative;
            z-index: 2;
            display: block;
            width: 25px;
            height: 25px;
            border-radius: inherit;
            background: #d95353;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.3);
            -webkit-transition: background-color 0.2s;
            -moz-transition: background-color 0.2s;
            transition: background-color 0.2s;
        }
        .cd-img-replace {
            display: inline-block;
            overflow: hidden;
            text-indent: 100%;
            white-space: nowrap;
        }
        .cd-single-point > a::before {
          height: 12px;
          width: 2px;
        }
        .cd-single-point > a::after {
            height: 2px;
            width: 12px;
        }
        ul, li {  list-style: none; }
        .cd-single-point > a::after, .cd-single-point > a:before {
              content: "";
              position: absolute;
              left: 50%;
              top: 50%;
              bottom: auto;
              right: auto;
              -webkit-transform: translateX(-50%) translateY(-50%);
              -moz-transform: translateX(-50%) translateY(-50%);
              -ms-transform: translateX(-50%) translateY(-50%);
              -o-transform: translateX(-50%) translateY(-50%);
              transform: translateX(-50%) translateY(-50%);
              background-color: #ffffff;
              -webkit-transition-property: -webkit-transform;
              -moz-transition-property: -moz-transform;
              transition-property: transform;
              -webkit-transition-duration: 0.2s;
              -moz-transition-duration: 0.2s;
              transition-duration: 0.2s;
          }
        .cd-single-point::after {
            content: "";
            position: absolute;
            z-index: 1;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            border-radius: inherit;
            background-color: transparent;
            -webkit-animation: cd-pulse 2s infinite;
            -moz-animation: cd-pulse 2s infinite;
            animation: cd-pulse 2s infinite;
        }
 
       .icon-360 {position: absolute;
    top: 0;
    margin: 30px;} 

@-webkit-keyframes cd-pulse {
  0% {
    -webkit-transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  100% {
    -webkit-transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
}
@-moz-keyframes cd-pulse {
  0% {
    -moz-transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  100% {
    -moz-transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
}
@keyframes cd-pulse {
  0% {
    -webkit-transform: scale(1);
    -moz-transform: scale(1);
    -ms-transform: scale(1);
    -o-transform: scale(1);
    transform: scale(1);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  50% {
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0.8);
  }
  100% {
    -webkit-transform: scale(1.6);
    -moz-transform: scale(1.6);
    -ms-transform: scale(1.6);
    -o-transform: scale(1.6);
    transform: scale(1.6);
    box-shadow: inset 0 0 1px 1px rgba(217, 83, 83, 0);
  }
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
      .hotspots-label { width: 60%;
    margin: 0 auto;} 
    
    .active-hps{position: absolute;
    background: #fff;
    padding: 2px 10px;
    bottom: 21px;
    left: -8px;
    opacity: .75;
    border-radius: 50%;}

    #inactive-hp .active-hps{ display: none;}

    /* Modal */
    /* The Modal (background) */
.modal {
  display: none;
  position: fixed;
  z-index: 99999;
  padding-top: 0;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: black;
}

/* Modal Content */
.modal-content {
  position: relative;
  background-color: #fefefe;
  margin: auto;
  padding: 0;
  width: 90%;
  max-width: 1200px;
 
}

.hp-contents{ position: absolute;bottom: 0; padding:10px; color: #fff;margin-bottom: 7px;width: 50%;text-align: center;background: rgb(14 14 14 / 30%); }
.hp-contents a.c-color { text-decoration: none; color: #e4b327; }
.close:hover, .close:focus{
  color: #dcae05;
  border-color: #dcae05;
}

/* The Close Button */
.close {
  color: #ff0202;
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 20px;
    font-weight: bold;
    z-index: 999;
    border: 1px solid #ff0a0a;
    border-radius: 50%;
    padding: 0px 10px;
}

.close:hover,
.close:focus {
  color: #999;
  text-decoration: none;
  cursor: pointer;
}

.mySlides {
  display: none;
  background: #000;
}

.cursor {
  cursor: pointer;
}

/* Next & previous buttons */
.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: auto;
  padding: 16px;
  margin-top: -50px;
  color: white;
  font-weight: bold;
  font-size: 20px;
  transition: 0.6s ease;
  border-radius: 0 3px 3px 0;
  user-select: none;
  -webkit-user-select: none;
}

/* Position the "next button" to the right */
.next {
  right: 0;
  border-radius: 3px 0 0 3px;
}

/* On hover, add a black background color with a little bit see-through */
.prev:hover,
.next:hover {
  background-color: rgba(0, 0, 0, 0.8);
}
.open-exterior, .open-interior{
    text-transform: uppercase;
    cursor: pointer;
    color: #fff;
    padding: 7px 15px;
    background-color: #191e47;
    font-size: 10px;
    line-height: 13px;
    z-index: 99;
    }
.open-interior, .open-exterior,  .photos.img, .videos.img, .content-action{ display: none;}
.content-action .img{text-transform: uppercase;
    cursor: pointer;
    color: #fff;
    padding: 7px 15px;
    background-color: #191e47;
    font-size: 10px;
    line-height: 13px;
    z-index: 99;}
   
.active{background-color: #fbad18;}
.content-action{margin-top: -27px; 
    justify-content: center;}
    .divider{
      margin: 0 10px;
    border: 2px solid #007bc3;
    z-index: 9;
    }
    
  
  .slick-slide{ width: 230px !important;}
  .slick-track { min-width: 1000px;}
  .video-slider{ margin-top: -175px; }
  .display-videos-thumbnails{
    visibility: hidden;
    width: 1300px;
    margin-left: 30px;}
    .slick-initialized { visibility: visible; }
    .container{width: 100%;  max-width: 1366px; height:768px; padding:0}

@media (max-width:576px) {
    .container {
        height: 275px;
    }
}
    </style>
<script> var base_url = "{{URL::to('/')}}"; </script>
</head>

<body>
    <div id="app">
        <v-app>
            @yield('content')
        </v-app>
    </div>
    @include('product.scripts')

    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
</body>

</html>