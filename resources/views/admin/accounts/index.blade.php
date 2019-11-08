@extends('layouts.main')

@section('content')
  <div class="app-body bg-light theme-color">
    <div class="main">
      <div class="animated fadeIn">
        <div class="card">
          <div class="card-body table-responsive">
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
    </div>
  </div>
@stop

@push('user_index')
<script>
	$(document).ready(function() {
		const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q');

		$('#accounts_table').DataTable({
      "order": [[ 0, "desc" ]],
      "searching": false,
      "processing": true,
      "serverSide": true,
      "responsive": true,
      "ajax": {
        url: '{{ route('admin.accounts.table') }}',
        "type": "GET",
        "data": {
          "q": q,
          "_token": '{{csrf_token()}}'
        },
      },
      "columns":[
        { "data": "id" },
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
  });
</script>
@endpush
