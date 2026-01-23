
@extends('admin.layouts.app')
@section('title')
    Users  
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
              <h5 class="card-title">User Restore</h5>
              <div class="card-text mt-3">
                <form class="needs-validation" enctype="multipart/form-data">  
                  <div class="row">
                      <div class="col-md-12" id="uptd_choices">
                          <select id="uptd" name="uptd_id" class=" form-select uptd_choices" required>
                                  <option value="" >UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan</option>
                        
                          </select>
                          @error('uptd_id')
                              <div class="invalid-feedback" style="display: block">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                  </div>
                 
                  <div class="mt-6">
                    <div class="row">
                      <div class="d-grid col gap-2 mx-auto">
                        <button class="btn btn-danger btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.user.index') }}">Back</button>
                      </div>
                      <div class="d-grid col gap-2 mx-auto">
                        <button class="btn btn-primary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.user.restore') }}">Filter</button>
                      </div>
                    </div>

                  </div>
                </form>
              </div>
  
          </div>
      </div>
    </div>
    
  </div>

  <div class="card">
    <h5 class="card-header">Data Suspend </h5>
    <div class="table-responsive text-wrap">
      <table id="example" class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Identitas</th>
            <th>Jabatan</th>

            <th>Deleted</th>

            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($users as $data)
          <tr class="table-default">
            <td>
              @if ($data->account_verified_at)
              <img src="{{ asset('assets/theme1/img/avatars/verified.png')}}" alt="Avatar" class="rounded-circle avatar avatar-xs pull-up" />
              @else
              <img src="{{ asset('assets/theme1/img/avatars/unverified.png')}}" alt="Avatar" class="rounded-circle avatar avatar-xs pull-up" />

              @endif
              <span>
                {{ $data->name }}
              </span>
            </td>
            <td>
              @if ($data->nik){{ $data->nik }}<br>@endif
              @if ($data->nip){{ $data->nip }}<br>@endif
              @if ($data->email){{ $data->email }}<br>@endif

            </td>
            <td>
              {{ @$data->jabatan }}
            </td>
            
            <td>
              {{ @$data->deleted_at }}
            </td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  
                  {{-- @if (Auth::user()->id == 0) --}}
                  @if (Auth::user()->role == 'admin' || Auth::user()->role == 'admin-pusat')

                  <button onClick="Delete(this.id)" id="{{ $data->id }}" class="dropdown-item">
                    <i class="bx bx-undo me-1"></i> 
                    RESTORE
                  </button>
                    
                  @endif
                  
                </div>
              </div>
            </td>
          </tr>
          @endforeach
          
         
        </tbody>
        <tfoot>
          <tr>
            <th>Nama</th>
            <th>Identitas</th>
            <th>Jabatan</th>
            <th>Verified</th>

            <th>Actions</th>
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
  // $('#example').DataTable({
  //   layout: {
  //       topStart: {
  //           buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
  //       }
  //   }
  // });
  new DataTable('#example');
  </script>
  <script>
    
    //ajax delete
    function Delete(id)
    {
            var id = id;
            var token = $("meta[name='csrf-token']").attr("content");

            swal({
                title: "APAKAH KAMU YAKIN ?",
                text: "INGIN MENGEMBALIKAN DATA INI!",
                icon: "warning",
                buttons: [
                    'TIDAK',
                    'YA'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {

                    //ajax delete
                    jQuery.ajax({
                        url: "/admin/user/restore/"+id,
                        data:   {
                            "id": id,
                            "_token": token
                        },
                        type: 'DELETE',
                        success: function (response) {
                            if (response.status == "success") {
                                swal({
                                    title: 'BERHASIL!',
                                    text: 'DATA BERHASIL DIKEMBALIKAN!',
                                    icon: 'success',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false,
                                }).then(function() {
                                    location.reload();
                                });
                            }else{
                                swal({
                                    title: 'GAGAL!',
                                    text: 'DATA GAGAL DIKEMBALIKAN!',
                                    icon: 'error',
                                    timer: 1000,
                                    showConfirmButton: false,
                                    showCancelButton: false,
                                    buttons: false,
                                }).then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });

                } else {
                    return true;
                }
            })
    }

  </script>
@endpush

