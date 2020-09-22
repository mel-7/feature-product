@extends('layouts.builder')

@section('content')
<v-main>
  <v-container>
    <router-view :auth-user="{{ Auth::user() }}" :products="{{Auth::user()->products}}"></router-view>
  </v-container>
</v-main>
@endsection
