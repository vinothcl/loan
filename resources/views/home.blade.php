@extends('layouts.admin_app')

@section('content')
<div class="content-wrapper">
  <section class="content">
      <div class="container-fluid">
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">All Leads</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active">All Leads</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
  </div>
</div>
</div>
</section>
</div>

@endsection