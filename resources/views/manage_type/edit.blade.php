@extends('layouts.admin_app')

@section('content')
<div class="content-wrapper">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin-index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manage-category') }}">{{ $title }}</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
  <div class="row">
    <div class="col-md-6">
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
      <div class="card card-primary">
        <form role="form" id="add-category" action="{{ route('manage-category-update') }}" name="add-category" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Category Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name')?old('name'):$info->name }}">
            </div> 
            <div class="form-group">
              <label for="order_by">Order</label>
              <input type="text" class="form-control" id="order_by" name="order_by" placeholder="Enter Order" value="{{ old('order_by')?old('order_by'):$info->order_by }}">
            </div>           
            <div class="form-group">
              <div class="col-xs-12 col-sm-6">
                <label for="category_pic">Category Pic</label>
                <div class="custom-file">  
                  <input type="file" name="category_pic" class="custom-file-input" id="category_pic" accept="image/*">
                  <label class="custom-file-label" for="category_pic">Choose file</label>
                </div>
              </div>
              @if($info->image)
              <div class="col-xs-12 col-sm-6">              
                  <a href="{{ asset($info->image) }}" target="_blank">View Category Picture</a>
              </div>
              @endif
            </div>            
            <div class="form-group clearfix">
                <div class="icheck-primary d-inline">
                  <input type="radio" id="radioPrimary1" name="status" value="1" {{ $info->status==1?'checked':'' }}>
                  <label for="radioPrimary1">Active</label>
                </div>
                <div class="icheck-danger d-inline">
                  <input type="radio" id="radioPrimary2" name="status" value="0" {{ $info->status==0?'checked':'' }}>
                  <label for="radioPrimary2">Inactive</label>
                </div>
              </div> 
          </div>
          <div class="card-footer">
            <input type="hidden" name="id" value="{{ $info->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-category') }}" class="btn btn-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
  </div>
@endsection
@section('script')
<script type="text/javascript">
  $('#category_pic').change(function(event){
        var fileName = event.target.files[0].name;
        if (event.target.nextElementSibling!=null){
            event.target.nextElementSibling.innerText=fileName;
        }
    });
$(document).ready(function () {
  $('#add-category').validate({
    rules: {
      name: {
        required: true,
      },
      status: {
        required: true,
      }    
    },
    messages: {
      name: {
        required: "Please enter a name",
      },
      status: {
        required: "Please select a status",
      }
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });
});
</script>
@endsection