@extends('layouts.builder')

@section('content')
<v-main>
  <v-container>
   
    <router-view :auth-user="{{ Auth::user() }}" :product="{{$product}}"></router-view>
  </v-container>
</v-main>
@endsection
