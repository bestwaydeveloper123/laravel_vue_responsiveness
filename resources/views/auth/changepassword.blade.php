@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main" id="vue-app">
    <div class="card mb-0">
      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong><i class="fa fa-key"></i> Change Password</strong>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <form method="POST" action="/admin/user/updatePassword">
          @csrf
          <table class="table table-borderless table-striped">
            <tr>
              <td width="20%">
                <label for="username">Old Password</label>
              </td>
              <td>
                <input type="password" id="old_password" name="old_password" class="form-control">
                @if ($errors->has('old_password'))
                <div class="text-danger">{{ $errors->first('old_password') }}</div>
                @endif
              </td>
            </tr>
            <tr>
              <td width="20%">
                <label for="username">New Password</label>
              </td>
              <td>
                <input type="password" id="password" name="password" class="form-control">
                @if ($errors->has('password'))
                <div class="text-danger">{{ $errors->first('password') }}</div>
                @endif
              </td>
            </tr>
            <tr>
              <td width="20%">
                <label for="username">Confirm Password</label>
              </td>
              <td>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                @if ($errors->has('confirm_password'))
                <div class="text-danger">{{ $errors->first('confirm_password') }}</div>
                @endif
              </td>
            </tr>
            <tr>
              <td colspan="1"><input class="button bg-success" type="submit" value="Save"></td>
              <td>
                @if (session('success'))
                <div class="alert alert-success alert-dismissable fade show" role="alert">
                  <strong>Success! </strong>{{ session('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-block">
                  <button type="button" class="close" data-dismiss="alert">×</button>
                  <strong>{{ $message }}</strong>
                </div>
                @endif
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
