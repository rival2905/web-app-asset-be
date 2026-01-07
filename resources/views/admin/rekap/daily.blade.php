
@extends('admin.layouts.app')
@section('title')
    Rekapitulasi Harian
@parent
@stop

@push('links')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
  <link rel="stylesheet" href="https://js.arcgis.com/4.26/esri/themes/light/main.css">


@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="card text-center">
                <div class="card-body">
                  <form  enctype="multipart/form-data">
                    <h5 class="card-title">Rekapitulasi Harian</h5>
                    @if(!$is_role)
                          <div class="card-text">
                              <div class="row">
                                  <div class="col" id="uptd_choices">
                                      <select id="uptd" name="uptd_id" class=" form-select uptd_choices" required onchange="changeOptionUPTD()">
                                          @foreach ($uptds as $uptd)
                                              <option value="{{ $uptd }}" @if($uptd == @$filter['uptd_id']) selected @endif>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $uptd }}</option>
                                          @endforeach
                                      </select>
                                      @error('uptd_id')
                                      <div class="invalid-feedback" style="display: block">
                                          {{ $message }}
                                      </div>
                                      @enderror
                                  </div>
                                  <div class="col" id="ksppj_choices">
                                      <select id="data_ksppj" name="ksppj_id" class=" form-select ksppj_choices">
                                          {{-- <option value="">Select KSPPJ</option> --}}
                                          @foreach ($ksppjs as $ksppj)
                                              <option value="{{ $ksppj->id }}" @if($ksppj->id == @$filter['ksppj_id']) selected @endif>{{ $ksppj->name }}({{ $ksppj->jabatan }})</option>
                                          @endforeach
                                      </select>
                                      @error('ksppj_id')
                                      <div class="invalid-feedback" style="display: block">
                                          {{ $message }}
                                      </div>
                                      @enderror
                                  </div>
                                  <div class="col">
                                      <input class="form-control" type="date" value="{{ old('tanggal_akhir',@$filter['tanggal_akhir']) }}"name="tanggal_akhir" placeholder="Masukan Tanggal" />
                                      @error('tanggal_akhir')
                                      <div class="invalid-feedback" style="display: block">
                                          {{ $message }}
                                      </div>
                                      @enderror
                                  </div>
                              </div>
                          </div>
                    @else
                          <div class="card-text">
                              <div class="row">
                                  <div class="col">
                                      <input class="form-control" type="date" value="{{ old('tanggal_akhir',@$filter['tanggal_akhir']) }}"name="tanggal_akhir" placeholder="Masukan Tanggal" />
                                      @error('tanggal_akhir')
                                      <div class="invalid-feedback" style="display: block">
                                          {{ $message }}
                                      </div>
                                      @enderror
                                  </div>
                              </div>
                          </div>
                    @endif
                    <div class="mt-6">
                      <div class="row">
                        <div class="d-grid col gap-2 mx-auto">
                          <button class="btn btn-primary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.daily') }}">Filter</button>
                        </div>
                        {{-- <div class="d-grid col gap-2 mx-auto">
                         <button class="btn btn-secondary btn-sm" type="submit" formmethod="get" formaction="{{ route('admin.rekap.daily.export','presence&-'.Crypt::encryptString($filter['uptd_id'])) }}">Export</button>
                        </div> --}}
                      </div>

                    </div>
                  </form>

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">{{ $total_absen }}</h5>
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

                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-xl-6">
            <div class="card bg-danger text-white">

                  <a href="{{ url('/admin/recapitulation/daily/absence?uptd_id='.$filter['uptd_id'].'&ksppj_id='.$filter['ksppj_id'].'&tanggal_akhir='.$filter['tanggal_akhir']) }}" class="card-body link-light link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover text-white">
                    <h5 class="card-title text-white">
                      {{ $total_tidak_absen }}
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

                  </a>

            </div>
        </div>
    </div>
    <!-- Contextual Classes -->

    <div class="card">
      <h5 class="card-header">
        Data Kehadiran Pegawai || {{ @$filter['tanggal_akhir'] }}
        <br>
        UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ @$filter['uptd_id'] }}

      </h5>
      <div class="table-responsive text-wrap">
        <table id="example" class="table">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Keterangan</th>
              <th>Masuk</th>
              <th>Keluar</th>
              <th>Jabatan</th>
              <th>Aksi</th>

            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($presences as $data)
                @if ($data->keterangan == "Terlambat")
                    <tr class="table-warning">
                @else
                    <tr class="table-default">
                @endif
                <td>

                {{ $data->user->name }}
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
                {{ @$data->user->jabatan }}
                </td>
                <td>
                <a href="{{ route('admin.rekap.user',Crypt::encryptString($data->user->id)) }}" class="btn btn-xs btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-offset="0,4" data-bs-placement="top" data-bs-html="true" title="<i class='bx bx-search bx-xs' ></i> <span>Detail</span>">
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
              <th>Masuk</th>
              <th>Keluar</th>
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

@if ($is_pengamat || Auth::user()->id == 0 || Auth::user()->id == 3422)
  <div class="buy-now">
    
    <button type="button" class="btn btn-danger btn-buy-now" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <i class="menu-icon tf-icons bx bx-user-check"></i>
        Bantu Kehadiran
    </button>
      
  </div>

  <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="staticBackdropLabel">Bantu Kehadiran Pegawai</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            {{-- <form class="row g-3 needs-validation" action="{{ route('admin.help_presensi.store') }}" method="POST" enctype="multipart/form-data"> --}}
            <form class="row g-3 needs-validation" action="{{ route('admin.help_presensi.store') }}" method="POST" enctype="multipart/form-data">
            
              @csrf
                <div class="modal-body">
                  <div class="form-check">
                    <select class="form-select" name="name" aria-label="Default select example">
                      <option value="">Pilih Pegawai</option>
                      @foreach ($user_absences as $peg)
                      @if (!$peg->absensi()->where('tanggal',$filter['tanggal_akhir'])->exists())
                      <option value="{{ $peg->id }}">{{ $peg->name }} - {{ $peg->jabatan }} ({{ $peg->email }})</option>  
                      @endif
                      @endforeach
                    </select>
                    @error('name')
                    <div class="invalid-feedback" style="display: block">
                        {{ $message }}
                    </div>
                    @enderror
                  </div>
                  
                  <div class="form-check">
                    <label class="col-form-label">Foto Pegawai</label>
                    <input name="image" class="form-control @error('image') is-invalid @enderror" type="file" accept="image/*" placeholder="Foto Pegawai" required/>
                    @error('image')
                        <div class="invalid-feedback" style="display: block; color:red">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-check" hidden>
                    <input class="form-control" type="date" value="{{ old('tanggal_akhir',@$filter['tanggal_akhir']) }}" name="tanggal" placeholder="Masukan Tanggal" required readonly/>
                    
                    @error('tanggal')
                        <div class="invalid-feedback" style="display: block; color:red">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-check">
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" name="keterangan" rows="2" placeholder="Keterangan"></textarea>
                    @error('keterangan')
                        <div class="invalid-feedback" style="display: block; color:red">
                            {{ $message }}
                        </div>
                    @enderror
                  </div>
                  <div class="form-check">
                    <div class="row">
                      <div class="col-md-6">
                          <input id="lat" name="latitude" type="text" class="form-control lat" placeholder="Latitude" required>
                      </div>
                      <div class="col-md-6">
                          <input id="long" name="longitude" type="text" class="form-control long" placeholder="Longitude" required>
                      </div>
                    </div>
                    <div id="mapLatLong" class="full-map mb-2" style="height: 300px; width: 100%"></div>
                  </div>
                </div>
                <div class="modal-footer">

                <button type="submit" class="btn btn-primary col-6 mx-auto">Save</button>

                </div>
            </form>
        </div>
    </div>
  </div>
@endif

@stop

@push('scripts')
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://js.arcgis.com/4.26/"></script>

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
<script>
  $(document).ready(function() {
      // Format mata uang.
  
      $('.lat').mask('-0.0000000000000000000000000000');
      $('.long').mask('000.0000000000000000000000000000');
  
      $('#mapLatLong').ready(() => {
          require([
              "esri/Map",
              "esri/views/MapView",
              "esri/geometry/Circle",
              "esri/Graphic",
              "esri/layers/GraphicsLayer"
          ], function(Map, MapView, Circle, Graphic, GraphicsLayer) {
  
              const map = new Map({
                  basemap: "osm"
              });
  
              const view = new MapView({
                  container: "mapLatLong",
                  map: map,
                  center: [107.6191, -6.9175],
                  zoom: 8,
              });
  
              let tempGraphic;
              view.on("click", function(event) {
                  if ($("#lat").val() != '' && $("#long").val() != '') {
                      view.graphics.remove(tempGraphic);
                  }
                  var graphic = new Graphic({
                      geometry: event.mapPoint,
                      symbol: {
                          type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                          url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                          width: "14px",
                          height: "24px"
                      }
                  });
                  tempGraphic = graphic;
                  $("#lat").val(event.mapPoint.latitude);
                  $("#long").val(event.mapPoint.longitude);
  
                  view.graphics.add(graphic);
              });
              if ($("#lat").val() != '' && $("#long").val() != '') {
              
                  view.graphics.remove(tempGraphic);
                  
                  const graphicsLayer = new GraphicsLayer();
                  map.add(graphicsLayer);
  
                  // const point = { //Create a point
                  //     type: "point",
                  //     longitude: -118.80657463861,
                  //     latitude: 34.0005930608889
                  // };
                  // const simpleMarkerSymbol = {
                  //     type: "simple-marker",
                  //     color: [226, 119, 40],  // Orange
                  //     outline: {
                  //         color: [255, 255, 255], // White
                  //         width: 1
                  //     }
                  // };
  
                  // const pointGraphic = new Graphic({
                  //     geometry: point,
                  //     symbol: simpleMarkerSymbol
                  // });
                  // graphicsLayer.add(pointGraphic);
  
                  // // Create a polygon geometry
                  // const polygon = {
                  //     type: "polygon",
                  //     rings: [
                  //         [-118.818984489994, 34.0137559967283], //Longitude, latitude
                  //         [-118.806796597377, 34.0215816298725], //Longitude, latitude
                  //         [-118.791432890735, 34.0163883241613], //Longitude, latitude
                  //         [-118.79596686535, 34.008564864635],   //Longitude, latitude
                  //         [-118.808558110679, 34.0035027131376]  //Longitude, latitude
                  //     ]
                  // };
  
                  // const simpleFillSymbol = {
                  //     type: "simple-fill",
                  //     color: [227, 139, 79, 0.8],  // Orange, opacity 80%
                  //     outline: {
                  //         color: [255, 255, 255],
                  //         width: 1
                  //     }
                  // };
  
                  // const popupTemplate = {
                  //     title: "{Name}",
                  //     content: "{Description}"
                  // }
                  // const attributes = {
                  //     Name: "Graphic",
                  //     Description: "I am a polygon"
                  // }
  
                  // const polygonGraphic = new Graphic({
                  //     geometry: polygon,
                  //     symbol: simpleFillSymbol,
  
                  //     attributes: attributes,
                  //     popupTemplate: popupTemplate
  
                  // });
                  // graphicsLayer.add(polygonGraphic);
  
                  // Create a circle geometry
                  if ($("#exampleFormControlInputRadius").val() != '') {
                      const circleGeometry = new Circle({
                          center: [ $("#long").val(), $("#lat").val() ],
                          geodesic: true,
                          numberOfPoints: 100,
                          radius: $("#exampleFormControlInputRadius").val(),
                          radiusUnit: "meters"
                      });
                      const polycircleGraphic = new Graphic({
                          geometry: circleGeometry,
                          symbol: {
                              type: "simple-fill",
                              // style: "none",
                              color: [227, 139, 79, 0.8],  // Orange, opacity 80%
  
                              outline: {
                                  width: 3,
                                  color: "red"
                              }
                          }
                      });
                      graphicsLayer.add(polycircleGraphic);
                  }
                  //Create a point marker
                  const point = { 
                      type: "point",
                      longitude: $("#long").val(),
                      latitude: $("#lat").val()
                  };
                  const pointGraphic = new Graphic({
                      geometry: point,
                      symbol: {
                          type: "picture-marker", // autocasts as new SimpleMarkerSymbol()
                          url: "http://esri.github.io/quickstart-map-js/images/blue-pin.png",
                          width: "14px",
                          height: "24px"
                      }
                  });
                  tempGraphic = pointGraphic;
                  graphicsLayer.add(pointGraphic);
                  
              }
              // $("#lat, #long").keyup(function() {
              // });
          });
      });
      
  });
</script>
@endpush
