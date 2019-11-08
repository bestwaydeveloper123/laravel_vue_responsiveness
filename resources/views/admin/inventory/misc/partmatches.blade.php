@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      @include('admin.inventory.misc.breadcrumbs')
      {{--<input type="hidden" name="createdby" value="{{ auth()->user()->username }}">--}}
      {{-- <input type="hidden" class="form-control" name="accounthnumber" value="{{ $accounthnumber ? $accounthnumber : "Account H# (IE H123456) fill now or from before" }}"> --}}
      <div class="card mb-0">
        <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
          <strong><i class="fa fa-edit"></i> Inventory Insert</strong>
        </div>
        @if ($fords->isEmpty() && $chryslers->isEmpty() && $mazdas->isEmpty())
          <div class="card-body">
            No result for search
          </div>
        @else
          <div class="card-body pb-0 pt-0 pl-0 pr-0">
            @if (!$fords->isEmpty())
              <table class="table table-striped table-bordered table-hover table-sm bg-gray-100 inventory-fonts">
                <thead>
                <tr>
                  <strong class="form-control head-title bg-gray-500 text-dark" >Ford</strong>
                </tr>
                <tr class="head-column">
                  <th scope="col">Hardware Code</th>
                  <th scope="col">Catch Code</th>
                  <th scope="col">Part Number</th>
                  <th scope="col">Description</th>
                  <th scope="col">XYZ position</th>
                  <th scope="col"><center>Action</center></th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($fords as $ford)
                    <tr class="body-column">
                      <input type="hidden" name="ford_id" value="{{ $ford->id }}" form="{{ 'ford-'. $ford->id }}">
                      <input type="hidden" name="account_id" value="{{ $accounthnumber }}" form="{{ 'ford-'. $ford->id }}">
                      <input type="hidden" name="gxxorlsa" value="{{ $gxxorlsa }}" form="{{ 'ford-'. $ford->id }}">
                      <td>{{ $ford->hardware }}</td>
                      <td>{{ $ford->catch_word }}</td>
                      <td>{{ $ford->part_number }}</td>
                      <td>{{ $ford->model_year ." ". $ford->vehicle ." ". $ford->engine }}</td>
                      <td><input type="text" class="form-control form-control-sm" name="xyzposition" value="{{ $ford->xyzposition }}" form="{{ 'ford-'. $ford->id }}"></td>
                      <td>
                        <form action="/admin/inventory/addinventory" method="POST" id="{{ 'ford-'. $ford->id }}">
                          @csrf
                          <center><button type="submit" class="btn btn-outline-success pt-0 pb-0"><i class="icon-arrow-up"></i></button></center>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif

            @if (!$chryslers->isEmpty())
              <table class="table table-bordered table-striped table-hover table-sm bg-gray-100 inventory-fonts">
                <thead>
                <tr>
                  <strong class="form-control head-title bg-gray-500 text-dark" >Chrysler</strong>
                </tr>
                <tr class="head-column">
                  <th scope="col">Module Type</th>
                  <th scope="col">Hardware Code</th>
                  <th scope="col">Latest Software</th>
                  <th scope="col">Older Software</th>
                  <th scope="col">Year</th>
                  <th scope="col">Body</th>
                  <th scope="col">Engine</th>
                  <th scope="col">Software Descrip</th>
                  <th scope="col">XYZ position</th>
                  <th scope="cik"><center>Action</center></th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($chryslers as $chrysler)
                    <tr class="body-column">
                      <input type="hidden" name="chrysler_id" value="{{ $chrysler->id }}" form="{{ 'chrysler-'. $chrysler->id }}">
                      <input type="hidden" name="account_id" value="{{ $accounthnumber }}" form="{{ 'chrysler-'. $chrysler->id }}">
                      <input type="hidden" name="gxxorlsa" value="{{ $gxxorlsa }}" form="{{ 'chrysler-'. $chrysler->id }}">
                      <td>{{ $chrysler->module_type }}</td>
                      <td>{{ $chrysler->hardware_pn }}</td>
                      <td>{{ $chrysler->software_pn }}</td>
                      <td>{{ $chrysler->older_software_pn }}</td>
                      <td>{{ $chrysler->year }}</td>
                      <td>{{ $chrysler->body }}</td>
                      <td>{{ $chrysler->engine }}</td>
                      <td>{{ $chrysler->software_description }}</td>
                      <td><input type="text" class="form-control form-control-sm" name="xyzposition" value="{{ $chrysler->xyzposition }}" form="{{ 'chrysler-'. $chrysler->id }}"></td>
                      <td>
                        <form action="/admin/inventory/addinventory" method="POST" id="{{ 'chrysler-'. $chrysler->id }}">
                          @csrf
                          <center><button type="submit" class="btn btn-outline-success pt-0 pb-0 inventory"><i class="icon-arrow-up"></i></button></center>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{-- <div class="mx-auto">
                {{ $chryslers->links() }}
              </div> --}}
            @endif

            @if (!$mazdas->isEmpty())
              <table class="table table-bordered table-stripted table-hover table-sm bg-gray-100">
                <thead>
                <tr>
                  <strong class="form-control head-title bg-gray-500 text-dark" >Mazda</strong>
                </tr>
                <tr class="head-column">
                  <th scope="col">Hardware</th>
                  <th scope="col">18881 P/N</th>
                  <th scope="col">Catch</th>
                  <th scope="col">12A650</th>
                  <th scope="col">EPC P/N</th>
                  <th scope="col">Model</th>
                  <th scope="col">Year</th>
                  <th scope="col">Engine</th>
                  <th scope="col">Trans</th>
                  <th scope="col">Emissions</th>
                  <th scope="col">Sec</th>
                  <th scope="col">Description</th>
                  <th scope="col">VIN</th>
                  <th scope="col">XYZ</th>
                  <th secop="col"><center>Action</center></th>
                </tr>
                </thead>
                <tbody>
                  @foreach ($mazdas as $mazda)
                    <tr class="body-column">
                      <input type="hidden" name="mazda_id" value="{{ $mazda->id }}" form="{{ 'mazda-'. $mazda->id }}">
                      <input type="hidden" name="account_id" value="{{ $accounthnumber }}" form="{{ 'mazda-'. $mazda->id }}">
                      <input type="hidden" name="gxxorlsa" value="{{ $gxxorlsa }}" form="{{ 'mazda-'. $mazda->id }}">
                      <td>{{ $mazda->hardware }}</td>
                      <td>{{ $mazda->_18881_pn }}</td>
                      <td>{{ $mazda->catch }}</td>
                      <td>{{ $mazda->_12a650 }}</td>
                      <td>{{ $mazda->epc_pn }}</td>
                      <td>{{ $mazda->mazda_model }}</td>
                      <td>{{ $mazda->year }}</td>
                      <td>{{ $mazda->engine }}</td>
                      <td>{{ $mazda->trans }}</td>
                      <td>{{ $mazda->emissions }}</td>
                      <td>{{ $mazda->sec }}</td>
                      <td>{{ $mazda->description }}</td>
                      <td>{{ $mazda->vin_ex }}</td>
                      <td><input type="text" class="form-control form-control-sm" name="xyzposition" value="{{ $mazda->xyzposition }}" form="{{ 'mazda-'. $mazda->id }}"></td>
                      <td>
                        <form action="/admin/inventory/addinventory" method="POST" id="{{ 'mazda-'. $mazda->id }}">
                          @csrf
                          <center><button type="submit" class="btn btn-outline-success pt-0 pb-0 inventory"><i class="icon-arrow-up"></i></button></center>
                        </form>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
