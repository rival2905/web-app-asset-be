
@extends('admin.layouts.app')
@section('title')
    Rekapitulasi Harian
@parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="row">
      {{-- Start Filter  --}}
      <div class="col-sm-12 col-md-12 col-xl-12">
          <div class="card text-center">
              <div class="card-body">
                <form enctype="multipart/form-data">  
                  <h5 class="card-title">Rekapitulasi Harian</h5>
                  
                  <div class="card-text">
                    <div class="row">
                      @if(!$is_role)
                      <div class="col-md col-sm-12" id="unit_choices">
                         
                          <select id="unit" name="unit_id" class=" form-select unit_choices" required onchange="changeOptionunit()">
                            @php
                              $status = '';
                              $unit_name = '';
                            @endphp
                              @foreach ($units as $unit)
                                  @php
                                    
                                    if($unit->id == @$filter['unit_id']){
                                      $status = 'selected';
                                      $unit_name = $unit->name;
                                    } 
                                  @endphp
                                  <option value="{{ $unit->id }}" {{ $status }}>{{ $unit->name }}</option>
                              @endforeach
                          </select>
                          @error('unit_id')
                              <div class="invalid-feedback" style="display: block">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                      @endif
                      <div class="col-md col-sm-12">
                          <input class="form-control" type="date" value="{{ old('tanggal_akhir',@$filter['tanggal_akhir']) }}"name="tanggal_akhir" placeholder="Masukan Tanggal" />
                          @error('tanggal_akhir')
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
                        <button class="btn btn-primary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.daily_absence') }}">Filter</button>
                      </div>
                      <div class="d-grid col gap-2 mx-auto">
                        <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.daily.export','absence&-'.Crypt::encryptString($filter['unit_id'])) }}">Export</button>
                      </div>
                    </div>

                  </div>
                </form>
                
              </div>
          </div>
      </div>
      {{-- End Filter --}}
      <div class="col-sm-12 col-md-6 col-xl-6">
          <div class="card bg-success text-white">
            <a href="{{ url('/admin/recapitulation/daily?unit_id='.$filter['unit_id'].'&tanggal_akhir='.$filter['tanggal_akhir']) }}" class="card-body link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover text-white">
              <h5 class="card-title text-white">{{ $presences->count() }}</h5>
              <p class="card-text">Total Kehadiran</p>
              <p class="card-text">
                <div class="demo-inline-spacing">
                  <span class="badge bg-label-primary">Tepat Waktu
                      <span class="badge rounded-pill bg-primary">{{ $total_tepat_waktu }}</span>
                  </span>
                  <span class="badge bg-label-warning">Terlambat
                      <span class="badge rounded-pill bg-warning">{{ $total_terlambat }}</span>
  
                  </span>
                  ||
                  <span class="badge bg-label-danger">Absen Pulang
                      <span class="badge rounded-pill bg-danger">{{ $total_absen_pulang }}</span>
  
                  </span>
                </div>
              </p>
            </a>
          </div>
      </div>
      <div class="col-sm-12 col-md-6 col-xl-6">
          <div class="card bg-danger text-white">
              <div class="card-body">
                  <h5 class="card-title text-white">
                    {{ count($user_check) -  $presences->count()}}
                  </h5>
                  <p class="card-text">Total Tidak Hadir</p>
                  <p class="card-text">
                    <div class="demo-inline-spacing">
                      <span class="badge bg-label-warning">Izin Sakit
                      <span class="badge rounded-pill bg-warning">{{ $total_izin_sakit }}</span>
  
                      </span>
                      <span class="badge bg-label-warning">Izin Lainya
                      <span class="badge rounded-pill bg-warning">{{ $total_izin_lainnya }}</span>
  
                      </span>
                      <span class="badge bg-label-danger">Tanpa Keterangan
                      <span class="badge rounded-pill bg-danger">{{ $total_tanpa_keterangan }}</span>
  
                      </span>
                    </div>
                  </p>
  
              </div>
          </div>
      </div>
  </div>
  <!-- Contextual Classes -->
  
  <div class="card">
    <h5 class="card-header">Data Pegawai yang Tidak Hadir || || {{ @$filter['tanggal_akhir'] }}
      <br>
      {{ @$unit_name }}
    </h5>
    <div class="table-responsive text-wrap">
      <table id="example" class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Keterangan</th>
            <th>Jabatan</th>
            <th>Aksi</th>

          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          
          @php
          $ket = "Tanpa Keterangan";
          @endphp
          @foreach ($user_absences as $user)
            @if ($user->absensi)
              @if ($user->absensi()->where('tanggal',$filter['tanggal_akhir'])->first())
                @if ($user->absensi()->where('tanggal',$filter['tanggal_akhir'])->first()->keterangan == 'Izin - Izin Sakit' || $user->absensi()->where('tanggal',$filter['tanggal_akhir'])->first()->keterangan == 'Izin - Izin Lainnya')
                  <tr class="table-warning">
                    @php
                      $ket = $user->absensi()->where('tanggal',$filter['tanggal_akhir'])->first()->keterangan;
                    @endphp
                @else
                  <tr class="table-danger">
                  @php
                    $ket = $user->absensi()->where('tanggal',$filter['tanggal_akhir'])->first()->keterangan;
                  @endphp
                @endif
              @else
                <tr class="table-danger">
                  @php
                    $ket = "Tanpa Keterangan";
                  @endphp
              @endif
            @endif
    
            <td>{{ $user->name }}</td>
            <td>
              {{ $ket }}
            </td>
            <td>
              {{ @$user->jabatan }}
            </td>
              <td>
                <a href="{{ route('admin.rekap.user',Crypt::encryptString($user->id)) }}" class="btn btn-xs btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<i class='bx bx-search bx-xs' ></i> <span>Detail</span>">
                  <span class="tf-icons bx bx-search bx-15px"></span>
                </a>
              </td>
          </tr>
          @endforeach
        </tbody>
        <tfoot>
          <tr>
            <th>Nama</th>
            <th>Keterangan</th>
            <th>Jabatan</th>
            <th>Aksi</th>

          </tr>
        </tfoot>
      </table>
    </div>
  
  
  
  
  
  </div>
  <!--/ Contextual Classes -->
  
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

  }
</script>
@endpush
