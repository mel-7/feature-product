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
margin: 15px;
display: none;
} 

.icon-360 img{ width: 50%; }

.round-container{  
  margin: 0 auto;
    width: 210px;
    }
.center-con {
  display:none;
  width: 24%;
    left: 38%;
    bottom: 65px;
    height: 50px;
    position: absolute;
    margin: 0 auto;
    padding-top: 7px;
    background: rgb(14 14 14 / 50%);
} 
.label-360{ position: relative; }
.round {
  border: 2px solid #fff;
    width: 35px;
    display: inline-block;
    height: 35px;
    border-radius: 100%;
} 
.round-label{
  display: inline-block;
    color: #fff;
    bottom: 10px;
    position: relative;
    font-weight: bold;
    margin-right:10px;
}

#cta{
    width:100%; cursor: pointer; position: absolute;
}

#cta .arrow{left: 2%;}
.arrow {position: absolute; bottom: 0;  margin-left:0px; width: 12px; height: 12px; background-size: contain; top:10px;}
.segunda{margin-left: 8px;}
.next-arrow {
	background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+PHN0eWxlPi5zdDB7ZmlsbDojZmZmfTwvc3R5bGU+PHBhdGggY2xhc3M9InN0MCIgZD0iTTMxOS4xIDIxN2MyMC4yIDIwLjIgMTkuOSA1My4yLS42IDczLjdzLTUzLjUgMjAuOC03My43LjZsLTE5MC0xOTBjLTIwLjEtMjAuMi0xOS44LTUzLjIuNy03My43UzEwOSA2LjggMTI5LjEgMjdsMTkwIDE5MHoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzE5LjEgMjkwLjVjMjAuMi0yMC4yIDE5LjktNTMuMi0uNi03My43cy01My41LTIwLjgtNzMuNy0uNmwtMTkwIDE5MGMtMjAuMiAyMC4yLTE5LjkgNTMuMi42IDczLjdzNTMuNSAyMC44IDczLjcuNmwxOTAtMTkweiIvPjwvc3ZnPg==);
}

@keyframes bounceAlpha {
  0% {opacity: 1; transform: translateX(0px) scale(1);}
  25%{opacity: 0; transform:translateX(10px) scale(0.9);}
  26%{opacity: 0; transform:translateX(-10px) scale(0.9);}
  55% {opacity: 1; transform: translateX(0px) scale(1);}
}

.bounceAlpha {
    animation-name: bounceAlpha;
    animation-duration:1.4s;
    animation-iteration-count:infinite;
    animation-timing-function:linear;
}

.arrow.primera.bounceAlpha {
    animation-name: bounceAlpha;
    animation-duration:1.4s;
    animation-delay:0.2s;
    animation-iteration-count:infinite;
    animation-timing-function:linear;
}

.round .arrow{
    animation-name: bounceAlpha;
    animation-duration:1.4s;
    animation-iteration-count:infinite;
    animation-timing-function:linear;
}
.round .arrow.primera{
    animation-name: bounceAlpha;
    animation-duration:1.4s;
    animation-delay:0.2s;
    animation-iteration-count:infinite;
    animation-timing-function:linear;
}

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

@media (max-width:970px) {
  .center-con { bottom: 35px; height: 40px;width: 40%; left: 30%; } 
  .round-container{ left: 30%; width: 144px; }
    .round-label{ font-size:12px;}
    .round{ width: 25px; height: 25px; }
    .arrow{ top:7px;width: 8px; height: 8px;}
}
 
@media (max-width:576px) {
  .custom-hotspot{ height: 23px; width: 23px;}
  .hp-contents{ width: 100%; }
  h2{ font-size: 14px; }

  .hp-contents p, .hp-contents a { font-size: 11px; }
  .cd-single-point > a { width: 18px; height: 18px;}
    .container {
        height: 275px;
    } 

    .center-con {
      bottom: 35px;
    height: 40px;
    width: 50%;
    left: 28%;} 
    .round-container{ left: 30%; width: 144px; }
    .round-label{ font-size:12px;}
    .round{ width: 25px; height: 25px; }
    .arrow{ top:7px;width: 8px; height: 8px;}
}


.lds-spinner {
  color: official;
  display: inline-block;
  position: absolute;
  width: 80px;
  height: 80px;
  top: 40%;
  left: 50%;
  z-index: 9999;
}
.lds-spinner div {
  transform-origin: 40px 40px;
  animation: lds-spinner 1.2s linear infinite;
}
.lds-spinner div:after {
  content: " ";
  display: block;
  position: absolute;
  top: 3px;
  left: 37px;
  width: 6px;
  height: 18px;
  border-radius: 20%;
  background: #000;
}
.lds-spinner div:nth-child(1) {
  transform: rotate(0deg);
  animation-delay: -1.1s;
}
.lds-spinner div:nth-child(2) {
  transform: rotate(30deg);
  animation-delay: -1s;
}
.lds-spinner div:nth-child(3) {
  transform: rotate(60deg);
  animation-delay: -0.9s;
}
.lds-spinner div:nth-child(4) {
  transform: rotate(90deg);
  animation-delay: -0.8s;
}
.lds-spinner div:nth-child(5) {
  transform: rotate(120deg);
  animation-delay: -0.7s;
}
.lds-spinner div:nth-child(6) {
  transform: rotate(150deg);
  animation-delay: -0.6s;
}
.lds-spinner div:nth-child(7) {
  transform: rotate(180deg);
  animation-delay: -0.5s;
}
.lds-spinner div:nth-child(8) {
  transform: rotate(210deg);
  animation-delay: -0.4s;
}
.lds-spinner div:nth-child(9) {
  transform: rotate(240deg);
  animation-delay: -0.3s;
}
.lds-spinner div:nth-child(10) {
  transform: rotate(270deg);
  animation-delay: -0.2s;
}
.lds-spinner div:nth-child(11) {
  transform: rotate(300deg);
  animation-delay: -0.1s;
}
.lds-spinner div:nth-child(12) {
  transform: rotate(330deg);
  animation-delay: 0s;
}
@keyframes lds-spinner {
  0% {
    opacity: 1;
  }
  100% {
    opacity: 0;
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