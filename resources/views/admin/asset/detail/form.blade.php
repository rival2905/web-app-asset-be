@extends('admin.layouts.app')

@section('title')
    Asset Detail 
    @if ($action == 'store') Create @else Edit @endif
@stop

@push('links')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <div class="card">
        <h5 class="card-header">
            @if ($action == 'store') Create @else Edit @endif Asset Detail
        </h5>

        <div class="card-body">

            {{-- Tampilkan semua error validation --}}
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ $action == 'store' ? route('admin.asset-detail.store') : route('admin.asset-detail.update', $data->id) }}" method="POST">
                @csrf
                @if($action == 'update') @method('PUT') @endif

                {{-- Asset dropdown --}}
                <div class="mb-3">
                    <label for="asset_id" class="form-label">Asset</label>
                    <select name="asset_id" id="asset_id" class="form-control select2" required>
                        <option value="">-- Select Asset --</option>
                        @foreach ($assets as $asset)
                        <option value="{{ $asset->id }}" 
                            {{ old('asset_id', $data->asset_id ?? '') == $asset->id ? 'selected' : '' }}>
                            {{ $asset->name }} | Serial: {{ $asset->number_seri ?? '-' }} | Production Year: {{ $asset->production_year ?? '-' }} | Price: {{ number_format($asset->unit_price ?? 0) }} | Condition: {{ $asset->condition ?? '-' }}
                        </option>
                        @endforeach
                    </select>
                    @error('asset_id')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Fields inline --}}
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="number_seri" class="form-label">Serial Number</label>
                        <input type="text" name="number_seri" id="number_seri" class="form-control" 
                               value="{{ old('number_seri', $data->number_seri ?? '') }}">
                    </div>

                    <div class="col-md-3">
                        <label for="production_year" class="form-label">Production Year</label>
                        <input type="number" name="production_year" id="production_year" class="form-control" 
                               value="{{ old('production_year', $data->production_year ?? '') }}" 
                               min="1900" max="{{ date('Y') }}" placeholder="e.g., 2023">
                        @error('production_year')
                            <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="unit_price" class="form-label">Unit Price</label>
                        <input type="number" step="0.01" name="unit_price" id="unit_price" class="form-control" 
                               value="{{ old('unit_price', $data->unit_price ?? '') }}">
                        @error('unit_price')
                            <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label for="condition" class="form-label">Condition</label>
                        <select name="condition" id="condition" class="form-control" required>
                            <option value="">-- Select Condition --</option>
                            @foreach (['Baru','Baik','Cukup','Rusak'] as $cond)
                                <option value="{{ $cond }}" {{ old('condition', $data->condition ?? '') == $cond ? 'selected' : '' }}>
                                    {{ $cond }}
                                </option>
                            @endforeach
                        </select>
                        @error('condition')
                            <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('admin.asset-detail.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.select2').select2({ placeholder: '-- Select Asset --', width: '100%' });
});
</script>
@endpush
