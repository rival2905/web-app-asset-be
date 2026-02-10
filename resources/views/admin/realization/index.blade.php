@extends('admin.layouts.app')

@section('title')
    Data Realization Asset
@parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <h5 class="card-header">Data Realization Asset</h5>
                <div class="card-body">
                    <div class="table-responsive text-wrap">
                        <table id="example" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Asset</th>
                                    <th>Date</th>
                                    <th>Room</th>
                                    <th>Detail Asset (Seri)</th>
                                    @if (Auth::user()->role == 'admin-pusat')
                                    <th>Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($realizations as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data->asset->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->date)->format('d-m-Y') }}</td>
                                    <td>{{ $data->room->name ?? '-' }}</td>
                                    <td>
                                        {{ $data->assetDetail->number_seri ?? '-' }} 
                                        <span class="badge bg-label-secondary">{{ $data->assetDetail->condition ?? '-' }}</span>
                                    </td>
                                    @if (Auth::user()->role == 'admin-pusat')
                                    <td>
                                        <a href="{{ route('admin.asset-realization.edit', $data->id) }}" class="btn btn-warning btn-sm">
                                            <i class='bx bx-edit'></i> Edit
                                        </a>
                                        <button onClick="Delete({{ $data->id }})" type="button" class="btn btn-danger btn-sm">
                                            <i class='bx bx-trash'></i> Delete
                                        </button>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
@if (Auth::user()->role == 'admin-pusat')
<div class="buy-now">
    <a href="{{ route('admin.asset-realization.create') }}" class="btn btn-danger btn-buy-now">
        <i class="menu-icon tf-icons bx bx-plus"></i> Create New
    </a>
</div>
@endif
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    new DataTable('#example');
</script>

<script>
function Delete(id)
{
    var token = $("meta[name='csrf-token']").attr("content");
    Swal.fire({
        title: "APAKAH KAMU YAKIN ?",
        text: "INGIN MENGHAPUS DATA INI!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'YA, HAPUS!',
        cancelButtonText: 'TIDAK'
    }).then((result) => {
        if (result.isConfirmed) {
            jQuery.ajax({
                url: "/admin/realization/destroy/" + id,
                data: { "_token": token },
                type: 'DELETE',
                success: function (response) {
                    if (response.status == "success") {
                        Swal.fire('BERHASIL!', 'DATA BERHASIL DIHAPUS!', 'success').then(() => location.reload());
                    } else {
                        Swal.fire('GAGAL!', 'DATA GAGAL DIHAPUS!', 'error');
                    }
                },
                error: function() { Swal.fire('GAGAL!', 'TERJADI KESALAHAN SISTEM!', 'error'); }
            });
        }
    })
}
</script>
@endpush