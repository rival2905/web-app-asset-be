@extends('admin.layouts.app')

@section('title')
    Create Asset Realization
@parent
@stop

@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h5 class="card-header">Create Asset Realization</h5>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            <h4 class="alert-heading">Gagal menyimpan!</h4>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.asset-realization.store') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Asset <span class="text-danger">*</span></label>
                                <select name="asset_id" id="asset_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Asset --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->asset_code ?? $asset->id }} - {{ $asset->asset_name ?? $asset->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" name="date" class="form-control" value="{{ old('date') }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room <span class="text-danger">*</span></label>
                                <select name="room_id" id="room_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Room --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                            {{ $room->room_name ?? $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Detail Asset <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select name="detail_asset_id" id="detail_asset_id" class="form-control select2-main" required>
                                        <option value="">-- Pilih Asset Dulu --</option>
                                    </select>
                                    <button type="button" class="btn btn-outline-primary" onclick="openAddDetailModal()">
                                        <i class="bx bx-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Realization</button>
                            <a href="{{ route('admin.asset-realization.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade" id="modalAddDetail" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Detail Asset Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formQuickAddDetail">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="asset_id" id="modal_asset_id">
                    <div class="mb-3">
                        <label class="form-label">Number Seri</label>
                        <input type="text" name="number_seri" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-control" required>
                            <option value="Baru">Baru</option>
                            <option value="Baik">Baik</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        $('.select2-main').select2({ width: '100%' });
        let initialDetailId = "{{ old('detail_asset_id') }}";

        $('#asset_id').on('change', function() {
            let assetId = $(this).val();
            let detailSelect = $('#detail_asset_id');
            detailSelect.empty().append('<option value="">-- Pilih Detail --</option>');
            if (!assetId) return;
            $.ajax({
                url: `/admin/realization/details-by-asset/${assetId}`,
                type: 'GET',
                success: function(data) {
                    if (data.length > 0) {
                        $.each(data, function(key, value) {
                            detailSelect.append(`<option value="${value.id}">${value.number_seri} (${value.condition})</option>`);
                        });
                        if (initialDetailId) {
                            detailSelect.val(initialDetailId).trigger('change');
                            initialDetailId = null;
                        }
                    }
                }
            });
        });
        if ($('#asset_id').val()) $('#asset_id').trigger('change');

        window.openAddDetailModal = function() {
            if (!$('#asset_id').val()) return Swal.fire('Info', 'Pilih Asset dulu', 'warning');
            $('#modal_asset_id').val($('#asset_id').val());
            new bootstrap.Modal(document.getElementById('modalAddDetail')).show();
        };

        $('#formQuickAddDetail').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "/admin/asset-detail/store",
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    $('#modalAddDetail').modal('hide');
                    let serial = $('#formQuickAddDetail input[name="number_seri"]').val();
                    $('#detail_asset_id').append(new Option(`${serial} (Baru)`, res.id, true, true)).trigger('change');
                    $('#formQuickAddDetail')[0].reset();
                }
            });
        });
    });
</script>
@endpush