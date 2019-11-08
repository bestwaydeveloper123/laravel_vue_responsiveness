@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main" id="vue-app">
    <div class="card mb-0">
      <div class="card-header py-1 bg-gray-700 theme-color text-light">
        <strong><i class="fa fa-edit"></i> My Settings</strong>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <form method="POST" action="/admin/user/mysettings">
          @csrf
          <table class="table table-borderless table-striped">
            <tr>
              <td width="10%"><label for="username">User Name</label></td>
              <td>
                <input type="text" id="username" name="username" class="form-control" value="{{ auth()->user()->username }}">
                @if ($errors->has('username'))
                  <span class="text-danger" role="alert">
                    <strong>{{ $errors->first('username') }}</strong>
                  </span>
                @endif
              </td>
            </tr>
            <tr>
              <td><label for="first-name">First Name</label></td>
              <td><input type="text" id="first-name" name="first_name" class="form-control" value="{{ auth()->user()->first_name }}"></td>
            </tr>
            <tr>
              <td><label for="last-name">Last Name</label></td>
              <td><input type="text" id="last-name" name="last_name" class="form-control" value="{{ auth()->user()->last_name }}"></td>
            </tr>
            <tr>
              <td><label for="email">Email</label></td>
              <td>
                <input type="text" id="email" name="email" class="form-control" value="{{ auth()->user()->email }}">
                @if ($errors->has('email'))
                  <span class="text-danger" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                  </span>
                @endif
              </td>
            </tr>
            <tr>
              <td><label for="phone">Phone</label></td>
              <td>
                <input type="number" id="phone" name="phone" class="form-control" value="{{ auth()->user()->phone }}">
                @if ($errors->has('phone'))
                  <span class="text-danger" role="alert">
                    <strong>{{ $errors->first('phone') }}</strong>
                  </span>
                @endif
              </td>
            </tr>
            <tr>
              <td><label for="extension">Extension</label></td>
              <td>
                <input type="text" id="extension" name="extension" class="form-control" value="{{ auth()->user()->extension }}">
                @if ($errors->has('extension'))
                  <span class="text-danger" role="alert">
                    <strong>{{ $errors->first('extension') }}</strong>
                  </span>
                @endif
              </td>
            </tr>
            <tr>
              <td><label for="role">Role</label></td>
              <td>
                <select class="form-control" id="role" name="role">
                  @foreach($Roles as $Role)
                    @if(auth()->user()->role == $Role->id )
                      @php $selected = 'selected'; @endphp
                    @else
                      @php $selected = ''; @endphp
                    @endif
                    <option value="{{$Role->id}}" {{$selected}}>{{$Role->description}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="team">Team</label></td>
              <td>
                <select class="form-control" id="team" name="team" value="{{ auth()->user()->team }}">
                  <option>Select Team</option>
                  <option value="Admin Team" @if(auth()->user()->team == 'Admin Team') selected @endif>Admin Team</option>
                  <option value="Pink Team" @if(auth()->user()->team == 'Pink Team') selected @endif>Pink Team</option>
                  <option value="Gold Team" @if(auth()->user()->team == 'Gold Team') selected @endif>Gold Team</option>
                  <option value="Green Team" @if(auth()->user()->team == 'Green Team') selected @endif>Green Team</option>
                  <option value="Purple Team" @if(auth()->user()->team == 'Purple Team') selected @endif>Purple Team</option>
                  <option value="Orange Team" @if(auth()->user()->team == 'Orange Team') selected @endif>Orange Team</option>
                  <option value="Kevin" @if(auth()->user()->team == 'Kevin') selected @endif>Kevin</option>
                  <option value="Mark" @if(auth()->user()->team == 'Mark') selected @endif>Mark</option>
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="rank">Rank</label></td>
              <td>
                <select class="form-control" id="rank" name="rank" value="{{ auth()->user()->rank }}">
                  <option value="">Select Rank</option>
                  @foreach($Ranks as $Rank)
                    @if(auth()->user()->rank == $Rank->id )
                      @php $selected = 'selected'; @endphp
                    @else
                      @php $selected = ''; @endphp
                    @endif
                    <option value="{{$Rank->id}}" {{$selected}}>{{$Rank->description}}</option>
                  @endforeach
                </select>
              </td>
            </tr>
            <tr>
              <td><label for="credit-card">Credit Card</label></td>
              <td><input type="text" id="credit-card" maxlength="4" name="creditcard" class="form-control" value="{{ auth()->user()->creditcard }}"></td>
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
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
