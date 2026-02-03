@extends('admin.layouts.app')
@section('title')
    Asset Realization
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
                    Asset Realization
                </h5>

                <div class="card-body">
                    @if ($action == 'store')
                        <form class="needs-validation" 
                              action="{{ route('admin.asset-realization.store') }}" 
                              method="post" 
                              enctype="multipart/form-data">  
                    @else
                        <form class="needs-validation" 
                              action="{{ route('admin.asset-realization.update', $data->id) }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                        @method('PUT') 
                    @endif
                    @csrf

                    <div class="row g-6">

                        <!-- Asset ID -->
                        <div class="col-md-6">
                            <label for="asset_id" class="form-label">Asset ID</label>
                            <input class="form-control" 
                                   type="text" 
                                   id="asset_id" 
                                   name="asset_id" 
                                   value="{{ old('asset_id', @$data->asset_id) }}" 
                                   placeholder="Input Asset ID..." 
                                   required />
                            @error('asset_id')
                                <div class="invalid-feedback" style="display:block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Date -->
                        <div class="col-md-6">
                            <label for="date" class="form-label">Date</label>
                            <input class="form-control" 
                                   type="date" 
                                   id="date" 
                                   name="date" 
                                   value="{{ old('date', @$data->date) }}" 
                                   required />
                            @error('date')
                                <div class="invalid-feedback" style="display:block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Room ID -->
                        <div class="col-md-6">
                            <label for="room_id" class="form-label">Room ID</label>
                            <input class="form-control" 
                                   type="text" 
                                   id="room_id" 
                                   name="room_id" 
                                   value="{{ old('room_id', @$data->room_id) }}" 
                                   placeholder="Input Room ID..." 
                                   required />
                            @error('room_id')
                                <div class="invalid-feedback" style="display:block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Detail Asset -->
                        <div class="col-md-6">
                            <label for="detail_asset" class="form-label">Detail Asset</label>
                            <textarea class="form-control" 
                                      id="detail_asset" 
                                      name="detail_asset" 
                                      rows="3" 
                                      placeholder="Input detail asset..." 
                                      required>{{ old('detail_asset', @$data->detail_asset) }}</textarea>
                            @error('detail_asset')
                                <div class="invalid-feedback" style="display:block">
                                    {{ $message }}
                                </div>
                            @enderror
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
@endpush
