@extends('admin.layouts.app')
@section('title')
    Building
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
                    Asset Building
                </h5>

                <div class="card-body">
                    @if ($action == 'store')
                        <form class="needs-validation" action="{{ route('admin.master-building.store') }}" method="post" enctype="multipart/form-data">
                    @else
                        <form class="needs-validation" action="{{ route('admin.master-building.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                    @endif
                    @csrf

                    <div class="row g-6">
                        {{-- NAME (TIDAK DIUBAH) --}}
                        <div class="col-md-12">
                            <label for="name" class="form-label">Name</label>
                            <input class="form-control"
                                   type="text"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', @$data->name) }}"
                                   placeholder="Input name of building.."
                                   autofocus
                                   required />
                            @error('name')
                                <div class="invalid-feedback" style="display:block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- UNIT (TAMBAHAN SAJA) --}}
                        <div class="col-md-12 mt-4">
                            <label for="unit_id" class="form-label">Unit</label>
                            <select name="unit_id" id="unit_id" class="form-control">
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}"
                                        {{ old('unit_id', @$data->unit_id) == $unit->id ? 'selected' : '' }}>
                                        {{ $unit->name }}
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
    $(document).ready(function () {
        $('#unit_id').select2();
    });
</script>
@endpush
