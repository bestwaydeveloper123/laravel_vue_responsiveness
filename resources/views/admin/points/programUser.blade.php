@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      <program-user date-from="{{ $from }}" date-to="{{ $to }}"></program-user>
    </div>
  </div>
@endsection
