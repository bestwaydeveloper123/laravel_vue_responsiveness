
{{--@extends('layouts.main')--}}

{{--@section('content')--}}
<div class="app-body bg-light theme-color pl-2 print-style">
  <div class="main">
    <div class="maxprintsize">
      <ul class="list-unstyled maxprintsize" style="float: left;">
        <li><img src="data:image/png;base64,{{ DNS1D::getBarcodePNG(strval($id), 'C128') }}" alt="barcode" /></li>
      </ul>
      <ul class="list-unstyled maxprintsize" style="float: left;">
        <li>S#: <span class="text-450">{{$id}}</span></li>
        <li>HW: <span class="text-450">{{$hardware}}</span></li>
        <li>SW: <span class="text-450">{{$software}}</span></li>
        <li>XYZ: <span class="text-450">{{$xyzposition}}</span></li>
      </ul>
    </div>
  </div>
</div>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

<style>
  .maxprintsize {
    max-width: 400px;
    max-height: 50px;
    margin-top: 0px;
    margin-bottom: 0px;
    line-height: 8px;
    font-size: 10px;
  }

  .maxprintsize img{
    height: 26px;
  }

  .print-style {
    font-size: .8rem;
    line-height: .7;
    letter-spacing: -.5px;
    font-weight: 700;
  }

  .list-unstyled {
    list-style: none;
    padding-top: 5px;
    padding-left: 5px;
  }

  .text-450{
    font-weight: 450;
  }
</style>
{{--@endsection--}}
