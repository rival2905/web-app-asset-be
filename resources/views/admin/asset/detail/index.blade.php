@extends('admin.layouts.app')

@section('title')
    Asset Details
    @parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="row mb-3">
        <div class="col-12">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Data Detail Asset</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-2">
        <div class="table-responsive text-wrap">
            <table id="example" class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>Asset</th>
                    <th>Nomor Seri</th>
                    <th>Tahun Produksi</th>
                    <th>Harga</th>
                    <th>Kondisi</th>
                    <th>Nama Detail</th>
                    <th>Slug</th>
                    @if (Auth::user()->role == 'admin-pusat')
                        <th>Actions</th>
                    @endif
                </tr>
                </thead>

                <tbody>
                @foreach ($details as $data)
                    <tr>
                        <td>{{ $data->asset->name ?? '-' }}</td>
                        <td>{{ $data->number_seri ?? '-' }}</td>
                        <td>{{ $data->production_year ?? '-' }}</td>
                        <td>{{ number_format($data->unit_price ?? 0, 2) }}</td>
                        <td>{{ $data->condition ?? '-' }}</td>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->slug ?? '-' }}</td>

                        @if (Auth::user()->role == 'admin-pusat')
                            <td>
                                <a href="{{ route('admin.asset-detail.edit', $data->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bx bx-edit"></i> Edit
                                </a>

                                <button onclick="Delete(this.id)" id="{{ $data->id }}" class="btn btn-danger btn-sm">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>

                <tfoot>
                <tr>
                    <th>Asset</th>
                    <th>Nomor Seri</th>
                    <th>Tahun Produksi</th>
                    <th>Harga</th>
                    <th>Kondisi</th>
                    <th>Nama Detail</th>
                    <th>Slug</th>
                    @if (Auth::user()->role == 'admin-pusat')
                        <th>Actions</th>
                    @endif
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@stop

@push('scripts')
@if (Auth::user()->role == 'admin-pusat')
<div class="buy-now">
    <a href="{{ route('admin.asset-detail.create') }}" class="btn btn-danger btn-buy-now">
        <i class="menu-icon tf-icons bx bx-plus"></i> Create
    </a>
</div>
@endif

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script>
    new DataTable('#example');

    function Delete(id) {
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        swal({
            title: "APAKAH KAMU YAKIN ?",
            text: "INGIN MENGHAPUS DATA INI!",
            icon: "warning",
            buttons: ['TIDAK', 'YA'],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                fetch("/admin/asset/asset-detail/destroy/" + id, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": token
                    }
                }).then(() => location.reload());
            }
        });
    }
</script>
@endpush
