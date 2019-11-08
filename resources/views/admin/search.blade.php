@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main">
    <div class="animated fadeIn">
        <div class="card mb-0">
          <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
            <strong><i class="fa fa-edit"></i> Accounts</strong>
          </div>
          <div class="card-body table-responsive pt-1">
            <table id="accounts_table" class="table table-bordered table-striped display compact hover stripe" width="100%">
              <thead>
                <tr>
                  <th>H#</th>
                  <th>Account Name</th>
                  <th>Primary</th>
                  <th>Team</th>
                  <th>Edit</th>
                  <th>Created</th>
                  <th>Order Type</th>
                  <th>Item Purchased</th>
                  <th>Date Purchased</th>
                  <th>Secondary</th>
                  <th>Edit</th>
                </tr>
              </thead>
              <tbody style="font-weight:500">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="animated fadeIn">
        <div class="card mb-0">
          <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
            <strong><i class="fa fa-edit"></i> Customer Meta Data</strong>
          </div>
          <div class="card-body table-responsive pt-1">
            <table id="customermetadatas_table" class="table table-bordered table-striped display compact hover stripe" width="100%">
              <thead>
                <tr>
                  <th>H#</th>
                  <th>Ship To</th>
                  <th>Edit</th>
                  <th>Street1</th>
                  <th>Street2</th>
                  <th>City</th>
                  <th>State</th>
                  <th>Zip</th>
                  <th>Country</th>
                  <th>Phone</th>
                  <th>Alt Phone</th>
                  <th>Email</th>
                  <th>Alt Email</th>
                  <th>Account Type</th>
                  <th>Edit</th>
                </tr>
              </thead>
              <tbody style="font-weight:500">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="animated fadeIn">
        <div class="card mb-0">
          <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
            <strong><i class="fa fa-edit"></i> Vendors</strong>
          </div>
          <div class="card-body table-responsive pt-1">
            <table id="vendors_table" class="table table-bordered table-striped display compact hover stripe" width="100%">
              <thead>
                <tr>
                  <th>H#</th>
                  <th>Purchased From</th>
                  <th>SalesPerson Stock#</th>
                  <th>VIN</th>
                  <th>Part Price</th>
                  <th>Shipping Price</th>
                  <th>Pickup Charge</th>
                  <th>TrackingNumber</th>
                  <th>Edit</th>
                </tr>
              </thead>
              <tbody style="font-weight:500">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="animated fadeIn">
        <div class="card mb-0">
          <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
            <strong><i class="fa fa-edit"></i> Account Notes</strong>
          </div>
          <div class="card-body table-responsive pt-1">
            <table id="accountnotes_table" class="table table-bordered table-striped display compact hover stripe" width="100%">
              <thead>
                <tr>
                  <th>H#</th>
                  <th>Account Note</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody style="font-weight:500">
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="animated fadeIn">
        <div class="card mb-0">
          <div class="card-header py-1 bg-gray-700 theme-color text-light">
            <strong><i class="fa fa-edit"></i> Credit Cards</strong>
          </div>
          <div class="card-body table-responsive pt-1">
            <table id="creditcards_table" class="table table-bordered table-striped display compact hover stripe" width="100%">
              <thead>
                <tr>
                  <td>H#</td>
                  <td>Credit Card</td>
                  <td>Part Price Paid</td>
                  <td>Shipping Price</td>
                  <td>Pickup Charge</td>
                  <td>Total Used</td>
                  <td>Date Purchased</td>
                  <td>Action</td>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@stop

@push('manual_search')
<script>
  $(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q');

    // customer meta data table
    $('#customermetadatas_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "scrollX": true,
      "ajax": {
        url: '{{ route('searches.customermetadatastable') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
      },
      "columns":[
        { "data": "account_id",
          // render: $.fn.dataTable.render.number( ',', '.', 0, 'H' )
        },
        { "data": "shipto" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
        { "data": "street1" },
        { "data": "street2" },
        { "data": "city" },
        { "data": "state" },
        { "data": "zip" },
        { "data": "country" },
        { "data": "phone" },
        { "data": "phone2" },
        { "data": "email" },
        { "data": "email2" },
        { "data": "level" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
      ]
    });

    // accounts table
    $('#accounts_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "scrollX": true,
      "ajax": {
        url: '{{ route('searches.accountstable') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
        "dataSrc": function(json) {
          if (json.data.length === 1) {
            const url = `/accounts/${json.data[0].id}/edit`;
            window.location.href = url;
          }
          return json.data;
        },
      },
      "columns":[
        { "data": "id",
          // render: $.fn.dataTable.render.number( ',', '.', 0, 'H' )
        },
        { "data": "accountname" },
        { "data": "primaryaccountmanager" },
        { "data": "accountteam" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
        { "data": "created_at" },
        { "data": "ordertype" },
        { "data": "itempurchased" },
        { "data": "datecustomerpurchased" },
        { "data": "secondaryaccountmanager" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
      ]
    });

    // vendors table
    $('#vendors_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "scrollX": true,
      "ajax": {
        url: '{{ route('searches.vendorstable') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
      },
      "columns":[
        { "data": "account_id",
          // render: $.fn.dataTable.render.number( ',', '.', 0, 'H' )
        },
        { "data": "purchasedfrom" },
        { "data": "salespersonstockno" },
        { "data": "vin" },
        { "data": "partprice" },
        { "data": "shippingprice" },
        { "data": "pickupcharge" },
        { "data": "trackingnumber" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
      ]
    });

    // accountnotes table
    $('#accountnotes_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "scrollX": true,
      "ajax": {
        url: '{{ route('searches.accountnotestable') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
      },
      "columns":[
        { "data": "account_id",
          // render: $.fn.dataTable.render.number( ',', '.', 0, 'H' )
        },
        { "data": "notes" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
      ]
    });

    // Credit Card
    $('#creditcards_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "scrollX": true,
      "ajax": {
        url: '{{ route('searches.creditcardstable') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
      },
      "columns":[
        { "data": "account_id" },
        { "data": "creditcard" },
        { "data": "partprice" },
        { "data": "shippingprice" },
        { "data": "pickupcharge" },
        { "data": "total" },
        { "data": "datepurchased" },
        {data: 'edit', name: 'edit', orderable: false, searchable: false},
      ]
    });
  });
</script>
@endpush
