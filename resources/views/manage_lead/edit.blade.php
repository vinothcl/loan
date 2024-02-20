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
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('manage-lead') }}">Manage Lead</a></li>
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
        <form role="form" id="add-category" action="{{ route('manage-lead-update') }}" name="add-category" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="card-body">
            <div class="form-group">
              <label for="name">Lead Name<span class="text-danger">*</span></label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Enter name" value="{{ old('name')?old('name'):$info->name }}">
            </div>
            <div class="form-group">
              <label for="email">Lead Email<span class="text-danger">*</span></label>
              <input type="email" class="form-control" required id="email" name="email" placeholder="Enter Email" value="{{ old('email')?old('email'):$info->email }}">
            </div>
            <div class="form-group">
              <label for="phone">Lead Phone<span class="text-danger">*</span></label>
              <input type="text" class="form-control" required id="phone" name="phone" placeholder="Enter phone" value="{{ old('phone')?old('phone'):$info->phone }}">
            </div>
            <div class="form-group">
              <label for="address">Lead Address</label>
              <textarea name="address" id="address" cols="60" rows="5" class="form-control" placeholder="Please enter Lead address">{{ old('address')?old('address'):$info->address }}</textarea>
            </div>
            <div class="form-group">
              <label for="type">Requirement Type</label>
              <select class="form-control" name="type" id="type">
                <option value="Loan" {{ $info->type=='Loan'?'selected':'' }}>Loan</option>
                <option value="Credit Card" {{ $info->type=='Credit Card'?'selected':'' }}>Credit Card</option>
                <option value="Insurance" {{ $info->type=='Insurance'?'selected':'' }}>Insurance</option>
                <option value="Consulting" {{ $info->type=='Consulting'?'selected':'' }}>Consulting</option>
              </select>
            </div>
          </div>

          <div class="card-footer">
            <input type="hidden" name="id" value="{{ $info->id }}">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="{{ route('manage-lead') }}" class="btn btn-secondary">Cancel</a>
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