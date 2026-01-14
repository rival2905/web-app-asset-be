
@extends('admin.layouts.app')
@section('title')
    Rekapitulasi Bulanan
@parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">           
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                  <form  enctype="multipart/form-data">
                    <h5 class="card-title">Rekapitulasi Bulanan</h5>
                    <div class="card-text">
                        <div class="row">
                            <div class="col-6" id="unit_choices">
                                <select id="unit" name="unit_id" class=" form-select unit_choices" required onchange="changeOptionUPTD()">
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" @if($unit->id == @$filter['unit_id']) selected @endif>{{ $unit->name }}</option>
                                    @endforeach
                                </select>
                                @error('unit_id')
                                    <div class="invalid-feedback" style="display: block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-6">
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
                          <button class="btn btn-primary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.monthly') }}">Filter</button>
                        </div>
                        <div class="d-grid col gap-2 mx-auto">
                            {{-- <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.monthly.export') }}">Export</button> --}}

                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.monthly.category','first_periode') }}">Periode 1</button>
                                <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.monthly.category','second_periode') }}">Periode 2</button>
                                <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.monthly.export') }}">Full Periode</button>
                            </div>

                        </div>
                      </div>

                    </div>
                  </form>
                  
                </div>
            </div>
        </div>
        @if (@$filter['ksppj_id'])
            
        <div class="col-sm-12 col-md-12 col-xl-12 mt-3">
            <!-- Contextual Classes -->
            <div class="card">
                <h5 class="card-header">
                    Data Kehadiran Tenaga Harian Lepas
                    <br>
                    UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $filter['uptd_id'] }}
                    @if (@$filter['ksppj_id'])
                    <br>
                    Wilayah {{ App\Models\User::where('id',$filter['ksppj_id'])->first()->jabatan }}
                    @endif
                    @if (@$filter['pengamat_id'])
                        @if (App\Models\User::where('id',$filter['pengamat_id'])->first()->kepengamatan()->exists())
                        <br>
                        Kepengamatan
                            @foreach (App\Models\User::where('id',$filter['mandor_id'])->first()->kepengamatan as $ruas)
                                {{ $ruas->nama }};
                            @endforeach
                        @endif
                    @endif
                    @if (@$filter['mandor_id'])
                    <br>
                    Kemandoran
                        @if (App\Models\User::where('id',$filter['mandor_id'])->first()->lokasi_kerja()->exists())
                            @foreach (App\Models\User::where('id',$filter['mandor_id'])->first()->lokasi_kerja as $ruas)
                                {{ $ruas->nama }};
                            @endforeach
                        @endif
                    @endif
                </h5>
                <div class="table-responsive text-wrap">
                    <table id="example" class="table table-striped">
                        <thead>
                            <tr>
                                <th class="align-middle text-center" rowspan='3'>No</th>

                                <th class="align-middle" rowspan='3'>Nama</th>
                                <th class="align-middle" rowspan='3'>Jabatan</th>
                                <th class="text-center" colspan="{{ count($data_temp['periode']->dates) * 2 }}  ">Tanggal</th>
                                <th class="align-middle" rowspan='3'>Total</th>

                            </tr>
                            <tr>
                                @for ($i=0;$i<count($data_temp['periode']->dates);$i++)
                                    <th class="text-center" colspan="2">{{ $data_temp['periode']->dates[$i] }}</th>
                                @endfor
                            </tr>
                            <tr>
                                @for ($i=0;$i<count($data_temp['periode']->dates);$i++)
                                    <th>Masuk</th>
                                    <th>Pulang</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                        @foreach ($user_check as $no=> $user)
                            <tr>
                                <td class="text-center">{{ ++$no }}</td>

                                <td style="white-space:nowrap;">
                                    <a href="{{ route('admin.rekap.user',Crypt::encryptString($user->id)) }}" target="_blank">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td>{{ $user->jabatan }}</td>
                                
                                @for ($i=0;$i<count($data_temp['periode']->dates);$i++)
                                    {{-- @if ($user->absensi()->whereDate('tanggal',$data_temp['periode']->full_dates[$i])->whereNotNull('jam_masuk')->exists())
                                        @php
                                            $absen = $user->absensi()->whereDate('tanggal',$data_temp['periode']->full_dates[$i])->whereNotNull('jam_masuk')->first();
                                        @endphp
                                        <td>
                                            {{ $absen->jam_masuk }}
                                        </td>
                                        <td>
                                            {{ $absen->jam_keluar }}
                                        </td>
                                    @else
                                        <td class="bg-danger"></td>
                                        <td class="bg-danger"></td>
                                    @endif --}}
                                    @php
                                        $tanggalDicari = $data_temp['periode']->full_dates[$i];
                                        $hasil = array_filter($data_absen, function($item) use ($tanggalDicari,$user) {
                                            return $item['id'] === $user->id && $item['tanggal'] === $tanggalDicari && $item['jam_masuk'] !== null;
                                        });
                                        $indeks = array_keys($hasil);
                                        
                                    @endphp
                                    @if (!empty($hasil))
                                    
                                        <td>
                                            @foreach ($indeks as $key)
                                                {{ $data_absen[$key]['jam_masuk'] }}
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($indeks as $key)
                                                {{ $data_absen[$key]['jam_keluar'] }}
                                            @endforeach
                                        </td>
                                    @else
                                        <td class="bg-danger"></td>
                                        <td class="bg-danger"></td>
                                    @endif
                                @endfor
                                <td>
                                    @if ($ket_periode =='full')
                                        @if ($user->absensi()->whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->whereNotNull('jam_masuk')->exists())
                                        {{ $user->absensi()->whereYear('tanggal',$filter['year'])->whereMonth('tanggal',$filter['month'])->whereNotNull('jam_masuk')->count() }}
                                        @else
                                        0
                                        @endif
                                    @elseif ($ket_periode =='first')
                                        {{ $user->absensi()->whereBetween('tanggal',[$temp_periode->start_first_periode,$temp_periode->end_first_periode])->whereNotNull('jam_masuk')->count() }}

                                    @elseif ($ket_periode =='second')
                                        {{ $user->absensi()->whereBetween('tanggal',[$temp_periode->start_second_periode,$temp_periode->end_second_periode])->whereNotNull('jam_masuk')->count() }}
                                    @else
                                        0
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>No</th>

                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th class="text-center" colspan="{{ count($data_temp['periode']->dates) * 2 }}  ">Tanggal</th>
                                <th class="align-middle" rowspan='3'>Total</th>

                
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <!--/ Contextual Classes -->
        </div>
        @endif
    </div>
    <hr class="my-12" />
</div>
@stop

@push('scripts')
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>

<script>
    new DataTable('#example');
</script>
<script>
    function changeOptionUPTD() {

        //untuk select SUP
        // id = document.getElementById("province").value
        id = $("#uptd_choices").find('.uptd_choices').val()

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

    function changeOptionKSPPJ() {

        //untuk select SUP
        // id = document.getElementById("province").value
        id = $("#ksppj_choices").find('.ksppj_choices').val()

        url = "{{ url('getPengamatByKSPPJ') }}"
        id_select = '#data_pengamat'
        text = 'Choose...'
        option = 'name'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)

        url = "{{ url('getMandorByKSPPJ') }}"
        id_select = '#data_mandor'
        text = 'Choose...'
        option = 'name'
        value = 'id'
        setDataSelect(id, url, id_select, text, value, option)

    }
    function changeOptionPengamat() {

    //untuk select SUP
    // id = document.getElementById("province").value
    id = $("#pengamat_choices").find('.pengamat_choices').val()

    url = "{{ url('getMandorByPengamat') }}"
    id_select = '#data_mandor'
    text = 'Choose...'
    option = 'name'
    value = 'id'
    setDataSelect(id, url, id_select, text, value, option)

    }
</script>
@endpush
