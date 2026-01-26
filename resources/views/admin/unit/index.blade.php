
@extends('admin.layouts.app')
@section('title')
    Units  
@parent
@stop

@push('links')

<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

{{-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"> --}}
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">
@endpush

@section('content')
<!-- Content -->
            
<div class="container-xxl flex-grow-1 container-p-y">
  <!-- Contextual Classes -->
  <div class="row">
    <div class="col-sm-12 col-md-12 col-xl-12">
      <div class="card text-center">
          <div class="card-body">
              <h5 class="card-title">Unit <br>Dinas Bina Marga dan Penataan Ruang Provinsi Jawa Barat</h5>

          </div>
      </div>
    </div>
  </div>

  <div class="card mt-1">

    <div class="table-responsive text-wrap">
      <table id="example" class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>UPTD</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($units as $data)
          <tr class="table-default">
            <td>
              {{ @$data->name }}
            </td>
            <td>
              {{ @$data->uptd_id }}
            </td>
          </tr>
          @endforeach
        
        </tbody>
        <tfoot>
          <tr>
            <th>Nama</th>
            <th>UPTD</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
  <!--/ Contextual Classes -->

  <hr class="my-12" />
</div>
<!-- / Content -->
@stop

@push('scripts')


  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script> --}}
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

  <script>

  new DataTable('#example');
  </script>
@endpush

