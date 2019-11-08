@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      @include('admin/core/misc/breadcrumbs')
      <input type="hidden" name="createdby" value="{{ auth()->user()->username }}">
      <div class="card mb-0">
        <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
          <strong><i class="fa fa-edit"></i> Add Core</strong>
        </div>
        <div class="card-body pb-0 pt-0 pl-0 pr-0">
          <form method="POST" action="/admin/core/partmatches">
            @csrf
            <table class="table table-bordered bg-gray-200">
              <tr>
                <td class="pb-0 pt-0" colspan="5"><strong class="form-control bg-gray-500 text-dark" >Ford, Chrysler, Mazda</strong></td>
              </tr>
              <tr>
                <td><input type="text" class="form-control" name="q" placeholder="Software/Hardware"></td>
                {{-- <td><input type="text" class="form-control" name="xyzposition" placeholder="Aisle-Height-Row"></td> --}}
                <td>
                  <select type="text" class="form-control" name="gxxorlsa" placeholder="Aisle-Height-Row">
                    <option value="" selected disabled>-- GXX or LSA --</option>
                    <option value="GXX">GXX</option>
                    <option value="LSA">LSA</option>
                    <option value="GXX+LSA">GXX + LSA</option>
                    <option value="No GXX">No GXX</option>
                    <option value="No LSA">No LSA</option>
                  </select>
                </td>
                <td><input type="text" class="form-control" name="accounthnumber" placeholder="Account H# (IE H123456)"></td>
                <td><button type="submit" class="btn btn-success">Submit</button></td>
              </tr>
            </table>
          </form>

          <form method="POST" action="/admin/core/addgeneric">
            @csrf
            <input type="hidden" name="survey" value="generic">
            <table class="table table-bordered bg-gray-200">
              <tr class="mt-5">
                <td class="pb-0 pt-0" colspan="7"><strong class="form-control bg-gray-500 text-dark">Generic</strong></td>
              </tr>
              <tr>
                <td><input type="text" class="form-control" name="hardwarecode" placeholder="Hardware"></td>
                <td><input type="text" class="form-control" name="softwarecode" placeholder="Software"></td>
                <td><input type="text" class="form-control" name="xyzposition" placeholder="Aisle-Height-Row"></td>
                <td>
                  <select type="text" class="form-control" name="gxxorlsa">
                    <option value="" selected disabled>-- GXX or LSA --</option>
                    <option value="GXX">GXX</option>
                    <option value="LSA">LSA</option>
                    <option value="GXX+LSA">GXX + LSA</option>
                    <option value="No GXX">No GXX</option>
                    <option value="No LSA">No LSA</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><textarea type="text" class="form-control" name="description" placeholder="description" rows="1"></textarea></td>
                <td><input type="text" class="form-control" name="account_id" placeholder="Account H# (IE H123456)"></td>
                <td><input type="submit" class="btn btn-success" value="Submit"></td>
              </tr>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
