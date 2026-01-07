<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">

    <title>
        Data Rekapitulasi Bulanan Pegawai 
        @if ($filter['uptd_id'])UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $filter['uptd_id'] }} @endif 
    </title>
</head>

<body class="container">
<h1 class="text-center">
    
    @if ($filter['uptd_id'])<br>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $filter['uptd_id'] }} @endif 
    
    @if($filter['ksppj_id'])
    <br>Wilayah {{ $ksppj->jabatan }} 
    @endif
    
   
       
</h1>
@if (@$filter['pengamat_id'])
    @if ($pengamat->kepengamatan()->exists())
    <br>
    Kepengamatan
        @foreach ($pengamat->kepengamatan as $ruas)
            {{ $ruas->nama }};
        @endforeach
    @endif
@endif
@if (@$filter['mandor_id'])
<br>
Kemandoran
    @if ($mandor->lokasi_kerja()->exists())
        @foreach ($mandor->lokasi_kerja as $ruas)
            {{ $ruas->nama }};
        @endforeach
    @endif
@endif
@if (@$filter['month'] && $filter['year'])
<br>
Absensi Bulan {{ $filter['bulan'] }}
@endif
<table id="example" class="display table table-striped" style="width:100%">
   
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
    <tbody>
        @foreach ($user_check as $no=> $user)
        <tr>
            <td class="text-center">{{ ++$no }}</td>

            <td style="white-space:nowrap;">
                {{ $user->name }}
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
                    <td class="bg-danger">-</td>
                    <td class="bg-danger">-</td>
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
                    <td class="bg-danger">-</td>
                    <td class="bg-danger">-</td>
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
    
</table>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>

{{-- <script>
    $('#example').DataTable({
        layout: {
        topStart: {
            buttons: [
                {
                    extend: 'excelHtml5',
                    autoFilter: true,
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                        // Loop over the cells in column `C`
                        $('row c', sheet).each(function () {
                            // Get the value
                            if ($('is t', this).text() == '-') {
                                $(this).attr('s', '20');
                            }
                        });
                    }
                }
                ,{
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A3',
                    download: 'open',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 5;
                        doc.styles.tableHeader.fontSize = 5;
                    }
                }
            ]
        }
    }
});
</script> --}}

<script>
    $('#example').DataTable({
        layout: {
            topStart: {
                buttons: [
                    {
                        extend: 'excelHtml5',
                        autoFilter: true,
                        customize: function (xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
    
                            // Loop over the cells in column `C`
                            $('row c', sheet).each(function () {
                                // Get the value
                                if ($('is t', this).text() == '-') {
                                    $(this).attr('s', '20');
                                }
                            });
                        }
                    },
                    
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A3',
                        download: 'open',

                        customize: function(doc) {
                            doc.defaultStyle.fontSize = 5;
                            doc.styles.tableHeader.fontSize = 5;

                            // doc.content.splice(0, 0, {
                            //     text: [
                            //         "Data Kehadiran Tenaga Harian Lepas\n",
                            //         "UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan 6\n",
                            //         "Wilayah KSPPJJ Indramayu 1\n",
                            //         "Kemandoran Pekandangan - Jatibarang; Jl. Ir. Sutami;"
                            //     ],
                            //     fontSize: 10,
                            //     margin: [0, 0, 0, 12],
                            //     alignment: 'left',
                            //     bold: true,
                            // });
                            
                        }
                    }
                ]
            }
        }
    });
</script>


</body>

</html>