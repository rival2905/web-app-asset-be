@extends('admin.layouts.app')

@section('title')
    Asset Realization
    @if ($action == 'store') Create @else Edit @endif
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
                <h5 class="card-header">
                    @if ($action == 'store') Create @else Edit @endif Asset Realization
                </h5>

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

                    <form action="{{ $action == 'store' ? route('admin.asset-realization.store') : route('admin.asset-realization.update', $data->id) }}" method="POST">
                        @if ($action == 'update')
                            @method('PUT')
                        @endif
                        @csrf

                        <div class="row g-3">
                            {{-- Asset --}}
                            <div class="col-md-6">
                                <label for="asset_id" class="form-label">Asset <span class="text-danger">*</span></label>
                                <select name="asset_id" id="asset_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Asset --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" {{ old('asset_id', @$data->asset_id) == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->item_code ?? $asset->id }} - {{ $asset->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date --}}
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="date" name="date" value="{{ old('date', @$data->date) }}" required />
                            </div>

                            {{-- Room --}}
                            <div class="col-md-6">
                                <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                                <select name="room_id" id="room_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Room --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', @$data->room_id) == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Detail Asset --}}
                            <div class="col-md-6">
                                <label for="detail_asset_id" class="form-label">Detail Asset <span class="text-danger">*</span></label>
                                
                                <div class="input-group">
                                    <select name="detail_asset_id" id="detail_asset_id" class="form-control select2-main" required>
                                        <option value="">-- Pilih Asset Dulu --</option>
                                    </select>
                                    
                                    @if ($action == 'store')
                                    <button type="button" class="btn btn-outline-primary" onclick="openAddDetailModal()">
                                        <i class="bx bx-plus"></i> Tambah
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary me-3">Save</button>
                            <a href="{{ route('admin.asset-realization.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL QUICK ADD DETAIL ASSET --}}
<div class="modal fade" id="modalAddDetail" tabindex="-1" aria-hidden="true">
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
                        <input type="text" name="number_seri" class="form-control" required placeholder="Contoh: SN-12345">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Condition</label>
                        <select name="condition" class="form-control" required>
                            <option value="Baru">Baru</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
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
    // Init Select2
    $('.select2-main').select2({ width: '100%' });

    // Tentukan ID Detail Awal (untuk Edit Mode)
    let initialDetailId = "{{ old('detail_asset_id', @$data->detail_asset_id) }}";

    // Load Detail Asset saat Asset berubah
    $('#asset_id').on('change', function() {
        let assetId = $(this).val();
        let detailSelect = $('#detail_asset_id');
        detailSelect.empty().append('<option value="">-- Pilih Detail --</option>');

        if (!assetId) return;

        $.ajax({
            url: `{{ url('/admin/realization/details-by-asset') }}/${assetId}`,
            type: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    $.each(data, function(key, value) {
                        let label = `${value.number_seri || 'No Serial'} (${value.condition})`;
                        detailSelect.append(`<option value="${value.id}">${label}</option>`);
                    });
                    
                    // Set value otomatis jika ada initialDetailId
                    if (initialDetailId) {
                        detailSelect.val(initialDetailId).trigger('change');
                        initialDetailId = null;
                    }
                } else {
                    detailSelect.append('<option value="">Tidak ada detail tersedia</option>');
                }
            },
            error: function() {
                Swal.fire('Error', 'Gagal mengambil data detail asset', 'error');
            }
        });
    });

    // Trigger load pertama (untuk Edit Mode)
    if ($('#asset_id').val()) {
        $('#asset_id').trigger('change');
    }

    // Modal Logic
    window.openAddDetailModal = function() {
        let assetId = $('#asset_id').val();
        if (!assetId) {
            Swal.fire('Info', 'Pilih Asset dulu', 'warning');
            return;
        }
        $('#modal_asset_id').val(assetId);
        new bootstrap.Modal(document.getElementById('modalAddDetail')).show();
    }

    // Submit Modal Quick Add
    $('#formQuickAddDetail').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('admin.asset-detail.store-ajax') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function(res) {
                bootstrap.Modal.getInstance(document.getElementById('modalAddDetail')).hide();
                Swal.fire('Berhasil', 'Detail asset berhasil ditambahkan', 'success');
                
                let serial = res.number_seri || 'No Serial';
                let newOption = new Option(`${serial} (${res.condition})`, res.id, true, true);
                $('#detail_asset_id').append(newOption).trigger('change');
                
                $('#formQuickAddDetail')[0].reset();
            },
            error: function(xhr) {
                let errorMsg = xhr.responseJSON?.message || 'Gagal menyimpan data';
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    });
});
</script>
@endpush