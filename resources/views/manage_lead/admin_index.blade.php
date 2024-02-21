@extends('layouts.admin_app')

@section('content')
<div class="content-wrapper">
  <section class="content">
      <div class="container-fluid">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 col-12">
      @if (count($errors) > 0)
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      @if(session()->has('success'))
          <div class="alert alert-success"> {!! session('success') !!} </div>
      @endif @if(session()->has('error'))
          <div class="alert alert-danger"> {!! session('error') !!} </div>
      @endif
      <div class="row">
    <div class="col-lg-12 col-12">
      <div class="table-responsive">
        <div class="col-xs-12 offset-md-8 col-md-4 text-right" style="padding: 10px;"><a href="{{ route('manage-lead-add') }}" class="btn btn-info btn-sm">Add lead</a></div>
        <form action="{{ route('manage-lead') }}" method="GET">
        <div class="col-xs-12 offset-md-8 col-md-4 text-md-right text-sm-left" style="padding: 10px;">
          <div class="form-group row">
          <label for="staticEmail" class="col-sm-6 col-form-label">Created By:</label>
          <div class="col-sm-6">
              <select class="form-control" name="created_by" id="created_by">
                <option value="">All</option>
              @foreach($users as $user)
                <option value="{{ $user->id }}" {{$created_by==$user->id?'selected':''}}>{{ $user->name }}</option>
              @endforeach
              </select>
          </div>
          </div>
          <div class="form-group row">
          <label for="staticEmail" class="col-sm-6 col-form-label">Search:</label>
          <div class="col-sm-6">
              <input class="form-control" type="text" name="q" value="{{ $q }}">
          </div>
          </div>
          <div class="form-group row">
          <label for="staticEmail" class="col-sm-6 col-form-label">Date:</label>
          <div class="col-sm-6">
              <select class="form-control" name="date" id="date">
                <option value="">All</option>
                <?php
for ($i = 0; $i <= 11; $i++) {
	$months[] = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
}
?>
                @foreach($months as $dateVal)
                  <option value="{{ $dateVal }}" {{$date==$dateVal?'selected':''}}>{{ $dateVal }} ({{ date("F", strtotime(date($dateVal))) }})</option>
                @endforeach
              </select>
          </div>
          </div>

          <div class="form-group row">
          <label for="staticEmail" class="col-sm-6 col-form-label"></label>
          <div class="col-sm-6">
              <button type="submit" class="btn btn-info btn-sm" name="action" value="filter" style="">Filter</button>
              <button type="submit" class="btn btn-info btn-sm" name="action" value="export" style="">Export</button>
          </div>
          </div>

        </div>
        </form>

        <table id="manage-lead" class="table table-bordered">
          <thead>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
            <th>Address</th>
            <th>Req Type</th>
            <th>Created By</th>
            <th>Action</th>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
</div>
</section>
</div>
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('template/datatables/css/dataTables.bootstrap4.css') }}"/>
<link rel="stylesheet" type="text/css" href="{{ asset('template/datatables_custom.css') }}">
@endsection
@section('script')
<script type="text/javascript" src="{{ asset('template/datatables/js/jquery.dataTables.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/datatables/js/dataTables.bootstrap4.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/datatables/js/dataTables.rowReorder.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/datatables/js/dataTables.scroller.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/datatables_custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('template/datatables_custom.js') }}"></script>
<script type="text/javascript">
    var thData = [
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'phone', name: 'phone'},
                    {data: 'address', name: 'address'},
                    {data: 'type', name: 'type'},
                    {data: 'ename', name: 'ename', },
                    {data: 'action', name: 'action', sortable: false},
                  ];
  </script>
<script type="text/javascript">
$(document).ready(function() {
  $('#manage-lead').DataTable({
    "processing": true,
    "serverSide": true,
    "pageLength": 10,
    "searching":   false,
    "bPaginate": true,
    "bLengthChange": true,
    "bInfo" : false,
    "aaSorting": [],
    "order": [[ 0, "desc" ]],
    "ajax": "{!! route('getleadListAjax', ['created_by'=> $created_by, 'q'=>$q, 'date'=>$date]) !!}",
    "columns": thData
  });
  //$('.dataTables_processing').html('<div class="lds-ripple"><div></div><div></div></div>');
  $(document).on('click', '.delete-lead', function(){
    var id = $(this).attr('data-id');
    var del_url = $(this).attr('data-url');
    swal({
        title: 'Are you sure?',
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#22D69D',
        cancelButtonColor: '#FB8678',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'No, cancel!',
        confirmButtonClass: 'btn',
        cancelButtonClass: 'btn',
    }).then(function (result) {
       if (result.value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
                  type: "DELETE",
                  dataType: 'json',
                  url: del_url,
                  success: function (data) {
                    if(data){
                       swal({
                        title: "Success",
                        text: "Deleted Successfully.",
                        type: "success",
                        confirmButtonColor: "#22D69D"
                    });
                      $('#manage-lead').DataTable().draw();
                    }
                  }
              });
        }
    });
  });
});
</script>
@endsection