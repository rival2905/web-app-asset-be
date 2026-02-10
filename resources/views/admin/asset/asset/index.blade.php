@extends('admin.layouts.app')

@section('title')
    Asset  
@parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">
@endpush

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Data Asset</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-1">
        <div class="table-responsive text-wrap">
            <table id="example" class="table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Item Code</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Material</th>
                    <th>Brand</th>
                    @if (Auth::user()->role == 'admin-pusat')
                        <th>Actions</th>
                    @endif
                </tr>
                </thead>

                <tbody class="table-border-bottom-0">
                @foreach ($assets as $data)
                <tr class="table-default">
                    <td>{{ @$data->name }}</td>
                    <td>{{ @$data->size }}</td>
                    <td>{{ @$data->item_code }}</td>
                    <td>{{ @$data->stock }}</td>

                    <td>{{ @$data->assetType->name ?? '-' }}</td>
                    <td>{{ @$data->assetMaterial->name ?? '-' }}</td>
                    <td>{{ @$data->brand->name ?? '-' }}</td>

                    @if (Auth::user()->role == 'admin-pusat')
                    <td>
                        <a href="{{ route('admin.asset-asset.edit', $data->id) }}"
                           class="btn btn-warning btn-sm">
                            <i class='bx bx-edit'></i> Edit
                        </a>

                        <button onClick="Delete(this.id)"
                                id="{{ $data->id }}"
                                type="button"
                                class="btn btn-danger btn-sm">
                            <i class='bx bx-trash'></i> Delete
                        </button>
                    </td>
                    @endif
                </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th>Name</th>
                    <th>Size</th>
                    <th>Item Code</th>
                    <th>Stock</th>
                    <th>Type</th>
                    <th>Material</th>
                    <th>Brand</th>
                    @if (Auth::user()->role == 'admin-pusat')
                        <th>Actions</th>
                    @endif
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <hr class="my-12" />
</div>
<!-- / Content -->
@stop

@push('scripts')
@if (Auth::user()->role == 'admin-pusat')
<div class="buy-now">
    <a href="{{ route('admin.asset-asset.create') }}"
       class="btn btn-danger btn-buy-now">
        <i class="menu-icon tf-icons bx bx-plus"></i>
        Create Asset
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

<script>
    new DataTable('#example');
</script>

<script>
function Delete(id)
{
    var token = $("meta[name='csrf-token']").attr("content");

    swal({
        title: "APAKAH KAMU YAKIN ?",
        text: "INGIN MENGHAPUS DATA INI!",
        icon: "warning",
        buttons: ['TIDAK','YA'],
        dangerMode: true,
    }).then(function(isConfirm) {
        if (isConfirm) {
            jQuery.ajax({
                url: "/admin/asset/destroy/" + id,
                data: {
                    "id": id,
                    "_token": token
                },
                type: 'DELETE',
                success: function (response) {
                    if (response.status == "success") {
                        swal({
                            title: 'BERHASIL!',
                            text: 'DATA BERHASIL DIHAPUS!',
                            icon: 'success',
                            timer: 1000,
                            buttons: false,
                        }).then(function() {
                            location.reload();
                        });
                    } else {
                        swal({
                            title: 'GAGAL!',
                            text: 'DATA GAGAL DIHAPUS!',
                            icon: 'error',
                            timer: 1000,
                            buttons: false,
                        }).then(function() {
                            location.reload();
                        });
                    }
                }
            });
        }
    });
}
</script>
@endpush
