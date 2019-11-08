@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main" id="vue-app">
      @include('admin/inventory/misc/breadcrumbs')

      <div class="card mb-0">
        <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
          <strong><i class="fa fa-edit"></i> Bulk Check Inventory</strong>
        </div>
        <div class="card-body pb-0 pt-0 pl-0 pr-0">
          <form method="GET" action="{{ route('admin.bulkCheckInventoryCache') }}">
            @csrf
            <div class="form-group">
              <textarea id="bulk-info" class="form-control" name="bulk-info" rows="6"></textarea>
              <button class="btn btn-success">Submit</button>
            </div>

            @if(!empty($checked))
            <hr>
            <a href="{{ route('inventory.print', ['print_table' => $checked]) }}" class="btn-print btn btn-success float-right"><i class="fa fa-print"></i>Print</a>
            <table id="bulkCheckInventory" class="table table-striped table-bordered table-hover table-sm bg-gray-100 inventory-fonts">
              <thead>
              <tr>
                <strong class="form-control head-title bg-gray-500 text-dark" > Checked Inventory</strong>
              </tr>
              <tr class="head-column">
                <th scope="col" width="5%">ID</th>
                <th scope="col" width="10%">Team</th>
                <th scope="col" width="15%">H#</th>
                <th scope="col" width="30%">PCMHardwareType</th>
                <th scope="col" width="5%">Stock</th>
                <th scope="col" width="5%">Returned</th>
                <th scope="col" width="15%">XYZ Position</th>
                <th scope="col" width="10%">Stock#</th>
              </tr>
              </thead>
              <tbody>
                @foreach($checked as $key => $item)
                <tr class="body-column">
                  <td>{{ $key + 1 }}</td>
                  <td>{{ $item['team'] }}</td>
                  <td>{{ $item['hnumber'] }}</td>
                  <td>{{ $item['pcmhardwaretype'] }}</td>
                  <td>
                    @if($item['stock'])
                      <i class="fa fa-check text-success"></i>
                    @endif
                  </td>
                  <td>
                    @if($item['returned'])
                      <i class="fa fa-check text-danger"></i>
                    @endif
                  </td>
                  <td>{{ $item['xyzposition'] }}</td>
                  <td>{{ $item['stocknumber'] }}</td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@push('print.preview')
<script>
  $(document).ready(function() {
    $('.btn-print').printPage();
  })
</script>
@endpush
