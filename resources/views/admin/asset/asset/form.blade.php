@extends('admin.layouts.app')

@section('title')
    Asset 
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
                    Asset
                </h5>

                <div class="card-body">
                    @if ($action == 'store')
                        <form class="needs-validation" action="{{ route('admin.asset-asset.store') }}" method="POST">
                    @else
                        <form class="needs-validation" action="{{ route('admin.asset-asset.update', $data->id) }}" method="POST">
                        @method('PUT')
                    @endif
                    @csrf

                    <div class="row g-3">

                        {{-- Name --}}
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input class="form-control" 
                                   type="text" 
                                   name="name" 
                                   value="{{ old('name', @$data->name) }}" 
                                   placeholder="Input asset name..." 
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Size --}}
                        <div class="col-md-6">
                            <label class="form-label">Size</label>
                            <input class="form-control" 
                                   type="text" 
                                   name="size" 
                                   value="{{ old('size', @$data->size) }}" 
                                   placeholder="Input size...">
                        </div>

                        {{-- Item Code --}}
                        <div class="col-md-6">
                            <label class="form-label">Item Code</label>
                            <input class="form-control" 
                                   type="text" 
                                   name="item_code" 
                                   value="{{ old('item_code', @$data->item_code) }}" 
                                   placeholder="Input item code...">
                            @error('item_code')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Stock --}}
                        <div class="col-md-6">
                            <label class="form-label">Stock</label>
                            <input class="form-control" 
                                   type="number" 
                                   name="stock" 
                                   value="{{ old('stock', @$data->stock) }}" 
                                   placeholder="Input stock...">
                        </div>

                        {{-- Description --}}
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Input description...">{{ old('description', @$data->description) }}</textarea>
                        </div>

                        {{-- Asset Type --}}
                        <div class="col-md-4">
                            <label class="form-label">Asset Type</label>
                            <select class="form-control select2" name="asset_type_id">
                                <option value="">-- Select Type --</option>
                                @foreach($assetTypes ?? [] as $type)
                                    <option value="{{ $type->id }}"
                                        {{ old('asset_type_id', @$data->asset_type_id) == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Asset Material --}}
                        <div class="col-md-4">
                            <label class="form-label">Asset Material</label>
                            <select class="form-control select2" name="asset_material_id">
                                <option value="">-- Select Material --</option>
                                @foreach($assetMaterials ?? [] as $material)
                                    <option value="{{ $material->id }}"
                                        {{ old('asset_material_id', @$data->asset_material_id) == $material->id ? 'selected' : '' }}>
                                        {{ $material->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Brand --}}
                        <div class="col-md-4">
                            <label class="form-label">Brand</label>
                            <select class="form-control select2" name="brand_id">
                                <option value="">-- Select Brand --</option>
                                @foreach($brands ?? [] as $brand)
                                    <option value="{{ $brand->id }}"
                                        {{ old('brand_id', @$data->brand_id) == $brand->id ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-3">Save</button>
                        <a href="{{ route('admin.asset-asset.index') }}" class="btn btn-outline-secondary">Cancel</a>
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
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
@endpush
