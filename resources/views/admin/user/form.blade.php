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
                    <form class="row g-3 needs-validation" action="{{route('admin.user.store')}}" method="post" enctype="multipart/form-data">  
                @else
                    <form class="row g-3 needs-validation" action="{{ route('admin.user.update', $data->id) }}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-6" id="jabatan_choices">
                        <label class="form-label" for="jabatan">Jabatan</label>
                        <select id="jabatan" name="jabatan" class="form-select jabatan_choices" required onchange="changeOptionJabatan()" required>
                            <option value="">Select</option>
                            @foreach ($positions as $position)
                            <option value="{{ $position }}" @if($position == @$data->jabatan) selected @endif>{{ $position }}</option>
                            @endforeach
                        </select>
                        @error('jabatan')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label" for="bidang">Bidang</label>
                        <input class="form-control" list="datalistOptions" value="{{ old('bidang', @$data->bidang) }}" id="bidang" name="bidang" placeholder="Bidang ..." required>
                        <datalist id="datalistOptions">
                            @foreach ($fields as $field)
                                <option value="{{ $field->bidang }}" >
                            @endforeach
                        </datalist>
                        @error('bidang')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if (Auth::user()->id == 0 || Auth::user()->id == 3422)
                    <div class="col-md-12">
                        <label class="form-label">Role</label>
                        <select id="role" name="role" class=" form-select">
                            <option value="">Select</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" @if($role == @$data->role) selected @endif>{{ $role }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>    
                    @endif
                    <div class="col-md-12" id="uptd_choices">
                        <label class="form-label" for="uptd">UPTD</label>
                        <select id="uptd" name="uptd_id" class=" form-select uptd_choices" required onchange="changeOptionUPTD()">
                            <option value="">Select</option>
                            @foreach ($uptds as $uptd)
                                <option value="{{ $uptd }}" @if($uptd == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $uptd }}</option>
                                
                            @endforeach
                            {{-- <option value="1" @if(1 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan I</option>
                            <option value="2" @if(2 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan II</option>
                            <option value="3" @if(3 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan III</option>
                            <option value="4" @if(4 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan IV</option>
                            <option value="5" @if(5 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan V</option>
                            <option value="6" @if(6 == @$data->uptd_id) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan VI</option> --}}
                        </select>
                        @error('uptd_id')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    @if (Auth::user()->id == 0 || Auth::user()->id == 3422)
                    
                    <div class="col-md-12">
                        <label class="form-label" for="lokasi_kerja">LOKASI KERJA</label>
                        <select id="lokasi_kerja" name="lokasi_kerja_id[]" multiple  class="js-example-basic-multiple-limit form-select">
                            <option value="">Select</option>
                            @foreach ($locations as $location)
                            <option value="{{ $location->id }}" @if(@$data->lokasi_kerja) {{ in_array($location->id, @$data->lokasi_kerja()->pluck('master_lokasi_kerja_id')->toArray()) ? 'selected' : '' }} @endif>{{ $location->nama }}</option>
                                
                            @endforeach
                            
                        </select>
                        @error('lokasi_kerja_id')
                            <div class="invalid-feedback" style="display: block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div id="form-atasan" class="col-md-12 ">
                        <div class="card ">
                            <div class="card-header text-center">
                              Pilih Atasan
                            </div>
                            <div class="card-body">
                                <div id="form-ksppj" class="col-md-12">
                                    <label class="form-label" for="data_ksppj">KSPPJ</label>
                                    <select id="data_ksppj" name="data_ksppj" class="form-select">
                                        <option value="">Select</option>
                                        @foreach ($data_ksppj as $ksppj)
                                        <option value="{{ $ksppj->id }}" @if($ksppj->id == @$data->ksppj_id) selected @endif>{{ $ksppj->jabatan }}({{ $ksppj->name }})</option>
                                        @endforeach
                                    </select>
                                    @error('data-ksppj')
                                        <div class="invalid-feedback" style="display: block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div id="form-pengamat" class="col-md-12">
                                    <label class="form-label" for="data_pengamat">Pengamat</label>
                                    <select id="data_pengamat" name="data_pengamat" class="form-select">
                                        <option value="">Select</option>
                                        @foreach ($data_pengamat as $pengamat)
                                        <option value="{{ $pengamat->id }}" @if($pengamat->id == @$data->pengamat_id) selected @endif>{{ $pengamat->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('data-pengamat')
                                        <div class="invalid-feedback" style="display: block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div id="form-mandor" class="col-md-12">
                                    <label class="form-label" for="data_mandor">Mandor</label>
                                    <select id="data_mandor" name="data_mandor" class="form-select">
                                        <option value="">Select</option>
                                        @foreach ($data_mandor as $mandor)
                                        <option value="{{ $mandor->id }}" @if($mandor->id == @$data->mandor_id) selected @endif>{{ $mandor->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('data_mandor')
                                        <div class="invalid-feedback" style="display: block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
    @if ($action == 'update')
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

<script>
    val_jabatan = $("#jabatan_choices").find('.jabatan_choices').val()
     
    if (val_jabatan == 'Pekerja') {
        $("#form-atasan").show();
        $("#form-ksppj").show();
        $("#form-pengamat").show();
        $("#form-mandor").show();
    } else if (val_jabatan == 'Pengamat'){
        $("#form-atasan").show();
        $("#form-ksppj").show();
        $("#form-pengamat").hide();
        $("#form-mandor").hide();
    } else if (val_jabatan == 'Mandor'){
        $("#form-atasan").show();
        $("#form-ksppj").show();
        $("#form-pengamat").show();
        $("#form-mandor").hide();
    } else if (val_jabatan == 'Operator/Supir'){
        $("#form-atasan").show();
        $("#form-ksppj").show();
        $("#form-pengamat").show();
        $("#form-mandor").hide();
    } else if (val_jabatan == 'Mekanik'){
        $("#form-atasan").show();
        $("#form-ksppj").show();
        $("#form-pengamat").show();
        $("#form-mandor").hide();
    }else {
        $("#form-atasan").hide();
        $("#form-ksppj").hide();
        $("#form-pengamat").hide();
        $("#form-mandor").hide();
    }


   
    $(".js-example-basic-multiple-limit").select2({
        maximumSelectionLength: 12
    });

    function changeOptionUPTD() {

        //untuk select SUP
        // id = document.getElementById("province").value
        id = $("#uptd_choices").find('.uptd_choices').val()
        url = "{{ url('getLokasiByUPTD') }}"
        id_select = '#lokasi_kerja'
        text = 'Choose...'
        option = 'nama'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)

        url = "{{ url('getKSPPJByUPTD') }}"
        id_select = '#data_ksppj'
        text = 'Choose...'
        option = 'jabatan'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)
        
        url = "{{ url('getPengamatByUPTD') }}"
        id_select = '#data_pengamat'
        text = 'Choose...'
        option = 'name'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)

        url = "{{ url('getMandorByUPTD') }}"
        id_select = '#data_mandor'
        text = 'Choose...'
        option = 'name'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)

    }

    function changeOptionJabatan() {
        val = $("#jabatan_choices").find('.jabatan_choices').val()

        // alert(val);
        if (val == 'Pekerja') {
            $("#form-atasan").show();
            $("#form-ksppj").show();
            $("#form-pengamat").show();
            $("#form-mandor").show();
        } else if (val == 'Pengamat'){
            $("#form-atasan").show();
            $("#form-ksppj").show();
            $("#form-pengamat").hide();
            $("#form-mandor").hide();
        } else if (val == 'Mandor'){
            $("#form-atasan").show();
            $("#form-ksppj").show();
            $("#form-pengamat").show();
            $("#form-mandor").hide();
        } else if (val == 'Operator/Supir'){
            $("#form-atasan").show();
            $("#form-ksppj").show();
            $("#form-pengamat").show();
            $("#form-mandor").hide();
        } else if (val == 'Mekanik'){
            $("#form-atasan").show();
            $("#form-ksppj").show();
            $("#form-pengamat").show();
            $("#form-mandor").hide();
        }else if (val == ''){
            $("#form-atasan").hide();
            $("#form-ksppj").hide();
            $("#form-pengamat").hide();
            $("#form-mandor").hide();
        } else{
            $("#form-atasan").hide();
            $("#form-ksppj").hide();
            $("#form-pengamat").hide();
            $("#form-mandor").hide();
        }

        
    }
</script>
@endpush
