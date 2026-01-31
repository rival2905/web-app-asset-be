@extends('admin.layouts.app')
@section('title')
    Asset Material 
    @if ($action == 'store')
        Create
    @else
        Edit
    @endif  
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
                    @if ($action == 'store')
                        Create
                    @else
                        Edit
                    @endif 
                    Asset Material
                </h5>

                <div class="card-body">
                    @if ($action == 'store')
                        <form class="needs-validation"
                              action="{{ route('admin.asset-material.store') }}"
                              method="POST" enctype="multipart/form-data">
                    @else
                        <form class="needs-validation"
                              action="{{ route('admin.asset-material.update', $data->id) }}"
                              method="POST" enctype="multipart/form-data">
                            @method('PUT')
                    @endif
                    @csrf

                    <div class="row g-6">

                        {{-- NAME --}}
                        <div class="col-md-12">
                            <label class="form-label">Name</label>
                            <input class="form-control"
                                   type="text"
                                   name="name"
                                   value="{{ old('name', @$data->name) }}"
                                   placeholder="Input name of material.."
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ASSET CATEGORY --}}
                        <div class="col-md-4">
                            <label class="form-label">Asset Category</label>
                            <select name="asset_category_id" class="form-control select2">
                                <option value="">-- Pilih Asset Category --</option>
                                @foreach ($assetCategories as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('asset_category_id', @$data->asset_category_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- BRAND --}}
                        <div class="col-md-4">
                            <label class="form-label">Brand</label>
                            <select name="brand_id" class="form-control select2">
                                <option value="">-- Pilih Brand --</option>
                                @foreach ($brands as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('brand_id', @$data->brand_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SERI --}}
                        <div class="col-md-4">
                            <label class="form-label">Seri</label>
                            <select name="seri_id" class="form-control select2">
                                <option value="">-- Pilih Seri --</option>
                                @foreach ($series as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('seri_id', @$data->seri_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn btn-primary me-3">Save</button>
                        <button type="reset" class="btn btn-outline-secondary">Cancel</button>
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
    $('.select2').select2();
</script>
@endpush
