@extends('admin.layouts.app')
@section('title')
    Asset materials
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
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Data Material Asset</h5>
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
                        <th>Slug</th>
                        <th>Asset Category</th> {{-- ✅ GANTI --}}
                        <th>Brand</th>
                        <th>Seri</th>
                        @if (Auth::user()->role == 'admin-pusat')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="table-border-bottom-0">
                    @foreach ($asset_materials as $data)
                    <tr>
                        <td>{{ $data->name }}</td>
                        <td>{{ $data->slug }}</td>

                        {{-- ✅ GANTI RELASI --}}
                        <td>{{ $data->assetCategory->name ?? '-' }}</td>
                        <td>{{ $data->brand->name ?? '-' }}</td>
                        <td>{{ $data->seri->name ?? '-' }}</td>

                        @if (Auth::user()->role == 'admin-pusat')
                        <td>
                            <a href="{{ route('admin.asset-material.edit', $data->slug) }}"
                               class="btn btn-warning btn-sm">
                                <i class='bx bx-edit'></i> Edit
                            </a>

                            <button onClick="Delete(this.id)"
                                    id="{{ $data->id }}"
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
                        <th>Nama</th>
                        <th>Slug</th>
                        <th>Asset Category</th>
                        <th>Brand</th>
                        <th>Seri</th>
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
@stop

@push('scripts')
@if (Auth::user()->role == 'admin-pusat')
<div class="buy-now">
    <a href="{{ route('admin.asset-material.create') }}"
       class="btn btn-danger btn-buy-now">
        <i class="menu-icon tf-icons bx bx-plus"></i>
        Create Material
    </a>
</div>
@endif

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script>
    new DataTable('#example');
</script>
@endpush
