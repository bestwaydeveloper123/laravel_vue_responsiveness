@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main">
      <div class="card mb-0" id="vue-app">
        <team-settings name="{{ $UserName }}" />
      </div>
    </div>
  </div>
@endsection
