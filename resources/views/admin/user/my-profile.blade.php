@extends('admin.layouts.app')
@section('title')
    My Profile 
@parent
@stop

@push('links')
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-md-12">
        
        <div class="card mb-6">
            
            <!-- Account -->
            <div class="card-header">
                <h5 >
                    <div class="button-wrapper">
                        <label class="btn btn-primary me-3 mb-4" tabindex="0" >
                        <span class="d-none d-sm-block">{{ $data->name }}</span>
                        <i class="bx bx-upload d-block d-sm-none"></i>
                        </label>
                        <div>{{ $data->jabatan }} {{ $data->bidang }}</div>
                    </div>
                </h5>
                <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom ">
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
                </div>
            </div>
            <div class="card-body pt-4">
                <form class="row g-3 needs-validation" action="{{ route('user.myprofile.update') }}" method="POST" enctype="multipart/form-data">
                @method('PUT') 
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
                    @if (!$data->account_verified_at)   
                    <div class="col-6">
                        <label class="form-label">Foto Identitas (KTP)</label>
                        <input name="identity_photo" class="form-control @error('identity_photo') is-invalid @enderror" type="file" accept="image/*"/>
              
                        @error('identity_photo')
                            <div class="invalid-feedback" style="display: block; color:red">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @endif
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
                            class="form-control @error('password') is-invalid @enderror ">
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
                            placeholder="Masukkan Konfirmasi Password" class="form-control">
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
      </div>
    </div>
    <div class="buy-now">
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
    </div>
    
    @stop
    
    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Verifikasi Pegawai</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">Perhatian!</h4>
                    <p>Input semua Dokumen yang diwajibkan!</p>
                    <hr>
                    <p class="mb-0">Hubungi Administrator untuk dilakukan Verifikasi!!</p>
                </div>
            </div>
        </div>
    </div>

    
</div>



@push('scripts')

@endpush
