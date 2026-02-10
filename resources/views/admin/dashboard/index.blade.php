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

    <div class="row mb-4">
        @foreach($dashboardCards as $card)
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <small class="text-muted">{{ $card['title'] }}</small>
                    <h2 class="fw-bold">
                        <a href="{{ $card['route'] }}" class="text-decoration-none text-primary">
                            {{ $card['count'] }}
                        </a>
                    </h2>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Grafik Aset Per Bulan -->
    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Grafik Aset Per Bulan</h5>
            <div id="asetChart" style="height: 350px;"></div>
        </div>
    </div>

</div>
@stop

@push('scripts')
<script>
    const chartDom = document.getElementById('asetChart');
    const myChart = echarts.init(chartDom);

    const option = {
        tooltip: { trigger: 'axis' },
        xAxis: {
            type: 'category',
            data: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des']
        },
        yAxis: { type: 'value' },
        series: [{
            name: 'Aset',
            type: 'bar',
            data: @json($chartData)
        }]
    };

    myChart.setOption(option);
</script>
@endpush
