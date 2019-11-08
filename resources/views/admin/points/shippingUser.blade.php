@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      <shipping-user id="{{ isset($id) ? $id : '' }}" />
    </div>
  </div>
@endsection
