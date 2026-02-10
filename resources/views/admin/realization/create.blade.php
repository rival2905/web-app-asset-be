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
                    
                    {{-- Tampilkan Error Validasi --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form Store --}}
                    <form class="needs-validation" 
                          action="{{ route('admin.asset-realization.store') }}" 
                          method="POST">
                        @csrf

                        <div class="row g-6">
                            {{-- Asset --}}
                            <div class="col-md-6">
                                <label for="asset_id" class="form-label">Asset</label>
                                <select name="asset_id" id="asset_id" class="form-control select2" required>
                                    <option value="">-- Pilih Asset --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}">
                                            {{ $asset->asset_code ?? $asset->id }} - {{ $asset->asset_name ?? $asset->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date --}}
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input class="form-control flatpickr-basic" 
                                       type="date" 
                                       id="date" 
                                       name="date" 
                                       value="{{ old('date') }}" 
                                       required />
                            </div>

                            {{-- Room --}}
                            <div class="col-md-6">
                                <label for="room_id" class="form-label">Room</label>
                                <select name="room_id" id="room_id" class="form-control select2" required>
                                    <option value="">-- Pilih Room --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $room->room_name ?? $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 🔥 DETAIL ASSET (Dengan Tombol Quick Add) --}}
                            <div class="col-md-6">
                                <label for="detail_asset_id" class="form-label">Detail Asset</label>
                                
                                <div class="input-group">
                                    <select name="detail_asset_id" id="detail_asset_id" class="form-control select2" required>
                                        <option value="">-- Pilih Asset Dulu --</option>
                                    </select>
                                    
                                    <button type="button" class="btn btn-outline-primary" onclick="openAddDetailModal()">
                                        <i class="bx bx-plus"></i>
                                    </button>
                                </div>
                                
                                @error('detail_asset_id')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Save</button>
                            <a href="{{ route('admin.asset-realization.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 🔥 MODAL QUICK ADD DETAIL ASSET --}}
<div class="modal fade" id="modalAddDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Detail Asset Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formQuickAddDetail">
                <div class="modal-body">
                    @csrf
                    
                    {{-- ID Asset (Hidden) --}}
                    <input type="hidden" name="asset_id" id="modal_asset_id">

                    <div class="mb-3">
                        <label class="form-label">Number Seri</label>
                        <input type="text" name="number_seri" class="form-control" required placeholder="Contoh: SN-12345">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Production Year</label>
                        <input type="number" name="production_year" class="form-control" placeholder="2024">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Unit Price</label>
                        <input type="number" name="unit_price" class="form-control" placeholder="5000000">
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
                    <button type="submit" class="btn btn-primary">Simpan Detail</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
 $(document).ready(function () {
    $('.select2').select2({ width: '100%' });

    // 🔥 LOGIKA: Ambil Detail Asset saat Asset dipilih
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
                        let label = `${value.number_seri || 'No Serial'} (${value.condition})`;
                        detailSelect.append('<option value="'+ value.id +'">'+ label +'</option>');
                    });
                } else {
                    detailSelect.append('<option value="">Tidak ada detail tersedia</option>');
                }
            },
            error: function() {
                alert('Gagal mengambil data detail asset');
            }
        });
    });

    // 🔥 FUNGSI BUKA MODAL QUICK ADD
    window.openAddDetailModal = function() {
        let assetId = $('#asset_id').val();

        if (!assetId) {
            swal('Peringatan', 'Harap pilih Asset terlebih dahulu!', 'warning');
            return;
        }

        $('#modal_asset_id').val(assetId);
        var myModal = new bootstrap.Modal(document.getElementById('modalAddDetail'));
        myModal.show();
    }

    // 🔥 FUNGSI SIMPAN DETAIL BARU (AJAX)
    $('#formQuickAddDetail').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();
        let detailSelect = $('#detail_asset_id');

        $.ajax({
            // Pastikan route ini mengarah ke Controller AssetDetail store
            url: "/admin/asset-detail/store", 
            type: 'POST',
            data: formData,
            success: function(response) {
                // Tutup Modal
                $('#modalAddDetail').modal('hide');
                
                // Tampilkan Sukses
                swal('Berhasil', 'Detail Asset berhasil ditambahkan!', 'success');

                // Masukkan ke dropdown otomatis
                let newOption = new Option(
                    $('#formQuickAddDetail input[name="number_seri"]').val() + ' (Baru)', 
                    response.id, 
                    true, 
                    true
                );
                detailSelect.append(newOption).trigger('change');

                // Reset Form
                $('#formQuickAddDetail')[0].reset();
            },
            error: function(xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = "Gagal menyimpan data.";
                
                if(errors) {
                    let firstError = Object.keys(errors)[0];
                    errorMsg = errors[firstError][0];
                }
                swal('Gagal', errorMsg, 'error');
            }
        });
    });
});
</script>
@endpush