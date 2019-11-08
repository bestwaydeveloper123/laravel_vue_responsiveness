@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      <account-manager-point user-name="{{ $user }}" date-from="{{ $from }}" date-to="{{ $to }}" />
    </div>
  </div>
@endsection
