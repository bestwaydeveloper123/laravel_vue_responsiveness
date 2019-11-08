@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
	<div class="main" id="vue-app">
    <shipping-search users="{{ $Users }}"/>
	</div>
</div>
@stop
