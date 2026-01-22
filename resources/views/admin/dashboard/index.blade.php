
@extends('admin.layouts.app')
@section('title')
    Dashboard  
@parent
@stop

@push('links')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.6.0/echarts.min.js"></script>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card text-center">
          <div class="card-body">
            <h1>Dashboard</h1>
            
          </div>
          
        </div>
      </div>

    </div>
</div>

@stop

@push('scripts')

@endpush
