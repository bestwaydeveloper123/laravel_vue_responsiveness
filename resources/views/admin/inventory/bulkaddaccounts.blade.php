@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main" id="vue-app">
    @include('admin/inventory/misc/breadcrumbs')
    <input type="hidden" name="createdby" value="{{ auth()->user()->username }}">
    <div class="card mb-0">
      <bulk-add-account></bulk-add-account>
      <!-- <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong><i class="fa fa-edit"></i> Bulk Add Accounts</strong>
      </div>
      <div class="card-body">
        <form method="POST" action="/admin/inventory/bulkinsertinfo">
          @csrf
          <div class="form-group">
            <textarea id="bulk-info" class="form-control" name="bulk-info" rows="6"></textarea>
            <button class="btn btn-success">Submit</button>
          </div>
        </form>

        @if (!empty($bulkinfos))

        <hr>
        <div class="overflow-auto">
          <table class="table table-striped table-bordered table-hover table-sm bg-gray-100 inventory-fonts">
            <thead>
              <tr>
                <strong class="form-control head-title bg-gray-500 text-dark">Ford</strong>
              </tr>
              <tr class="head-column">
                <th scope="col">Sales Record Number</th>
                <th scope="col">Record Owner</th>
                <th scope="col">Type of Order</th>
                @php $ebaycount = true; $shipcount = true; @endphp
                @foreach ($bulkinfos as $info)
                  @if(array_key_exists('ebay_id', $info))
                    @if($ebaycount)
                    <th scope="col">eBay User ID</th>
                    @endif
                    @php $ebaycount = false; @endphp
                  @endif
                  @if(array_key_exists('ship_to_name', $info))
                    @if($shipcount)
                    <th scope="col">Ship to name</th>
                    @endif
                    @php $shipcount = false; @endphp
                  @endif
                @endforeach
                <th scope="col">Account name</th>
                <th scope="col">Phone</th>
                <th scope="col">Customer email</th>
                <th scope="col">Item purchased</th>
                <th scope="col">Customer VIN</th>
                <th scope="col">Price paid</th>
                <th scope="col">Date purchased</th>
                <th scope="col">Customer street</th>
                <th scope="col">Ship to Address Additional Info</th>
                <th scope="col">Customer city</th>
                <th scope="col">Customer state/province</th>
                <th scope="col">Customer zip/postal code</th>
                <th scope="col">Customer country</th>
              </tr>
            </thead>
            <tbody class="overflow-auto">
              @foreach ($bulkinfos as $info)
              <tr class="body-column">
                <td>{{ $info['record_number'] }}</td>
                <td>{{ $info['record_owner'] }}</td>
                <td>{{ $info['order_type'] }}</td>
                @if(array_key_exists('ebay_id', $info))
                <td>{{ $info['ebay_id'] }}</td>
                @endif
                @if(array_key_exists('ship_to_name', $info))
                <td>{{ $info['ship_to_name'] }}</td>
                @endif
                <td>{{ $info['account_name'] }}</td>
                <td>{{ $info['phone'] }}</td>
                <td>{{ $info['customer_email'] }}</td>
                <td>{{ $info['item_purchased'] }}</td>
                <td>{{ $info['customer_vin'] }}</td>
                <td>{{ $info['price_paid'] }}</td>
                <td>{{ $info['date_purchased'] }}</td>
                <td>{{ $info['customer_street'] }}</td>
                <td>{{ $info['ship_address_info'] }}</td>
                <td>{{ $info['customer_city'] }}</td>
                <td>{{ $info['customer_state'] }}</td>
                <td>{{ $info['customer_zipcode'] }}</td>
                <td>{{ $info['customer_country'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @endif
      </div> -->
    </div>
  </div>
</div>
@endsection
