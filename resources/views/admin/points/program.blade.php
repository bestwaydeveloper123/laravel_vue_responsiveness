@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      <program-page role="{{ auth()->user()->role }}"></program-page>
    </div>
  </div>
@endsection
