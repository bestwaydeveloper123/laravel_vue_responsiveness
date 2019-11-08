@extends('layouts.main')

@section('content')

    <div id="vue-app" class="app-body bg-light theme-color">
        <search-inventory core-type="inventory" role="{{ $role }}"/>
    </div>


@endsection
