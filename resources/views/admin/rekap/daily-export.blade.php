<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.2.2/css/buttons.bootstrap5.css">

    <title>
        @if ($key[0] == 'presence')
            Data Kehadiran Pegawai 
        @else
            Data Pegawai yang Tidak Hadir
        @endif
        @if ($filter['uptd_id'])UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $filter['uptd_id'] }} @endif 
        @if($filter['tanggal_akhir']){{ $filter['tanggal_akhir'] }} @endif
    </title>
</head>

<body class="container">
<h1 class="text-center">
    @if ($key[0] == 'presence')
        Data Kehadiran Pegawai 
    @else
        Data Pegawai yang Tidak Hadir
    @endif
    @if ($filter['uptd_id'])<br>UPTD Pengelolaan Jalan dan Jembatan Wilayah Pelayanan {{ $filter['uptd_id'] }} @endif 
    
    @if($filter['ksppj_id'])<br>{{ $ksppj->jabatan }} @endif

    @if($filter['tanggal_akhir'])<br>{{ $filter['tanggal_akhir'] }} @endif
       
</h1>

<table id="example" class="display table table-striped" style="width:100%">
    @if ($key[0] == 'presence')
    <thead>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Keterangan</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Jabatan</th>
    </tr>
    </thead>
    <tbody>
        
        @foreach($data as $no => $presence )
        <tr>
            <td class="text-center">{{ ++$no }}</td>

            <td>
                {{ $presence->user->name }}
            </td>
            <td>
                {{ @$presence->keterangan }}
            </td>
            <td>
                {{ @$presence->jam_masuk }}
            </td>
            <td>
                {{ @$presence->jam_keluar }}
            </td>
            <td>
                {{ @$presence->user->jabatan }}
            </td>

        </tr>
        @endforeach
    </tbody>
    </tfoot>
    @else
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Keterangan</th>
            <th>Jabatan</th>
            <th>Ruas</th>
                  
        </tr>
    </thead>
    <tbody>
          
        @php
        $ket = "Tanpa Keterangan";
        @endphp
        @foreach ($data as $no => $user)
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
          <td class="text-center">{{ ++$no }}</td>
  
          <td>{{ $user->name }}</td>
          <td>
            {{ $ket }}
          </td>
          <td>
            {{ @$user->jabatan }}
          </th>
          <td>
            @if ($user->lokasi_kerja()->exists())
                @foreach ($user->lokasi_kerja as $ruas)
                    {{ $ruas->nama }};
                @endforeach
            @endif
          </th>
            
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Keterangan</th>
          <th>Jabatan</th>
          <th>Ruas</th>

        </tr>
      </tfoot>
    @endif
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

<script>
    $('#example').DataTable({
    layout: {
        topStart: {
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        }
    }
});
</script>

</body>

</html>