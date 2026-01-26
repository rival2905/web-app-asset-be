@extends('admin.layouts.app')
@section('title')
    User 
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
        
        <div class="card mb-6">
            
            <!-- Account -->
            <div class="card-header">
                <h5 >
                    @if ($action == 'store')
                    User
                    Create
                    @else
                    <div class="button-wrapper">
                        <label class="btn btn-primary me-3 mb-4" tabindex="0" >
                        <span class="d-none d-sm-block">{{ $data->name }}</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        </label>
                        <div>{{ $data->jabatan }} {{ $data->bidang }}</div>
                    </div>
                    @endif  
                </h5>
                <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom ">
                    @if ($action == 'store')
                    
                    @else
                    <div class="row">
                        <div class="col">
                            @if ($data->avatar) 
                                <img
                                    src="{{ asset('/storage/avatar/'.$data->avatar) }}"
                                    alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded"
                                    id="uploadedAvatar" />
                            @else
                                <img
                                    src="{{ asset('assets/theme1/img/avatars/1.png')}}"
                                    alt="user-avatar"
                                    class="d-block w-px-100 h-px-100 rounded"
                                    id="uploadedAvatar" />
                            @endif
                        </div>
                        <div class="col">
                            @if ($data->identity_photo) 
                                <img
                                    src="{{ asset('/storage/ktp/'.$data->identity_photo) }}"
                                    alt="user-avatar"
                                    class="d-block rounded"
                                    style="height: 150px"
                                    id="uploadedAvatar" />
                            @else
                                <img
                                    src="{{ asset('assets/theme1/img/elements/ktp-dumy.png')}}"
                                    alt="user-avatar"
                                    class="d-block rounded"
                                    id="uploadedAvatar" 
                                    style="height: 150px"
                                    />
                            @endif
                        </div>
                    </div>
                        
                    @endif  
                </div>
            </div>
            <div class="card-body pt-4">
                @if ($action == 'store')
                    <form class="needs-validation" action="{{route('admin.user.store')}}" method="post" enctype="multipart/form-data">  
                @else
                    <form class="needs-validation" action="{{ route('admin.user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT') 
                @endif
                @csrf
                <div class="row g-6">
                    <div class="col-6">
                        <label class="form-label">Foto Profil</label>
                        <input name="avatar" class="form-control @error('avatar') is-invalid @enderror" type="file" accept="image/*"/>
              
                        @error('avatar')
                            <div class="invalid-feedback" style="display: block; color:red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label">Foto Identitas (KTP)</label>
                        <input name="identity_photo" class="form-control @error('identity_photo') is-invalid @enderror" type="file" accept="image/*"/>
              
                        @error('identity_photo')
                            <div class="invalid-feedback" style="display: block; color:red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="name" class="form-label">Name</label>
                        <input
                            class="form-control"
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', @$data->name) }}"
                            placeholder="Input your name.."
                            autofocus 
                            required
                        />
                        @error('name')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                        
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nip">NIP</label>
                        <input class="form-control" type="text" id="nip" value="{{ old('nip', @$data->nip) }}" name="nip" placeholder="Masukan NIP ...." />
                        @error('nip')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="nik">NIK</label>
                        <input class="form-control" type="text" value="{{ old('nik', @$data->nik) }}" id="nik" name="nik" placeholder="Masukan NIK ...." />
                        @error('nik')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input
                            class="form-control"
                            type="text"
                            id="email"
                            name="email"
                            value="{{ old('email', @$data->email) }}"
                            placeholder="email-thl@example.com" 
                            required
                        />
                        @error('email')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password" value="{{ old('password') }}"
                            placeholder="Masukkan Password"
                            class="form-control @error('password') is-invalid @enderror " @if ($action == 'store') required @endif>
                        @error('password')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Re-Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            value="{{ old('password_confirmation') }}"
                            placeholder="Masukkan Konfirmasi Password" class="form-control" @if ($action == 'store') required @endif>
                    </div>
                    <div class="col-md-6" id="unit_choices">
                        <label class="form-label" for="unit">Unit</label>
                        <select id="unit" name="unit" class="form-select unit_choices" onchange="changeOptionUnit()" required>
                            <option value="">Select</option>
                            @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @if($unit->id == @$data->unit_id) selected @endif>{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('unit')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="col-md-6" id="jabatan_choices">
                        <label class="form-label" for="jabatan">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select jabatan_choices" required>
                            <option value="">Select</option>
                            @foreach ($jabatans as $jabatan)
                            <option value="{{ $jabatan->jabatan }}" @if($jabatan->jabatan == @$data->jabatan) selected @endif>{{ $jabatan->jabatan }}</option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                
                </div>
                <div class="mt-6">
                    <button type="submit" class="btn btn-primary me-3">Save changes</button>
                    <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
        <div class="card">
            <h5 class="card-header">Delete Account</h5>
            <div class="card-body">
                <div class="mb-6 col-12 mb-0">
                <div class="alert alert-warning">
                    <h5 class="alert-heading mb-1">Are you sure you want to delete your account?</h5>
                    <p class="mb-0">Once you delete your account, there is no going back. Please be certain.</p>
                </div>
                </div>
                <form id="formAccountDeactivation" onsubmit="return false">
                <div class="form-check my-8 ms-2">
                    <input
                    class="form-check-input"
                    type="checkbox"
                    name="accountActivation"
                    id="accountActivation" />
                    <label class="form-check-label" for="accountActivation"
                    >I confirm my account deactivation</label
                    >
                </div>
                <button type="submit" class="btn btn-danger deactivate-account" disabled>
                    Deactivate Account
                </button>
                </form>
            </div>
        </div>
      </div>
    </div>
    <div class="buy-now">
        @if ($action == 'update')
            @if (@$data->account_verified_at)
                <a href="#" class="btn btn-success btn-buy-now">
                    <i class="menu-icon tf-icons bx bx-check-shield"></i>
                    Verified
                </a>
            @else
       
                <button type="button" class="btn btn-danger btn-buy-now" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                    <i class="menu-icon tf-icons bx bx-shield-x"></i>
                    Unverified
                </button>
            @endif
        @endif
    </div>
    
    @stop
    
    <!-- Modal -->
    @if ($action == 'update' && Auth::user()->role == 'admin-pusat')
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Verifikasi Pegawai</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="row g-3 needs-validation" action="{{ route('admin.user.verified-account', $data->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT') 
                        @csrf
                        <div class="modal-body">
                            <div class="form-check">
                                <input class="form-check-input" name="verified" type="checkbox" id="defaultCheck1" required>
                                <label class="form-check-label" for="defaultCheck1">
                                    Data akun ini adalah benar dan seorang pegawai serta tidak di manipulasi!
                                </label>
                            </div>
                            @error('verified')
                                <div class="invalid-feedback" style="display: block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                        {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                        <button type="submit" class="btn btn-primary ml-3">Verified</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    
</div>



@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
{{-- <script src="{{ asset('assets/theme1/js/ui-modals.js')}}"></script> --}}

@endpush
