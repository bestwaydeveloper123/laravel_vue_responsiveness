@extends('layouts.main')

@section('content')
<div class="app-body bg-light theme-color">
  <div class="main">

    <div class="card mb-0">
      <div class="card-header pt-1 pb-1 bg-gray-700 theme-color text-light">
        <strong><i class="fa fa-edit"></i> Mass Print</strong>
      </div>
      <div class="card-body pb-0 pt-0 pl-0 pr-0">
        <form class="form-horizontal" action="{{ url('/mass-print') }}" method="post">
          @csrf
          <div class="form-group">
            <span class="pb-0 pt-0"> 
              <label class="form-control bg-gray-500 text-dark" for="email">Accounts</label>
            </span>
            <div class="col-sm-10">
              <textarea name="accounts" class="form-control" rows="5" placeholder="h1234&#10;h12345&#10;h123456"></textarea>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" class="btn btn-success">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
 
  </div>
</div>
</div>
@stop

@push('user_index')
<script>

</script>
@endpush