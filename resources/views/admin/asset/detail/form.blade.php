@extends('admin.layouts.app')

@section('title')
    Asset Detail 
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

                <!-- Header -->
                <h5 class="card-header">
                    @if ($action == 'store')
                        Create
                    @else
                        Edit
                    @endif 
                    Asset Detail
                </h5>

                <div class="card-body">

                    <!-- Form Start -->
                    @if ($action == 'store')
                        <form action="{{ route('admin.asset-detail.store') }}" method="POST" enctype="multipart/form-data">
                    @else
                        <form action="{{ route('admin.asset-detail.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                            @method('PUT')
                    @endif
                        @csrf

                        <!-- Hidden asset_id -->
                        <input type="hidden" name="asset_id" value="{{ old('asset_id', $data->asset_id ?? 1) }}">

                        <!-- Name input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                name="name" 
                                id="name"
                                value="{{ old('name', $data->name ?? '') }}" 
                                placeholder="Input name of asset detail..."
                                required
                            >
                            @error('name')
                                <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                        </div>

                    </form>
                    <!-- Form End -->

                </div>
            </div>
        </div>
    </div>  

</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
@endpush
