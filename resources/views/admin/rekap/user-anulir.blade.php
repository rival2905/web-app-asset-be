
@extends('admin.layouts.app')
@section('title')
    User Data Anulir
@parent
@stop

@push('links')
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        {{-- Start Filter  --}}
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                <form enctype="multipart/form-data">  
                    <h5 class="card-title">
                        @if ($user->avatar) 
                            <img
                                src="{{ asset('/storage/foto_absensi/masuk/'.$user->avatar) }}"
                                alt="user-avatar"
                                class="d-block w-px-100 mx-auto h-px-100 rounded"
                                id="uploadedAvatar" />
                        @else
                            <img
                                src="{{ asset('assets/theme1/img/avatars/1.png')}}"
                                alt="user-avatar"
                                class="d-block w-px-100 mx-auto h-px-100 rounded"
                                id="uploadedAvatar" />
                        @endif
                        <br>    
                        {{ $user->name }}
                        <br>
                        {{ $user->jabatan }} {{ $user->bidang }}
                    </h5>
                    
                </form>
                
                </div>
            </div>
        </div>
        {{-- End Filter --}}
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Perhatian!</h4>
                <p>Kehadiran Anda ditolak karena tidak memenuhi kebijakan dan peraturan yang ditetapkan. <br>Perlu diketahui, data yang ditolak akan dihapus secara permanen setelah 60 hari! </p>
                <hr>
                <p class="mb-0">Apabila memerlukan klarifikasi lebih lanjut, silakan hubungi Administrator atau Pengamat terkait!</p>
            </div>

            <div class="card text-center">
                <div class="card-body">
                    <h5 >
                        Data Anulir
                    </h5>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card">
                <div class="table-responsive text-wrap">
                  <table id="example" class="table">
                    <thead>
                      <tr>

                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Foto</th>
                        @if (Auth::user()->id == 0 || Auth::user()->id == 3422 || Auth::user()->role == 'pengamat')
                            <th>Eksekusi</th>
                        @endif
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                      @foreach ($presences as $no => $data)
                      @if ($data->keterangan == "Terlambat")
                      <tr class="table-warning">
                      @else
                      <tr class="table-default">
                      @endif
                        <td>
                            {{ @$data->tanggal }}
                        </td>
                        <td>
                          {{ @$data->keterangan }}
                        </td>
                        <td> 
                          {{ @$data->jam_masuk }}
                        </td>
                        <td>
                          {{ @$data->jam_keluar }}
                        </td>
                        <td>
                            <img
                                src="{{ asset('/storage/foto_absensi/masuk/'.$data->foto_masuk) }}"
                                alt="user-avatar"
                                class="rounded"
                                style="width: 90px;object-fit:cover"
                                id="uploadedAvatar" />
                                @if ($data->foto_keluar)
                                    <img
                                    src="{{ asset('/storage/foto_absensi/keluar/'.$data->foto_keluar) }}"
                                    alt="user-avatar"
                                    class="rounded"
                                    style="width: 90px;object-fit:cover"
                                    id="uploadedAvatar" />
                                @else
                                <img
                                    src="{{ asset('assets/theme1/img/avatars/person-x.png') }}"
                                    alt="user-avatar"
                                    class="rounded"
                                    style="width: 90px;object-fit:cover"
                                    id="uploadedAvatar" />
                                @endif
                        </td>
                        @if (Auth::user()->id == 0 || Auth::user()->id == 3422 || Auth::user()->role == 'pengamat')
                        <td>
                            {{-- @dd($data) --}}
                            <a href="{{ route('admin.rekap.user-restore-anulir',[$data->user_id,$data->id]) }}" 
                                class="btn btn-danger btn-sm" 
                                onclick="return confirm('Apakah Anda yakin ingin mengembalikan data ini?');">
                                Re-Store
                            </a>
                        </td>
                        @endif
                      </tr>
                      @endforeach
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>Tanggal</th>
                        <th>Keterangan</th>
                        <th>Masuk</th>
                        <th>Keluar</th>
                        <th>Foto</th>
                        @if (Auth::user()->id == 0 || Auth::user()->id == 3422 || Auth::user()->role == 'pengamat')
                            <th>Eksekusi</th>
                        @endif
                      </tr>
                    </tfoot>
                  </table>
                </div>
        
            </div>
        </div>
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="d-grid col gap-2 mx-auto mt-4">
                <a href="{{ route('admin.rekap.user',Crypt::encryptString($user->id)) }}" class="btn btn-primary" >
                    Rekap Kehadiran
                </a>
            </div>
        </div>
    </div>

</div>
{{-- @dd($akumulasi) --}}
@stop

@push('scripts')
@endpush
