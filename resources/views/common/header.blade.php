<header class="app-header navbar pr-3">
  <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" data-toggle="sidebar-show" type="button">
    <span class="navbar-toggler-icon">
    </span>
  </button>

  <a class="navbar-brand d-md-down-none" href="/">
    <h2 alt="Hydra2 Logo" class="navbar-brand-full" height="25" width="89">Hydra2
      <h2 alt="Hydra2 Logo" class="navbar-brand-minimized" height="30" width="30"><i class="nav-icon icon-home"></i>
      </h2>
    </h2>
  </a>

  <button class="navbar-toggler sidebar-toggler d-md-down-none" data-toggle="sidebar-lg-show" type="button">
    <span class="navbar-toggler-icon">
    </span>
  </button>

  <form action="/searches" method="GET" role="search">
    @csrf
    <ul class="nav navbar-nav d-md-down-none navbar-brand-full">
      <li>
        <div class="input-group">
          <input class="form-control" type="text" id="q2" name="q2" placeholder="...">
          <span class="input-group-append">
            <button id="sub" class="btn btn-primary" type="submit">
              <i class="fa fa-search"></i> Search
            </button>
          </span>
        </div>
      </li>
      <li>
        <div class="input-group col-1">
          <a href="/accounts/create"><button class="btn btn-primary" type="button">
              <i class="fa fa-plus"></i> New Account
            </button></a>
        </div>
      </li>
      <li>
        <div class="input-group col-1">
          <a href="#">
            <button class="btn btn-primary CountZero" data-toggle="modal" data-target="#myModal" type="button">
              <i class="fa fa-bell"></i>
              <span class="badge badge-danger NotificationCount">0</span>
            </button>
          </a>
        </div>
      </li>
    </ul>
    <ul class="nav navbar-nav d-lg-none navbar-brand-minimized mr-auto">
      <li>
        <div class="input-group">
          <input class="form-control" type="text" id="q" name="q" placeholder="Search">
          <span class="input-group-append">
            <button id="sub2" class="btn btn-primary" type="submit">
              <i class="fa fa-search"></i>
            </button>
          </span>
        </div>
      </li>
      <li>
        <div class="input-group col-1">
          <a href="/accounts/create"><button class="btn btn-primary" type="button">
              <i class="fa fa-plus"></i></button></a>
        </div>
      </li>
    </ul>
  </form>

  <ul class="nav navbar-nav ml-auto">
    <li class="nav-item dropdown">
      <a aria-expanded="false" aria-haspopup="true" class="nav-link" data-toggle="dropdown" href="#" role="button">
        {{ auth()->user()->email }}
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        <div class="dropdown-header text-center">
          <strong>
            Settings
          </strong>
        </div>
        <a class="dropdown-item" href="/admin/user/settings">
          <i class="fa fa-user">
          </i>
          Profile
        </a>
        @if ( auth::check() && Auth::user()->role == '1')
        <a class="dropdown-item" href="/admin/user/teamsettings">
          <i class="fa fa-user">
          </i>
          Teams
        </a>
        @endif
        <a class="dropdown-item" href="/admin/user/changepassword">
          <i class="fa fa-key">
          </i>
          Change Password
        </a>
        <a class="dropdown-item" href="#">
          <i class="fa fa-wrench">
          </i>
          Settings
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fa fa-lock"></i> {{ __('Logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </div>
    </li>
  </ul>
</header>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" style="position: absolute;">Ping Employees</h4>
      </div>
      <div class="modal-body">
        <span class="PingemployeesData"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<script>
  //search union
  document.getElementById("q2").oninput = function() {
    document.getElementById("q").value = this.value
  };

  var input = document.getElementById("q2");

  input.addEventListener("keyup", function(event) {
    if (event.keyCode === 13) {
      event.preventDefault();
      document.getElementById("sub").click();
    }
  });

  function fetchdata() {
    $.ajax({
      url: '{{ url("api/GetMyPing") }}',
      type: 'get',
      success: function(response) {
        $('.NotificationCount').html(response.Data.Count);
        $(".PingemployeesData").html(response.Data.Pingemployees);
      }
    });
  }

  $('.CountZero').click(function(){
    $.ajax({
      url: '{{ url("api/ReadMyPing") }}',
      type: 'get',
      success: function(response) {
        // alert(response); 
      }
    });
  });

  $(document).ready(function() {
    fetchdata();
    setInterval(fetchdata, 2000);
  });
</script>
