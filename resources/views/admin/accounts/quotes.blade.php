@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main">
    <div class="animated fadeIn">
      <div class="form-inline ml-3">
        <div class="form-group mr-3">
          <label for="start" class="col-form-control mr-1">Start Date</label>
          <input id="start" type="date" class="form-control form-control-sm" /><br />
        </div>
        <div class="form-group mr-3">
          <label for="end" class="col-form-control mr-1">End Date</label>
          <input id="end" type="date" class="form-control form-control-sm" /><br />
        </div>
        <div class="form-group mr-3 float-right">
          <label for="end" class="col-form-control mr-2">User</label>
          <select id="user" class="form-control form-control-sm">
            <option value disabled selected>Users</option>
          </select>
        </div>
        <div class="form-group mr-1 float-right">
          <label for="end" class="col-form-control mr-2">Team</label>
          <select id="team" class="form-control form-control-sm">
            <option value disabled selected>Teams</option>
          </select>
        </div>
        <button class="btn btn-primary my-1 mr-1" id="filter">Filter</button>
        <button id="clearFilter" class="btn btn-primary my-1 mr-5">Clear Filter</button>
      </div>
    </div>
    <div class="card">
      <div class="card-body table-responsive">
        <table id="oTable" class="table table-bordered table-striped display compact hover stripe" width="100%">
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
              <th>Create By</th>
              <th>Edit</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
@stop

@push('user_index')
<script>
  var $tableSel = $('#oTable');
  function datatableFunction(sdate = '', edate = '', user = '', team = ''){
    const urlParams = new URLSearchParams(window.location.search);
    const q = urlParams.get('q'); 
    // var dataArray = [];
    var cc = 1;

    $tableSel.dataTable({
      "bFilter": true,
      "columnDefs": [{
        "targets": [10],
        "visible": false,
        "searchable": true
      }],
      "ajax": {
        url: '{{ route('admin.quotes.table') }}',
        "type": "GET",
        "data": {
          "q": q,
          "sdate": sdate,
          "edate": edate,
          "user": user,
          "team": team,
          "_token": '{{csrf_token()}}'
        },
      },
      'aoColumns': [{
          "data": "id"
        },
        {
          "data": "accountname"
        },
        {
          "data": "primaryaccountmanager"
        },
        {
          "data": "accountteam"
        },
        {
          data: 'edit',
          name: 'edit',
          orderable: false,
          searchable: false
        },
        {
          "data": "created_at"
        },
        {
          "data": "ordertype"
        },
        {
          "data": "itempurchased"
        },
        {
          "data": "datecustomerpurchased"
        },
        {
          "data": "secondaryaccountmanager"
        },
        {
          "data": "created_by"
        },
        {
          data: 'edit',
          name: 'edit',
          orderable: false,
          searchable: false
        },
      ],
      drawCallback: function() {
        cc++;
        if (cc < 4) {
          var dataArray = [];
          var table = $('#oTable').DataTable();
          var count = table.data().count();
          if (count > 0) {
            for (var i = 0; i < count; i++) {
              if (table.row(i).data() != undefined)
                dataArray.push(table.row(i).data());
            }
          }

          var users = [];
          users = dataArray.map(dataArray => dataArray.created_by);
          var teams = [];
          teams = dataArray.map(dataArray => dataArray.accountteam);

          var uniqueUsers = [];
          for (i = 0; i < users.length; i++) {
            if (uniqueUsers.indexOf(users[i]) === -1) {
              uniqueUsers.push(users[i]);
            }
          }

          var uniqueTeams = [];
          for (i = 0; i < teams.length; i++) {
            if (uniqueTeams.indexOf(teams[i]) === -1) {
              uniqueTeams.push(teams[i]);
            }
          }

          var selectUser = document.getElementById("user");
          for (var i = 0; i < uniqueUsers.length; i++) {
            var opt = uniqueUsers[i];
            var el = document.createElement("option");
            el.textContent = opt;
            el.value = opt;
            selectUser.appendChild(el);
          }
          var selectTeam = document.getElementById("team");
          for (var i = 0; i < uniqueTeams.length; i++) {
            var opt = uniqueTeams[i];
            var el = document.createElement("option");
            el.textContent = opt;
            el.value = opt;
            selectTeam.appendChild(el);
          }
        }
      }
    });
  }
  $(function() {
    datatableFunction();
    // const urlParams = new URLSearchParams(window.location.search);
    // const q = urlParams.get('q');
    // var $tableSel = $('#oTable');
    // // var dataArray = [];
    // var cc = 1;

    $('#team').change(function() {
      $tableSel.fnFilter($(this).val());
    });
    $('#user').change(function() {
      $tableSel.fnFilter($(this).val());
    });

    $('#filter').on('click', function(e) {
      var startDate = $('#start').val(),
        endDate = $('#end').val(), user = $('#user').val(), team = $('#team').val();
      $("#oTable").dataTable().fnDestroy();
      datatableFunction(startDate, endDate, user, team);
      // e.preventDefault();
      // $.fn.dataTableExt.afnFiltering.length = 0;
      // $tableSel.dataTable().fnDraw();
      // var startDate = $('#start').val(),
      //   endDate = $('#end').val();
      // filterByDate(5, startDate, endDate);
      // $tableSel.dataTable().fnDraw();
    });

    $('#clearFilter').on('click', function(e) {
      e.preventDefault();
      $.fn.dataTableExt.afnFiltering.length = 0;
      $tableSel.dataTable().fnDraw();
      location.reload(true);
    });

    document.getElementById("oTable_filter").style.display = "none";
  });

  /* var filterByDate = function(column, startDate, endDate) {
    $.fn.dataTableExt.afnFiltering.push(
      function(oSettings, aData, iDataIndex) {
        var rowDate = normalizeDate(aData[column]),
          start = normalizeDate(startDate),
          end = normalizeDate(endDate);

        if (start <= rowDate && rowDate <= end) {
          return true;
        } else if (rowDate >= start && end === '' && start !== '') {
          return true;
        } else if (rowDate <= end && start === '' && end !== '') {
          return true;
        } else {
          return false;
        }
        $('#start').val() = '';
        $('#end').val() = '';
      }
    );
  };

  var normalizeDate = function(dateString) {
    var date = new Date(dateString);
    var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
    return normalized;
  } */
</script>
@endpush