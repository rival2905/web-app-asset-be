
@extends('admin.layouts.app')
@section('title')
    User Absen Recapitulations
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
                        {{ $user->jabatan }}~{{ $user->bidang }}
                    </h5>
                    <div class="card-text">
                        <div class="row">
                            
                            <div class="col-md-12">
                                <input class="form-control" type="month" value="{{ old('month',@$filter['year'].'-'.@$filter['month']) }}"name="month" placeholder="Masukan Bulan" />
                                @error('month')
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                    <div class="row">
                        <div class="d-grid col gap-2 mx-auto">
                        <button class="btn btn-primary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.user',Crypt::encryptString($user->id)) }}">Filter</button>
                        </div>
                        {{-- <div class="d-grid col gap-2 mx-auto">
                        <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.daily.export','absence&-'.Crypt::encryptString($filter['uptd_id'])) }}">Export</button>
                        </div> --}}
                    </div>

                    </div>
                </form>
                
                </div>
            </div>
        </div>
        {{-- End Filter --}}
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                    <h5 >
                        Periode
                        <br>
                        {{Carbon\Carbon::createFromFormat('m', $filter['month'])->format('F') }} {{ $filter['year'] }}
                    </h5>
                    @php
                        $akumulasi['hadir']=0;
                        $akumulasi['tepat_waktu']=0;
                        $akumulasi['terlambat']=0;
                        $akumulasi['tidak_hadir']=0;
                        $akumulasi['izin_sakit']=0;
                        $akumulasi['izin_lainnya']=0;
                        $akumulasi['alpha']=0;

                    @endphp
                    @for ($i=0;$i < count($data_temp['periode1']['dates']);$i++)
                        @if ($filter['now'] >= $data_temp['periode1']['full_dates'][$i])  
                            @if ($user->absensi()->whereDate('tanggal',$data_temp['periode1']['full_dates'][$i])->exists())
                                @if ($user->absensi()->whereDate('tanggal',$data_temp['periode1']['full_dates'][$i])->whereNotNull('jam_masuk')->exists())
                                    @if (in_array($user->absensi()->whereDate('tanggal', $data_temp['periode1']['full_dates'][$i])->whereNotNull('jam_masuk')->first()->keterangan, ['Tepat Waktu', 'WFA']))
                                        @php $akumulasi['tepat_waktu']++; @endphp
                                        <span class="badge badge-center bg-primary">{{ $data_temp['periode1']['dates'][$i] }}</span>
                                    @else
                                        @php $akumulasi['terlambat']++; @endphp
                                        <span class="badge badge-center bg-warning">{{ $data_temp['periode1']['dates'][$i] }}</span>
                                    @endif
                                    @php $akumulasi['hadir']++; @endphp
                                @else
                                    @if ($user->absensi()->whereDate('tanggal',$data_temp['periode1']['full_dates'][$i])->where('keterangan','like','%Izin%')->exists())
                                        @if ($user->absensi()->whereDate('tanggal',$data_temp['periode1']['full_dates'][$i])->where('keterangan','Izin - Izin Sakit')->exists())
                                            @php $akumulasi['izin_sakit']++; @endphp
                                        @else
                                            @php $akumulasi['izin_lainnya']++; @endphp
                                        @endif
                                    @else  
                                        @php $akumulasi['alpha']++; @endphp
                                    @endif
                                    @php $akumulasi['tidak_hadir']++; @endphp
                                    <span class="badge badge-center bg-danger">{{ $data_temp['periode1']['dates'][$i] }}</span>
                                @endif
                            @else
                                @php $akumulasi['tidak_hadir']++; @endphp
                                @php $akumulasi['alpha']++; @endphp
                                <span class="badge badge-center bg-danger">{{ $data_temp['periode1']['dates'][$i] }}</span>
                            @endif
                        @else
                            <span class="badge badge-center bg-label-primary">{{ $data_temp['periode1']['dates'][$i] }}</span>
                        @endif
                    @endfor
                    <div class="mt-2">
                        @for ($x=0;$x < count($data_temp['periode2']['dates']);$x++)
                            @if ($filter['now'] >= $data_temp['periode2']['full_dates'][$x])  
                                @if ($user->absensi()->whereDate('tanggal',$data_temp['periode2']['full_dates'][$x])->exists())
                                    @if ($user->absensi()->whereDate('tanggal',$data_temp['periode2']['full_dates'][$x])->whereNotNull('jam_masuk')->exists())
                                        @if ($user->absensi()->whereDate('tanggal',$data_temp['periode2']['full_dates'][$x])->whereNotNull('jam_masuk')->first()->keterangan == "Tepat Waktu")
                                            @php $akumulasi['tepat_waktu']++; @endphp
                                            <span class="badge badge-center bg-primary">{{ $data_temp['periode2']['dates'][$x] }}</span>
                                        @else
                                            @php $akumulasi['terlambat']++; @endphp
                                            <span class="badge badge-center bg-warning">{{ $data_temp['periode2']['dates'][$x] }}</span>
                                        @endif
                                        @php $akumulasi['hadir']++; @endphp
                                    @else
                                        @if ($user->absensi()->whereDate('tanggal',$data_temp['periode2']['full_dates'][$x])->where('keterangan','like','%Izin%')->exists())
                                            @if ($user->absensi()->whereDate('tanggal',$data_temp['periode2']['full_dates'][$x])->where('keterangan','Izin - Izin Sakit')->exists())
                                                @php $akumulasi['izin_sakit']++; @endphp
                                            @else
                                                @php $akumulasi['izin_lainnya']++; @endphp
                                            @endif
                                        @else  
                                            @php $akumulasi['alpha']++; @endphp
                                        @endif
                                        @php $akumulasi['tidak_hadir']++; @endphp
                                        <span class="badge badge-center bg-danger">{{ $data_temp['periode2']['dates'][$x] }}</span>
                                    @endif
                                @else
                                    @php $akumulasi['tidak_hadir']++; @endphp
                                    @php $akumulasi['alpha']++; @endphp
                                    <span class="badge badge-center bg-danger">{{ $data_temp['periode2']['dates'][$x] }}</span>
                                @endif
                            @else
                                <span class="badge badge-center bg-label-primary">{{ $data_temp['periode2']['dates'][$x] }}</span>
                            @endif
                        @endfor
                    </div>
                    <div class="row mt-2">
                        <div class="col-sm-12 col-md-6 col-xl-6">
                            <div class="card bg-success text-white">
                                <h5 class="card-title text-white">{{ $akumulasi['hadir'] }}</h5>
                                <p class="card-text">Total Kehadiran</p>
                                <p class="card-text">
                                  <div class="demo-inline-spacing">
                                    <span class="badge bg-label-primary">Tepat Waktu 
                                        <span class="badge rounded-pill bg-primary">{{ $akumulasi['tepat_waktu'] }}</span>
                                    </span>
                                    <span class="badge bg-label-warning">Terlambat 
                                        <span class="badge rounded-pill bg-warning">{{ $akumulasi['terlambat'] }}</span>
                  
                                    </span>
                                  </div>
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-xl-6">
                            <div class="card bg-danger text-white">
                                <h5 class="card-title text-white">{{ $akumulasi['tidak_hadir'] }}</h5>
                                <p class="card-text">Total Tidak Hadir</p>
                                <p class="card-text">
                                    <div class="demo-inline-spacing">
                                        <span class="badge bg-label-warning">Izin Sakit 
                                        <span class="badge rounded-pill bg-warning">{{ $akumulasi['izin_sakit'] }}</span>
                
                                        </span>
                                        <span class="badge bg-label-warning">Izin Lainya 
                                        <span class="badge rounded-pill bg-warning">{{ $akumulasi['izin_lainnya'] }}</span>
                
                                        </span>
                                        <span class="badge bg-label-danger">Tanpa Keterangan 
                                        <span class="badge rounded-pill bg-danger">{{ $akumulasi['alpha'] }}</span>
                
                                        </span>
                                    </div>
                                </p>
                            </div>
                        </div>
                        @if ($user->data_anulir()->count()>0)
                        <div class="col-sm-12 col-md-12 col-xl-12">
                            <div class="d-grid col gap-2 mx-auto mt-4">
                                <a href="{{ route('admin.data-rekap.user-anulir',Crypt::encryptString($user->id)) }}" class="btn btn-warning" >
                                    {{ $user->data_anulir()->count() }}
                                    <br>
                                    Total Anulir
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
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
                            <a href="{{ route('admin.rekap.user-anulir',[$data->user_id,$data->id]) }}" 
                                class="btn btn-warning btn-sm" 
                                onclick="return confirm('Apakah Anda yakin ingin menganulir data ini?');">
                                Anulir
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
    </div>

</div>
{{-- @dd($akumulasi) --}}
@stop

@push('scripts')
@endpush
