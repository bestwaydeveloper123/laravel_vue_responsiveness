@extends('layouts.main')

@section('content')

    <div id="vue-app" class="app-body bg-light theme-color">
        <search-core core-type="core" role="{{ $role }}"/>
    </div>


@endsection
