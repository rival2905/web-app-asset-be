
@extends('admin.layouts.app')
@section('title')
    Dashboard  
@parent
@stop

@push('links')
<script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.6.0/echarts.min.js"></script>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-sm-12 col-md-12 col-xl-12">
        <div class="card text-center">
          <div class="card-body">
            <form class="needs-validation" action="{{route('admin.dashboard.index')}}" method="get" enctype="multipart/form-data">  
              <div class="card-text">
                  <div class="row">
                      
                      <div class="col-md-12">
                          <input class="form-control" type="date" value="{{ old('tanggal_akhir',@$filter['tanggal_akhir']) }}"name="tanggal_akhir" placeholder="Masukan Tanggal" />
                          @error('tanggal_akhir')
                              <div class="invalid-feedback" style="display: block">
                                  {{ $message }}
                              </div>
                          @enderror
                      </div>
                  </div>
              </div>
              <div class="mt-2 d-grid gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>

              </div>
            </form>
            
          </div>
          
        </div>
      </div>
      <div class="col-md-12">
        <div class="chart has-fixed-height" id="batang" style="width: 100%; height: 400px;"></div>
      </div>
    </div>
</div>

@stop

@push('scripts')
<script>

var uptdes = {!! json_encode($daily['key_data']) !!};
var tepat_waktu = {!! json_encode($daily['tepat_waktu']) !!};
var terlambat = {!! json_encode($daily['terlambat']) !!};
var dinas_luar = {!! json_encode($daily['dinas_luar']) !!};
var izin_sakit = {!! json_encode($daily['izin_sakit']) !!};
var izin_lainnya = {!! json_encode($daily['izin_lainnya']) !!};
var alpha = {!! json_encode($daily['alpha']) !!};

console.log(uptdes);

var chartDom = document.getElementById('batang');
var myChart = echarts.init(chartDom);
var option;

var series = [
  {
    data: tepat_waktu,
    type: 'bar',
    stack: 'a',
    name: 'Tepat Waktu'
  },
  {
    data: terlambat,
    type: 'bar',
    stack: 'a',
    name: 'Terlambat'
  },
  {
    data: dinas_luar,
    type: 'bar',
    stack: 'a',
    name: 'Dinas Luar'
  },
  {
    data: izin_sakit,
    type: 'bar',
    stack: 'b',
    name: 'Izin Sakit'
  },
  {
    data: izin_lainnya,
    type: 'bar',
    stack: 'b',
    name: 'Izin Lainnya'
  },
  {
    data: alpha,
    type: 'bar',
    stack: 'b',
    name: 'Tanpa Keterangan'
  }
];
const stackInfo = {};
for (let i = 0; i < series[0].data.length; ++i) {
  for (let j = 0; j < series.length; ++j) {
    const stackName = series[j].stack;
    if (!stackName) {
      continue;
    }
    if (!stackInfo[stackName]) {
      stackInfo[stackName] = {
        stackStart: [],
        stackEnd: []
      };
    }
    const info = stackInfo[stackName];
    const data = series[j].data[i];
    if (data && data !== '-') {
      if (info.stackStart[i] == null) {
        info.stackStart[i] = j;
      }
      info.stackEnd[i] = j;
    }
  }
}
for (let i = 0; i < series.length; ++i) {
  const data = series[i].data;
  const info = stackInfo[series[i].stack];
  for (let j = 0; j < series[i].data.length; ++j) {
    // const isStart = info.stackStart[j] === i;
    const isEnd = info.stackEnd[j] === i;
    const topBorder = isEnd ? 20 : 0;
    const bottomBorder = 0;
    data[j] = {
      value: data[j],
      itemStyle: {
        borderRadius: [topBorder, topBorder, bottomBorder, bottomBorder]
      }
    };
  }
}
option = {
  color: ["#0066CC", "#FFFF00", "#80FF00", "#FF8000","#FFCC99","#FF3333"],
  title: {
    text: 'Diagram Absensi Harian',
    left: 'center'
  },
  xAxis: {
    type: 'category',
    data: uptdes
  },
  legend: {
    top: 'bottom'
  },
  tooltip: {
    trigger: 'axis',
    axisPointer: {
      // Use axis to trigger tooltip
      type: 'shadow' // 'shadow' as default; can also be 'line' or 'shadow'
    }
  },
  yAxis: {
    type: 'value'
  },
  series: series
};

option && myChart.setOption(option);

</script>
@endpush
