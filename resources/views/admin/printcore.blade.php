
{{--@extends('layouts.main')--}}

{{--@section('content')--}}
<script type="text/javascript" src="{{ asset('js/app.js') }}" ></script>

<div class="app-body bg-light theme-color pl-2 print-style">
  <div class="main">
    <div class="maxprintsize">
      <ul class="list-unstyled maxprintsize">
        <li>{{"C:s".$id}}</li>
        <li>{{"HW:".$hardware}}</li>
        <li>{{"SW:".$software}}</li>
        <li>{{"XYZ:".$xyzposition}}</li>
        <li>{{$description}}</li>
      </ul>
    </div>
  </div>
</div>

<style>
.maxprintsize {
  max-width:400px;
  max-height:50px;
  margin-top:0px;
  margin-bottom:0px;
}
.print-style {
  font-size: .8rem;
  line-height: .7;
  letter-spacing: -.5px;
  font-weight: 700;
}
.list-unstyled {
  list-style: none;
  padding-top:5px;
  padding-left:5px;
}
</style>
{{--@endsection--}}
