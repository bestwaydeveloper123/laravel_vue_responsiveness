@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
	<div class="main" id="vue-app">
		<patch-notes email-add="{{ $email }}"/>
	</div>
</div>
@stop
