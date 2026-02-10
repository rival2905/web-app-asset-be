@extends('admin.layouts.app')

@section('title')
    Edit Asset Realization
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
                <h5 class="card-header">Edit Asset Realization</h5>
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

                    <form action="{{ route('admin.asset-realization.update', $data->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Asset</label>
                                <select name="asset_id" id="asset_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Asset --</option>
                                    @foreach($assets as $asset)
                                        <option value="{{ $asset->id }}" {{ old('asset_id', $data->asset_id) == $asset->id ? 'selected' : '' }}>
                                            {{ $asset->asset_code ?? $asset->id }} - {{ $asset->asset_name ?? $asset->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ old('date', $data->date) }}" required />
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Room</label>
                                <select name="room_id" id="room_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Room --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $data->room_id) == $room->id ? 'selected' : '' }}>
                                            {{ $room->room_name ?? $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Detail Asset</label>
                                <select name="detail_asset_id" id="detail_asset_id" class="form-control select2-main" required>
                                    <option value="">-- Pilih Asset Dulu --</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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
        $('.select2-main').select2({ width: '100%' });
        let initialDetailId = "{{ $data->detail_asset_id ?? '' }}";

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
        // Trigger load saat Edit
        if ($('#asset_id').val()) $('#asset_id').trigger('change');
    });
</script>
@endpush