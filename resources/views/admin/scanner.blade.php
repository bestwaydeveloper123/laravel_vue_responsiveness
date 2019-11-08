@extends('layouts.main')

@section('content')

<div class="app-body bg-light theme-color">
  <div class="main">
    <div class="card mb-0">
      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong><i class="fa fa-edit"></i> Scanner</strong>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <form method="POST" action="/admin/scannermatches">
          @csrf
          <table class="table table-bordered bg-gray-200 mb-0">
            <tr>
              <td><input type="text" class="form-control" name="barcode" id='stockId' placeholder="Enter Stock Or Core">
                @if ($errors->has('barcode'))
                  <div class="invalid-feedback">
                    <strong>{{ $errors->first('barcode') }}</strong>
                  </div>
                @endif
              </td>
            </tr>
            <tr>
              <td>
                <select type="text" class="form-control" name="username" placeholder="Username">
                  @foreach($Users as $User)
                    <option value="{{$User->username}}" @if(auth()->user()->username == $User->username) selected @endif>{{$User->username}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            <tr>
              <td><input class="form-control bg-success" type="submit" class="form-control" value="Submit"></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
