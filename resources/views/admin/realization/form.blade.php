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
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($action == 'store')
                        <form class="needs-validation" action="{{ route('admin.asset-realization.store') }}" method="POST">
                    @else
                        <form class="needs-validation" action="{{ route('admin.asset-realization.update', $data->id) }}" method="POST">
                        @method('PUT')
                    @endif
                    @csrf

                    <div class="row g-6">
                        {{-- Asset --}}
                        <div class="col-md-6">
                            <label for="asset_id" class="form-label">Asset</label>
                            <select name="asset_id" id="asset_id" class="form-control select2" required>
                                <option value="">-- Pilih Asset --</option>
                                @foreach($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id', @$data->asset_id) == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->asset_code ?? $asset->id }} - {{ $asset->asset_name ?? $asset->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date --}}
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input class="form-control flatpickr-basic" type="date" id="date" name="date" value="{{ old('date', @$data->date) }}" required />
                        </div>

                        {{-- Room (Menampilkan semua room) --}}
                        <div class="col-md-6">
                            <label for="room_id" class="form-label">Room</label>
                            <select name="room_id" id="room_id" class="form-control select2" required>
                                <option value="">-- Pilih Room --</option>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', @$data->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->room_name ?? $room->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 🔥 DETAIL ASSET (DIUBAH JADI DROPDOWN DINAMIS) --}}
                        <div class="col-md-6">
                            <label for="detail_asset_id" class="form-label">Detail Asset</label>
                            <select name="detail_asset_id" id="detail_asset_id" class="form-control select2" required>
                                <option value="">-- Pilih Asset Dulu --</option>
                                {{-- Opsi akan diisi via Javascript --}}
                            </select>
                            @error('detail_asset_id')
                                <div class="text-danger">{{ $message }}</div>
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
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
 $(document).ready(function () {
    $('.select2').select2({ width: '100%' });

    // 🔥 LOGIKA: Ambil Detail Asset saat Asset berubah
    $('#asset_id').on('change', function() {
        let assetId = $(this).val();
        let detailSelect = $('#detail_asset_id');

        // Reset dropdown detail
        detailSelect.empty().append('<option value="">-- Pilih Detail --</option>');

        if (!assetId) return;

        // Fetch detail asset
        $.ajax({
            url: `/admin/realization/details-by-asset/${assetId}`,
            type: 'GET',
            success: function(data) {
                if (data.length > 0) {
                    $.each(data, function(key, value) {
                        // Menampilkan Serial Number + Condition
                        let label = `${value.number_seri || 'No Serial'} (${value.condition})`;
                        detailSelect.append('<option value="'+ value.id +'">'+ label +'</option>');
                    });
                    
                    // Jika mode Edit, set selected value
                    @if($action == 'update' && $data->detail_asset_id)
                        detailSelect.val('{{ $data->detail_asset_id }}').trigger('change');
                    @endif
                } else {
                    detailSelect.append('<option value="">Tidak ada detail tersedia</option>');
                }
            },
            error: function() {
                alert('Gagal mengambil data detail asset');
            }
        });
    });

    // Trigger change saat load pertama kali (Mode Edit)
    @if($action == 'update')
        $('#asset_id').trigger('change');
    @endif
});
</script>
@endpush